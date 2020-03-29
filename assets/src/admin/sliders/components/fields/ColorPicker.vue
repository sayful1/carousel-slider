<template>
	<div>
		<input type="text" :value="value" :data-alpha="alpha">
	</div>
</template>

<script>
	export default {
		name: "ColorPicker",
		model: {prop: 'value', event: 'change'},
		props: {
			value: {type: String, default: ''},
			defaultColor: {default: false},
			alpha: {type: Boolean, default: true},
		},
		data() {
			return {}
		},
		mounted() {
			let $ = window.jQuery, self = this, element = $(this.$el).find('input');
			element.wpColorPicker({
				defaultColor: self.defaultColor,
				change: function (event, ui) {
					self.$emit('input', ui.color.toString());
				},
			});
		}
	}
</script>

<style scoped>

</style>
