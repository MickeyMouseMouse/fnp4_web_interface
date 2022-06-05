<template>
	<div class="setup_wrapper">
		<div class="control_interface_main">
			<div class="row">
				<div class="title">
					{{$t("setup.control_interface_ip")}}
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
		</div>
		<div class="nav_btns">
			<Button :label="$t('common.back')" @click="prevPage" icon="pi pi-angle-left"
				style="margin-right:0.5em" />
			<div>
				<Button :label="$t('common.skip')" class="p-button-link" @click="nextPage"  />
				<Button :label="$t('common.apply')" @click="apply" 
					:disabled="ip=='' || !ipValid || mask=='' || !maskValid" />
			</div>
		</div>
	</div>
</template>

<script>
import { mapGetters } from "vuex"
export default {
	name: "v-setup-control-interface",
	data() {
		return {
			ip: "",
			ipValid: true,
			mask: "",
			maskValid: true
		}
	},
	methods: {
		apply() {
			let formData = new FormData()
			formData.set("cmd", "interface control set address=" + this.ip + "/" + this.mask)
			this.postRequest({"url": "/php/cli.php", "body": formData})
			this.nextPage()
		},
		nextPage() {
			this.$emit("nextPage", {pageIndex: 2})
		},
		prevPage() {
			this.$emit("prevPage", {pageIndex: 2});
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
.setup_wrapper {
	display: flex;
	flex-flow: column;
	width: 26em;
}

.control_interface_main {
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
