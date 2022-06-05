<template>
	<div class="setup_wrapper">
		<div class="default_gateway_main">
			<div class="row">
				<div class="title">
					{{$t("setup.default_gateway")}}
				</div>
				<div class="setting">
					<InputText v-model="ip" :class="{'p-invalid': !ipValid}" />
				</div>
			</div>
		</div>
		<div class="nav_btns">
			<Button :label="$t('common.back')" @click="prevPage" icon="pi pi-angle-left"
				style="margin-right:0.5em" />
			<div>
				<Button :label="$t('common.skip')" class="p-button-link" @click="nextPage"  />
				<Button :label="$t('common.apply')" @click="apply" :disabled="ip=='' || !ipValid" />
			</div>
		</div>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
export default {
	name: "v-setup-control-interface",
	data() {
		return {
			ip: "",
			ipValid: true
		}
	},
	methods: {
		apply() {
			/*
			let formData = new FormData()
			formData.set("cmd", "system route add dst-address=0.0.0.0 gateway=" + this.ip)
			this.postRequest({"url": "/php/cli.php", "body": formData})
			*/
			this.nextPage()
		},
		nextPage() {
			this.$emit("nextPage", {pageIndex: 3})
		},
		prevPage() {
			this.$emit("prevPage", {pageIndex: 3});
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

.default_gateway_main {
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
