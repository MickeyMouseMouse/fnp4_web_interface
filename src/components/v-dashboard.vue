<template>
	<div v-if="showDesktopVersion" id="dashboard">
		<div id="sidebar_desktop">
			<v-sidebar />
		</div>
		<div id="workplace_desktop">
			<v-workplace />
		</div>
	</div>
	<div v-else id="dashboard">
		<div v-show="isMobileMenuOpened" id="sidebar_mobile">
			<v-sidebar />
		</div>
		<div v-show="!isMobileMenuOpened" id="workplace_mobile">
			<v-workplace />
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import vSidebar from "./sidebar/v-sidebar.vue"
import vWorkplace from "./workplace/v-workplace.vue"
export default {
	name: "v-dashboard",
	components: {
		vSidebar,
		vWorkplace
	},
	created() {
		this.startTimer()
		if (this.showMobileVersion) {
			document.addEventListener("touchstart", this.touchStartListener)
			document.addEventListener("touchend", this.touchEndListener)
		}
	},
	data() {
		return {
			touchStartX: 0,
			touchStartY: 0
		}
	},
	methods: {
		touchStartListener(e) {
			this.touchStartX = e.changedTouches[0].screenX
			this.touchStartY = e.changedTouches[0].screenY
		},
		touchEndListener(e) {
			if (Math.abs(this.touchStartY - e.changedTouches[0].screenY) < 30) {
				if (this.isMobileMenuOpened) {
					// swipe from right to left
					if (this.touchStartX - e.changedTouches[0].screenX > 100)
						this.setMobileMenuOpened(false)
				} else {
					// swipe from left to right
					if (e.changedTouches[0].screenX - this.touchStartX > 100)
						this.setMobileMenuOpened(true)
				}
			}
		},
		...mapActions([
			"startTimer",
			"setMobileMenuOpened"
		])
	},
	computed: {
		...mapGetters([
			"showDesktopVersion",
			"showMobileVersion",
			"isMobileMenuOpened",
			"isAuthorized"
		])
	},
	beforeUnmount() {
		document.removeEventListener("touchstart", this.touchStartListener)
		document.removeEventListener("touchend", this.touchEndListener)
	}
}
</script>

<style scoped>
#dashboard {
	display: flex;
	flex: 1;
}

#sidebar_desktop {
	display: flex;
	width: 14em;
}

#workplace_desktop {
	display: flex;
	flex: 1;
}

#sidebar_mobile, #workplace_mobile {
	display: flex;
	width: 100%;
}
</style>
