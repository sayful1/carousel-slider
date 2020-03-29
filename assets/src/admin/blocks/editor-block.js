(function (blocks, element, components) {

    var el = element.createElement,
        InspectorControls = blocks.InspectorControls,
        SelectControl = components.SelectControl,
        i18n = window.carousel_slider_i18n;

    blocks.registerBlockType('carousel-slider/slider', {
        title: i18n.block_title,
        icon: 'slides',
        category: 'common',

        attributes: {
            sliderId: {
                type: 'integer',
                default: 0
            }
        },

        edit: function (props) {
            var focus = props.focus;
            var sliderId = props.attributes.sliderId;
            var children = [];

            if (!sliderId)
                sliderId = ''; // Default.

            function onFormChange(newSliderID) {
                // updates the form id on the props
                props.setAttributes({sliderId: newSliderID});
            }

            // Set up the form dropdown in the side bar 'block' settings
            var inspectorControls = el(InspectorControls, {},
                el(SelectControl,
                    {
                        label: i18n.selected_slider,
                        value: sliderId.toString(),
                        options: i18n.sliders,
                        onChange: onFormChange
                    }
                )
            );

            /**
             * Create the div container, add an overlay so the user can interact
             * with the form in Gutenberg, then render the iframe with form
             */
            if ('' === sliderId) {
                children.push(el('div', {style: {width: '100%'}},
                    el('img', {src: i18n.block_logo, className: 'carousel-slider-block-logo'}),
                    el('h3', {className: 'carousel-slider-block-title'}, 'Carousel Slider'),
                    el(SelectControl, {value: sliderId, options: i18n.sliders, onChange: onFormChange})
                ));
            } else {
                children.push(el('div', {className: 'carousel-slider-container'},
                    el('div', {className: 'carousel-slider-overlay'}),
                    el('iframe', {
                        src: i18n.site_url + '?carousel_slider=1&preview=1&slider_id=' + sliderId,
                        height: '0',
                        width: '500',
                        scrolling: 'no'
                    })
                ));
            }

            return [children, !!focus && inspectorControls];
        },

        save: function (props) {
            var sliderId = props.attributes.sliderId;

            if (!sliderId)
                return '';
            /**
             * we're essentially just adding a short code, here is where
             * it's save in the editor
             *
             * return content wrapped in DIV as raw HTML is unsupported
             */
            var returnHTML = '[carousel_slide id=' + parseInt(sliderId) + ']';
            return el('div', null, returnHTML);
        },
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.components
);
