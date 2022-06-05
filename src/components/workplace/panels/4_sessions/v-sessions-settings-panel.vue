<template>
	<div v-show="!isLoading" id="sessions_settings_panel">
		<div id="panel_wrapper" :class="showDesktopVersion ? 'panel_wrapper_desktop' : 'panel_wrapper_mobile'">
			<div class="table">
				<div :class="['table_header', {'centered': showMobileVersion}]">
					{{$t("sessions_settings.sessions_settings")}}
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.session_control_state")}}
					</div>
					<div class="cell">
						<InputSwitch v-model="sessionControlToggle" :disabled="isReadPrivilege"
							@click="toggle('sessionControl')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.dropped_packets_logging")}}
					</div>
					<div class="cell">
						<InputSwitch v-model="droppedPacketsLoggingToggle" :disabled="isReadPrivilege"
							@click="toggle('droppedPacketsLogging')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.application_rules_usage")}}
					</div>
					<div class="cell">
						<InputSwitch v-model="applicationRulesUsageToggle" :disabled="isReadPrivilege"
							@click="toggle('applicationRulesUsage')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.link_level_data_usage")}}
					</div>
					<div class="cell">
						<InputSwitch v-model="linkLevelDataUsageToggle" :disabled="isReadPrivilege"
							@click="toggle('linkLevelDataUsage')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.deep_tcp_control")}}
					</div>
					<div class="cell">
						<InputSwitch v-model="deepTcpControlToggle" :disabled="isReadPrivilege"
							@click="toggle('deepTcpControl')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.trace_sessions_usage")}}
					</div>
					<div class="cell">
						<InputSwitch v-model="traceSessionsUsageToggle" :disabled="isReadPrivilege"
							@click="toggle('traceSessionsUsage')" />
					</div>
				</div>
			</div>
			<div v-if="!isReadPrivilege" class="default_btn_panel">
				<Button class="p-button-rounded"
					:label="$t('sessions_settings.default_settings')"
					@click="showResetTimeoutsConfirmWindow = true" />
			</div>
			<div class="table">
				<div :class="['table_header', {'centered': showMobileVersion}]">
					{{$t("sessions_settings.session_timeouts")}}
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.tcp_initialization")}}
					</div>
					<div class="cell">
						{{data["sm_tcp_syn"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('tcp_init')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.tcp_established")}}
					</div>
					<div class="cell">
						{{data["sm_tcp_estab"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('tcp_estab')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.tcp_finalization")}}
					</div>
					<div class="cell">
						{{data["sm_tcp_fin"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('tcp_fin')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.udp_initialization")}}
					</div>
					<div class="cell">
						{{data["sm_udp_syn"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('udp_init')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.udp_established")}}
					</div>
					<div class="cell">
						{{data["sm_udp_estab"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('udp_estab')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.icmp_initialization")}}
					</div>
					<div class="cell">
						{{data["sm_icmp_syn"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('icmp_init')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.icmp_established")}}
					</div>
					<div class="cell">
						{{data["sm_icmp_estab"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('icmp_estab')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.other_initialization")}}
					</div>
					<div class="cell">
						{{data["sm_other_syn"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('other_init')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.other_established")}}
					</div>
					<div class="cell">
						{{data["sm_other_estab"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('other_estab')" />
					</div>
				</div>
			</div>
			<div v-if="!isReadPrivilege" class="default_btn_panel">
				<Button class="p-button-rounded"
					:label="$t('sessions_settings.default_settings')"
					@click="showResetThresholdConfirmWindow = true" />
			</div>
			<div class="table">
				<div :class="['table_header', {'centered': showMobileVersion}]">
					{{$t("sessions_settings.flood_attacks")}}
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.flood_attacks_detecting")}}
					</div>
					<div class="cell">
						<InputSwitch v-model="floodAttacksDetectingToggle" :disabled="isReadPrivilege"
							@click="toggle('floodAttacksDetecting')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.flood_attacks_alarm")}}
					</div>
					<div class="cell">
						<InputSwitch v-model="floodAttacksAlarmToggle" :disabled="isReadPrivilege"
							@click="toggle('floodAttacksAlarm')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.tcp_threshold")}}
					</div>
					<div class="cell">
						{{data["flood_thresh_tcp"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('tcp_threshold')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.udp_threshold")}}
					</div>
					<div class="cell">
						{{data["flood_thresh_udp"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('udp_threshold')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.icmp_threshold")}}
					</div>
					<div class="cell">
						{{data["flood_thresh_icmp"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('icmp_threshold')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.temporary_ip_rule_lifetime")}}
					</div>
					<div class="cell">
						{{data["flood_rule_lifetime"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('temp_ip_rule_lifetime')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.temporary_ip_rule_logging")}}
					</div>
					<div class="cell">
						<InputSwitch v-model="temporaryIpRuleLoggingToggle" :disabled="isReadPrivilege"
							@click="toggle('temporaryIpRuleLogging')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sessions_settings.temporary_ip_rule_comment")}}
					</div>
					<div class="cell">
						{{data["flood_rule_comment"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('temp_ip_rule_comment')" />
					</div>
				</div>
			</div>
		</div>
	</div>

	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'" v-model:visible="showEditWindow">
		<div style="display:flex; justify-content:center; align-items:center">
			<InputText v-if="editField=='temp_ip_rule_comment'" v-model="newComment" autofocus />
			<InputText v-else v-model="newValue" autofocus />
		</div>
		<template #footer>
			<Button :label="$t('common.cancel')" icon="pi pi-times" class="p-button-text"
				@click="showEditWindow=false" />
			<Button :label="$t('common.done')" icon="pi pi-check"
				:disabled="(newValue=='' || !newValueValid) && editField!='temp_ip_rule_comment'"
				@click="setNewValue" />
		</template>
	</Dialog>
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'"
		v-model:visible="showResetTimeoutsConfirmWindow">
		{{$t("sessions_settings.reset_session_timeouts")}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showResetTimeoutsConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="resetTimeouts" />
		</template>
	</Dialog>
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'"
		v-model:visible="showResetThresholdConfirmWindow">
		{{$t("sessions_settings.reset_threshold_values")}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showResetThresholdConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="resetThreshold" />
		</template>
	</Dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
export default {
	name: "v-sessions-settings-panel",
	created() {
		this.setLoading(true)
		this.fetchData()
	},
	data() {
		return {
			data: {},
			
			// InputSwitces state
			sessionControlToggle: Boolean,
			droppedPacketsLoggingToggle: Boolean,
			applicationRulesUsageToggle: Boolean,
			linkLevelDataUsageToggle: Boolean,
			deepTcpControlToggle: Boolean,
			traceSessionsUsageToggle: Boolean,
			floodAttacksDetectingToggle: Boolean,
			floodAttacksAlarmToggle: Boolean,
			temporaryIpRuleLoggingToggle: Boolean,
			
			editField: String,
			newValue: String,
			newValueValid: true,
			newComment: String,
			
			showEditWindow: false,
			showResetTimeoutsConfirmWindow: false,
			showResetThresholdConfirmWindow: false
		}
	},
	methods: {
		fetchData() {
			this.getRequest("/php/4_sessions/sessions_settings.php")
				.then(async response => {
					if (response.status == 200) {
						this.data = await response.json()
						this.sessionControlToggle = this.data["is_sm"] == 1
						this.droppedPacketsLoggingToggle = this.data["is_sm_reg"] == 1
						this.applicationRulesUsageToggle = this.data["is_sm_arp"] == 1
						this.linkLevelDataUsageToggle = this.data["is_sm_mac"] == 1
						this.deepTcpControlToggle = this.data["is_sm_deeptcp"] == 1
						this.traceSessionsUsageToggle = this.data["is_sm_trace"] == 1
						this.floodAttacksDetectingToggle = this.data["is_block_flood"] == 1
						this.floodAttacksAlarmToggle = this.data["flood_alarm"] == 1
						this.temporaryIpRuleLoggingToggle = this.data["flood_rule_islog"] == 1
						this.setLoading(false)
					}
				})
		},
		openEditWindow(editField) {
			this.editField = editField
			this.newValue = "",
			this.newValueValid = true,
			this.newComment = "",
			this.showEditWindow = true
		},
		toggle(property) {
			let cmd
			if (property == "sessionControl")
				if (this.sessionControl)
					cmd = "disable"
				else
					cmd = "enable"
			else if (property == "droppedPacketsLogging")
				if (this.droppedPacketsLogging)
					cmd = "invalid log disable"
				else
					cmd = "invalid log enable"
			else if (property == "applicationRulesUsage")
				if (this.applicationRulesUsage)
					cmd = "ap disable"
				else
					cmd = "ap enable"
			else if (property == "linkLevelDataUsage")
				if (this.linkLevelDataUsage)
					cmd = "mac disable"
				else
					cmd = "mac enable"
			else if (property == "deepTcpControl")
				if (this.deepTcpControl)
					cmd = "deeptcp disable"
				else
					cmd = "deeptcp enable"
			else if (property == "traceSessionsUsage")
				if (this.traceSessionsUsage)
					cmd = "trace disable"
				else
					cmd = "trace enable"
			else if (property == "floodAttacksDetecting")
				if (this.floodAttacksDetecting)
					cmd = "flood disable"
				else
					cmd = "flood enable"
			else if (property == "floodAttacksAlarm")
				if (this.floodAttacksAlarm)
					cmd = "flood alarm disable"
				else
					cmd = "flood alarm enable"
			else if (property == "temporaryIpRuleLogging")
				if (this.temporaryIpRuleLogging)
					cmd = "flood rule log=disable"
				else
					cmd = "flood rule log=enable"

			let formData = new FormData()
			formData.set("cmd", "session " + cmd)
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(response => {
					if (response.status == 200) {
						this.fetchData()
					}
				})
		},
		setNewValue() {
			let cmd
			if (this.editField == "tcp_init")
				cmd = "session timeout protocol=tcp syn=" + this.newValue
			else if (this.editField == "tcp_estab")
				cmd = "session timeout protocol=tcp established=" + this.newValue
			else if (this.editField == "tcp_fin")
				cmd = "session timeout protocol=tcp fin=" + this.newValue
			else if (this.editField == "udp_init")
				cmd = "session timeout protocol=udp syn=" + this.newValue
			else if (this.editField == "udp_estab")
				cmd = "session timeout protocol=udp established=" + this.newValue
			else if (this.editField == "icmp_init")
				cmd = "session timeout protocol=icmp syn=" + this.newValue
			else if (this.editField == "icmp_estab")
				cmd = "session timeout protocol=icmp established=" + this.newValue
			else if (this.editField == "other_init")
				cmd = "session timeout protocol=other syn=" + this.newValue
			else if (this.editField == "other_estab")
				cmd = "session timeout protocol=other established=" + this.newValue
			else if (this.editField == "tcp_threshold")
				cmd = "session flood threshold tcp=" + this.newValue
			else if (this.editField == "udp_threshold")
				cmd = "session flood threshold udp=" + this.newValue
			else if (this.editField == "icmp_threshold")
				cmd = "session flood threshold icmp=" + this.newValue
			else if (this.editField == "temp_ip_rule_lifetime")
				cmd = "session flood rule lifetime=" + this.newValue
			else if (this.editField == "temp_ip_rule_comment")
				cmd = "session flood rule comment=\"" + this.newComment + "\""
			
			let formData = new FormData()
			formData.set("cmd", cmd)
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(response => {
					if (response.status == 200) {
						this.fetchData()
					}
				})
			
			this.showEditWindow = false
		},
		resetTimeouts() {
			let formData = new FormData()
			formData.set("cmd", "session timeout default")
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(response => {
					if (response.status == 200) {
						this.fetchData()
					}
				})
			this.showResetTimeoutsConfirmWindow = false
		},
		resetThreshold() {
			let formData = new FormData()
			formData.set("cmd", "session flood threshold default")
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(response => {
					if (response.status == 200) {
						this.fetchData()
					}
				})
			this.showResetThresholdConfirmWindow = false
		},
		...mapActions([
			"getRequest",
			"postRequest",
			"setLoading"
		])
	},
	computed: {
		...mapGetters([
			"isLoading",
			"showDesktopVersion",
			"showMobileVersion",
			"isReadPrivilege"
		])
	},
	watch: {
		newValue: function() {
			this.newValueValid = this.newValue.match(/^[1-9]([0-9])*$/)
		}
	}
}
</script>

<style scoped>
#sessions_settings_panel {
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

.default_btn_panel {
	display: flex;
	justify-content: right;
	margin-bottom: 0.3em;
}

.table {
	margin-bottom: 1.5em;
	box-shadow: 0px 10px 10px lightgrey;
}

.table_header {
	display: flex;
	background-color: var(--primary-light-color);
	padding-top: 0.5em;
	padding-bottom: 0.5em;
	padding-left: 0.4em;
	border: 1px solid black;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px;
}

.centered {
	justify-content: center;
}

.row {
	display: flex;
	padding: 0.5em;
	border-left: 1px solid black;
	border-right: 1px solid black;
	border-bottom: 1px solid black;
	background-color: white;
}

.title_cell {
	display: flex;
	align-items: center;
}

.cell {
	display: flex;
	flex: 1;
	align-items: center;
	justify-content: right;
	text-align: right;
}
</style>
