<template>
	<div class="setup_wrapper">
		<div class="dns_main">
			<div class="row">
				<div class="title">
					{{$t("settings_http_proxy.dns")}} 1
				</div>
				<div class="setting">
					<InputText v-model="dnsIP1" :class="{'p-invalid': !dnsIP1Valid}" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					{{$t("settings_http_proxy.dns")}} 2
				</div>
				<div class="setting">
					<InputText v-model="dnsIP2" :class="{'p-invalid': !dnsIP2Valid}" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					{{$t("settings_http_proxy.dns")}} 3
				</div>
				<div class="setting">
					<InputText v-model="dnsIP3" :class="{'p-invalid': !dnsIP3Valid}" />
				</div>
			</div>
		</div>
		<div class="nav_btns">
			<Button :label="$t('common.back')" @click="prevPage" icon="pi pi-angle-left"
				style="margin-right:0.5em" />
			<div>
				<Button :label="$t('common.skip')" class="p-button-link" @click="nextPage"  />
				<Button :label="$t('common.apply')" @click="apply" 
					:disabled="(dnsIP1=='' || !dnsIP1Valid) && (dnsIP2=='' || !dnsIP2Valid) && (dnsIP3=='' || !dnsIP3Valid)" />
			</div>
		</div>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
export default {
	name: "v-setup-dns",
	data() {
		return {
			dnsIP1: "",
			dnsIP1Valid: true,
			dnsIP2: "",
			dnsIP2Valid: true,
			dnsIP3: "",
			dnsIP3Valid: true
		}
	},
	methods: {
		apply() {
			let cmd = "system dns set address="
			if (this.dnsIP1 != "") cmd += this.dnsIP1
			if (this.dnsIP2 != "") {
				if (this.dnsIP1 != "") cmd += ","
				cmd += this.dnsIP2
			}
			if (this.dnsIP3 != "") {
				if (this.dnsIP1 != "" || this.dnsIP2 != "") cmd += ","
				cmd += this.dnsIP3
			}
			
			let formData = new FormData()
			formData.set("cmd", cmd)
			this.postRequest({"url": "/php/cli.php", "body": formData})
			
			this.nextPage()
		},
		nextPage() {
			this.$emit("nextPage", {pageIndex: 4})
		},
		prevPage() {
			this.$emit("prevPage", {pageIndex: 4})
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
		dnsIP1: function() {
			this.dnsIP1Valid = this.isIP4Valid(this.dnsIP1)
		},
		dnsIP2: function() {
			this.dnsIP2Valid = this.isIP4Valid(this.dnsIP2)
		},
		dnsIP3: function() {
			this.dnsIP3Valid = this.isIP4Valid(this.dnsIP3)
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

.dns_main {
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
