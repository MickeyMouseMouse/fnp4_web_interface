<template>
	<div v-show="!isLoading" id="settings_device_panel">
		<div id="panel_wrapper" :class="showDesktopVersion ? 'panel_wrapper_desktop' : 'panel_wrapper_mobile'">
			<div v-if="!isReadPrivilege" class="btn_panel">
				<Button class="p-button-link" icon="pi pi-download"
					:label="$t('settings_device.certificate_chain')"
					@click="downloadCertificateChain" />
				<Button class="p-button-link" icon="pi pi-download"
					:label="$t('settings_device.device_certificate')"
					@click="downloadDeviceCertificate" />
			</div>
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
						<Button class="p-button-link" icon="pi pi-pencil" :disabled="isReadPrivilege"
							@click="openEditWindow('device_name')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("state_device.comment")}}
					</div>
					<div class="cell">
						{{data["device_comment"]}}
						<Button class="p-button-link" icon="pi pi-pencil" :disabled="isReadPrivilege"
							@click="openEditWindow('device_comment')" />
					</div>
				</div>
			</div>
			<div v-if="!isReadPrivilege" class="btn_panel">
				<Button class="p-button-rounded" :disabled="isReadPrivilege || !ntpToggle"
					:label="$t('settings_device.update_time')"
					@click="showUpdateSystemTimeConfirmWindow = true" />
				<Button class="p-button-rounded p-button-danger" style="margin-left: 0.5em"
					:label="$t('settings_device.reset')"
					@click="showResetNTPServerConfirmWindow = true" />
			</div>
			<div class="table">
				<div :class="['table_header', {'centered': showMobileVersion}]">
					{{$t("settings_device.system_date_time")}}
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("settings_device.date_time")}}
					</div>
					<div class="cell">
						{{data["date_time"]}}
						<Button class="p-button-link" icon="pi pi-pencil" :disabled="isReadPrivilege"
							@click="openEditWindow('time')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("settings_device.time_zone")}}
					</div>
					<div class="cell">
						{{data["time_zone"]}}
						<Button class="p-button-link" icon="pi pi-pencil" :disabled="isReadPrivilege"
							@click="openEditWindow('time_zone')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("settings_device.ntp")}}
					</div>
					<div class="cell">
						<InputSwitch v-model="ntpToggle" :disabled="isReadPrivilege"
							@click="toggle('NTP')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("settings_device.ntp_server_ip")}}
					</div>
					<div class="cell">
						{{data["ntp_server"]}}
						<Button class="p-button-link" icon="pi pi-pencil" :disabled="isReadPrivilege"
							@click="openEditWindow('server_ip')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("settings_device.ntp_logging")}}
					</div>
					<div class="cell">
						<InputSwitch v-model="ntpLoggingToggle" :disabled="isReadPrivilege"
							@click="toggle('NTPLogging')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("settings_device.ntp_polling_timeout")}}
					</div>
					<div class="cell">
						{{data["ntp_timeout"]}}
						<Button class="p-button-link" icon="pi pi-pencil" :disabled="isReadPrivilege"
							@click="openEditWindow('timeout')" />
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'" v-model:visible="showEditWindow">
		<div style="display:flex; justify-content:center; align-items:center">
			<Calendar v-if="editField=='time'"
				v-model="newDateTime" :showIcon="true" :showTime="true" :showSeconds="true" dateFormat="dd.mm.yy" />
			<Dropdown v-else-if="editField=='time_zone'" style="width: 90%"
				v-model="newTimeZone" :options="timeZoneList"
				:placeholder="$t('settings_device.time_zone')" autofocus />
			<InputText v-else-if="editField=='server_ip'"
				v-model="newServerIP" :class="{'p-invalid': !newServerIPValid}" autofocus />
			<InputText v-else v-model="newValue" autofocus />
		</div>
		<template #footer>
			<Button :label="$t('common.cancel')" icon="pi pi-times" class="p-button-text"
				@click="showEditWindow=false" />
			<Button :label="$t('common.done')" icon="pi pi-check"
				:disabled="newValue=='' && newDateTime==null && (newServerIP=='' || !newServerIPValid)"
				@click="setNewValue" />
		</template>
	</Dialog>
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'" v-model:visible="showUpdateSystemTimeConfirmWindow">
		{{$t("settings_device.update_time_question")}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showUpdateSystemTimeConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="updateSystemTime" />
		</template>
	</Dialog>
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'" v-model:visible="showResetNTPServerConfirmWindow">
		{{$t("settings_device.reset_question")}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showResetNTPServerConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="resetNTPServer" />
		</template>
	</Dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-settings-device-panel",
	created() {
		this.setLoading(true)
		this.fetchData()
	},
	data() {
		return {
			data: {},
			timeZoneList: null,
			showEditWindow: false,
			showUpdateSystemTimeConfirmWindow: false,
			showResetNTPServerConfirmWindow: false,
			
			// InputSwitces state
			ntpToggle: Boolean,
			ntpLoggingToggle: Boolean,
			
			editField: String,
			newValue: String,
			newDateTime: new Date(),
			newTimeZone: String,
			newServerIP: "",
			newServerIPValid: true
		}
	},
	methods: {
		async fetchData() {
			this.getRequest("/php/2_settings/settings_device.php")
				.then(async response => {
					if (response.status == 200) {
						this.data = await response.json()
						this.ntpToggle = this.data["ntp"] == 1
						this.ntpLoggingToggle = this.data["ntp_log"] == 1
						this.setLoading(false)
						
						this.getRequest("/php/2_settings/settings_time_zone_list.php")
							.then(async r => {
								this.timeZoneList = (await r.json())["tz"]
							})
					}
				})
		},
		downloadCertificateChain() {
			window.open("/php/2_settings/download_cert.php?cert=ca", "_blank")
		},
		downloadDeviceCertificate() {
			window.open("/php/2_settings/download_cert.php?cert=device", "_blank")
		},
		resetNTPServer() {
			let formData = new FormData()
			formData.set("cmd", "system time ntp delete")
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(async response => {
					if (response.status == 200) {
						this.fetchData()
					}
				})
			this.showResetNTPServerConfirmWindow = false
		},
		updateSystemTime() {
			let formData = new FormData()
			formData.set("cmd", "system time ntp update")
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(async response => {
					if (response.status == 200) {
						let r = await response.json()
						if (r["type"] == "Error")
							this.$toast.add({severity: "error", detail: r["result"], life: 3000})
						this.fetchData()
					}
				})
			this.showUpdateSystemTimeConfirmWindow = false
		},
		openEditWindow(editField) {
			this.editField = editField
			this.newValue = "",
			this.newDateTime = null,
			this.newServerIP = "",
			this.newServerIPValid = true
			this.showEditWindow = true
		},
		toggle(property) {
			let cmd
			if (property == "NTP") {
				if (this.ntpToggle)
					cmd = "system time ntp set state=disable"
				else {
					if (this.data["ntp_server"] == "undefine") {
						this.$toast.add({severity: "error",
							detail: t("settings_device.no_ntp_server"), life: 3000})
					} else {
						cmd = "system time ntp set state=enable"
					}
				}
			} else if (property == "NTPLogging") {
				if (this.ntpLoggingToggle)
					cmd = "system time ntp set log=disable"
				else
					cmd = "system time ntp set log=enable"
			}
			
			let formData = new FormData()
			formData.set("cmd", cmd)
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(response => {
					if (response.status == 200) {
						this.fetchData()
					}
				})
		},
		setNewValue() {
			let cmd
			if (this.editField == "device_name")
				cmd = "config device name=\"" + this.newValue + "\""
			else if (this.editField == "device_comment")
				cmd = "config device comment=\"" + this.newValue + "\""
			else if (this.editField == "time")
				cmd = this.getCmdSettingNewDate()
			else if (this.editField == "time_zone")
				cmd = "system time set zone=" + this.newTimeZone
			else if (this.editField == "server_ip")
				cmd = "system time ntp set server=" + this.newServerIP
			else if (this.editField == "timeout")
				cmd = "system time ntp set timeout=" + this.newValue
			
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
		getCmdSettingNewDate() {
			let hours = this.newDateTime.getHours()
			let minutes = this.newDateTime.getMinutes()
			let seconds = this.newDateTime.getSeconds()
			let cmd = "system time set time="
			if (hours < 10) cmd += "0"
			cmd += hours + ":"
			if (minutes < 10) cmd += "0"
			cmd += minutes + ":"
			if (seconds < 10) cmd += "0"
			cmd += seconds + " date="
			
			let day = this.newDateTime.getDate()
			let month = this.newDateTime.getMonth() + 1
			let year = this.newDateTime.getFullYear()
			if (day < 10) cmd += "0"
			cmd += day + "."
			if (month < 10) cmd += "0"
			cmd += month + "."
			cmd += year
			
			return cmd
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
			"isReadPrivilege",
			"isIP4Valid"
		])
	},
	watch: {
		newServerIP: function() {
			this.newServerIPValid = this.isIP4Valid(this.newServerIP)
		}
	}
}
</script>

<style scoped>
#settings_device_panel {
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
