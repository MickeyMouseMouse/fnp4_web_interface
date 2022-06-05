<template>
	<div v-show="!isLoading" id="state_device_panel">
		<div id="panel_wrapper" :class="showDesktopVersion ? 'panel_wrapper_desktop' : 'panel_wrapper_mobile'">
			<div class="table">
				<div :class="['table_header', {'centered': showMobileVersion}]">
					{{$t("state_device.device_info")}}
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.device_name")}}
					</div>
					<div class="cell">
						{{data["device_name"]}}
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.comment")}}
					</div>
					<div class="cell">
						{{data["device_comment"]}}
					</div>
				</div>
			</div>
			<div class="table">
				<div :class="['table_header', {'centered': showMobileVersion}]">
					{{$t("state_device.sys_info")}}
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.cpu")}}
					</div>
					<div class="cell">
						{{data["cpu"]}}
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.core_num")}}
					</div>
					<div class="cell">
						{{data["core_num"]}}
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.memory")}}
					</div>
					<div class="cell">
						{{data["memory"]}}
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.software_version")}}
					</div>
					<div class="cell">
						{{data["release"]}}
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.serial_number")}}
					</div>
					<div class="cell">
						{{data["serial_number"]}}
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.eth")}}
					</div>
					<div class="cell">
						{{data["eth"]}}
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.sdate")}}
					</div>
					<div class="cell">
						{{data["sdate"]}}
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.stime")}}
					</div>
					<div class="cell">
						{{data["stime"]}}
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.timeout")}}
					</div>
					<div class="cell">
						{{data["timeout"]}}
					</div>
				</div>
			</div>
			<div class="table">
				<div :class="['table_header', {'centered': showMobileVersion}]">
					{{$t("state_device.control_interface")}}
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.ip_addr_mask")}}
					</div>
					<div class="cell">
						{{data["ip_addr_mask"]}}
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.carrier_media_mode")}}
					</div>
					<div class="cell">
						<span v-if="data['carrier_media_mode_status'] == 'on'">
							<span class="on pi pi-check-circle" />
							{{data["carrier_media_mode_info"]}}
						</span>
						<span v-else-if="data['carrier_media_mode_status'] == 'off'"
							class="off pi pi-times-circle" />
						<span v-else>
							{{data["carrier_media_mode_status"]}}{{data["carrier_media_mode_info"]}}
						</span>
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.port_aggregation")}}
					</div>
					<div class="cell">
						<span v-if="data['port_aggregation_status'] == 'on'">
							<span class="on pi pi-check-circle" />
							{{data["port_aggregation_info"]}}
						</span>
						<span v-else-if="data['port_aggregation_status'] == 'off'"
							class="off pi pi-times-circle" />
					</div>
				</div>
			</div>
			<div class="table">
				<div :class="['table_header', {'centered': showMobileVersion}]">
					{{$t("state_device.process_status")}}
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.packet_filtering")}}
					</div>
					<div class="cell">
						<Button v-show="showDesktopVersion" class="p-button-link"
							style="padding:0em; margin-right: 0.5em"
							:label="$t('state_device.filtering_settings')"
							@click="$router.push({path: '/dashboard/state_filtering'})" />
						<span v-if="data['packet_filtering'] == 'on'"
							class="on pi pi-check-circle" />
						<span v-else-if="data['packet_filtering'] == 'off'"
							class="off pi pi-times-circle" />
						<span v-else>
							{{data["packet_filtering"]}}
						</span>
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.integrity_checking")}}
					</div>
					<div class="cell">
						<span v-if="data['integrity_checking'] == 'on'"
							class="on pi pi-check-circle" />
						<span v-else-if="data['integrity_checking'] == 'off'"
							class="off pi pi-times-circle" />
						<span v-else>
							{{data["integrity_checking"]}}
						</span>
					</div>
				</div>
					<div class="row">
					<div class="title_cell">
						{{$t("state_device.authorization")}}
					</div>
					<div class="cell">
						<span v-if="data['authorization'] == 'on'"
							class="on pi pi-check-circle" />
						<span v-else-if="data['authorization'] == 'off'"
							class="off pi pi-times-circle" />
						<span v-else>
							{{data["authorization"]}}
						</span>
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.registration")}}
					</div>
					<div class="cell">
						<span v-if="data['registration'] == 'on'"
							class="on pi pi-check-circle" />
						<span v-else-if="data['registration'] == 'off'"
							class="off pi pi-times-circle" />
						<span v-else>
							{{data["registration"]}}
						</span>
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.high_availability")}}
					</div>
					<div class="cell">
						<span v-if="data['high_availability'] == 'on'" 
							class="on pi pi-check-circle" />
						<span v-else-if="data['high_availability'] == 'off'"
							class="off pi pi-times-circle" />
						<span v-else>
							{{data["high_availability"]}}
						</span>
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.remote_administration")}}
					</div>
					<div class="cell">
						<span v-if="data['remote_administration'] == 'on'"
							class="on pi pi-check-circle" />
						<span v-else-if="data['remote_administration'] == 'off'"
							class="off pi pi-times-circle" />
						<span v-else>
							{{data["remote_administration"]}}
						</span>
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.web_interface")}}
					</div>
					<div class="cell">
						<span v-if="data['web_interface'] == 'on'"
							class="on pi pi-check-circle" />
						<span v-else-if="data['web_interface'] == 'off'"
							class="off pi pi-times-circle" />
						<span v-else>
							{{data["web_interface"]}}
						</span>
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.snmp_interface")}}
					</div>
					<div class="cell">
						<span v-if="data['snmp_interface'] == 'on'"
							class="on pi pi-check-circle" />
						<span v-else-if="data['snmp_interface'] == 'off'"
							class="off pi pi-times-circle" />
						<span v-else>
							{{data["snmp_interface"]}}
						</span>
					</div>
				</div>
			</div>
			<div class="table">
				<div :class="['table_header', {'centered': showMobileVersion}]">
					{{$t("state_device.device_control")}}
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.delayed_reboot")}}
					</div>
					<div class="cell">
						<div v-if="data['delayed_reboot_status'] == 'on'" class="container" >
							<span class="on pi pi-check-circle" />
							<span>{{data["delayed_reboot_timeout"]}}</span>
							<Button class="btn p-button-raised p-button-rounded"
								:label="$t('common.cancel')"
								:disabled="isReadPrivilege"
								@click="showCancelDelayedRestartConfirmWindow=true" />
						</div>
						<div v-else-if="data['delayed_reboot_status'] == 'off'" class="container">
							<span class="off pi pi-times-circle" />
							<Button class="p-button-link" icon="pi pi-pencil" :disabled="isReadPrivilege"
								@click="showEditDelayedRestartWindow=true" />
						</div>
						<div v-else>
							<span>{{data["delayed_reboot"]}}</span>
						</div>
					</div>
				</div>
			</div>
			<div class="control_btns_panel">
				<Button class="p-button-raised p-button-rounded p-button-warning"
					style="width: 10em" icon="pi pi-refresh"
					:label="$t('state_device.restart')"
					:disabled="isReadPrivilege"
					@click="showRestartConfirmWindow=true" />
				<Button class="p-button-raised p-button-rounded p-button-danger"
					style="margin-left: 1em; width: 10em" icon="pi pi-power-off"
					:disabled="isReadPrivilege"
					:label="$t('state_device.shutdown')"
					@click="showShutdownConfirmWindow=true" />
			</div>
		</div>
	</div>
	
	<!--modal windows-->
	<Dialog :header="$t('state_device.delayed_reboot')" :modal="true" :draggable="false"
		:position="showDesktopVersion ? 'center' : 'top'"
		v-model:visible="showEditDelayedRestartWindow">
		<div>
			<div v-show="message!=''" style="display: flex; justify-content:center" >
				{{message}}
			</div>
			<div class="table" style="margin-top: 0.5em" >
				<div class="row" style="border-top: 1px solid black" >
					<div class="title_cell">
						{{$t("state_device.restart_timeout")}}
					</div>
					<div class="cell">
						<Calendar v-model="timeout" :showIcon="true" icon="pi pi-clock"
							:timeOnly="true" :showSeconds="true" style="width: 10em" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.additional_configuration")}}
					</div>
					<div class="cell">
						<Dropdown v-model="config" :options="data['conf']"
							style="width: 10em" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.additional_policy")}}
					</div>
					<div class="cell">
						<Dropdown v-model="policy" :options="data['policy']"
							style="width: 10em" />
					</div>
				</div>
			</div>
		</div>
		<template #footer>
			<Button :label="$t('common.cancel')" class="p-button-text"
				@click="showEditDelayedRestartWindow=false" />
			<Button :label="$t('common.apply')" @click="setDelayedRestart" />
		</template>
	</Dialog>
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'"
		v-model:visible="showCancelDelayedRestartConfirmWindow">
		{{$t("state_device.cancel_delayed_restart_warning")}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showCancelDelayedRestartConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="cancelDelayedRestart" />
		</template>
	</Dialog>
	
	<Dialog :header="$t('state_device.restart')" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'"
		:modal="true" :draggable="false" v-model:visible="showRestartConfirmWindow">
		{{$t("state_device.restart_warning")}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showRestartConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="restart" />
		</template>
	</Dialog>
	<Dialog :header="$t('state_device.shutdown')" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'"
		:modal="true" :draggable="false" v-model:visible="showShutdownConfirmWindow">
		{{$t("state_device.shutdown_warning")}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showShutdownConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="shutdown" />
		</template>
	</Dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-state-device-panel",
	created() {
		this.setLoading(true)
		this.fetchData()
	},
	data() {
		return {
			data: {},
			
			showEditDelayedRestartWindow: false,
			message: "",
			timeout: new Date(2022, 5, 11, 0, 3, 0),
			config: "",
			policy: "",
			
			showCancelDelayedRestartConfirmWindow: false,
			showRestartConfirmWindow: false,
			showShutdownConfirmWindow: false
		}
	},
	methods: {
		async fetchData() {
			this.getRequest("/php/1_status/state_device.php")
				.then(async response => {
					if (response.status == 200) {
						this.data = await response.json()
						this.setLoading(false)
						
						if (this.data["integrity_checking"] == "off") {
							this.$toast.add({severity: "error",
								summary: t("state_checksum.error")})
						}
					}
				})
		},
		async setDelayedRestart() {
			if (this.config == "" && this.policy == "") {
				this.message = t("state_device.no_conf_or_policy")
			} else {
				let hours = this.timeout.getHours()
				let minutes = this.timeout.getMinutes()
				let seconds = this.timeout.getSeconds()
				let timeout = ""
				if (hours < 10) timeout += "0"
				timeout += hours + ":"
				if (minutes < 10) timeout += "0"
				timeout += minutes + ":"
				if (seconds < 10) timeout += "0"
				timeout += seconds
				
				let cmd = "system reboot timeout=" + timeout
				if (this.config != "")
					cmd += " config=" + this.config
				else if (this.policy != "")
					cmd += " policy=" + this.policy
				
				let formData = new FormData()
				formData.set("cmd", cmd)
				this.postRequest({"url": "/php/cli.php", "body": formData})
					.then(async () => {
						this.fetchData()
						this.$toast.add({severity: "info", summary: 
							t("state_device.delayed_restart_is_set"), life: 3000})
					})
				this.showEditDelayedRestartWindow = false
			}
		},
		cancelDelayedRestart() {
			this.showCancelDelayedRestartConfirmWindow = false
			let formData = new FormData();
			formData.set("cmd", "system reboot timeout=disable");
			this.postRequest({"url": "/php/cli.php", "body": formData})
			this.fetchData()
			this.$toast.add({severity: "info",
				summary: t("state_device.delayed_restart_cancel"), life: 3000})
		},
		async restart() {
			this.showRestartConfirmWindow = false
			console.log("RESTART")
			
			/*
			this.getRequest("/php/1_status/restart.php")
			this.exit(false)
			*/
		},
		async shutdown() {
			this.showShutdownConfirmWindow = false
			console.log("SHUTDOWN")
			/*
			this.getRequest("/php/1_status/shutdown.php")
			this.exit(false)
			*/
		},
		...mapActions([
			"getRequest",
			"postRequest",
			"setLoading",
			"setPermissionDeniedWindowVisible",
			"exit"
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
#state_device_panel {
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

.container {
	display: flex;
	align-items: center;
}

.on {
	color: green;
	font-weight: bold;
}

.off {
	color: red;
	font-weight: bold;
}

.control_btns_panel {
	display: flex;
	justify-content: center;
	margin-bottom: 1em;
}
</style>
