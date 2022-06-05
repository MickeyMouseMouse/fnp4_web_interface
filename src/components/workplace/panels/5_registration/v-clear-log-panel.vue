<template>
	<div id="clear_log_panel" :class="{'disabled': isReadPrivilege}">
		<div id="panel_wrapper" :class="showDesktopVersion ? 'panel_wrapper_desktop' : 'panel_wrapper_mobile'">
			<div :class="showDesktopVersion ? 'desktop' : 'mobile'">
				<div class="row">
					<div class="title">
						{{$t("clear_log.packet_log")}}
					</div>
					<Button icon="pi pi-trash" class="p-button-rounded p-button-danger"
						:label="$t('common.clear')"
						@click="showClearPacketsConfirmWindow = true" />
				</div>
				<div class="row">
					<div class="title">
						{{$t("clear_log.session_log")}}
					</div>
					<Button icon="pi pi-trash" class="p-button-rounded p-button-danger"
						:label="$t('common.clear')"
						@click="showClearSessionsConfirmWindow = true" />
				</div>
			</div>
		</div>
	</div>
	
	<!--modal windows-->
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'"
		v-model:visible="showClearPacketsConfirmWindow">
		{{$t("clear_log.clear_packet_log")}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showClearPacketsConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="clearPackets" />
		</template>
	</Dialog>
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'"
		v-model:visible="showClearSessionsConfirmWindow">
		{{$t("clear_log.clear_session_log")}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showClearSessionsConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="clearSessions" />
		</template>
	</Dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-clear-log-panel",
	data() {
		return {
			showClearPacketsConfirmWindow: false,
			showClearSessionsConfirmWindow: false
		}
	},
	methods: {
		clearPackets() {
			let formData = new FormData()
			formData.set("cmd", "log packet clear")
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(async response => {
					if (response.status == 200) {
						this.$toast.add({severity: "info",
							summary: t("clear_log.packet_log_cleared"), life: 3000})
					}
				})
		},
		clearSessions() {
			let formData = new FormData()
			formData.set("cmd", "log session clear")
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(async response => {
					if (response.status == 200) {
						this.$toast.add({severity: "info",
							summary: t("clear_log.session_log_cleared"), life: 3000})
					}
				})
		},
		...mapActions([
			"postRequest"
		])
	},
	computed: {
		...mapGetters([
			"showDesktopVersion",
			"isReadPrivilege"
		])
	}
}
</script>

<style scoped>
#clear_log_panel {
	display: flex;
	justify-content: center;
}

#panel_wrapper {
	display: flex;
	flex-flow: column;
}

.panel_wrapper_desktop {
	width: 30em;
}

.panel_wrapper_mobile {
	flex: 1;
}

.desktop {
	width: 25em;
}

.mobile {
	width: 100%;
}

.row {
	display: flex;
	margin-bottom: 1em;
}

.title {
	display: flex;
	flex: 1;
	align-items: center;
	margin-right: 0.5em;
}

.disabled {
	pointer-events: none;
	opacity: 0.4;
}
</style>
