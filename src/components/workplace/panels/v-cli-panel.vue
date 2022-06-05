<template>
	<div id="cli_panel" :key="componentKey">
		<Terminal id="terminal" prompt="> " />
	</div>
</template>

<script>
import { mapActions } from "vuex"
import Terminal from "primevue/terminal"
import TerminalService from "primevue/terminalservice"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-cli-panel",
	components: {
		Terminal
	},
	data() {
		return {
			componentKey: 0
		}
	},
	methods: {
		async commandHandler(cmd) {
			if (cmd == "clear") {
				this.clear()
			} else if (cmd == "help") {
				let help = "help - " + t("debug.help_description") +
					"clear - " + t("debug.clear_description") +
					"config - " + t("debug.config_description") +
					"filter - " + t("debug.filter_description") +
					"directory - " + t("debug.directory_description") +
					"interface - " + t("debug.interface_description") +
					"log - " + t("debug.log_description") +
					"nat - " + t("debug.nat_description") +
					"policy - " + t("debug.policy_description") +
					"rule - " + t("debug.rule_description") +
					"reserv - " + t("debug.reserv_description") +
					"session - " + t("debug.session_description") +
					"system - " + t("debug.system_description") +
					"user - " + t("debug.user_description")
				TerminalService.emit("response", help)
			} else {
				let formData = new FormData()
				formData.set("cmd", cmd)
				this.postRequest({"url": "/php/cli.php", "body": formData})
					.then(async response => {
						if (response.status == 200) {
							let r = await response.json()
							TerminalService.emit("response", r["type"] + ": " + r["result"])
						} else {
							TerminalService.emit("response", t("debug.cmd_error"))
						}
					})
			}
		},
		clear() {
			this.componentKey += 1
		},
		...mapActions([
			"postRequest"
		])
	},
	mounted() {
		TerminalService.on("command", this.commandHandler)
	},
	beforeUnmount() {
		TerminalService.off("command", this.commandHandler)
	}
}
</script>

<style scoped>
#cli_panel {
	display: flex;
	flex-flow: column;
	flex: 1;
}

#terminal {
	flex: 1;
	background-color: #000000;
	color: #ffffff;
	word-break: break-all;
	white-space: pre-line;
}
</style>
