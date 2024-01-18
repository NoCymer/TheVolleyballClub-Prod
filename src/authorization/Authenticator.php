<?php

namespace Authorization;

use Database\DatabaseConnection;

class Authenticator{
    /**
     * Checks if a admin account exists with the given email
     * @param string $email Email to check
     * @return int | false Id of the admin or false if no account is found 
     */
    private static function checkAdminAccount(string $email): int | false {
        $connection = new DatabaseConnection();
        // Verify if it's an admin
        $stmt = $connection->getConnection()->prepare('SELECT * FROM `admin` WHERE loginAdmin=?');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        return isset($admin["idAdmin"]) ? $admin["idAdmin"] : false;
    }

    /**
     * Checks if a user account exists with the given email
     * @param string $email Email to check
     * @return int | false Id of the user or false if no account is found 
     */
    private static function checkUserAccount(string $email): int | false {
        $connection = new DatabaseConnection();
        // Verify if it's a user
        $stmt = $connection->getConnection()->prepare('SELECT * FROM `user` WHERE emailUser=?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return isset($user["idUser"]) ? $user["idUser"] : false;
    }
    /**
     * Tries to authenticate as a user
     * @param string $email Email of the account
     * @param string $hash Hash of the account's password
     * @return bool True if logged in, false if credentials incorrect
     */
    private static function tryAuthenticateUser(string $email, string $hash): bool {
        $connection = new DatabaseConnection();
        // Verify if it's a user
        $stmt = $connection->getConnection()->prepare(
            'SELECT * FROM user WHERE emailUser=? AND passwordUser=?'
        );
        $stmt->execute([$email, $hash]);
        $user = $stmt->fetch();
        if(isset($user['idUser'])) {
            $_SESSION['userConnect'] = $user['idUser']; 
            return true;
        }
        return false;
    }

    /**
     * Tries to authenticate as an admin
     * @param string $email Email of the account
     * @param string $hash Hash of the account's password
     * @return bool True if logged in, false if credentials incorrect
     */
    private static function tryAuthenticateAdmin(string $email, string $hash): bool {
        $connection = new DatabaseConnection();
        // Verify if it's an admin
        $stmt = $connection->getConnection()->prepare(
            'SELECT * FROM admin WHERE loginAdmin=? AND passwordAdmin=?'
        );
        $stmt->execute([$email, $hash]);
        $admin = $stmt->fetch();
        if(isset($admin['idAdmin'])) {
            $_SESSION['adminConnect'] = $admin['idAdmin'];
            return true;
        }
        return false;
    }
    
    /**
     * Tries to authenticate as either a user or an admin
     * @param string $email Email of the account
     * @param string $hash Hash of the account's password
     * @return AuthorizationLevel Authorization level of the authentication, Admin if admin account logged, User if user logged, or Guest if no account was found
     */
    public static function authenticate(string $email, string $hash): AuthorizationLevel {
        if(Authenticator::tryAuthenticateAdmin($email, $hash)) return AuthorizationLevel::Admin;
        if(Authenticator::tryAuthenticateUser($email, $hash)) return AuthorizationLevel::User;
        return AuthorizationLevel::Guest;
    }

    /**
     * Checks if an account exists with the given email
     * @param string $email Email to check
     * @return array | false The account id and the authorization level of the account if it exists, else false if no account found
     */
    public static function accountExists($email): array | false {
        $id_a = Authenticator::checkAdminAccount($email);
        if($id_a) return [
            "id" => $id_a,
            "authLevel" => AuthorizationLevel::Admin
        ];

        $id_u = Authenticator::checkUserAccount($email);
        if($id_u) return [
            "id" => $id_u,
            "authLevel" => AuthorizationLevel::User
        ];
        
        return false;
    }
}