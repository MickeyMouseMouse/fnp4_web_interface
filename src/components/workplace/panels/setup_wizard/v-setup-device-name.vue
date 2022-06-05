<template>
	<div class="setup_wrapper">
		<div class="device_name_main">
			<div class="row">
				<div class="title">
					{{$t("state_device.device_name")}}
				</div>
				<div class="setting">
					<InputText v-model="name" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					{{$t("state_device.comment")}}
				</div>
				<div class="setting">
					<InputText v-model="comment" />
				</div>
			</div>
		</div>
		<div class="nav_btns">
			<Button :label="$t('common.back')" @click="prevPage" icon="pi pi-angle-left"
				style="margin-right:0.5em" />
			<div>
				<Button :label="$t('common.skip')" class="p-button-link" @click="nextPage"  />
				<Button :label="$t('common.apply')" @click="apply"
					:disabled="name=='' || comment==''" />
			</div>
		</div>
	</div>
</template>

<script>
import { mapActions} from "vuex"
export default {
	name: "v-setup-device-name",
	data() {
		return {
			name: "",
			comment: ""
		}
	},
	methods: {
		apply() {
			let formData = new FormData()
			formData.set("cmd", "config device name=\"" + this.name + "\"")
			this.postRequest({"url": "/php/cli.php", "body": formData})
			
			formData.set("cmd", "config device comment=\"" + this.comment + "\"")
			this.postRequest({"url": "/php/cli.php", "body": formData})
			
			this.nextPage()
		},
		nextPage() {
			this.$emit("nextPage", {pageIndex: 1});
		},
		prevPage() {
			this.$emit("prevPage", {pageIndex: 1});
		},
		...mapActions([
			"postRequest"
		])
	}
}
</script>

<style scoped>
.setup_wrapper {
	display: flex;
	flex-flow: column;
	width: 26em;
}

.device_name_main {
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
