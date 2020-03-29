<template>
	<div class="carousel-slider-control-button-generator">

		<div class="carousel-slider-control-button-generator--text">
			<h4>Button Text</h4>
			<input type="text" v-model="value.text">
		</div>

		<div class="carousel-slider-control-button-generator--url">
			<h4>Button Link</h4>
			<input type="url" v-model="value.url">
		</div>

		<div class="carousel-slider-control-button-generator--target">
			<h4>Button Link Target</h4>
			<div class="mdl-radio-button-container">
				<radio-button v-model="value.target" value="_self">Save Window</radio-button>
				<radio-button v-model="value.target" value="_blank">New Window</radio-button>
			</div>
		</div>

		<div class="carousel-slider-control-button-generator--type">
			<h4>Button Type</h4>
			<div class="mdl-radio-button-container">
				<radio-button v-model="value.type" value="normal">Normal</radio-button>
				<radio-button v-model="value.type" value="stroke">Stroke</radio-button>
			</div>
		</div>

		<div class="carousel-slider-control-button-generator--size">
			<h4>Button Size</h4>
			<div class="mdl-radio-button-container">
				<radio-button v-model="value.size" value="large">Large</radio-button>
				<radio-button v-model="value.size" value="medium">Medium</radio-button>
				<radio-button v-model="value.size" value="small">Small</radio-button>
			</div>
		</div>

		<div class="carousel-slider-control-button-generator--border-width">
			<h4>Border Width</h4>
			<input type="text" v-model="value.border_width">
		</div>

		<div class="carousel-slider-control-button-generator--border-radius">
			<h4>Border Radius</h4>
			<input type="text" v-model="value.border_radius">
		</div>

		<div class="carousel-slider-control-button-generator--background-color">
			<h4>Background Color</h4>
			<color-picker v-model="value.background_color"></color-picker>
		</div>

		<div class="carousel-slider-control-button-generator--color">
			<h4>Text Color</h4>
			<color-picker v-model="value.color"></color-picker>
		</div>

	</div>
</template>

<script>
	import RadioButton from '../../../material-design-lite/radio-button/mdlRadioButton.vue';
	import ColorPicker from './ColorPicker.vue';

	export default {
		name: "ButtonGenerator",
		components: {ColorPicker, RadioButton},
		props: {
			value: {
				type: Object, default: function () {
					return {}
				}
			},
			supports: {
				type: Array, default: function () {
					return ['text', 'color']
				}
			}
		}
	}
</script>

<style scoped>

</style>
