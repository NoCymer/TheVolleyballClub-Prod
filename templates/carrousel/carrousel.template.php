<div class="carrousel-wrapper">
    <div class="controls">
        <div class="prev">
            <?php echo get_public_file("symbols/arrow-left-symbol.svg"); ?>
        </div>
        <div class="selectors">
            {{images_span_el_str}}
        </div>
        <div class="next">
            <?php echo get_public_file("symbols/arrow-right-symbol.svg"); ?>
        </div>
    </div>
    <div class="images-container">
        {{images_el_str}}
    </div>
</div>