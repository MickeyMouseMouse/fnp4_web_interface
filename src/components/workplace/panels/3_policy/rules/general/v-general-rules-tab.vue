<template>
	<div id="general_rules_tab" v-show="show">
		<div v-if="showDesktopVersion" id="btn_panel">
			<Button icon="pi pi-plus" :disabled="isReadPrivilege"
				:label="$t('policy_rules.new_rule')" @click="openWizard()" />
			<Button icon="pi pi-search" :disabled="isReadPrivilege"
				:label="$t('policy_rules.anomaly_search')" @click="checkForAnomalies()" />
		</div>
		<div id="table">
			<GeneralRule v-if="showDesktopVersion" :is_table_header="true" />
			<div id="table_content">
				<div :class="showDesktopVersion ? 'title_row' : 'title_row_mobile'">
					<span>{{$t("policy_rules.global_rule")}}</span>
				</div>
				<GeneralRule
					active="enable"
					number="0"
					:action="data.global_rule?.action"
					:is_log_packet="data.global_rule?.is_log_packet" 
					:is_log_session="data.global_rule?.is_log_session"
					:is_alarm="data.global_rule?.is_alarm"
					:protocol="data.global_rule?.protocol"
					:source="data.global_rule?.source"
					:destination="data.global_rule?.destination"
					:comment="data.global_rule?.comment" />
				<div :class="showDesktopVersion ? 'title_row' : 'title_row_mobile'">
					<span>{{$t("policy_rules.general_rules")}}</span>
				</div>
				<GeneralRule v-for="rule in data.rules" :key="rule.id" @updateData="fetchData"
					:active="rule.active"
					:number="rule.name"
					:action="rule.action"
					:is_log_packet="rule.is_log_packet" 
					:is_log_session="rule.is_log_session"
					:is_alarm="rule.is_alarm"
					:protocol="rule.protocol"
					:source="rule.source"
					:destination="rule.destination"
					:comment="rule.comment" />
			</div>
		</div>
	</div>

	<Dialog header=" " :modal="true" :draggable="false" :closable="false"
		v-model:visible="showGeneralRuleWizard">
		<router-view @complete="complete" @closeWizard="closeWizard"/>
	</Dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import GeneralRule from "./v-general-rule.vue"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-general-rules-tab",
	components: {
		GeneralRule
	},
	created() {
		this.fetchData()
	},
	data() {
		return {
			data: {},
			show: false,
			
			showGeneralRuleWizard: false
		}
	},
	methods: {
		fetchData() {
			this.getRequest("/php/3_policy/rules/general_rules.php")
				.then(async response => {
					if (response.status == 200) {
						this.data = await response.json()
						this.show = true
					}
				})
		},
		openWizard() {
			this.showGeneralRuleWizard = true
			this.$router.push({path: "/dashboard/policy_rules/general/wizard/rule_attributes"})
		},
		closeWizard() {
			this.showGeneralRuleWizard = false
			this.$router.push({path: "/dashboard/policy_rules/general"})
		},
		complete() {
			this.closeWizard()
			this.fetchData()
			this.checkForAnomalies(true)
		},
		checkForAnomalies(silenceMode = false) {
			this.$toast.removeAllGroups()
			this.getRequest("/php/3_policy/rules/anomaly_checking.php")
				.then(async response => {
					if (response.status == 200) {
						let result = await response.json()
						if (result.anomalies == "no") {
							if (!silenceMode)
								this.$toast.add({severity: "success", life: 3000,
									detail: t("policy_rules.no_anomalies_detected")})
						} else {
							for (let i = 0; i < result.anomalies.length; i++) {
								if (result.anomalies[i].anomaly == "shading") {
									this.$toast.add({
										severity: "error",
										summary: t("policy_rules." + result.anomalies[i].anomaly),
										detail: t("policy_rules.rule_shading", {
											rule1: result.anomalies[i].problem_rule,
											rule2: result.anomalies[i].other
										})
									})
								}
								if (result.anomalies[i].anomaly == "redundancy") {
									this.$toast.add({
										severity: "error",
										summary: t("policy_rules." + result.anomalies[i].anomaly),
										detail: t("policy_rules.rule_redundancy", {
											rule1: result.anomalies[i].problem_rule,
											rule2: result.anomalies[i].other
										})
									})
								}
								if (result.anomalies[i].anomaly == "merge") {
									this.$toast.add({
										severity: "warn",
										detail: t("policy_rules.rules_can_be_merged", {
											rule1: result.anomalies[i].rule1,
											rule2: result.anomalies[i].rule2,
											field: t("policy_rules." + result.anomalies[i].field)
										})
									})
								}
							}
						}
					}
				})
		},
		...mapActions([
			"getRequest"
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
#general_rules_tab {
	display: flex;
	flex-flow: column;
	flex: 1;
	height: 100%;
	overflow: hidden;
}

#btn_panel {
	display: flex;
	justify-content: space-between;
	margin-bottom: 1em;
}

#table {
	display: flex;
	flex-flow: column;
	height: 100%;
	overflow: hidden;
}

.title_row {
	background-color: var(--third-color);
	text-align: center;
	padding-top: 0.3em;
	padding-bottom: 0.3em;
	border-left: 1px solid black;
	border-right: 1px solid black;
	border-bottom: 1px solid black;
	background-color: white;
}

.title_row_mobile {
	margin-bottom: 0.5em;
	padding-top: 0.5em;
	padding-bottom: 0.5em;
	text-align: center;
	font-weight: bold;
	background-color: white;
}

#table_content {
	overflow: auto;
}
</style>
