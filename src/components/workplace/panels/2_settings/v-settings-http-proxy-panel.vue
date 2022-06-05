<template>
	<div v-show="!isLoading" id="settings_http_proxy_panel">
		<div id="panel_wrapper" :class="showDesktopVersion ? 'panel_wrapper_desktop' : 'panel_wrapper_mobile'">
			<div class="table">
				<div :class="['table_header', {'centered': showMobileVersion}]">
					{{$t("sidebar.http_proxy")}}
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("sidebar.status")}}
					</div>
					<div class="cell">
						<InputSwitch v-model="proxyToggle" :disabled="isReadPrivilege" @click="toggle" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("settings_http_proxy.used_interface")}}
					</div>
					<div class="cell">
						<Dropdown v-model="proxyInterface" :options="data['eths']" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("settings_http_proxy.ip_address")}}/{{$t("settings_http_proxy.mask")}}
					</div>
					<div class="cell">
						{{data["ip"]}}/{{data["mask"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('ip_mask')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("settings_http_proxy.port")}}
					</div>
					<div class="cell">
						{{data["port"]}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('port')" />
					</div>
				</div>
			</div>
			<div v-if="!isReadPrivilege" class="btn_panel">
				<Button class="p-button-rounded p-button-danger"
					:label="$t('settings_device.reset')"
					@click="showRemoveDNSConfirmWindow = true" />
			</div>
			<div class="table">
				<div :class="['table_header', {'centered': showMobileVersion}]">
					{{$t("settings_http_proxy.dns")}}
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("settings_http_proxy.dns_server")}} 1
					</div>
					<div class="cell">
						{{data.dns?.at(0).address}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('dns1')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("settings_http_proxy.dns_server")}} 2
					</div>
					<div class="cell">
						{{data.dns?.at(1).address}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('dns2')" />
					</div>
				</div>
				<div class="row">
					<div class="title_cell">
						{{$t("settings_http_proxy.dns_server")}} 3
					</div>
					<div class="cell">
						{{data.dns?.at(2).address}}
						<Button class="p-button-link" icon="pi pi-pencil"
							@click="openEditWindow('dns3')" />
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'" v-model:visible="showEditWindow">
		<div style="display:flex;justify-content:center">
			<div v-if="editField=='ip_mask'" style="display:flex;flex-flow:column;align-items:center">
				<InputText v-model="newIP" :class="{'p-invalid': !newIPValid}" 
					:placeholder="$t('settings_http_proxy.ip_address')" autofocus />
				<InputText v-model="newMask" :class="{'p-invalid': !newMaskValid}"
					:placeholder="$t('settings_http_proxy.mask')" />
			</div>
			<InputText v-else-if="editField=='port'" v-model="newPort" :class="{'p-invalid': !newPortValid}"
				:placeholder="$t('settings_http_proxy.port')" autofocus />
			<InputText v-else v-model="newIP" :class="{'p-invalid': !newIPValid}"
				:placeholder="$t('settings_http_proxy.ip_address')" autofocus />
		</div>
		<template #footer>
			<Button :label="$t('common.cancel')" icon="pi pi-times" class="p-button-text"
				@click="showEditWindow=false" />
			<Button :label="$t('common.done')" icon="pi pi-check"
				:disabled="(newPort=='' || !newPortValid) && (newIP=='' || !newIPValid)"
				@click="setNewValue" />
		</template>
	</Dialog>
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'" v-model:visible="showRemoveDNSConfirmWindow">
		{{$t("settings_http_proxy.remove_dns_question")}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showRemoveDNSConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="resetDNS" />
		</template>
	</Dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
export default {
	name: "v-settings-http-proxy-panel",
	created() {
		this.setLoading(true)
		this.fetchData()
	},
	data() {
		return {
			data: {},
			showEditWindow: false,
			showRemoveDNSConfirmWindow: false,
			
			// InputSwitces state
			proxyToggle: Boolean,
			
			proxyInterface: String,
			newIP: "",
			newIPValid: true,
			newMask: "",
			newMaskValid: true,
			newPort: "",
			newPortValid: true
		}
	},
	methods: {
		async fetchData() {
			this.getRequest("/php/2_settings/settings_http_proxy.php")
				.then(async response => {
					if (response.status == 200) {
						this.data = await response.json()
						this.proxyToggle = this.data["proxy_state"] == 1
						this.proxyInterface = this.data["proxy_interface"]
						if (this.proxyToggle)
							this.data["eths"].unshift(this.proxyInterface)
						this.setLoading(false)
					}
				})
		},
		openEditWindow(editField) {
			this.editField = editField
			this.newIP = ""
			this.newIPValid = true
			this.newMask = ""
			this.newMaskValid = true
			this.newPort = ""
			this.newPortValid = true
			this.showEditWindow = true
		},
		toggle() {
			let cmd = "system proxy set state="
			if (this.proxyToggle)
				cmd += "disable"
			else
				cmd += "enable"
			
			let formData = new FormData()
			formData.set("cmd", cmd)
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(response => {
					if (response.status == 200) {
						this.fetchData()
					}
				})
		},
		resetDNS() {
			let formData = new FormData()
			formData.set("cmd", "system dns clear")
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(async response => {
					if (response.status == 200) {
						this.fetchData()
					}
				})
			this.showRemoveDNSConfirmWindow = false
		},
		setNewValue() {
			let cmd
			if (this.editField == "ip_mask")
				cmd = "system proxy set address=" + this.newIP + "/" + this.newMask
			else if (this.editField == "port")
				cmd = "system proxy set port=" + this.newPort
			else if (this.editField == "dns1") {
				cmd = "system dns set address=" + this.newIP
				if (this.data["dns"][1]["address"] != "")
					cmd += "," + this.data["dns"][1]["address"]
				if (this.data["dns"][2]["address"] != "")
					cmd += "," + this.data["dns"][2]["address"]
			} else if (this.editField == "dns2") {
				cmd = "system dns set address="
				if (this.data["dns"][0]["address"] != "")
					cmd += this.data["dns"][0]["address"] + ","
				cmd += this.newIP
				if (this.data["dns"][2]["address"] != "")
					cmd += "," + this.data["dns"][2]["address"]
			} else if (this.editField == "dns3") {
				cmd = "system dns set address="
				if (this.data["dns"][0]["address"] != "")
					cmd += this.data["dns"][0]["address"] + ","
				if (this.data["dns"][1]["address"] != "")
					cmd += this.data["dns"][1]["address"] + ","
				cmd += this.newIP
			}
			
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
			"isIP4Valid",
			"isMaskValid"
		])
	},
	watch: {
		proxyInterface: function() {
			if (this.proxyInterface != this.data["proxy_interface"]) {
				let cmd = "system proxy set interface=" +
					this.proxyInterface.slice(this.proxyInterface.length - 1)
				let formData = new FormData()
				formData.set("cmd", cmd)
				this.postRequest({"url": "/php/cli.php", "body": formData})
					.then(response => {
						if (response.status == 200) {
							this.fetchData()
						}
					})
			}
		},
		newIP: function() {
			this.newIPValid = this.isIP4Valid(this.newIP)
		},
		newMask: function() {
			this.newMaskValid = this.isMaskValid(this.newMask)
		},
		newPort: function() {
			this.newPortValid = this.newPort.match(/^[1-9]([0-9])*$/)
		}
	}
}
</script>

<style scoped>
#settings_http_proxy_panel {
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
