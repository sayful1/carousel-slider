<div id="carousel-slider-tab-style" class="shapla-tab tab-style">

    <div class="sp-input-group" id="field-_content_alignment">
        <div class="sp-input-label">
            <label for="_content_alignment"><?php esc_html_e( 'Content Alignment', 'carousel-slider' ); ?></label>
            <p class="sp-input-desc"><?php esc_html_e( 'Select how the heading, description and buttons will be aligned', 'carousel-slider' ); ?></p>
        </div>
        <div class="sp-input-field">
            <select name="carousel_slider_content[<?php echo $slide_num; ?>][content_alignment]"
                    id="_content_alignment" class="sp-input-text">
                <option value="left">Left</option>
                <option value="center">Center</option>
                <option value="right">Right</option>
            </select>
        </div>
    </div>

    <div class="sp-input-group" id="field-_heading_font_size">
        <div class="sp-input-label">
            <label for="_heading_font_size"><?php esc_html_e( 'Heading Font Size', 'carousel-slider' ); ?></label>
            <p class="sp-input-desc"><?php esc_html_e( 'Enter heading font size without px unit. In pixels, ex: 50 instead of 50px. Default: 60', 'carousel-slider' ); ?></p>
        </div>
        <div class="sp-input-field">
            <input type="number" id="_heading_font_size"
                   class="regular-text"
                   name="carousel_slider_content[<?php echo $slide_num; ?>][heading_font_size]">
        </div>
    </div>

    <div class="sp-input-group" id="field-_heading_color">
        <div class="sp-input-label">
            <label for="_heading_color"><?php esc_html_e( 'Heading Color', 'carousel-slider' ); ?></label>
            <p class="sp-input-desc"><?php esc_html_e( 'Select a color for the heading font. Default: #fff', 'carousel-slider' ); ?></p>
        </div>
        <div class="sp-input-field">
            <input type="text" id="_heading_color"
                   class="color-picker"
                   data-default-color="#ffffff" data-alpha="true"
                   name="carousel_slider_content[<?php echo $slide_num; ?>][heading_color]">
        </div>
    </div>

    <div class="sp-input-group" id="field-_heading_background_color">
        <div class="sp-input-label">
            <label for="_heading_background_color"><?php esc_html_e( 'Heading Background Color', 'carousel-slider' ); ?></label>
            <p class="sp-input-desc"><?php esc_html_e( 'If you would like a semi-transparent background behind your heading, Choose from here. If you would not like, leave it blank. Default: rgba(0,0,0, 0.4)', 'carousel-slider' ); ?></p>
        </div>
        <div class="sp-input-field">
            <input type="text" id="_heading_background_color"
                   class="color-picker" data-alpha="true"
                   data-default-color="rgba(0,0,0, 0.4)"
                   name="carousel_slider_content[<?php echo $slide_num; ?>][heading_background_color]">
        </div>
    </div>

    <div class="sp-input-group" id="field-_description_font_size">
        <div class="sp-input-label">
            <label for="_description_font_size"><?php esc_html_e( 'Description Font Size', 'carousel-slider' ); ?></label>
            <p class="sp-input-desc"><?php esc_html_e( 'Enter description font size without px unit. In pixels, ex: 20 instead of 20px. Default: 24', 'carousel-slider' ); ?></p>
        </div>
        <div class="sp-input-field">
            <input type="number" id="_description_font_size"
                   class="regular-text"
                   name="carousel_slider_content[<?php echo $slide_num; ?>][description_font_size]">
        </div>
    </div><!-- Description Font Size -->

    <div class="sp-input-group" id="field-_description_color">
        <div class="sp-input-label">
            <label for="_description_color"><?php esc_html_e( 'Description Color', 'carousel-slider' ); ?></label>
            <p class="sp-input-desc"><?php esc_html_e( 'Select a color for the description font. Default: #fff', 'carousel-slider' ); ?></p>
        </div>
        <div class="sp-input-field">
            <input type="text" id="_description_color"
                   class="color-picker"
                   data-default-color="#ffffff" data-alpha="true"
                   name="carousel_slider_content[<?php echo $slide_num; ?>][description_color]">
        </div>
    </div>

    <div class="sp-input-group" id="field-_description_background_color">
        <div class="sp-input-label">
            <label for="_description_background_color"><?php esc_html_e( 'Description Background Color', 'carousel-slider' ); ?></label>
            <p class="sp-input-desc"><?php esc_html_e( 'If you would like a semi-transparent background behind your description, Choose from here. If you would not like, leave it blank. Default: rgba(0,0,0, 0.4)', 'carousel-slider' ); ?></p>
        </div>
        <div class="sp-input-field">
            <input type="text" id="_description_background_color"
                   class="color-picker" data-alpha="true"
                   data-default-color="rgba(0,0,0, 0.4)"
                   name="carousel_slider_content[<?php echo $slide_num; ?>][description_background_color]">
        </div>
    </div>

</div>
<!-- .tab-style -->