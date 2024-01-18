<?php

namespace Components\Navigation\Dashboard;

enum AdminNavbarEntry: int{
    case profile = 1;
    case accounts = 2;
    case competitions = 3;
    case partner = 4;
    case location = 5;
}
class AdminNavbar extends AbstractDashboardNavbar{
    public function __construct(AdminNavbarEntry $active) {
        parent::__construct($active, array(
            AdminNavbarEntry::profile->value => array(
                "symbol" => get_public_file("symbols/user-symbol.svg"),
                "link" => "/dashboard/profile",
                "link_text" => "Mon profil"
            ),
            AdminNavbarEntry::partner->value => array(
                "symbol" => get_public_file("symbols/partner-symbol.svg"),
                "link" => "/dashboard/partners",
                "link_text" => "Partenaires"
            ),
            AdminNavbarEntry::location->value => array(
                "symbol" => get_public_file("symbols/location-symbol.svg"),
                "link" => "/dashboard/locations",
                "link_text" => "Gymnases"
            ),
            AdminNavbarEntry::competitions->value => array(
                "symbol" => get_public_file("symbols/competition-symbol.svg"),
                "link" => "/dashboard/competitions",
                "link_text" => "Compétitions"
            ),
            AdminNavbarEntry::accounts->value => array(
                "symbol" => get_public_file("symbols/account-symbol.svg"),
                "link" => "/dashboard/accounts",
                "link_text" => "Comptes"
            ),
        ));
    }
}
