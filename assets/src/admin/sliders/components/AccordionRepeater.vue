<template>
	<div class="carousel-slider-toggle" :class="{'is-opened':isActive}">
		<div class="carousel-slider-toggle__heading">
			<span class="carousel-slider-toggle__title" @click="isActive = !isActive">{{title}}</span>
			<span class="carousel-slider-toggle__icons">
				<span class="carousel-slider-toggle__icons-copy" @click="clickCopy">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
						<path fill="none" d="M0 0h24v24H0V0z"></path>
						<path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm-1 4H8c-1.1 0-1.99.9-1.99 2L6 21c0 1.1.89 2 1.99 2H19c1.1 0 2-.9 2-2V11l-6-6zM8 21V7h6v5h5v9H8z"></path>
					</svg>
				</span>
				<span class="carousel-slider-toggle__icons-clear" @click="clickClear">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
						<path fill="none" d="M0 0h24v24H0V0z"></path>
						<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"></path>
					</svg>
				</span>
			</span>
		</div>
		<div class="carousel-slider-toggle__panel" :class="{'is-panel-opened':isActive}">
			<slot></slot>
		</div>
	</div>
</template>

<script>
	export default {
		name: "AccordionRepeater",
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
		methods: {
			clickCopy() {
				this.$emit('click:copy');
			},
			clickClear() {
				this.$emit('click:clear');
			}
		}
	}
</script>

<style lang="scss">
	.carousel-slider-toggle {
		// border-top: 1px solid #e2e4e7;
		background-color: #fff;

		&:not(:last-child) {
			margin-bottom: 15px;
		}

		&:last-child {
			// border-bottom: 1px solid #e2e4e7;
		}

		&__heading {
			position: relative;
			outline: none;
			width: 100%;
			font-weight: 600;
			text-align: left;
			color: #191e23;
			border: none;
			border: 1px solid #e2e4e7;
			box-shadow: none;
			transition: 0.1s background ease-in-out;

			display: flex;
			justify-content: space-between;

			&:hover {
				// background: #f8f9f9;
			}

			.is-opened & {
				// background: #f5f5f5;
			}
		}

		&__title {
			padding: 10px;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			display: flex;
			flex-grow: 1;
		}

		&__icons {
			display: flex;

			&-copy,
			&-clear {
				padding: 10px;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				border-left: 1px solid #e2e4e7;

				svg {
					width: 16px;
					height: 16px;
				}
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
				border: 1px solid #e2e4e7;
				border-top-width: 0;
				display: block;
				padding: 15px;
			}
		}
	}
</style>
