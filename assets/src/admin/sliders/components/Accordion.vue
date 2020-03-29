<template>
	<div class="carousel-slider-accordion" :class="{'is-opened':isActive}">
		<div class="carousel-slider-accordion__heading" :class="{'is-heading-opened':isActive}"
			 @click="isActive = !isActive">
			<span class="carousel-slider-accordion__title">{{title}}</span>
			<span class="carousel-slider-accordion__icon">
				<svg class="carousel-slider-accordion__arrow" width="24px" height="24px" viewBox="0 0 24 24"
					 xmlns="http://www.w3.org/2000/svg" focusable="false">
					<g><path fill="none" d="M0,0h24v24H0V0z"></path></g>
					<g v-if="isActive"><path d="M12,8l-6,6l1.41,1.41L12,10.83l4.59,4.58L18,14L12,8z"></path></g>
					<g v-else><path d="M7.41,8.59L12,13.17l4.59-4.58L18,10l-6,6l-6-6L7.41,8.59z"></path></g>
				</svg>
			</span>
		</div>
		<div class="carousel-slider-accordion__panel" :class="{'is-panel-opened':isActive}">
			<slot></slot>
		</div>
	</div>
</template>

<script>
	export default {
		name: "Accordion",
		props: {
			active: {type: Boolean, default: false},
			title: {type: String, required: true}
		},
		data() {
			return {
				isActive: false,
			}
		},
		mounted() {
			if (this.active) {
				this.isActive = true;
			}
		},
		computed: {}
	}
</script>

<style lang="scss">
	.carousel-slider-accordion {
		//border-top: 1px solid #e2e4e7;
		background-color: #fff;

		.carousel-slider-accordion {
			border: 1px solid #e2e4e7;
		}

		&:not(:last-child) {
			margin-bottom: 15px;
		}

		&:last-child {
			//border-bottom: 1px solid #e2e4e7;
		}

		&__heading {
			position: relative;
			padding: 15px;
			outline: none;
			width: 100%;
			font-weight: 600;
			text-align: left;
			color: #191e23;
			border: none;
			box-shadow: none;
			transition: 0.1s background ease-in-out;

			&:hover {
				background: #f8f9f9;
			}

			&.is-heading-opened {
				background: #f5f5f5;
			}
		}

		&__arrow {
			position: absolute;
			right: 10px;
			top: 50%;
			transform: translateY(-50%);
			color: #191e23;
			fill: currentColor;
			transition: 0.1s color ease-in-out;
		}

		&__panel {
			display: none;

			&.is-panel-opened {
				padding: 15px;
				display: block;
			}
		}
	}
</style>
