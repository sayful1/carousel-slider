<template>
	<div class="carousel-slider-edit-page">
		<h1 class="wp-heading-inline">Edit Slider</h1>
		<div class="clear"></div>
		<div class="carousel-slider-content">
			<div id="titlediv">
				<div id="titlewrap">
					<label class="screen-reader-text" id="title-prompt-text" for="title">Enter title here</label>
					<input type="text" name="post_title" size="30" v-model="slider.title" id="title"
						   spellcheck="true" autocomplete="off">
				</div>
			</div>
			<div class="carousel-slider-preview">
				<template v-if="slider.type === 'image-carousel'">
					<image-carousel :options="slider"></image-carousel>
				</template>
				<template v-if="slider.type === 'image-carousel-url'">
					<image-carousel-url :options="slider"></image-carousel-url>
				</template>
				<template v-if="slider.type === 'video-carousel'">
					<video-carousel :options="slider"></video-carousel>
				</template>
				<template v-if="slider.type === 'hero-banner-slider'">
					<hero-carousel :options="slider"></hero-carousel>
				</template>
				<template v-if="slider.type === 'post-carousel'">
					<post-carousel :options="slider"></post-carousel>
				</template>
			</div>
		</div>
		<div class="carousel-slider-sidebar">
			<accordion v-for="section in _sections" :title="section.title" :key="section.id" :active="section.active">
				<template v-for="field in _fields" v-if="field.section === section.id">
					<div class="carousel-slider-control__field" :data-type="field.type" v-show="fieldConditions(field)">
						<label class="carousel-slider-control__label" :for="field.id" v-if="'gallery' !== field.type">
							{{field.label}}
							<template v-if="field.description">
								<mdl-tooltip :large="true">
									<div v-html="field.description"></div>
								</mdl-tooltip>
							</template>
						</label>
						<template v-if="'repeater' === field.type">
							<draggable v-model="slider[field.id]">
								<template v-for="(item, index) in slider[field.id]">
									<accordion-repeater :title="getItemTitle(index)" :key="index + 1"
														@click:clear="clearItem(item, slider[field.id])"
														@click:copy="copyItem(item, slider[field.id])">
										<template v-for="_field in field.fields">
											<div class="carousel-slider-control__field"
												 v-show="fieldConditions(_field, item)">
												<template v-if="isTextfield(_field.type)">
													<label class="carousel-slider-control__label">{{_field.label}}</label>
													<input class="widefat" :type="_field.type"
														   v-model="item[_field.id]">
												</template>
												<template v-if="'textarea' === _field.type">
													<label class="carousel-slider-control__label">{{_field.label}}</label>
													<textarea class="widefat" :rows="_field.input_attrs.rows"
															  v-model="item[_field.id]"></textarea>
												</template>
												<template v-if="'background' === _field.type">
													<accordion :title="_field.label">
														<background v-model="item[_field.id]"
																	:supports="_field.supports"></background>
													</accordion>
												</template>
												<template v-if="'rich-text' === _field.type">
													<accordion :title="_field.label">
														<rich-text v-model="item[_field.id]"
																   :supports="_field.supports"></rich-text>
													</accordion>
												</template>
												<template v-if="'button-generator' === _field.type">
													<accordion :title="_field.label">
														<button-generator v-model="item[_field.id]"
																		  :supports="_field.supports"></button-generator>
													</accordion>
												</template>
												<template v-if="'radio-button' === _field.type">
													<label class="carousel-slider-control__label">{{_field.label}}</label>
													<div class="mdl-radio-button-container">
														<mdl-radio-button v-for="(value, key) in _field.choices"
																		  :key="key" v-model="item[_field.id]"
																		  :value="key"> {{value}}
														</mdl-radio-button>
													</div>
												</template>
											</div>
										</template>
									</accordion-repeater>
								</template>
							</draggable>
							<div class="media-gallery-button">
								<mdl-button type="raised" color="default"
											@click="addRepeaterItem(field, slider[field.id])">{{field.button_text}}
								</mdl-button>
							</div>
						</template>
						<template v-if="'select' === field.type">
							<select :id="field.id" v-model="slider[field.id]" class="carousel-slider-control__input">
								<option v-for="(choice, key) in field.choices" :value="key">{{choice}}</option>
							</select>
						</template>
						<template v-if="'slider' === field.type">
							<mdl-slider v-model="slider[field.id]" :default="field.default" :min="getMin(field)"
										:max="getMax(field)" :step="getStep(field)"></mdl-slider>
						</template>
						<template v-if="'switch' === field.type">
							<mdl-switch v-model="slider[field.id]"></mdl-switch>
						</template>
						<template v-if="'color' === field.type">
							<color-picker v-model="slider[field.id]" :default-color="field.default"></color-picker>
						</template>
						<template v-if="'radio-button' === field.type">
							<div class="mdl-radio-button-container">
								<mdl-radio-button v-for="(value, key) in field.choices" :key="key"
												  v-model="slider[field.id]" :value="key">{{value}}
								</mdl-radio-button>
							</div>
						</template>
						<template v-if="'gallery' === field.type">
							<media-uploader v-model="slider[field.id]" :multiple="true"></media-uploader>
						</template>
						<template v-if="isTextfield(field.type)">
							<input class="widefat" :type="field.type" v-model="slider[field.id]">
						</template>
					</div>
				</template>
			</accordion>
		</div>
		<mdl-fab @click="saveSlider">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<path fill="none" d="M0 0h24v24H0V0z"></path>
				<path fill="white"
					  d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm2 16H5V5h11.17L19 7.83V19zm-7-7c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM6 6h9v4H6z"></path>
			</svg>
		</mdl-fab>
		<div class="carousel-slider-spinner" v-show="loading">
			<mdl-spinner :active="loading"></mdl-spinner>
		</div>
	</div>
</template>

<script>
	import draggable from 'vuedraggable';
	import Accordion from '../components/Accordion.vue';
	import ImageCarousel from '../components/sliders/ImageCarousel.vue';
	import ImageCarouselUrl from '../components/sliders/ImageCarouselUrl.vue';
	import VideoCarousel from '../components/sliders/VideoCarousel.vue';
	import HeroCarousel from '../components/sliders/HeroCarousel.vue';
	import PostCarousel from '../components/sliders/PostCarousel.vue';
	import AccordionRepeater from '../components/AccordionRepeater.vue';
	import ColorPicker from '../components/fields/ColorPicker.vue';
	import MediaUploader from '../components/fields/MediaUploader.vue';
	import Background from '../components/fields/Background.vue';
	import RichText from '../components/fields/RichText.vue';
	import ButtonGenerator from '../components/fields/ButtonGenerator.vue';
	import mdlSlider from '../../material-design-lite/slider/mdlSlider.vue';
	import mdlSwitch from '../../material-design-lite/switch/mdlSwitch.vue';
	import mdlRadioButton from '../../material-design-lite/radio-button/mdlRadioButton.vue';
	import mdlFab from '../../material-design-lite/button/mdlFab.vue';
	import mdlTooltip from '../../material-design-lite/tooltip/mdlTooltip.vue';
	import mdlButton from '../../material-design-lite/button/mdlButton.vue';
	import mdlTextfield from '../../material-design-lite/textfield/mdlTextfield.vue';
	import mdlSpinner from '../../material-design-lite/spinner/mdlSpinner.vue';

	export default {
		name: "Slider",
		components: {
			draggable,
			Accordion,
			AccordionRepeater,
			mdlSlider,
			mdlSwitch,
			mdlRadioButton,
			ColorPicker,
			mdlFab,
			mdlTooltip,
			mdlButton,
			mdlTextfield,
			mdlSpinner,
			MediaUploader,
			ImageCarousel,
			ImageCarouselUrl,
			VideoCarousel,
			HeroCarousel,
			PostCarousel,
			Background,
			RichText,
			ButtonGenerator,
		},
		data() {
			return {
				id: 0,
				loading: true,
				slider: {},
				sections: [],
				fields: [],
				modules: [],
			}
		},
		mounted() {
			let settings = window.CAROUSEL_SLIDER_SETTINGS;
			this.sections = settings.general_settings.sections;
			this.fields = settings.general_settings.fields;
			this.modules = settings.modules_settings;
			this.id = parseInt(this.$route.params.id);
			this.getItem();
		},
		computed: {
			_sections() {
				if (typeof this.slider.type !== "undefined") {
					let sections = this.modules[this.slider.type]['sections'];

					return sections.concat(this.sections);
				}
				return this.sections;
			},
			_fields() {
				if (typeof this.slider.type !== "undefined") {
					let fields = this.modules[this.slider.type]['fields'];

					return fields.concat(this.fields);
				}
				return this.fields;
			}
		},
		methods: {
			fieldConditions(settings, item) {
				if (typeof settings.conditions === "undefined") return true;
				let conditions = settings.conditions,
						firstCondition = conditions[0],
						value = this.slider[firstCondition.name];

				if (typeof item !== "undefined") {
					value = item[firstCondition.name]
				}

				if (firstCondition.operator === '==' && (value === firstCondition.value)) {
					return true;
				}
				if (firstCondition.operator === '!=' && (value !== firstCondition.value)) {
					return true;
				}
				return false;
			},
			isTextfield(type) {
				let valid = ['text', 'number', 'url', 'date'];

				return valid.indexOf(type) !== -1;
			},
			getItem() {
				let $ = window.jQuery, self = this;
				self.loading = true;
				$.ajax({
					method: 'GET',
					url: carouselSliderSettings.root + '/sliders/' + self.id,
					success: function (response) {
						if (response.data) {
							self.slider = response.data;
						}
						self.loading = false;
					},
					error: function () {
						self.loading = false;
					}
				});
			},
			getMin(slider) {
				if (typeof slider.input_attrs.min !== "undefined") {
					return slider.input_attrs.min;
				}

				return 0;
			},
			getMax(slider) {
				if (typeof slider.input_attrs.max !== "undefined") {
					return slider.input_attrs.max;
				}

				return 100;
			},
			getStep(slider) {
				if (typeof slider.input_attrs.step !== "undefined") {
					return slider.input_attrs.step;
				}

				return 1;
			},
			saveSlider() {
				let $ = jQuery, self = this;
				self.loading = true;
				$.ajax({
					url: window.carouselSliderSettings.root + '/sliders/' + self.slider.id,
					method: 'PUT',
					data: self.slider,
					success: function (response) {
						if (response.data) {
							self.slider = response.data;
						}
						self.loading = false;
						self.$root.$emit('show-snackbar', {
							message: 'Data hes been saved!',
						});
					},
					error: function () {
						self.loading = false;
					}
				});
			},
			getItemTitle(index) {
				return 'Item ' + (index + 1);
			},
			addRepeaterItem(field, options) {
				let _ids = {}, fields = field.fields ? field.fields : [];
				fields.map((el) => {
					_ids[el.id] = '';
				});
				options.push(_ids);
			},
			copyItem(item, items) {
				let index = items.indexOf(item) + 1;
				items.splice(index, 0, item);
			},
			clearItem(item, items) {
				this.$delete(items, items.indexOf(item));
			}
		}
	}
</script>

<style lang="scss">
	.carousel-slider-edit-page {
		width: 100%;
		height: 100%;

		.mdl-button--fab {
			position: fixed;
			bottom: 20px;
			right: 20px;
			z-index: 100;
		}

		.mdl-tooltip--icon {
			svg {
				width: 16px;
				height: 16px;
				fill: #555d66;
			}
		}

		.mdl-tooltip--container {
			margin-left: 4px;
		}
	}

	.mdl-slider__reset-icon {
		box-sizing: border-box;
		height: 32px;
		width: 32px;
	}

	.carousel-slider-control {
		&__field {
			&:not(:last-child) {
				margin-bottom: 15px;
				padding-bottom: 15px;
				border-bottom: 1px solid rgba(#000, 0.12);
			}

			&[data-type="switch"] {
				display: flex;

				.mdl-switch {
					width: auto;
					margin-right: 10px;
				}

				> * {
					display: inline-flex;
				}
			}
		}

		&__label, &__input {
			display: block;
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
			font-size: 13px;
			width: 100%;
		}

		&__label {
			color: rgba(#000, .85);
			margin-bottom: 10px;
			display: flex;
			align-items: center;
		}

		&__input {
			background: #fff;
			height: 36px;
			line-height: 36px;
			margin: 1px;
			outline: 0;
			width: 100%;
			-webkit-tap-highlight-color: rgba(0, 0, 0, 0) !important;

			@media (min-width: 782px) {
				height: 28px;
				line-height: 28px;
			}
		}

		input, select, textarea {
			padding: 6px 8px;
			box-shadow: 0 0 0 transparent;
			transition: box-shadow 0.1s linear;
			border-radius: 4px;
			border: 1px solid #8d96a0;
			color: #555d66;
		}
	}

	.carousel-slider-content {
		display: block;
		width: 100%;
	}

	.carousel-slider-sidebar {
		margin: 20px 0 0;
		width: 100%;
	}

	.mdl-radio-button-container {
		display: flex;

		.mdl-radio-button:not(:last-child) {
			border-right: 1px solid rgba(#000, .45);
		}
	}

	@media (min-width: 782px) {
		.carousel-slider-content {
			float: left;
			width: calc(100% - 340px);
		}

		.carousel-slider-sidebar {
			margin: 0 0 20px 20px;
			width: 320px;
			float: right;
		}
	}

	.carousel-slider-preview {
		background: white;
		margin-top: 20px;
		min-height: 100px;
		padding: 50px;
		width: 100%;
	}
</style>
