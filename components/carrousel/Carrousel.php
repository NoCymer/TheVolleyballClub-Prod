<?php
    namespace Components\Carrousel;

    use Templates\Template;
    class Carrousel{
        private $images;
        public function __construct($images) {
            $this->images = $images;
        }
        public function display() {
            $template = new Template("carrousel/carrousel.template.php");

            $span_str = "";
            foreach($this->images as $image) $span_str.="<span></span>";

            $imgs_str = "";
            foreach($this->images as $image) $imgs_str.="<img src=\"$image\" alt=\"\">";
            
            $template->fill_placeholder("images_span_el_str", $span_str);
            $template->fill_placeholder("images_el_str", $imgs_str);
            $template->display();
            
            echo('<script src="/components/carrousel/carrousel.js" defer></script>');
        }
    }
?>
