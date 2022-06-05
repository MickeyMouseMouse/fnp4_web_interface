<template>
	<div class="wizard_wrapper">
		<div id="src_params_main">
			<div class="row">
				<div class="title">
					{{$t("settings_http_proxy.ip_address")}}
				</div>
				<div class="setting">
					<InputText v-model="ip" :class="{'p-invalid': !ipValid}" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					{{$t("settings_http_proxy.mask")}}
				</div>
				<div class="setting">
					<InputText v-model="mask" :class="{'p-invalid': !maskValid}" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					{{$t("settings_http_proxy.port")}}
				</div>
				<div class="setting">
					<InputText v-model="port" />
				</div>
			</div>
		</div>
		<div class="nav_btns">
			<Button :label="$t('common.cancel')" icon="pi pi-times" @click="cancel" />
			<div>
				<Button :label="$t('common.back')" icon="pi pi-angle-left"
					@click="prevPage" :disabled="false"
					style="margin-right: 0.5em" />
				<Button :label="$t('common.next')" icon="pi pi-angle-right" iconPos="right"
					@click="nextPage" :disabled="false" />
			</div>
		</div>
	</div>
</template>

<script>
import { mapGetters } from "vuex"
export default {
	name: "v-general-rule-wizard-source-parameters",
	data() {
		return {
			ip: "",
			ipValid: true,
			mask: "",
			maskValid: true,
			port: ""
		}
	},
	methods: {
		nextPage() {
			let cmd = ""
			if (this.ip != "")
				cmd += "srcip4=" + this.ip
			if (this.mask != "")
				cmd += "/" + this.mask
			if (this.port != "")
				cmd += " srcport=" + this.port
			
			this.$emit("setStep", {stepNumber: 2, cmd: cmd})
			this.$emit("nextPage", {pageIndex: 1})
		},
		prevPage() {
			
			this.$emit("prevPage", {pageIndex: 1})
		},
		cancel() {
			this.$emit("cancel")
		}
	},
	computed: {
		...mapGetters([
			"isIP4Valid",
			"isMaskValid"
		])
	},
	watch: {
		ip: function() {
			this.ipValid = this.isIP4Valid(this.ip)
		},
		mask: function() {
			this.maskValid = this.isMaskValid(this.mask)
		}
	}
}
</script>

<style scoped>
#src_params_main {
	width: 45vw;
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
