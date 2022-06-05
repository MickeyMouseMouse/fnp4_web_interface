<template>
	<div id="policy_rules_panel">
		<div id="tab_menu">
			<TabMenu :model="items" />
		</div>
		<div id="tab">
			<router-view />
		</div>
	</div>
</template>

<script>
import TabMenu from "primevue/tabmenu"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-policy-rules-panel",
	components: {
		TabMenu
	},
	data() {
		return {
			items: [
				{
					label: t("policy_rules.general"),
					to: "/dashboard/policy_rules/general"
				}
			]
		}
	},
	beforeUnmount() {
		this.$toast.removeAllGroups()
	}
}
</script>

<style scoped>
#policy_rules_panel {
	display: flex;
	flex-flow: column;
	height: 100%;
	overflow: hidden;
}

#tab_menu {
	margin-bottom: 1.5em;
}

#tab {
	overflow: hidden;
}

</style>
