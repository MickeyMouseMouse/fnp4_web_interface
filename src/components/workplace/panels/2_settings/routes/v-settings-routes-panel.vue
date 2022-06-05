<template>
	<div v-show="!isLoading" id="settings_route_panel">
		<div id="panel_wrapper" :class="showDesktopVersion ? 'panel_wrapper_desktop' : 'panel_wrapper_mobile'">
			<div v-if="showDesktopVersion" style="margin-bottom: 1em">
				<Button icon="pi pi-plus" :disabled="isReadPrivilege"
					:label="$t('settings_routes.new_route')"/>
			</div>
			<div id="table">
				<Route v-if="showDesktopVersion" :is_table_header="true" />
				<div id="table_content">
					<Route v-for="route in data" :key="route.id" @updateData="fetchData"
						:number="route['number']"
						:dest_ip="route['dst-address']"
						:gateway_ip="route['gateway']" 
						:interface_name="route['interface']" />
				</div>
			</div>
		</div>
	</div>
	

</template>

<script>
import { mapGetters, mapActions } from "vuex"
import Route from "./v-route.vue"
export default {
	name: "v-settings-routes-panel",
	components: {
		Route
	},
	created() {
		this.setLoading(true)
		this.fetchData()
	},
	data() {
		return {
			data: {}
			
		}
	},
	methods: {
		async fetchData() {
			this.getRequest("/php/2_settings/settings_routes.php")
				.then(async response => {
					this.data = (await response.json())["list"]
					this.setLoading(false)
				})
		},
		...mapActions([
			"getRequest",
			"postRequest",
			"setLoading"
		])
	},
	computed: {
		...mapGetters([
			"showDesktopVersion",
			"showMobileVersion",
			"isLoading",
			"isReadPrivilege"
		])
	}
}
</script>

<style scoped>
#settings_route_panel {
	display: flex;
	justify-content: center;
}

#panel_wrapper {
	display: flex;
	flex-flow: column;
}

.panel_wrapper_desktop {
	width: 35em;
}

.panel_wrapper_mobile {
	flex: 1;
}

.btn_panel {
	display: flex;
	justify-content: right;
	align-items: center;
	margin-bottom: 0.5em;
}

</style>
