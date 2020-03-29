<template>
	<div class="carousel-slider-app">
		<router-view/>
		<confirm-dialog/>
		<notification :options="notification"/>
		<spinner :active="loading"/>
	</div>
</template>

<script>
	import spinner from 'shapla-spinner';
	import notification from 'shapla-notifications';
	import {ConfirmDialog} from 'shapla-confirm-dialog';
	import {mapState} from 'vuex';

	export default {
		name: 'App',
		components: {notification, spinner, ConfirmDialog},
		computed: {
			...mapState(['loading', 'notification']),
		}
	}
</script>

<style lang="scss">
	.carousel-slider-app {
		box-sizing: border-box;

		* {
			box-sizing: border-box;
		}
	}
</style>
