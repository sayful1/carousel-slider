<template>
	<div :class="outer_classes">
		<div :id='slider_id' :class='slider_classes' data-carousel_slider="true" :data-slide-type='options.type'>
			<div class="carousel-slider-post" v-for="post in options.posts">
				<a class="carousel-slider-post__thumbnail" v-if="post.thumbnail" :href="post.link">
					<img :src="post.thumbnail.src" :alt="post.thumbnail.image_alt">
				</a>
				<header class="carousel-slider-post__header">
					<div class="carousel-slider-post__cat-links">
						<template v-for="(category, cat_index) in post.categories">
							<template v-if="cat_index > 0">,</template>
							<a :href="category.link">{{category.name}}</a>
						</template>
					</div>
					<h2 class="carousel-slider-post__title">
						<a :href="post.link" rel="bookmark">{{post.title}}</a>
					</h2>
				</header>
				<div class="carousel-slider-post__summary" v-html="post.excerpt"></div>
				<div class="carousel-slider-post__tags-links">
					<template v-for="(tag, tag_index) in post.tags">
						<template v-if="tag_index > 0">,</template>
						<a :href="tag.link">{{tag.name}}</a>
					</template>
				</div>
				<footer class="carousel-slider-post__footer">
					<span class="carousel-slider-post__byline">
						<span class="carousel-slider-post__vcard"><img :src="post.author.avatar"/></span>
						<span class="carousel-slider-post__author">
							by <a class="url fn n" :href="post.author.url">{{post.author.name}}</a></span>
					</span>
					<span class="carousel-slider-post__posted-on">
						<a :href="post.link" rel="bookmark" v-text="dateToHuman(post.date.modified)"></a>
					</span>
				</footer>
			</div>
		</div>
	</div>
</template>

<script>
	import {carousel} from './carousel.js';

	export default {
		name: "PostCarousel",
		mixins: [carousel],
		props: {
			options: {type: Object, required: true}
		},
		watch: {
			options(newValue, oldValue) {
				this.rebuildCarousel();
			}
		},
		mounted() {
			this.initCarousel();
		},
		methods: {
			dateToHuman(dateString) {
				let dateTime = new Date(dateString);
				return dateTime.toDateString();
			}
		}
	}
</script>

<style lang="scss">
	.carousel-slider[data-slide-type="post-carousel"] {
		.owl-stage {
			display: flex;
			flex-wrap: wrap;
		}

		.owl-item {
			display: flex;
			height: auto !important;
		}
	}

	.carousel-slider-post {
		background: #fff;
		box-shadow: 0 1px 3px rgba(#000, 0.1), 0 0 0 1px rgba(#000, 0.1);
		margin: 2px;
		padding: 1rem;
		display: flex;
		flex-direction: column;
		height: 100%;
		border-radius: 3px;

		&__thumbnail {
			margin: 0;
			padding: 0;

			img {
				width: 100%;
				height: auto;
			}
		}

		&__header {
			margin-bottom: 1rem;
			padding: 0;
			border: none;
		}

		&__cat-links {
			display: block;
			margin-top: 0;
			margin-bottom: .5rem;
			line-height: 1.25;
		}

		&__title {
			margin: 0;
			padding: 0;
			font-size: 1.25rem;
			overflow-wrap: break-word;
		}

		&__summary {
			margin: 0 0 1rem;
			overflow-wrap: break-word;
		}

		&__tags-links {
			margin: 0 0 1rem;
		}

		&__footer {
			display: flex;
			margin: 0 0 1rem;
			overflow: hidden;
		}

		&__byline,
		&__posted-on {
			float: left;
			font-size: .875rem;
			line-height: 1.33;
		}

		&__byline {
			display: flex;
			align-items: center;
		}

		&__author {
			margin-left: 0.5rem;
		}

		&__vcard img {
			display: inline-block;
			width: auto;
			max-width: 2rem;
		}

		&__posted-on {
			float: right;
		}

		.published:not(.updated) {
			display: none;
		}
	}
</style>
