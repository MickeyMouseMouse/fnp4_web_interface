<template>
	<div class="setup_wrapper">
		<div class="language_main">
			<div class="row">
				<div class="title">
					{{$t("web_interface.language")}}
				</div>
				<div class="setting">
					<Dropdown v-model="currentLocale" :options="availableLocales" />
				</div>
			</div>
		</div>
		<div class="nav_btns">
			<Button :label="$t('common.continue')" @click="nextPage" />
		</div>
	</div>
</template>

<script>
import { mapActions} from "vuex"
export default {
	name: "v-setup-language",
	data() {
		return {
			currentLocale: this.$i18n.locale,
			availableLocales: ["ru", "en"]
		}
	},
	methods: {
		nextPage() {
			this.$emit("nextPage", {pageIndex: 0})
		},
		...mapActions([
			"postRequest"
		])
	},
	watch: {
		currentLocale: async function() {
			let formData = new FormData()
			formData.set("param", "locale")
			formData.set("newValue", this.currentLocale)
			this.postRequest({"url": "/php/interface_settings.php", "body": formData})
				.then(async response => {
					if (response.status == 200) {
						if (!this.$i18n.availableLocales.includes(this.currentLocale))
							this.$i18n.setLocaleMessage(this.currentLocale,
								await import(`@/i18n/locales/${this.currentLocale}`))
						this.$i18n.locale = this.currentLocale
						document.location.reload()
					}
				})
		}
	}
}
</script>

<style scoped>
.setup_wrapper {
	display: flex;
	flex-flow: column;
	width: 26em;
}

.language_main {
	height: 9em;
}

.row {
	display: flex;
	justify-content: center;
	align-items: center;
}

.title {
	flex: 1;
	margin-right: 0.5em;
}

.nav_btns {
	display: flex;
	justify-content: right;
	margin-top: 1.5em;
}
</style>
