<template>
	<div class="carousel-slider-list-page">
		<h1 class="wp-heading-inline">Sliders</h1>
		<div class="clear"></div>

		<ul class="status-lists">
			<li class="publish">
				<a href="#" :class="{current: postStatus === 'publish'}" @click="changeStatus('publish')">Published
					<span class="count">({{counts.publish}})</span></a>
			</li>
			<li class="trash">
				<a href="#" :class="{current: postStatus === 'trash'}" @click="changeStatus('trash')">Trash
					<span class="count">({{counts.trash}})</span></a>
			</li>
		</ul>

		<list-table
				:columns="columns"
				:rows="rows"
				:actions="actions"
				:bulk-actions="bulkActions"
				action-column="title"
				@action:click="onActionClick"
				@bulk:click="onBulkAction"
		>
			<template slot="slider-type" slot-scope="data">
				<span>{{sliderTypes[data.row.type]}}</span>
			</template>
			<template slot="shortcode" slot-scope="data">
				<copy-to-clipboard :value="shortcode(data.row)"></copy-to-clipboard>
			</template>
		</list-table>

		<mdl-fab @click="openModal">+</mdl-fab>
		<mdl-modal :active="modalActive" @close="closeModal" title="Add New Slider">
			<p v-for="(label, slug) in sliderTypes">
				<mdl-radio v-model="sliderType" :value="slug">{{label}}</mdl-radio>
			</p>
		</mdl-modal>
		<div class="carousel-slider-spinner" v-show="loading">
			<mdl-spinner :active="loading"></mdl-spinner>
		</div>
	</div>
</template>

<script>
	import ListTable from '../components/ListTable.vue';
	import CopyToClipboard from '../components/CopyToClipboard.vue';
	import mdlFab from '../../material-design-lite/button/mdlFab.vue';
	import mdlModal from '../../material-design-lite/modal/mdlModal.vue';
	import mdlRadio from '../../material-design-lite/radio/mdlRadio.vue';
	import mdlSpinner from '../../material-design-lite/spinner/mdlSpinner.vue';

	export default {
		name: "Home",
		components: {ListTable, mdlFab, mdlModal, mdlRadio, CopyToClipboard, mdlSpinner},
		data() {
			return {
				loading: true,
				modalActive: false,
				sliderTypes: {},
				sliderType: 'image-carousel',
				postStatus: 'publish',
				rows: [],
				columns: [],
				actions: [],
				bulkActions: [],
				counts: {},
			}
		},
		mounted() {
			let settings = window.carouselSliderSettings;
			this.sliderTypes = settings.sliderTypes;
			this.columns = settings.columns;
			this.actions = settings.actions.publish;
			this.bulkActions = settings.bulkActions.publish;
			this.counts = settings.countSliders;
			this.getItems();
		},
		methods: {
			shortcode(item) {
				return `[carousel_slide id='${item.id}']`
			},
			openModal() {
				this.modalActive = true;
			},
			closeModal() {
				this.modalActive = false;
			},
			changeStatus(status) {
				let settings = window.carouselSliderSettings;
				this.postStatus = status;
				if ('trash' === this.postStatus) {
					this.actions = settings.actions.trash;
					this.bulkActions = settings.bulkActions.trash;
				} else {
					this.actions = settings.actions.publish;
					this.bulkActions = settings.bulkActions.publish;
				}
				this.getItems();
			},
			getItems() {
				let $ = window.jQuery, self = this;
				self.loading = true;
				$.ajax({
					url: carouselSliderSettings.root + '/sliders',
					method: 'GET',
					data: {
						per_page: 20,
						post_status: self.postStatus,
					},
					success: function (response) {
						if (response.data) {
							self.rows = response.data;
						}
						self.loading = false;
					},
					error: function () {
						self.rows = [];
						self.loading = false;
					}
				});
			},
			trashItem(item) {
				let $ = window.jQuery, self = this;
				self.loading = true;
				$.ajax({
					url: carouselSliderSettings.root + '/sliders/' + item.id,
					method: 'DELETE',
					data: {
						force: false,
					},
					success: function () {
						self.$delete(self.rows, self.rows.indexOf(item));
						self.counts.publish -= 1;
						self.counts.trash += 1;
						self.loading = false;
					},
					error: function () {
						self.loading = false;
					}
				})
			},
			restoreItem(item) {
				let $ = window.jQuery, self = this;
				self.loading = true;
				$.ajax({
					url: ajaxurl,
					method: 'POST',
					data: {
						action: 'carousel_slider_restore_slider',
						id: item.id,
					},
					success: function () {
						self.$delete(self.rows, self.rows.indexOf(item));
						self.counts.publish += 1;
						self.counts.trash -= 1;
						self.loading = false;
					},
					error: function () {
						self.loading = false;
					}
				})
			},
			deleteItem(item) {
				let $ = window.jQuery, self = this;
				self.loading = true;
				$.ajax({
					url: carouselSliderSettings.root + '/sliders/' + item.id,
					method: 'DELETE',
					data: {
						force: true,
					},
					success: function () {
						self.$delete(self.rows, self.rows.indexOf(item));
						self.counts.trash -= 1;
						self.loading = false;
					},
					error: function () {
						self.loading = false;
					}
				})
			},
			onActionClick(action, row) {
				if ('edit' === action) {
					window.location.href = "#/" + row.id;
				} else if ('trash' === action) {
					if (confirm('Are you sure to move this item to trash?')) {
						this.trashItem(row);
					}
				} else if ('restore' === action) {
					if (confirm('Are you sure to restore this item?')) {
						this.restoreItem(row);
					}
				} else if ('delete' === action) {
					if (confirm('Are you sure to delete this item permanently?')) {
						this.deleteItem(row);
					}
				}
			},
			onBulkAction(action, items) {
				if ('trash' === action) {
					if (confirm('Are you sure to trash all selected items?')) {
						this.trashItems(items);
					}
				} else if ('delete' === action) {
					if (confirm('Are you sure to delete all selected items permanently?')) {
						this.deleteItems(items);
					}
				} else if ('restore' === action) {
					if (confirm('Are you sure to restore all selected items?')) {
						this.restoreItems(items);
					}
				}
			},
			trashItems(items) {
				let $ = window.jQuery, self = this;
				self.loading = true;
				$.ajax({
					url: ajaxurl,
					method: 'POST',
					data: {
						action: 'carousel_slider_delete_sliders',
						ids: items,
						force: false,
					},
					success: function (response) {
						if (response.status.success) {
							self.counts.publish -= response.status.success;
							self.counts.trash += response.status.success;
						}
						self.getItems();
					},
					error: function () {
						self.loading = false;
					}
				});
			},
			deleteItems(items) {
				let $ = window.jQuery, self = this;
				self.loading = true;
				$.ajax({
					url: ajaxurl,
					method: 'POST',
					data: {
						action: 'carousel_slider_delete_sliders',
						ids: items,
						force: true,
					},
					success: function (response) {
						if (response.status.success) {
							self.counts.trash -= response.status.success;
						}
						self.getItems();
					},
					error: function () {
						self.loading = false;
					}
				});
			},
			restoreItems(items) {
				let $ = window.jQuery, self = this;
				self.loading = true;
				$.ajax({
					url: ajaxurl,
					method: 'POST',
					data: {
						action: 'carousel_slider_restore_sliders',
						ids: items,
					},
					success: function (response) {
						if (response.status.success) {
							self.counts.publish += response.status.success;
							self.counts.trash -= response.status.success;
						}
						self.getItems();
					},
					error: function () {
						self.loading = false;
					}
				});
			},
		}
	}
</script>

<style lang="scss">
	.carousel-slider-list-page {
		.mdl-button--fab {
			position: fixed;
			bottom: 20px;
			right: 20px;
			z-index: 100;
		}

		.status-lists {
			list-style: none;
			margin: 8px 0;
			padding: 0;
			font-size: 13px;
			float: left;
			color: #666;

			li {
				display: inline-block;
				margin: 0;
				padding: 0;
				white-space: nowrap;

				a {
					line-height: 1.5;
					margin-right: .5em;
					padding-right: .5em;
					text-decoration: none;
					border-right: 1px solid #666;

					&.current {
						font-weight: 600;
						color: #000;
					}

				}

				&:last-child a {
					border-right: none;
				}
			}
		}
	}
</style>
