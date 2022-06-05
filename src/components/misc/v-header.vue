<template>
	<header>
		<div id="statusbar">
			<div v-if="showMobileVersion" id="open_menu_btn_panel">
				<Button class="p-button-link p-button-lg" icon="pi pi-bars"
					@click="openMobileMenu" />
			</div>
			<div id="location">
				<div v-show="showMobileVersion" class="gap" />
				<div id="loc_name">
					{{getCurrentLocation}}
				</div>
				<div class="gap">
					<span v-show="isLoading" class="pi pi-spin pi-spinner" />
				</div>
			</div>
			<div id="help">
				<Button id="btn_help" class="p-button-link p-button-lg"
					icon="pi pi-info-circle" @click="openHelp" :disabled="getHelp()==null" />
			</div>
		</div>
	</header>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-header",
	methods: {
		openMobileMenu() {
			this.setMobileMenuOpened(true)
		},
		getHelp() {
			if (this.getMenu[this.getTopLevelIndex].submenu == null)
				return this.getMenu[this.getTopLevelIndex].help
			else
				return this.getMenu[this.getTopLevelIndex]
					.submenu[this.getSublevelIndex].help
		},
		openHelp() {
			window.open(this.getHelp(), "_blank")
		},
		...mapActions([
			"setMobileMenuOpened"
		])
	},
	computed: {
		getCurrentLocation() {
			let result = t("sidebar." + this.getMenu[this.getTopLevelIndex].name)
			if (this.getMenu[this.getTopLevelIndex].submenu != null)
				result += ": " + t("sidebar." + this.getMenu[this.getTopLevelIndex]
					.submenu[this.getSublevelIndex].name)
			return result
		},
		...mapGetters([
			"showDesktopVersion",
			"showMobileVersion",
			"getMenu",
			"getTopLevelIndex",
			"getSublevelIndex",
			"isLoading"
		])
	}
}
</script>

<style scoped>
header {
	margin-top: 0.4em;
	margin-bottom: 0.6em;
}

#statusbar {
	display: flex;
	align-items: center;
}

#open_menu_btn_panel {
	margin-right: 0.5em;
}

#location {
	display: flex;
	flex: 1;
	font-weight: bold;
}

#loc_name {
	margin-right: 0.3em;
}

.gap {
	flex: 1;
}

#btn_help {
	cursor: help;
	margin-left: 0.5em;
}
</style>
