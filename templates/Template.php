<?php
    namespace Templates;

    /**
     * Template construction's behaviour 
     */
    enum TemplateBehaviour{
        /**
         * When the template is fetched at construction
         */
        case Fetch;

        /**
         * When the template is provided at construction
         */
        case Provide;
    }

    /**
     * Template class used to fetch / modify php templates files
     */
    class Template {
        /** Template contained */
        private $template;

        private static $template_dir = TEMPLATES;

        /**
         * Creates a template
         * @param string $template Either the path to the template or the template itself (path by default)
         * @param TemplateBehaviour $template_behaviour ( Optional ) Whether a path or the template is passed as first argument (path by default)
         */
        public function __construct($template, $template_behaviour = TemplateBehaviour::Fetch) {
            switch ($template_behaviour) {
                case TemplateBehaviour::Fetch:

                    // If the templates's path is not present
                    // Then adds it
                    if(!str_contains($template, Template::$template_dir))
                        $template = Template::$template_dir.$template;

                    $this->template = file_get_contents($template);
                    break;
                case TemplateBehaviour::Provide:
                    $this->template = $template;
                    break;
            }
        }
        
        /**
         * Replaces a given placeholder by its value
         */
        public function fill_placeholder($placeholder, $value) {
            $this->template = str_replace("{{".$placeholder."}}", $value, $this->template);
        }

        /**
         * Displays the template
         */
        public function display() {
            eval("?>"."".$this->template);
        }
    }