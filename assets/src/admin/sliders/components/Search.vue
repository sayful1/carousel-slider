<template>
	<p class="search-box">
		<label class="screen-reader-text" :for="id">{{screenReaderText}}</label>
		<input type="search" :id="id" v-model="query" @input="input">
		<button type="button" class="button" id="search-submit" @click.prevent="submit">{{buttonText}}</button>
	</p>
</template>

<script>
	export default {
		name: "Search",
		props: {
			buttonText: {
				type: String,
				required: false,
				default: 'Search'
			},
			screenReaderText: {
				type: String,
				required: false,
				default: 'Search:'
			},
			id: {
				type: String,
				required: true,
			}
		},
		data() {
			return {
				query: '',
			}
		},
		methods: {
			input() {
				this.$emit('searchInput', this.query);
			},
			submit() {
				this.$emit('searchSubmit', this.query);
			},
		},
	}
</script>

<style scoped>

</style>
