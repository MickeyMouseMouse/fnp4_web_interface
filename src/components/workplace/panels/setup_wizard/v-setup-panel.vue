<template>
	<div id="setup_panel" :class="{'disabled': isReadPrivilege}">
		<div id="steps_menu">
			<Steps :model="items" />
		</div>
		<div id="setup_content">
			<router-view v-slot="{Component}" :formData="formObject"
				@prevPage="prevPage($event)" @nextPage="nextPage($event)"
				@complete="complete">
				<keep-alive>
					<component :is="Component" />
				</keep-alive>
			</router-view>
		</div>
	</div>
</template>

<script>
import { mapGetters } from "vuex"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-setup-panel",
	data() {
		return {
			items: [
				{
					label: t("web_interface.language"),
					to: "/dashboard/setup/language"
				},
				{
					label: t("state_device.device_name"),
					to: "/dashboard/setup/device_name"
				},
				{
					label: t("state_device.control_interface"),
					to: "/dashboard/setup/control_interface"
				},
				{
					label: t("setup.default_gateway"),
					to: "/dashboard/setup/default_gateway"
				},
				{
					label: t("settings_http_proxy.dns"),
					to: "/dashboard/setup/dns"
				},
				{
					label: t("settings_device.ntp"),
					to: "/dashboard/setup/ntp"
				}
			],
			formObject: {}
		}
	},
	methods: {
		nextPage(event) {
			for (let field in event.formData)
				this.formObject[field] = event.formData[field]
			this.$router.push(this.items[event.pageIndex + 1].to)
		},
		prevPage(event) {
			this.$router.push(this.items[event.pageIndex - 1].to)
		},
		complete() {
			this.$router.push(this.items[0].to)
			this.$toast.add({severity:"success", detail: t("setup.complete"), life: 3000})
		}
	},
	computed: {
		...mapGetters([
			"isReadPrivilege"
		])
	}
}
</script>

<style scoped>
#steps_menu {
	padding: 2em;
	border-radius: 10px;
	margin-bottom: 2em;
	background-color: white;
}

#setup_content {
	display: flex;
	justify-content: center;
}

.disabled {
	pointer-events: none;
	opacity: 0.4;
}
</style>
