<template>
	<div>
		<div class="mdl-copy-to-clipboard">
			<input class="mdl-copy-to-clipboard__input" type="text" :value="value" @click="clickInput">
			<div class="mdl-copy-to-clipboard__icon" @click="clickIcon">
				<span class="mdl-copy-to-clipboard__tooltip">{{beforeCopy}}</span>
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
					 viewBox="0 0 16 16">
					<path d="M12.656 14v-9.344h-7.313v9.344h7.313zM12.656 3.344c0.719 0 1.344 0.594 1.344 1.313v9.344c0 0.719-0.625 1.344-1.344 1.344h-7.313c-0.719 0-1.344-0.625-1.344-1.344v-9.344c0-0.719 0.625-1.313 1.344-1.313h7.313zM10.656 0.656v1.344h-8v9.344h-1.313v-9.344c0-0.719 0.594-1.344 1.313-1.344h8z"></path>
				</svg>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: "CopyToClipboard",
		props: {
			value: {type: String, default: ''}
		},
		data() {
			return {
				beforeCopy: 'Copy to clipboard',
				afterCopy: 'Copied',
			}
		},
		methods: {
			clickInput() {
				let input = this.$el.querySelector('.mdl-copy-to-clipboard__input');
				let value = this.value;

				input.select();
				document.execCommand("copy");
				alert("Copied: " + value);
			},
			clickIcon() {
				let tooltip = this.$el.querySelector('.mdl-copy-to-clipboard__tooltip');
				let input = this.$el.querySelector('.mdl-copy-to-clipboard__input');

				input.select();
				document.execCommand("copy");
				tooltip.innerHTML = this.afterCopy;
				setTimeout(function (el) {
					tooltip.innerHTML = el.beforeCopy;
				}, 3000, this);
			}
		}
	}
</script>

<style lang="scss">
	.mdl-copy-to-clipboard {
		display: flex;
		align-items: center;

		&__input {
			background-color: #f1f1f1;
			letter-spacing: 1px;
			padding: 5px 8px;
			width: 100%;
			max-width: 16em;
		}

		&__tooltip {
			visibility: hidden;
			width: 140px;
			background-color: #555;
			color: #fff;
			text-align: center;
			border-radius: 6px;
			padding: 5px;
			position: absolute;
			z-index: 1;
			bottom: 150%;
			left: 50%;
			margin-left: -75px;
			opacity: 0;
			transition: opacity 0.3s;

			&::after {
				content: "";
				position: absolute;
				top: 100%;
				left: 50%;
				margin-left: -5px;
				border-width: 5px;
				border-style: solid;
				border-color: #555 transparent transparent transparent;
			}
		}

		&__icon {
			border: 1px solid rgb(221, 221, 221);
			display: inline-block;
			margin-left: 5px;
			padding: 5px;
			position: relative;

			&:hover .mdl-copy-to-clipboard__tooltip {
				visibility: visible;
				opacity: 1;
			}

			svg {
				overflow: hidden;
				display: block;
			}
		}
	}
</style>
