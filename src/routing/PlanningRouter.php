<?php

namespace Routing;

use Authorization\AuthorizationLevel;

class PlanningRouter extends AbstractRouter{
    public function __construct() {
        parent::__construct();
        $this->newRoute('', '@Views/planning/planning.php', AuthorizationLevel::Any);
        $this->newRoute('/view', '@Views/planning/view.php', AuthorizationLevel::Any);
        $this->newRoute('/participate', '@Controllers/ParticipationController.php', AuthorizationLevel::Any);
    }
}