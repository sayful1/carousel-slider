<template>
	<div class="carousel-slider-control-rich-text">

		<div class="carousel-slider-control-rich-text--input" v-if="is_enabled('text')">
			<h5>Text</h5>
			<textarea rows="3" v-model="value.text"></textarea>
		</div>

		<div class="carousel-slider-control-rich-text--font-family" v-if="is_enabled('font-family')">
			<h5>Font Family</h5>
			<select id="shapla-typography-font-family" class="widefat">
				<option value="default">Default</option>
			</select>
		</div>

		<div class="carousel-slider-control-rich-text--variant" v-if="is_enabled('font-weight')">
			<h5>Variant</h5>
			<select class="widefat" id="shapla-typography-variant">
				<option value="default">Default</option>
			</select>
		</div>

		<div class="carousel-slider-control-rich-text--font-size" v-if="is_enabled('font-size')">
			<h5>Font Size</h5>
			<input type="text" v-model="value.font_size"/>
		</div>

		<div class="carousel-slider-control-rich-text--line-height" v-if="is_enabled('line-height')">
			<h5>Line Height</h5>
			<input type="text" value=""/>
		</div>

		<div class="carousel-slider-control-rich-text--letter-spacing" v-if="is_enabled('letter-spacing')">
			<h5>Letter Spacing</h5>
			<input type="text" value=""/>
		</div>

		<div class="carousel-slider-control-rich-text--word-spacing" v-if="is_enabled('word-spacing')">
			<h5>Word Spacing</h5>
			<input type="text" value=""/>
		</div>

		<div class="carousel-slider-control-rich-text--text-align" v-if="is_enabled('text-align')">
			<h5>Text Align</h5>
			<div class="text-align-choices">
				<input type="radio" value="inherit" name="_customize-typography-text-align-radio-"
					   id="-text-align-inherit">
				<label for="-text-align-inherit">
					<span class="dashicons dashicons-editor-removeformatting"></span>
					<span class="screen-reader-text">Inherit</span>
				</label>

				<input type="radio" value="left" name="_customize-typography-text-align-radio-" id="-text-align-left">
				<label for="-text-align-left">
					<span class="dashicons dashicons-editor-alignleft"></span>
					<span class="screen-reader-text">Left</span>
				</label>

				<input type="radio" value="center" name="_customize-typography-text-align-radio-"
					   id="-text-align-center"/>
				<label for="-text-align-center">
					<span class="dashicons dashicons-editor-aligncenter"></span>
					<span class="screen-reader-text">Center</span>
				</label>
				<input type="radio" value="right" name="_customize-typography-text-align-radio-"
					   id="-text-align-right"/>
				<label for="-text-align-right">
					<span class="dashicons dashicons-editor-alignright"></span>
					<span class="screen-reader-text">Right</span>
				</label>

				<input type="radio" value="justify" name="_customize-typography-text-align-radio-"
					   id="-text-align-justify"/>
				<label for="-text-align-justify">
					<span class="dashicons dashicons-editor-justify"></span>
					<span class="screen-reader-text">Justify</span>
				</label>
			</div>
		</div>

		<div class="carousel-slider-control-rich-text--text-transform" v-if="is_enabled('text-transform')">
			<h5>Text Transform</h5>
			<select v-model="value.text_transform">
				<option value="none">None</option>
				<option value="capitalize">Capitalize</option>
				<option value="uppercase">Uppercase</option>
				<option value="lowercase">Lowercase</option>
				<option value="initial">Initial</option>
				<option value="inherit">Inherit</option>
			</select>
		</div>

		<div class="carousel-slider-control-rich-text--text-decoration" v-if="is_enabled('text-decoration')">
			<h5>Text Decoration</h5>
			<select v-model="value.text_decoration">
				<option value="none">None</option>
				<option value="underline">Underline</option>
				<option value="overline">Overline</option>
				<option value="line-through">Line-Through</option>
				<option value="initial">Initial</option>
				<option value="inherit">Inherit</option>
			</select>
		</div>

		<div class="carousel-slider-control-rich-text--margin-top" v-if="is_enabled('margin-top')">
			<h5>Margin Top</h5>
			<input type="text" v-model="value.margin_top">
		</div>

		<div class="carousel-slider-control-rich-text--margin-bottom" v-if="is_enabled('margin-bottom')">
			<h5>Margin Bottom</h5>
			<input type="text" v-model="value.margin_bottom">
		</div>

		<div class="carousel-slider-control-rich-text--color" v-if="is_enabled('color')">
			<h5>Color</h5>
			<color-picker v-model="value.color"></color-picker>
		</div>

	</div>
</template>

<script>
	import ColorPicker from './ColorPicker.vue';

	export default {
		name: "RichText",
		components: {ColorPicker},
		props: {
			value: {type: Object},
			supports: {
				type: Array, default: function () {
					return ['text', 'color', 'font-size']
				}
			}
		},
		methods: {
			is_enabled(feature) {
				return -1 !== this.supports.indexOf(feature);
			},
		}
	}
</script>

<style lang="scss">
	.carousel-slider-control-rich-text {
		position: relative;
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;

		h5 {
			margin: 0.67em 0 0;
		}

		input, textarea {
			width: 100%;
		}

		&--input,
		&--color,
		&--font-backup,
		&--font-family,
		&--font-size,
		&--letter-spacing,
		&--line-height,
		&--margin-bottom,
		&--margin-top,
		&--subsets,
		&--text-transform,
		&--variant {
			width: 100%;
			float: none;
			clear: both;
		}

		&--font-size,
		&--letter-spacing,
		&--line-height,
		&--margin-bottom,
		&--margin-top,
		&--text-transform,
		&--word-spacing {
			width: 48%;
		}

		&--color {
			width: auto;
		}
	}
</style>
