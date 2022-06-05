<template>
	<div v-show="!isLoading" id="policy_control_panel">

	</div>

	<v-modal-window :visible="showClearStatsConfirmWindow"
		:label="$t('common.clear')"
		@closeWindow="showClearStatsConfirmWindow = false">
		<div>
			{{$t("dashboard.main.policy_control.clear_stats")}}
		</div>
		<div class="modal_window_footer">
			<Button :label="$t('common.yes')" @click="clear" />
		</div>
	</v-modal-window>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
export default {
	name: "v-policy-control-panel",
	created() {
		this.setLoading(true)
		this.fetchData()
	},
	data() {
		return {
			showClearStatsConfirmWindow: false,
			data: {}
		}
	},
	methods: {
		fetchData() {
			this.getRequest("/php/")
				.then(async response => {
					if (response.status == 200) {
						this.data = await response.json()
						this.setLoading(false)
					}
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
			"isReadPrivilege"
		])
	}
}
</script>

<style scoped>
#policy_control_panel {
	display: flex;
	flex-flow: column;
	height: 100%;
	overflow: hidden;
}

</style>
