<template>
	<div class="media-gallery-container">
		<div class="media-gallery-status-status">
			<template v-if="has_image">
				<span class="media-gallery-status-title">{{count_images}} Images Selected</span>
				<span class="media-gallery-clear" @click="clearImages">(Clear)</span>
			</template>
			<template v-if="!has_image">
				<span class="media-gallery-status-title">No Images Selected</span>
			</template>
		</div>
		<div class="media-gallery-preview" :class="{'has-items': has_image}">
			<div class="media-gallery-list">
				<div class="media-gallery-list--item" v-for="image in thumbnails">
					<img class="media-gallery-list--item-image" :src="image" alt="">
				</div>
			</div>
		</div>
		<div class="media-gallery-button">
			<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-media-button">
				{{buttonText}}
			</button>
		</div>
	</div>
</template>

<script>
	import {MaterialButton} from '../../../material-design-lite/button/MaterialButton.js';
	import {MaterialRipple} from '../../../material-design-lite/ripple/MaterialRipple.js';

	export default {
		name: "MediaUploader",
		props: {
			buttonText: {type: String, default: 'Add Images'},
			galleryCreate: {type: String, default: 'Create Gallery'},
			galleryEdit: {type: String, default: 'Edit Gallery'},
			gallerySave: {type: String, default: 'Save Gallery'},
			galleryProgress: {type: String, default: 'Saving...'},
			galleryInsert: {type: String, default: 'Insert'},
			value: {type: Array, default: []},
		},
		data() {
			return {
				ids: [],
				images: [],
			}
		},
		computed: {
			count_images() {
				return this.value.length;
			},
			has_image() {
				return this.count_images > 0;
			},
			thumbnails() {
				return this.images.map((image) => {
					if (typeof image.thumbnail.source_url !== "undefined") {
						return image.thumbnail.source_url;
					}
					if (typeof image.thumbnail.url !== "undefined") {
						return image.thumbnail.url;
					}
					if (typeof image.full.source_url !== "undefined") {
						return image.full.source_url;
					}
					if (typeof image.full.url !== "undefined") {
						return image.full.url;
					}
				});
			}
		},
		mounted() {
			let self = this, frame, selection = false, button = self.$el.querySelector('.mdl-media-button');
			new MaterialButton(button);
			new MaterialRipple(button);

			if (self.value) {
				self.ids = self.value;
				self.getImages(self.ids);
			}
			button.addEventListener('click', function () {
				if (self.ids) {
					selection = self.loadImages(self.ids);
				}

				let options = {
					title: self.galleryCreate,
					state: 'gallery-edit',
					frame: 'post',
					selection: selection
				};

				if (frame || selection) {
					options['title'] = self.galleryEdit;
				}

				frame = wp.media(options).open();

				// Tweak Views
				frame.menu.get('view').unset('cancel');
				frame.menu.get('view').unset('separateCancel');
				frame.menu.get('view').get('gallery-edit').el.innerHTML = self.galleryEdit;
				frame.content.get('view').sidebar.unset('gallery'); // Hide Gallery Settings in sidebar

				// when editing a gallery
				overrideGalleryInsert();
				frame.on('toolbar:render:gallery-edit', function () {
					overrideGalleryInsert();
				});

				frame.on('content:render:browse', function (browser) {
					if (!browser) return;
					// Hide Gallery Settings in sidebar
					browser.sidebar.on('ready', function () {
						browser.sidebar.unset('gallery');
					});
					// Hide filter/search as they don't work
					browser.toolbar.on('ready', function () {
						if (browser.toolbar.controller._state === 'gallery-library') {
							browser.toolbar.$el.hide();
						}
					});
				});

				// All images removed
				frame.state().get('library').on('remove', function () {
					let models = frame.state().get('library');
					if (models.length === 0) {
						selection = false;
						self.clearImages();
					}
				});

				function overrideGalleryInsert() {
					frame.toolbar.get('view').set({
						insert: {
							style: 'primary',
							text: self.gallerySave,
							click: function () {
								let models = frame.state().get('library'),
										ids = [];

								models.each(function (attachment) {
									ids.push(attachment.id);
								});

								this.el.innerHTML = self.galleryProgress;

								selection = self.loadImages(ids);
								self.ids = ids;
								self.getImages(ids);
								self.$emit('input', ids);
								frame.close();
							}
						}
					});
				}
			})
		},
		methods: {
			loadImages(ids) {
				if (!ids) {
					return false;
				}
				if (typeof ids !== 'string') {
					ids = ids.toString();
				}

				let shortcode = new wp.shortcode({
					tag: 'gallery',
					attrs: {ids: ids},
					type: 'single'
				});

				let attachments = wp.media.gallery.attachments(shortcode);

				let selection = new wp.media.model.Selection(attachments.models, {
					props: attachments.props.toJSON(),
					multiple: true
				});

				selection.gallery = attachments.gallery;

				selection.more().done(function () {
					// Break ties with the query.
					selection.props.set({query: false});
					selection.unmirror();
					selection.props.unset('orderby');
				});

				return selection;
			},
			getImages(include) {
				let wpApiSettings = window.wpApiSettings, $ = window.jQuery, self = this;
				let url = wpApiSettings.root + wpApiSettings.versionString + 'media';

				$.ajax({
					url: url,
					method: 'GET',
					data: {
						include: include,
						per_page: include.length,
					},
					success: function (images) {
						self.images = [];
						include.forEach((id) => {
							images.forEach((element) => {
								if (id === element.id) {
									if (typeof element.media_details.sizes != "undefined") {
										self.images.push(element.media_details.sizes);
									} else {
										self.images.push({full: {url: element.source_url}});
									}
								}
							});
						});
					}
				});
			},
			clearImages() {
				if (confirm('Are you sure?')) {
					this.ids = [];
					this.images = [];
					this.$emit('input', []);
				}
			}
		}
	}
</script>

<style lang="scss">
	.media-gallery {
		&-button {
			margin-top: 15px;

			.mdl-button {
				width: 100%;
			}
		}

		&-clear {
			color: #b01b1b;
			cursor: pointer;
		}
	}

	.media-gallery-preview {
		display: flex;
		flex-wrap: wrap;

		&.has-items {
			margin: 10px 0;
		}
	}

	.media-gallery-list {
		box-sizing: border-box;
		display: flex;
		flex-wrap: wrap;
		margin-left: -5px;
		margin-right: -5px;

		&--item {
			display: inline-block;
			background: #fff;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
			margin: 5px;
		}

		&--item-image {
			margin: 5px;
			display: block;
			height: auto;
			max-height: 48px;
			width: 48px;
		}
	}
</style>
