/**
 * Carousel Slider Gallery from URL
 */
(function ($) {
	'use strict';

	var body = $('body'),
		modal = $('#CarouselSliderModal'),
		modalOpenBtn = $('#_images_urls_btn');

	let _i18CS = {url: 'URL', title: 'Title', caption: 'Caption', altText: 'Alt Text', linkToUrl: 'Link To URL'};
	let template = `<div class="carousel_slider-fields">
                    <label class="setting">
                        <span class="name">${_i18CS.url}</span>
                        <input type="url" name="_images_urls[url][]" value="" autocomplete="off">
                    </label>
                    <label class="setting">
                        <span class="name">${_i18CS.title}</span>
                        <input type="text" name="_images_urls[title][]" value="" autocomplete="off">
                    </label>
                    <label class="setting">
                        <span class="name">${_i18CS.caption}</span>
                        <textarea name="_images_urls[caption][]"></textarea>
                    </label>
                    <label class="setting">
                        <span class="name">${_i18CS.altText}</span>
                        <input type="text" name="_images_urls[alt][]" value="" autocomplete="off">
                    </label>
                    <label class="setting">
                        <span class="name">${_i18CS.linkToUrl}</span>
                        <input type="text" name="_images_urls[link_url][]" value="" autocomplete="off">
                    </label>
                    <div class="actions">
                        <span><span class="dashicons dashicons-move"></span></span>
                        <span class="add_row"><span class="dashicons dashicons-plus-alt"></span></span>
                        <span class="delete_row"><span class="dashicons dashicons-trash"></span></span>
                    </div>
                </div>`;

	// URL Images Model
	modalOpenBtn.on('click', function (e) {
		e.preventDefault();
		modal.css("display", "block");
		$("body").addClass("overflowHidden");
	});
	modal.on('click', '.carousel_slider-close', function (e) {
		e.preventDefault();
		modal.css("display", "none");
		$("body").removeClass("overflowHidden");
	});

	var carouselSliderBodyHeight = $(window).height() - (38 + 48 + 32 + 30);
	$('.carousel_slider-modal-body').css('height', carouselSliderBodyHeight + 'px');

	// Append new row
	body.on('click', '.add_row', function () {
		$(this).closest('.carousel_slider-fields').after(template);
	});

	// Delete current row
	body.on('click', '.delete_row', function () {
		$(this).closest('.carousel_slider-fields').remove();
	});

	// Make fields sortable
	$('#carousel_slider_form').sortable();

})(jQuery);
