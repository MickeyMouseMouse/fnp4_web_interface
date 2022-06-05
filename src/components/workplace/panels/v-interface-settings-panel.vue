<template>
	<div id="interface_settings_panel" :class="{'disabled': isReadPrivilege}">
		<div id="panel_wrapper" :class="showDesktopVersion ? 'panel_wrapper_desktop' : 'panel_wrapper_mobile'">
			<div :class="showDesktopVersion ? 'desktop' : 'mobile'">
				<div class="row">
					<div class="title">
						{{$t("web_interface.language")}}
					</div>
					<div class="setting">
						<Dropdown v-model="currentLocale" :options="availableLocales" />
					</div>
				</div>
				<div v-if="showDesktopVersion" class="row">
					<div class="title">
						{{$t("web_interface.nat_visible")}}
					</div>
					<div class="setting">
						<InputSwitch v-model="natVisible" />
					</div>
				</div>
				<div v-if="showDesktopVersion" class="row">
					<div class="title">
						{{$t("web_interface.debug_visible")}}
					</div>
					<div class="setting">
						<InputSwitch v-model="debugVisible" />
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
export default {
	name: "v-interface-settings-panel",
	created() {
		this.natVisible = this.getMenuItemVisible(1, 3)
		this.debugVisible = this.getMenuItemVisible(5)
	},
	data() {
		return {
			currentLocale: this.$i18n.locale,
			availableLocales: ["ru", "en"],
			natVisible: Boolean,
			debugVisible: Boolean
		}
	},
	methods: {
		getMenuItemVisible(top_level_index, sublevel_index) {
			if (sublevel_index == null)
				return this.getMenu[top_level_index].visible != false
			else
				return this.getMenu[top_level_index]
					.submenu[sublevel_index].visible != false
		},
		...mapActions([
			"postRequest",
			"setMenuItemVisible"
		])
	},
	computed: {
		...mapGetters([
			"showDesktopVersion",
			"getMenu",
			"isReadPrivilege"
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
					}
				})
		},
		natVisible: async function() {
			let formData = new FormData()
			formData.set("param", "natVisible")
			formData.set("newValue", this.natVisible)
			this.postRequest({"url": "/php/interface_settings.php", "body": formData})
				.then(async response => {
					if (response.status == 200) {
						let visible = this.natVisible
						this.setMenuItemVisible({"top_level": 1, "sublevel": 3,
							"visible": visible})
					}
				})
		},
		debugVisible: async function() {
			let formData = new FormData()
			formData.set("param", "debugVisible")
			formData.set("newValue", this.debugVisible)
			this.postRequest({"url": "/php/interface_settings.php", "body": formData})
				.then(async response => {
					if (response.status == 200) {
						let visible = this.debugVisible
						this.setMenuItemVisible({"top_level": 5, "visible": visible})
					}
				})
		}
	}
}
</script>

<style scoped>
#interface_settings_panel {
	display: flex;
	justify-content: center;
}

#panel_wrapper {
	display: flex;
	flex-flow: column;
}

.panel_wrapper_desktop {
	width: 30em;
}

.panel_wrapper_mobile {
	flex: 1;
}

.desktop {
	width: 25em;
}

.mobile {
	width: 100%;
}

.row {
	display: flex;
	margin-bottom: 1em;
}

.title {
	display: flex;
	flex: 1;
	align-items: center;
	margin-right: 0.5em;
}

.setting {
	display: flex;
	justify-content: center;
	width: 5em;
}

.disabled {
	pointer-events: none;
	opacity: 0.4;
}
</style>
