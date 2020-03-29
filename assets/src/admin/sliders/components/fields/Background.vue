<template>
	<div class="carousel-slider-control-background">

		<!-- background-color -->
		<div class="background-color">
			<h4>Background Color</h4>
			<color-picker v-model="value.color"></color-picker>
		</div>

		<!-- background-image -->
		<div class="background-image">
			<h4>Background Image</h4>
			<background-image v-model="value.image"></background-image>
		</div>

		<template v-if="has_image">

			<!-- background-overlay-color -->
			<div class="background-color" v-if="is_enabled('overlay-color')">
				<h4>Background Overlay</h4>
				<color-picker v-model="value.overlay_color"></color-picker>
			</div>

			<!-- background-repeat -->
			<div class="background-repeat" v-if="is_enabled('repeat')">
				<h4>Background Repeat</h4>
				<select class="widefat" v-model="value.repeat">
					<option v-for="_repeat in background_repeat" :value="_repeat.value" v-html="_repeat.label"></option>
				</select>
			</div>

			<!-- background-position -->
			<div class="background-position" v-if="is_enabled('position')">
				<h4>Background Position</h4>
				<select class="widefat" v-model="value.position">
					<option v-for="_position in background_position" :value="_position.value"
							v-html="_position.label"></option>
				</select>
			</div>

			<!-- background-size -->
			<div class="background-size" v-if="is_enabled('size')">
				<h4>Background Size</h4>
				<div class="buttonset">
					<template v-for="_size in background_size">
						<input class="switch-input screen-reader-text" type="radio" v-model="value.size"
							   :value="_size.value" :id="get_size_id(_size.value)">
						<label class="switch-label switch-label-off" :for="get_size_id(_size.value)"
							   v-html="_size.label"></label>
					</template>
				</div>
			</div>

			<!-- background-attachment -->
			<div class="background-attachment" v-if="is_enabled('attachment')">
				<h4>Background Attachment</h4>
				<div class="buttonset">
					<template v-for="_attachment in background_attachment">
						<input class="switch-input screen-reader-text" type="radio" v-model="value.attachment"
							   :value="_attachment.value" :id="get_attachment_id(_attachment.value)">
						<label class="switch-label switch-label-on" :for="get_attachment_id(_attachment.value)"
							   v-html="_attachment.label"></label>
					</template>
				</div>
			</div>

			<!-- background-effect -->
			<div class="background-effect" v-if="is_enabled('effect')">
				<h4>Ken Burns Effect</h4>
				<select class="widefat" v-model="value.effect">
					<option value="">None</option>
					<option value="zoom-in">Zoom In</option>
					<option value="zoom-out">Zoom Out</option>
				</select>
			</div>
		</template>

	</div>
</template>

<script>
	import ColorPicker from './ColorPicker.vue';
	import BackgroundImage from './BackgroundImage.vue';

	export default {
		name: "Background",
		components: {ColorPicker, BackgroundImage},
		props: {
			value: {
				type: Object, default: function () {
					return {
						attachment: 'fixed',
						size: 'cover',
						position: 'center center',
						repeat: 'no-repeat',
					}
				}
			},
			supports: {
				type: Array, default: function () {
					return ['color', 'image', 'position', 'size']
				}
			}
		},
		computed: {
			has_image() {
				if (typeof this.value.image === "undefined") return false;
				return (typeof this.value.image.src !== "undefined");
			},
			id() {
				return this._uid;
			},
			background_attachment() {
				return [
					{value: 'fixed', label: 'Fixed'},
					{value: 'scroll', label: 'Scroll'},
				]
			},
			background_size() {
				return [
					{value: 'cover', label: 'Cover'},
					{value: 'contain', label: 'Contain'},
					{value: 'auto', label: 'Auto'},
				]
			},
			background_position() {
				return [
					{value: 'left top', label: 'Left Top'},
					{value: 'left center', label: 'Left Center'},
					{value: 'left bottom', label: 'Left Bottom'},
					{value: 'right top', label: 'Right Top'},
					{value: 'right center', label: 'Right Center'},
					{value: 'right bottom', label: 'Right Bottom'},
					{value: 'center top', label: 'Center Top'},
					{value: 'center center', label: 'Center Center'},
					{value: 'center bottom', label: 'Center Bottom'},
				];
			},
			background_repeat() {
				return [
					{value: 'no-repeat', label: 'No Repeat'},
					{value: 'repeat', label: 'Repeat All'},
					{value: 'repeat-x', label: 'Repeat Horizontally'},
					{value: 'repeat-y', label: 'Repeat Vertically'},
				];
			},
		},
		methods: {
			is_enabled(feature) {
				return -1 !== this.supports.indexOf(feature);
			},
			get_attachment_id(attachment) {
				return 'background_attachment_' + attachment + '_' + this.id;
			},
			get_size_id(size) {
				return 'background_size_' + size + '_' + this.id;
			},
		}
	}
</script>

<style lang="scss">
	.carousel-slider-control-background {
		position: relative;

		.background-image h4,
		.background-attachment h4,
		.background-color h4,
		.background-position h4,
		.background-repeat h4,
		.background-size h4,
		.background-effect h4 {
			margin-bottom: 5px;
		}

		.background-attachment .buttonset,
		.background-size .buttonset {
			display: flex;
			flex-wrap: wrap;

			.switch-label {
				background: rgba(0, 0, 0, 0.05);
				border: 1px solid rgba(0, 0, 0, 0.1);
				color: #555;
				padding: 0.5em 1em;
				margin: 0;
				text-align: center;
				flex-grow: 1;
			}

			.switch-input:checked + .switch-label {
				background-color: #3498DB;
				color: #fff;
			}
		}
	}
</style>
