<template>
	<div :class="outer_classes">
		<div :id='slider_id' :class='slider_classes' data-carousel_slider="true" :data-slide-type='options.type'>
			<template v-for="data in options.content">
				<div class="carousel-slider-hero__cell">
					<div :class="cell_background_classes(data)" :style="cell_background_styles(data)"></div>
					<div :class="cell_inner_classes(data)">
						<div class="carousel-slider-hero__cell__background_overlay"
							 :style="cell_background_overlay(data)"></div>
						<div class="carousel-slider-hero__cell__content cell_content">
							<div class="carousel-slider-hero__cell__heading" v-html="data.heading.text"></div>
							<div class="carousel-slider-hero__cell__description" v-html="data.description.text"></div>
							<div class="carousel-slider-hero__cell__buttons" v-if="'button' === data.link_type">
								<span class="carousel-slider-hero__cell__button__one" v-if="data.button_one.url">
									<a :class="button_one_classes(data.button_one)"
									   :style="button_one_styles(data.button_one)" :href="data.button_one.url"
									   :target="data.button_one.target">{{data.button_one.text}}</a>
								</span>
							</div>
						</div>
					</div>
				</div>
			</template>
		</div>
	</div>
</template>

<script>
	import {carousel} from "./carousel";

	export default {
		name: "HeroCarousel",
		mixins: [carousel],
		props: {
			options: {type: Object, required: true}
		},
		mounted() {
			let $ = window.jQuery, slider = $(this.$el).find("[data-carousel_slider]");
			slider.owlCarousel(this.owl_options);
		},
		methods: {
			cell_background_styles(data) {
				return {
					'background-color': data.background.color,
					'background-image': 'url(' + data.background.image.src + ')',
					'background-size': data.background.size,
					'background-position': data.background.position,
				}
			},
			cell_background_overlay(data) {
				return {'background-color': data.background.overlay_color,}
			},
			cell_background_classes(data) {
				let classes = ['carousel-slider-hero__cell__background', 'cell_background'];
				if (data.background.effect === 'zoom-in') {
					classes.push('carousel-slider-hero-ken-in');
				}
				if (data.background.effect === 'zoom-out') {
					classes.push('carousel-slider-hero-ken-out');
				}

				if (this.options.lazy_load_image) {
					classes.push('owl-lazy');
				}

				return classes;
			},
			cell_inner_classes(data) {
				let classes = ['carousel-slider-hero__cell__inner', 'cell_inner', 'carousel-slider--h-position-center'];
				classes.push('carousel-slider--v-position-middle');
				if (data.content_alignment === 'left') {
					classes.push('carousel-slider--text-left');
				} else if (data.content_alignment === 'right') {
					classes.push('carousel-slider--text-right');
				} else {
					classes.push('carousel-slider--text-center');
				}

				return classes;
			},
			button_one_classes(data) {
				let classes = ['cs-hero-button'];
				classes.push('cs-hero-button-' + data.type);
				classes.push('cs-hero-button-' + data.size);

				return classes;
			},
			button_one_styles(data) {
				let styles = {'border-style': 'solid'};
				styles['border-color'] = data.background_color;
				styles['border-width'] = data.border_width;
				styles['border-radius'] = data.border_radius;

				if (data.type === 'stroke') {
					styles['background-color'] = 'transparent';
					styles['color'] = data.background_color;
				} else {
					styles['background-color'] = data.background_color;
					styles['color'] = data.color;
				}

				return styles;
			}
		}
	}
</script>

<style lang="scss">

</style>
