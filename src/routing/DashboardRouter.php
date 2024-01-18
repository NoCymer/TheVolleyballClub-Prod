<?php

namespace Routing;

use Authorization\AuthorizationLevel;

class DashboardRouter extends AbstractRouter{
    public function __construct() {
        parent::__construct();
        $this->newRoute('', '@Views/dashboard/user/profile.php', AuthorizationLevel::User);
        $this->newRoute('', '@Views/dashboard/admin/profile.php', AuthorizationLevel::Admin);


        $this->newRoute('/profile', '@Views/dashboard/user/profile.php', AuthorizationLevel::User);
        $this->newRoute('/profile/edit', '@Views/dashboard/user/edit.php', AuthorizationLevel::User);
        $this->newRoute('/profile/edit/submit', '@Controllers/UserController.php', AuthorizationLevel::User);
        $this->newRoute('/profile/edit-password', '@Views/dashboard/user/edit-password.php', AuthorizationLevel::User);
        $this->newRoute('/profile/edit-password/submit', '@Controllers/UserController.php', AuthorizationLevel::User);
        $this->newRoute('/documents', '@Views/dashboard/user/documents.php', AuthorizationLevel::User);
        $this->newRoute('/documents/submit', '@Controllers/UserController.php', AuthorizationLevel::User);
        $this->newRoute('/documents/download', '@Views/dashboard/user/download.php', AuthorizationLevel::User);
        $this->newRoute('/trainings', '@Views/dashboard/user/trainings.php', AuthorizationLevel::User);
        $this->newRoute('/competitions', '@Views/dashboard/user/competitions.php', AuthorizationLevel::User);
        
        $this->newRoute('/accounts', '@Views/dashboard/admin/accounts.php', AuthorizationLevel::Admin);
        $this->newRoute('/admins', '@Views/dashboard/admin/admins/admins.php', AuthorizationLevel::Admin);
        $this->newRoute('/admins/create', '@Views/dashboard/admin/admins/create.php', AuthorizationLevel::Admin);
        $this->newRoute('/admins/create/submit', '@Controllers/AdminController.php', AuthorizationLevel::Admin);

        $this->newRoute('/profile', '@Views/dashboard/admin/profile.php', AuthorizationLevel::Admin);
        $this->newRoute('/profile/edit', '@Views/dashboard/admin/edit.php', AuthorizationLevel::Admin);
        $this->newRoute('/profile/edit/submit', '@Controllers/AdminController.php', AuthorizationLevel::Admin);
        $this->newRoute('/profile/edit-password', '@Views/dashboard/admin/edit-password.php', AuthorizationLevel::Admin);
        $this->newRoute('/profile/edit-password/submit', '@Controllers/AdminController.php', AuthorizationLevel::Admin);

        $this->newRoute('/documents/validate', '@Controllers/UserController.php', AuthorizationLevel::Admin);
        $this->newRoute('/documents/download', '@Views/dashboard/user/download.php', AuthorizationLevel::Admin);
        $this->newRoute('/competitions', '@Views/dashboard/admin/competitions/competitions.php', AuthorizationLevel::Admin);
        $this->newRoute('/competitions/validate', '@Controllers/UserController.php', AuthorizationLevel::Admin);

        $this->newRoute('/members', '@Views/dashboard/admin/members/members.php', AuthorizationLevel::Admin);
        $this->newRoute('/members/view', '@Views/dashboard/admin/members/view.php', AuthorizationLevel::Admin);
        $this->newRoute('/members/edit', '@Views/dashboard/admin/members/edit.php', AuthorizationLevel::Admin);
        $this->newRoute('/members/edit/submit', '@Controllers/UserController.php', AuthorizationLevel::Admin);
        $this->newRoute('/members/delete', '@Controllers/UserController.php', AuthorizationLevel::Admin);

        $this->newRoute('/partners', '@Views/dashboard/admin/partners/partners.php', AuthorizationLevel::Admin);
        $this->newRoute('/partners/create', '@Views/dashboard/admin/partners/create.php', AuthorizationLevel::Admin);
        $this->newRoute('/partners/create/submit', '@Controllers/PartnerController.php', AuthorizationLevel::Admin);
        $this->newRoute('/partners/view', '@Views/dashboard/admin/partners/view.php', AuthorizationLevel::Admin);
        $this->newRoute('/partners/edit', '@Views/dashboard/admin/partners/edit.php', AuthorizationLevel::Admin);
        $this->newRoute('/partners/edit/submit', '@Controllers/PartnerController.php', AuthorizationLevel::Admin);

        $this->newRoute('/locations', '@Views/dashboard/admin/locations/locations.php', AuthorizationLevel::Admin);
        $this->newRoute('/locations/create', '@Views/dashboard/admin/locations/create.php', AuthorizationLevel::Admin);
        $this->newRoute('/locations/create/submit', '@Controllers/LocationController.php', AuthorizationLevel::Admin);
        $this->newRoute('/locations/view', '@Views/dashboard/admin/locations/view.php', AuthorizationLevel::Admin);
        $this->newRoute('/locations/edit', '@Views/dashboard/admin/locations/edit.php', AuthorizationLevel::Admin);
        $this->newRoute('/locations/edit/submit', '@Controllers/LocationController.php', AuthorizationLevel::Admin);
    }
}