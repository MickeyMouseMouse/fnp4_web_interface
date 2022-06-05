<template>
	<div v-show="appVisible" id="wrapper">
		<router-view />
	</div>
	<Toast :position="showDesktopVersion ? 'top-right' : 'top-center'" />
</template>

<script>
import { mapGetters, mapActions } from "vuex"
export default {
	name: "v-app",
	created() {
		// check weather the user has switched to the desktop version on a mobile device
		if (!this.isDesktop) // mobile device
			this.setDesktopVersionForced(sessionStorage
				.getItem("desktopVersionForced") === "true")
		this.init()
	},
	data() {
		return {
			appVisible: false
		}
	},
	methods: {
		init() {
			this.getRequest("/php/interface_settings.php")
				.then(async response => {
					if (response.status == 200) { //  set app locale
						let data = await response.json()
						if (!this.$i18n.availableLocales.includes(data.locale))
							this.$i18n.setLocaleMessage(data.locale,
								await import(`@/i18n/locales/${data.locale}`))
						this.$i18n.locale = data.locale
						
						this.loginUsingCookie() // attempt login using cookie
							.then(async () => {
								this.appVisible = true
							})
					}
				})
		},
		...mapActions([
			"setDesktopVersionForced",
			"getRequest",
			"loginUsingCookie"
		])
	},
	computed: {
		...mapGetters([
			"isDesktop",
			"showDesktopVersion"
		])
	}
}
</script>

<style>
#v-app {
	position: absolute;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
}

#wrapper {
	display: flex;
	height: 100%;
	overflow: hidden;
}
</style>
