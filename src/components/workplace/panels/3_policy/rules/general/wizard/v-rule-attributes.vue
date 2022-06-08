<template>
	<div class="wizard_wrapper">
		<div id="rule_attrib_main">
			<div class="row">
				<div class="title">
					{{$t("policy_rules.number")}}
				</div>
				<InputNumber v-model="number" showButtons :min="1"
					inputStyle="width: 4em" autofocus />
			</div>
			<div class="row">
				<div class="title">
					{{$t("policy_rules.activity")}}
				</div>
				<InputSwitch v-model="activityToggle" />
			</div>
			<div class="row">
				<div class="title">
					{{$t("policy_rules.action")}}
				</div>
				<div>
					<Dropdown v-model="action" :options="actions" />
					<InputNumber v-show="actions.indexOf(action)==2"
						v-model="gotoNumber" showButtons :min="1"
						inputStyle="width: 4em; margin-left: 0.3em" />
				</div>
			</div>
			<div class="row">
				<div class="container">
					<span style="margin-right: 0.3em">
						{{$t("sidebar.packets")}}
					</span>
					<InputSwitch v-model="packetsToggle" />
				</div>
				<div class="container">
					<span style="margin-right: 0.3em">
						{{$t("sidebar.sessions")}}
					</span>
					<InputSwitch v-model="sessionsToggle" />
				</div>
				<div class="container">
					<span style="margin-right: 0.3em">
						{{$t("policy_rules.alarm")}}
					</span>
					<InputSwitch v-model="alarmToggle" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					{{$t("policy_rules.protocol")}}
				</div>
				<Dropdown v-model="protocol" :options="protocols" />
			</div>
			<div class="row">
				<div class="title">
					{{$t("state_device.comment")}}
				</div>
				<InputText v-model="comment" />
			</div>
		</div>
		<div class="nav_btns">
			<Button :label="$t('common.cancel')" icon="pi pi-times" @click="cancel" />
			<Button :label="$t('common.next')" icon="pi pi-angle-right" iconPos="right"
				@click="nextPage()" :disabled="number==null" />
		</div>
	</div>
</template>

<script>
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-general-rule-wizard-rule_attributes",
	data() {
		return {
			number: null,
			action: t("policy_rules.accept"),
			actions: [t("policy_rules.accept"), t("policy_rules.drop"),
				t("policy_rules.goto"), t("policy_rules.deny")],
			gotoNumber: 1,
			activityToggle: true,
			packetsToggle: false,
			sessionsToggle: false,
			alarmToggle: false,
			protocol: "any",
			protocols: ["any", "tcp", "udp"],
			comment: ""
		}
	},
	methods: {
		nextPage() {
			let cmd = "rule:" + this.number + " action="
			
			let index = this.actions.indexOf(this.action)
			if (index == 0)
				cmd += "accept"
			else if (index == 1)
				cmd += "drop"
			else if (index == 2)
				cmd += "goto:" + this.gotoNumber
			else if (index == 3)
				cmd += "deny"
		
			cmd += " active="
			if (this.activityToggle)
				cmd += "enable"
			else
				cmd += "disable"
			
			cmd += " log="
			if (this.packetsToggle && this.sessionsToggle)
				cmd += "enable"
			else if (this.packetsToggle)
				cmd += "packet"
			else if (this.sessionsToggle)
				cmd += "session"
			else
				cmd += "disable"
			
			cmd += " alarm="
			if (this.alarmToggle)
				cmd += "enable"
			else
				cmd += "disable"
			
			cmd += " ipproto=" + this.protocol
			
			if (this.comment != "")
				cmd += " comment=" + this.comment
		
			this.$emit("setStep", {stepNumber: 1, cmd: cmd})
			this.$emit("nextPage", {pageIndex: 0})
		},
		cancel() {
			this.$emit("cancel")
		}
	}
}
</script>

<style scoped>
#rule_attrib_main {
	width: 45vw;
}

.row {
	display: flex;
	justify-content: space-evenly;
	align-items: center;
	margin-bottom: 0.5em;
}

.title {
	flex: 1;
	margin-right: 0.5em;
}

.container {
	display: flex;
	align-items: center;
}

.nav_btns {
	display: flex;
	justify-content: space-between;
	margin-top: 1.5em;
}
</style>
