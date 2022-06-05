<template>
	<aside>
		<div id="sidebar_header">
			<div v-if="showMobileVersion">
				<Button class="p-button-link p-button-lg"
					icon="pi pi-arrow-circle-left"
					@click="closeMenu" />
			</div>
			<div id="logo_panel">
				<v-logo />
			</div>
			<div v-if="showMobileVersion">
				<Button class="p-button-link p-button-lg" icon="pi pi-desktop"
					@click="switchToDesktopVersion" />
			</div>
		</div>
		<v-menu />
		<div id="sidebar_footer">
			<div v-if="isDesktopVersionForced" class="btn">
				<Button icon="pi pi-mobile" class="p-button-text p-button-info"
					:label="$t('sidebar.mobile_version')"
					@click="switchToMobileVersion" />
			</div>
			<div class="btn">
				<Button class="p-button-text p-button-danger"
					icon="pi pi-sign-out"
					:label="$t('sidebar.exit')"
					@click="exit" />
			</div>
		</div>
	</aside>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import vMenu from "./v-menu.vue"
export default {
	name: "v-sidebar",
	components: {
		vMenu
	},
	methods: {
		closeMenu() {
			this.setMobileMenuOpened(false)
		},
		switchToDesktopVersion() {
			this.setDesktopVersionForced(true)
			sessionStorage.setItem("desktopVersionForced", "true")
			this.$router.replace({path: "/dashboard/state_device"})
		},
		switchToMobileVersion() {
			this.setDesktopVersionForced(false)
			sessionStorage.setItem("desktopVersionForced", "false")
			this.$router.replace({path: "/dashboard/state_device"})
		},
		async exit() {
			this.closeMenu()
			this.setLogoutConfirmWindowVisible(true)
		},
		...mapActions([
			"setMobileMenuOpened",
			"setDesktopVersionForced",
			"setLogoutConfirmWindowVisible"
		])
	},
	computed: {
		...mapGetters([
			"showDesktopVersion",
			"showMobileVersion",
			"isDesktopVersionForced"
		])
	},
}
</script>

<style scoped>
aside {
	display: flex;
	flex-flow: column;
	flex: 1;
	width: 100%;
	background-color: var(--primary-light-color);
	box-shadow: 0px 0px 5px #808080;
	
	/*unselectable*/
	-webkit-user-select: none;
	-webkit-touch-callout: none;
	-moz-user-select: none;
	-ms-user-select: none;
	-khtml-user-select: none;
}

#sidebar_header {
	display: flex;
	padding: 0.4em;
}

#logo_panel {
	display: flex;
	flex: 1;
	height: 4em;
	align-items: center;
	justify-content: center;
}

#sidebar_footer {
	display: flex;
	flex-flow: column;
	padding-top: 1em;
}

.btn {
	margin-bottom: 0.5em;
	text-align: center;
}
</style>
