<?php

    namespace Components\Navigation\Navbar;

    use Templates\Template;

    enum NavbarEntry{
        case home;
        case informations;
        case planning;
        case contact;
        case connection;
        case dashboard;
        case none;

    }
    class Navbar {
        private $active;
        public function __construct($active) {
            $this->active = $active;
        }

        public function display() {
            $template = new Template("navbar/navbar.template.php");

            $entries_str = '
                <li '.($this->active==NavbarEntry::home ? 'class="selected"' : '').'><a href="/">ACCUEIL</a></li>
                <li '.($this->active==NavbarEntry::informations ? 'class="selected"' : '').'><a href="/info">INFORMATIONS</a></li>
                <li '.($this->active==NavbarEntry::planning ? 'class="selected"' : '').'><a href="/planning">PLANNING</a></li>
                <li '.($this->active==NavbarEntry::contact ? 'class="selected"' : '').'><a href="/contact">CONTACT</a></li>
            ';

            if(isset($_SESSION["userConnect"]) || isset($_SESSION["adminConnect"])) {
                $entries_str = $entries_str.'
                    <li '.($this->active==NavbarEntry::dashboard ? 'class="selected"' : '').'><a href="/dashboard">TABLEAU DE BORD</a></li>';

                // Displays disconnect button
                $entries_str = $entries_str.'
                    <li>
                        <a href="/sign-out">DECONNEXION</a>
                    </li>';
            }
            // If not connected then display connect button
            else {
                $entries_str = $entries_str.'
                    <li '.($this->active==NavbarEntry::connection ? 'class="selected"' : '').'>
                        <a href="/sign-in">CONNEXION</a>
                    </li>';
            }

            $template->fill_placeholder("navbar_entries_str", $entries_str);

            $template->display();

            echo('<script src="/components/navigation/navbar/navbar.js" defer></script>');
        }
    }
?>
