<template>
	<div class="setup_wrapper">
		<div class="ntp_main">
			<div class="row">
				<div class="title">
					{{$t("settings_device.ntp_server_ip")}}
				</div>
				<div class="setting">
					<InputText v-model="ip" :class="{'p-invalid': !ipValid}" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					{{$t("settings_device.ntp_polling_timeout")}}
				</div>
				<div class="setting">
					<InputText v-model="timeout" :class="{'p-invalid': !timeoutValid}"
						placeholder="3600" />
				</div>
			</div>
		</div>
		<div class="nav_btns">
			<Button :label="$t('common.back')" @click="prevPage" icon="pi pi-angle-left"
				style="margin-right:0.5em" />
			<div>
				<Button :label="$t('common.skip')" class="p-button-link" @click="nextPage"  />
				<Button :label="$t('common.apply')" @click="apply" 
					:disabled="ip=='' || !ipValid || !timeoutValid" />
			</div>
		</div>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
export default {
	name: "v-setup-ntp",
	data() {
		return {
			ip: "",
			ipValid: true,
			timeout: "",
			timeoutValid: true
		}
	},
	methods: {
		apply() {
			let formData = new FormData()
			formData.set("cmd", "system time ntp set server=" + this.ip + " state=enable")
			this.postRequest({"url": "/php/cli.php", "body": formData})
			
			formData.set("cmd", "system time ntp set timeout=" + this.timeout)
			this.postRequest({"url": "/php/cli.php", "body": formData})
			
			formData.set("cmd", "system time ntp update")
			this.postRequest({"url": "/php/cli.php", "body": formData})

			this.nextPage()
		},
		nextPage() {
			this.$emit("complete")
		},
		prevPage() {
			this.$emit("prevPage", {pageIndex: 5})
		},
		...mapActions([
			"postRequest"
		])
	},
	computed: {
		...mapGetters([
			"isIP4Valid"
		])
	},
	watch: {
		ip: function() {
			this.ipValid = this.isIP4Valid(this.ip)
		},
		timeout: function() {
			this.timeoutValid = this.timeout.match(/^[1-9]([0-9])*$/)
		}
	}
}
</script>

<style scoped>
.setup_wrapper {
	display: flex;
	flex-flow: column;
	width: 26em;
}

.ntp_main {
	height: 9em;
}

.row {
	display: flex;
	justify-content: center;
	align-items: center;
	margin-bottom: 0.5em;
}

.title {
	flex: 1;
	margin-right: 0.5em;
}

.nav_btns {
	display: flex;
	justify-content: space-between;
	margin-top: 1.5em;
}
</style>
