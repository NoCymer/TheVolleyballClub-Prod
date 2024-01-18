<?php

namespace Components\Navigation\Dashboard;

use Templates\Template;
abstract class AbstractDashboardNavbar {
    private $active;

    private $entries_infos= array();

    public function __construct($active, array $entries_infos) {
        $this->active = $active;
        $this->entries_infos = $entries_infos;
    }

    public final function display() {
        $template = new Template("navbar/navbar_dashboard.template.php");

        $entries_str = "";

        foreach ($this->entries_infos as $key => $entry_info) {
            // Checks if it is the selected entry
            $class_name = $this->active->value==$key ? "active" : "";

            // Fetches the correct symbol for the entry
            $curr_symbol = $entry_info["symbol"];

            // Fetches the correct link for the entry
            $curr_link = $entry_info["link"];

            // Fetches the correct link text for the entry
            $curr_link_text = $entry_info["link_text"];

            // Generates the html for the entry
            $entries_str = $entries_str.
            "
                <li class=\"$class_name\">
                    $curr_symbol
                    <a href=\"$curr_link\">$curr_link_text</a>
                </li>
            ";
        }

        $template->fill_placeholder("dashboard-navbar-entries", $entries_str);

        $template->display();
        echo('<script src="/components/navigation/dashboard/dashboardNavbar.js" defer></script>');
    }
}
