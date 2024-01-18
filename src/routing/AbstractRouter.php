<?php

namespace Routing;

use Authorization\AuthorizationLevel;
use Authorization\AuthorizationManager;

abstract class AbstractRouter{
    private $routes = array();
    private $aliases = array();
    private $uri_reducer_regex = "/(\/.*(?=\/))/";
    private $error_404_url = null;

    public function __construct() {
        $this->newAlias("@Views", ABSPATH . 'views');
        $this->newAlias("@Controllers", ABSPATH . 'src/controllers');
    }

    /**
     * Creates a new alias that maps to its value
     * The alias MUST start with an @ and an uppercase letter
     * e.g. 
     * newAlias("@Views", "src/views") will map the @Views to the value src/views
     * @param string $alias alias to bind
     * @param string $value value of the alias
     */
    protected function newAlias(string $alias, string $value) {
        $this->aliases[$alias] = $value;
    }

    /**
     * Resolves every existing aliases inside of the $request
     * @param $request Request to handle
     */
    private function resolveAliases($request): string {
        foreach($this->aliases as $alias => $value) {
            $request = str_replace($alias, $value, $request);
        }
        return $request;
    }

    /**
     * Creates a new handler for 404 errors
     * @param string $target Target page for when a 404 error is encountered
     */
    protected function new404Handler(string $target): void {
        $this->error_404_url = $this->resolveAliases($target);
    }

    /**
     * Adds the $route in the list of existing routes,
     * If route already exist then create an array containing them
     * 
     * Using aliases that have been declared with the newAlias() method:
     * if you pass as $target "@Dummy/aa/bb.php" @Dummy will be resolved to its value if the alias is defined
     * 
     * Predifined aliases are
     * - @Views which maps to :  ABSPATH . 'views'
     * - @Controllers which maps to :  ABSPATH . 'controllers'
     * 
     * @param string $route Route to add
     * @param array $route_infos Infos of the route
     */
    private function registerRoute(string $route, array $route_infos) {
        // If already exists but has different authorization level
        // Then creates an array holding both
        if(isset($this->routes[$route]) && isset($this->routes[$route]["route"])) {
            $temp = $this->routes[$route];
            $this->routes[$route] = array(
                $temp,
                $route_infos
            );
        } 
        // Else if the route already exists but is already an array
        // Then append at the end of the array
        else if (isset($this->routes[$route]) && !isset($this->routes[$route]["route"])) {
            array_push($this->routes[$route], $route_infos);
        }
        // Else simply create the entry in the array
        else {
            $this->routes[$route] = $route_infos;
        }
    }

    /**
     * Registers a new route
     * If two routes have the same $route then they will be first resolved by authorization level
     * Or if they have the same, the first one will be considered
     * 
     * Using aliases that have been declared with the newAlias() method:
     * if you pass as $target "@Dummy/aa/bb.php" @Dummy will be resolved to its value if the alias is defined
     * 
     * Predifined aliases are
     * - @Views which maps to :  ABSPATH . 'views'
     * - @Controllers which maps to :  ABSPATH . 'controllers'
     * 
     * @param string $route Route to resolve
     * @param string $target Url of the file that will be loaded
     * @param AuthorizationLevel $authorizationLevel Clearance necessary to access the route
     * @param string $unauthorized_redirect Redirect URL for when the clearance is not met
     */
    protected function newRoute(string $route, string $target, AuthorizationLevel $authorizationLevel = AuthorizationLevel::Any, $unauthorized_redirect = "/"): void {
        $route_infos = array();
        $route_infos["route"] = $route;
        $route_infos["authLevel"] = $authorizationLevel;
        $route_infos["type"] = "simple";
        $route_infos["target"] = $this->resolveAliases($target);
        $route_infos["unauthorized_redirect"] = $unauthorized_redirect;
        $this->registerRoute($route, $route_infos);

    }

    /**
     * Registers a new route delegation
     * If two routes have the same $route then they will be first resolved by authorization level
     * Or if they have the same, the first one will be considered
     * @param string $route Route to resolve
     * @param string $router_class Class of the router to delegate the route to
     * @param AuthorizationLevel $authorizationLevel Clearance necessary to access the route
     * @param string $unauthorized_redirect Redirect URL for when the clearance is not met
     */
    protected function newRouteDelegation(string $route, string $router_class, AuthorizationLevel $authorizationLevel = AuthorizationLevel::Any, $unauthorized_redirect = "/"): void {
        $route_infos = array();
        $route_infos["route"] = $route;
        $route_infos["authLevel"] = $authorizationLevel;
        $route_infos["type"] = "delegation";
        $route_infos["target"] = new $router_class();
        $route_infos["unauthorized_redirect"] = $unauthorized_redirect;
        $this->registerRoute($route, $route_infos);
    }

    /**
     * Checks if the authorization level is met for the $route
     * @param array $route Route to check
     * @return bool True if authorized, else false
     */
    private function verifyAuthorization($route): bool {
        switch($route["authLevel"]) {
            case AuthorizationLevel::Guest: 
                return AuthorizationManager::isGuest();
            case AuthorizationLevel::Admin: 
                return AuthorizationManager::isAdmin();
            case AuthorizationLevel::User: 
                return AuthorizationManager::isUser();
            case AuthorizationLevel::LoggedIn: 
                return AuthorizationManager::isLoggedIn();
            default: 
                return true;
        }
    }

    /**
     * Checks if the authorization level is met for the $route
     * If not authorized redirects to $route["unauthorized_redirect"]
     * @param array $route Route to check
     */
    private function resolveAuthorization($route):void {
        switch($route["authLevel"]) {
            case AuthorizationLevel::Any: 
                break;
            case AuthorizationLevel::Guest: 
                AuthorizationManager::requireGuest($route["unauthorized_redirect"]);
                break;
            case AuthorizationLevel::Admin: 
                AuthorizationManager::requireAdmin($route["unauthorized_redirect"]);
                break;
            case AuthorizationLevel::User: 
                AuthorizationManager::requireUser($route["unauthorized_redirect"]);
                break;
            case AuthorizationLevel::LoggedIn: 
                AuthorizationManager::requireLoggedIn($route["unauthorized_redirect"]);
                break;
        }
    }

    /**
     * Resolves the route by checking for authorization 
     * and either delegating or doing a simple load of the $route
     * @param $route Route to resolve
     * @param $request Current request
     * @return bool True if successfully loaded, else false
     */
    private function resolveRoute($route, $request): bool {
        if(!$this->verifyAuthorization($route)) return false;
        switch($route["type"]) {
            case "delegation":
                $new_request = str_replace($route["route"], "", $request);
                return $route["target"]->handleRequest($new_request);
            case "simple":
                define('HAS_LOADED_PAGE', true);
                include_once($route["target"]);
                return true;
            default:
                return false;
        }
    }

    /**
     * Handles the given request by checking for the correct route
     * @param $request Request to handle
     * @return bool True if a page was loaded, else false
     */
    public function handleRequest($request): bool {
        // If no exact match route is defined
        if(!isset($this->routes[$request])) {
            $reduced_request = $request;

            // Reduces the url until either getting a match or not matching anymore
            while(true) {
                $matches = null;
                if(preg_match($this->uri_reducer_regex, $reduced_request, $matches) == 0) break;
                $reduced_request = $matches[1];

                // If we finally have a match and its a delegation then resolve
                if(isset($this->routes[$reduced_request])){
                    // If it is a single route (not an array) and a delegation
                    // Then resolve the route
                    if(isset($this->routes[$reduced_request]["route"]) && $this->routes[$reduced_request]["type"] == "delegation") {
                        if(!$this->resolveRoute($this->routes[$reduced_request], $request)) {
                            $this->resolveAuthorization($this->routes[$reduced_request]);
                        } else {
                            return true;
                        }
                    }
                    // If it is a multiple route (an array)
                    // Then foreach route try to resolve if route is a delegation
                    // If it fails, resolves the Authorization of the first one
                    else if (!isset($this->routes[$reduced_request]["route"])) {
                        foreach($this->routes[$reduced_request] as $curr_route) {
                            if(
                                $curr_route["type"] == "delegation" &&
                                $this->resolveRoute($curr_route, $request)
                            ) return true;
                        }
                        $this->resolveAuthorization($this->routes[$reduced_request][0]);
                    }
                }
            }
        } 
        // If a exact match route does exists
        else {
            // If it is a single route (not an array)
            // Then resolve the route
            if(isset($this->routes[$request]["route"])) {
                if(!$this->resolveRoute($this->routes[$request], $request)) {
                    $this->resolveAuthorization($this->routes[$request]);
                } else {
                    return true;
                }
            }
            // If it is a multiple route (an array)
            // Then foreach route try to resolve
            // If it fails, resolves the Authorization of the first one
            else {
                foreach($this->routes[$request] as $curr_route) {
                    if(
                        $this->resolveRoute($curr_route, $request)
                    ) return true;
                }
                $this->resolveAuthorization($this->routes[$request][0]);
            }
        }
        // Displays error 404
        if($this->error_404_url) {
            include_once($this->error_404_url);
            http_response_code(404);
        }
        return false;
    }
}