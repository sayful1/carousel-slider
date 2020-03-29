<template>
	<div class="carousel-slider-control-background-image">
		<div class="carousel-slider-control-background-image--view">
			<div class="carousel-slider-control-background-image--thumbnail" v-if="has_image">
				<img :src="value.src" alt=""/>
			</div>
			<div class="carousel-slider-control-background-image--placeholder" v-if="!has_image">{{placeholderText}}
			</div>
			<div class="carousel-slider-control-background-image--actions">
				<button class="button" @click="clearImages" v-if="has_image">{{removeButtonText}}</button>
				<button class="button" @click="openMediaModal" v-if="!has_image">{{buttonText}}</button>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: "BackgroundImage",
		props: {
			placeholderText: {type: String, default: 'No File Selected'},
			buttonText: {type: String, default: 'Add Image'},
			removeButtonText: {type: String, default: 'Remove'},
			modalTitle: {type: String, default: 'Select Image'},
			modalButtonText: {type: String, default: 'Set Image'},
			value: {type: Object},
		},
		computed: {
			has_image() {
				return !!this.value.src;
			}
		},
		methods: {
			openMediaModal() {
				let self = this;

				let frame = new wp.media.view.MediaFrame.Select({
					title: self.modalTitle,
					multiple: false,
					library: {
						order: 'ASC',
						orderby: 'title',
						type: 'image',
						search: null,
						uploadedTo: null
					},

					button: {text: self.modalButtonText}
				});

				frame.on('select', function () {
					let collection = frame.state().get('selection'),
							ids = 0, sizes = [];

					collection.each(function (attachment) {
						ids = attachment.id;
						sizes = attachment.attributes.sizes;
					});

					self.$emit('input', {
						id: ids,
						src: sizes.full.url,
						height: sizes.full.height,
						width: sizes.full.width,
					});
				});

				frame.open();
			},
			clearImages() {
				if (confirm('Are you sure?')) {
					this.$emit('input', {});
				}
			}
		}
	}
</script>

<style lang="scss">
	.carousel-slider-control-background-image {
		position: relative;

		&--placeholder {
			border: 1px dashed #b4b9be;
			box-sizing: border-box;
			cursor: default;
			line-height: 20px;
			margin-bottom: 10px;
			padding: 9px 0;
			position: relative;
			text-align: center;
			width: 100%;
		}

		&--thumbnail {
			img {
				max-width: 100%;
				height: auto;
			}
		}
	}
</style>
