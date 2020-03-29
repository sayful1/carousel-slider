<template>
	<div class="carousel-slider-app">
		<router-view></router-view>
		<mdl-snackbar></mdl-snackbar>
	</div>
</template>

<script>
	import mdlSnackbar from '../material-design-lite/snackbar/mdlSnackbar.vue';

	export default {
		name: 'App',
		components: {mdlSnackbar},
	}
</script>

<style lang="scss">
	@import "../material-design-lite/ripple/ripple";

	.carousel-slider-app {
		box-sizing: border-box;

		* {
			box-sizing: border-box;
		}

		.carousel-slider-spinner {
			background-color: rgba(#000, 0.85);
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100%;
			left: 0;
			position: fixed;
			top: 0;
			width: 100%;
			z-index: 99999;
		}
	}
</style>
