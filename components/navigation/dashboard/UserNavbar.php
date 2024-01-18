<?php

namespace Components\Navigation\Dashboard;

enum UserNavbarEntry: int{
    case documents = 1;
    case profile = 2;
    case competitions = 3;
    case trainings = 4;

}
class UserNavbar extends AbstractDashboardNavbar{
    public function __construct(UserNavbarEntry $active) {
        parent::__construct($active, array(
            UserNavbarEntry::profile->value => array(
                "symbol" => get_public_file("symbols/user-symbol.svg"),
                "link" => "/dashboard/profile",
                "link_text" => "Mon profil"
            ),
            UserNavbarEntry::documents->value => array(
                "symbol" => get_public_file("symbols/document-symbol.svg"),
                "link" => "/dashboard/documents",
                "link_text" => "Mes documents"
            ),
            UserNavbarEntry::trainings->value => array(
                "symbol" => get_public_file("symbols/training-symbol.svg"),
                "link" => "/dashboard/trainings",
                "link_text" => "Mes entrainements"
            ),
            UserNavbarEntry::competitions->value => array(
                "symbol" => get_public_file("symbols/competition-symbol.svg"),
                "link" => "/dashboard/competitions",
                "link_text" => "Mes compétitions"
            )
        ));
    }
}
