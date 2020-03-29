<template>
	<div class="carousel-slider-list-page">
		<h1 class="wp-heading-inline">Help</h1>
		<div class="clear"></div>

		<div class="cs-columns">
			<div class="cs-column cs-column-3">
				<div class="cs-card cs-shadow--4dp">
					<div class="carousel-slider-iframe">
						<iframe width="1280" height="720" src="https://www.youtube.com/embed/_hVsamgr1k4"
								frameborder="0" allowfullscreen></iframe>
					</div>
					<h2 class="cs-card-title">Hero Slider</h2>
					<p class="cs-card-description">Hero carousel demo.</p>
				</div>
			</div>
			<div class="cs-column cs-column-3">
				<div class="cs-card cs-shadow--4dp">
					<div class="carousel-slider-iframe">
						<iframe width="1280" height="720" src="https://www.youtube.com/embed/ZzI1JhElrxc"
								frameborder="0" allowfullscreen></iframe>
					</div>
					<h2 class="cs-card-title">Images Carousel</h2>
					<p class="cs-card-description">Image carousel using gallery images.</p>
				</div>
			</div>
			<div class="cs-column cs-column-3">
				<div class="cs-card cs-shadow--4dp">
					<div class="carousel-slider-iframe">
						<iframe width="1280" height="720"
								src="https://www.youtube.com/embed/a7hqn1yNzwM" frameborder="0"
								allowfullscreen></iframe>
					</div>
					<h2 class="cs-card-title">Images Carousel</h2>
					<p class="cs-card-description">Image carousel using custom URLs</p>
				</div>
			</div>
			<div class="cs-column cs-column-3">
				<div class="cs-card cs-shadow--4dp">
					<div class="carousel-slider-iframe">
						<iframe width="1280" height="720"
								src="https://www.youtube.com/embed/ImJB946azy0" frameborder="0"
								allowfullscreen></iframe>
					</div>
					<h2 class="cs-card-title">Posts Carousel</h2>
					<p class="cs-card-description"></p>
				</div>
			</div>
			<div class="cs-column cs-column-3">
				<div class="cs-card cs-shadow--4dp">
					<div class="carousel-slider-iframe">
						<iframe width="1280" height="720"
								src="https://www.youtube.com/embed/yiAkvXyfakg" frameborder="0"
								allowfullscreen></iframe>
					</div>
					<h2 class="cs-card-title">WooCommerce Products Carousel</h2>
					<p class="cs-card-description"></p>
				</div>
			</div>
			<div class="cs-column cs-column-3">
				<div class="cs-card cs-shadow--4dp">
					<div class="carousel-slider-iframe">
						<iframe width="1280" height="720"
								src="https://www.youtube.com/embed/kYgp6wp27lM" frameborder="0"
								allowfullscreen></iframe>
					</div>
					<h2 class="cs-card-title">In Widget Areas</h2>
					<p class="cs-card-description"></p>
				</div>
			</div>
			<div class="cs-column cs-column-3">
				<div class="cs-card cs-shadow--4dp">
					<div class="carousel-slider-iframe">
						<iframe width="1280" height="720"
								src="https://www.youtube.com/embed/-OaYQZfr1RM" frameborder="0"
								allowfullscreen></iframe>
					</div>
					<h2 class="cs-card-title">With Page Builder by SiteOrigin</h2>
					<p class="cs-card-description"></p>
				</div>
			</div>
			<div class="cs-column cs-column-3">
				<div class="cs-card cs-shadow--4dp">
					<div class="carousel-slider-iframe">
						<iframe width="1280" height="720"
								src="https://www.youtube.com/embed/4LhDXH81whk" frameborder="0"
								allowfullscreen></iframe>
					</div>
					<h2 class="cs-card-title">With WPBakery Visual Composer</h2>
					<p class="cs-card-description"></p>
				</div>
			</div>
		</div>

	</div>
</template>

<script>
	export default {
		name: "Documentation"
	}
</script>

<style lang="scss">
	.cs-columns {
		box-sizing: border-box;
		display: flex;
		flex-wrap: wrap;
		margin-left: -1rem;
		margin-right: -1rem;
	}

	.cs-column {
		&.cs-column-3 {
			flex: 0 0 100%;
			padding: 1rem;
			box-sizing: border-box;

			@media screen and (min-width: 601px) {
				flex: 0 0 50%;
			}

			@media screen and (min-width: 1025px) {
				flex: 0 0 calc(100% / 3);
			}

			@media screen and (min-width: 1400px) {
				flex: 0 0 25%;
			}
		}
	}

	.cs-card {
		box-sizing: border-box;
		display: flex;
		flex-direction: column;
		padding: 1rem;
		height: 100%;

		&-title {
			font-size: 1.25rem;
			font-weight: normal;
			margin: 1rem 0 0;
		}

		&-description {
			font-size: 1rem;
			margin: 1rem 0 0;
		}
	}

	.cs-shadow--4dp {
		box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.14),
		0 1px 10px 0 rgba(0, 0, 0, 0.12),
		0 2px 4px -1px rgba(0, 0, 0, 0.2);
	}

	.carousel-slider-iframe {
		position: relative;
		padding-bottom: 56.25%;
		padding-top: 25px;
		height: 0;

		iframe {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}
	}
</style>
