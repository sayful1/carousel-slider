import React, {Component} from "react";
import $ from 'jquery';

class DiviBuilderModule extends Component {
	static slug = 'carousel_slider_divi_module';

	render() {
		// @ts-ignore
		const slider_id = this.props.slider_id;

		if (!slider_id) {
			return null;
		}
		let previewUrl = new URL(window.csDivi.site_url);
		previewUrl.searchParams.append('carousel_slider_preview', '1');
		previewUrl.searchParams.append('carousel_slider_iframe', '1');
		previewUrl.searchParams.append('slider_id', slider_id);
		let iFrameSrc = previewUrl.toString();

		return (
			<div className="carousel-slider-iframe-container">
				<div className="carousel-slider-iframe-overlay"/>
				<iframe className="carousel-slider-iframe" scrolling="no" src={iFrameSrc} height="0" width="500"/>
			</div>
		)
	}
}

$(window).on('et_builder_api_ready', (event, API) => {
	API.registerModules([DiviBuilderModule]);
});
