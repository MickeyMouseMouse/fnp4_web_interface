<template>
	<div id="general_rule_wizard_panel">
		<div id="steps_menu">
			<Steps :model="items" />
		</div>
		<div id="general_rule_wizard_content">
			<router-view v-slot="{Component}" :formData="formObject"
				@prevPage="prevPage($event)" @nextPage="nextPage($event)"
				@setStep="setStep" @cancel="cancel" @complete="complete">
				<keep-alive>
					<component :is="Component" />
				</keep-alive>
			</router-view>
		</div>
	</div>
</template>

<script>
import { mapActions } from "vuex"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-genral-rule-wizard-panel",
	props: {
		
	},
	data() {
		return {
			items: [
				{
					label: t("general_rule_wizard_panel.rule_attributes"),
					to: "/dashboard/policy_rules/general/wizard/rule_attributes"
				},
				{
					label: t("general_rule_wizard_panel.src_params"),
					to: "/dashboard/policy_rules/general/wizard/source_parameters"
				},
				{
					label: t("general_rule_wizard_panel.dest_params"),
					to: "/dashboard/policy_rules/general/wizard/destination_parameters"
				}
			],
			formObject: {},
			step1: "",
			step2: "",
			step3: ""
		}
	},
	methods: {
		nextPage(event) {
			for (let field in event.formData)
				this.formObject[field] = event.formData[field]
			this.$router.push(this.items[event.pageIndex + 1].to)
		},
		prevPage(event) {
			this.$router.push(this.items[event.pageIndex - 1].to)
		},
		setStep(data) {
			if (data.stepNumber == 1)
				this.step1 = data.cmd
			else if (data.stepNumber == 2)
				this.step2 = data.cmd
			else if (data.stepNumber == 3)
				this.step3 = data.cmd
		},
		complete() {
			let formData = new FormData()
			formData.set("cmd", "rule add " + this.step1 + " " + this.step2 + " " + this.step3)
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(async response => {
					if (response.status == 200) {
						let r = await response.json()
						if (r["type"] == "Error") {
							this.$toast.add({severity: "error", detail: r["result"], life: 4000})
							this.cancel()
						} else {
							this.$emit("complete")
						}
					}
				})
		},
		cancel() {
			this.$emit("closeWizard")
		},
		...mapActions([
			"postRequest"
		])
	}
}
</script>

<style scoped>
#steps_menu {
	border-radius: 10px;
	margin-bottom: 2em;
	background-color: white;
}

#general_rule_wizard_content {
	display: flex;
	justify-content: center;
	width: 40em;
}
</style>
