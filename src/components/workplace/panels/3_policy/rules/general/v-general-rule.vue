<template>
	<div v-if="is_table_header" id="general_rule_header">
		<div v-show="!isReadPrivilege" class="header_cell" style="width:25px">
		</div>
		<div class="pi pi-check-circle header_cell cell_separator" style="width:50px"
			:title="$t('policy_rules.activity')" />
		<div class="header_cell cell_separator" style="width:30px"
			:title="$t('policy_rules.number')" >
			№
		</div>
		<div class="pi pi-flag header_cell cell_separator" style="width:30px"
			:title="$t('policy_rules.action')" />
		<div class="pi pi-book header_cell cell_separator" style="width:50px"
			:title="$t('policy_rules.packet_session_logging')" />
		<div class="pi pi-bell header_cell cell_separator" style="width:30px"
			:title="$t('policy_rules.alarm')" />
		<div class="header_cell cell_separator" style="width:80px">
			{{$t("policy_rules.protocol")}}
		</div>
		<div class="header_cell cell_separator" style="width:230px">
			{{$t("policy_rules.source")}}
		</div>
		<div class="header_cell cell_separator" style="width:230px">
			{{$t("policy_rules.destination")}}
		</div>
		<div class="header_cell" style="flex:1">
			{{$t("policy_rules.comment")}}
		</div>
	</div>
	<div v-else-if="showDesktopVersion" id="general_rule_row_desktop">
		<div v-show="!isReadPrivilege" class="row_cell pi pi-ellipsis-h"
			style="width:25px" @click="toggleContextMenu">
			<TieredMenu ref="menu" :model="contextMenuItems" :popup="true">
				<template #item="{item}">
					<div class="context_menu_item" :style="item.style" @click="item.command">
						<div :class="item.icon" style="margin-right: 0.3em"></div>
						<div>{{item.label}}</div>
					</div>
				</template>
			</TieredMenu>
		</div>
		<div class="row_cell cell_separator" style="width:50px"
			:title="$t('policy_rules.activity')">
			<InputSwitch v-if="!isGlobalRule" v-model="activeToggle"
				:disabled="isReadPrivilege" @click="setRuleActive" />
		</div>
		<div :class="['row_cell', 'cell_separator', {'red': action=='drop',
			'yellow': action=='deny', 'green': action=='accept'}]"
			style="width:30px" :title="$t('policy_rules.number')">
			{{number}}
		</div>
		<div :class="['row_cell', 'cell_separator', 'pi pi-flag-fill',
			{'red': action=='drop', 'yellow': action=='deny',
			'green': action=='accept'}]" style="width:30px"
			:title="$t('policy_rules.action')" />
		<div class="row_cell cell_separator" style="width:50px"
			:title="$t('policy_rules.packet_session_logging')">
			<div :class="(is_log_packet=='enable') ? 'pi pi-check-circle green' : 'pi pi-times-circle red'"
				style="margin-right: 0.1em" />
			<div :class="(is_log_session=='enable') ? 'pi pi-check-circle green' : 'pi pi-times-circle red'" />
		</div>
		<div class="row_cell cell_separator" style="width:30px"
			:title="$t('policy_rules.alarm')">
			<div :class="(is_alarm=='enable') ? 'pi pi-check-circle green' : 'pi pi-times-circle red'" />
		</div>
		<div :class="['row_cell', 'cell_separator', {'red': action=='drop', 'yellow': action=='deny',
			'green': action=='accept'}]" style="width:80px">
			{{protocol}}
		</div>
		<div :class="['row_cell', 'cell_separator', {'red': action=='drop', 'yellow': action=='deny',
			'green': action=='accept'}]" style="width:230px">
			{{source}}
		</div>
		<div :class="['row_cell', 'cell_separator', {'red': action=='drop', 'yellow': action=='deny',
			'green': action=='accept'}]" style="width:230px">
			{{destination}}
		</div>
		<div class="row_cell" style="flex:1">
			{{comment}}
		</div>
	</div>
	<div v-else id="general_rule_row_mobile">
		<div class="horizontal_container">
			<div v-if="!isGlobalRule" class="vertical_container">
				<div :class="['cell_mobile', 'title_cell_mobile', {'green': activeToggle}]">
					<span class="pi pi-check-circle" />
				</div>
				<div class="cell_mobile">
					<InputSwitch v-model="activeToggle"
						:disabled="isReadPrivilege" @click="setRuleActive" />
				</div>
			</div>
			<div class="vertical_container">
				<div class="cell_mobile title_cell_mobile">
					<span>№</span>
				</div>
				<div class="cell_mobile">
					<span>
						{{number}}
					</span>
				</div>
			</div>
			<div class="vertical_container">
				<div class="cell_mobile title_cell_mobile">
					<span class="pi pi-flag" />
				</div>
				<div class="cell_mobile">
					<span v-if="action=='drop'" class="pi pi-flag-fill red" />
					<span v-else-if="action=='deny'" class="pi pi-flag-fill yellow" />
					<span v-else-if="action=='accept'" class="pi pi-flag-fill green" />
				</div>
			</div>
			<div class="vertical_container">
				<div class="cell_mobile title_cell_mobile">
					<span class="pi pi-book" />
				</div>
				<div class="cell_mobile">
					<div :class="(is_log_packet=='enable') ? 'pi pi-check-circle green' : 'pi pi-times-circle red'"
						style="margin-right: 0.1em" />
					<div :class="(is_log_session=='enable') ? 'pi pi-check-circle green' : 'pi pi-times-circle red'" />
				</div>
			</div>
			<div class="vertical_container">
				<div class="cell_mobile title_cell_mobile">
					<span class="pi pi-bell" />
				</div>
				<div class="cell_mobile">
					<div :class="(is_alarm=='enable') ? 'pi pi-check-circle green' : 'pi pi-times-circle red'" />
				</div>
			</div>
		</div>
		<div class="vertical_container">
			<div class="cell_mobile title_cell_mobile">
				<span>
					{{$t("policy_rules.protocol")}}
				</span>
			</div>
			<div class="cell_mobile">
				<span v-if="protocol!=''">{{protocol}}</span>
				<span v-else class="pi pi-minus" />
			</div>
		</div>
		<div class="vertical_container">
			<div class="cell_mobile title_cell_mobile">
				<span>
					{{$t("policy_rules.source")}}
				</span>
			</div>
			<div class="cell_mobile">
				<span v-if="source!=''">{{source}}</span>
				<span v-else class="pi pi-minus" />
			</div>
		</div>
		<div class="vertical_container">
			<div class="cell_mobile title_cell_mobile">
				<span>
					{{$t("policy_rules.destination")}}
				</span>
			</div>
			<div class="cell_mobile">
				<span v-if="destination!=''">{{destination}}</span>
				<span v-else class="pi pi-minus" />
			</div>
		</div>
		<div class="vertical_container">
			<div class="cell_mobile title_cell_mobile">
				<span>
					{{$t("policy_rules.comment")}}
				</span>
			</div>
			<div class="cell_mobile">
				<span v-if="comment!=''">{{comment}}</span>
				<span v-else class="pi pi-minus" />
			</div>
		</div>
	</div>

	<!--modal windows-->
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		v-model:visible="showCopyRuleConfirmWindow">
		<div>
			<span>{{$t("policy_rules.number")}}:</span>
			<InputNumber v-model="newRuleNumber" showButtons :min="1"
				inputStyle="width: 4em" />
		</div>
		<template #footer>
			<Button :label="$t('common.cancel')" icon="pi pi-times" class="p-button-text"
				@click="showCopyRuleConfirmWindow=false" />
			<Button :label="$t('common.apply')" icon="pi pi-check"
				@click="moveCopyRule('copy')" />
		</template>
	</Dialog>
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		v-model:visible="showMoveRuleConfirmWindow">
		<div>
			<span>{{$t("policy_rules.number")}}:</span>
			<InputNumber v-model="newRuleNumber" showButtons :min="1"
				inputStyle="width: 4em" />
		</div>
		<template #footer>
			<Button :label="$t('common.cancel')" icon="pi pi-times" class="p-button-text"
				@click="showMoveRuleConfirmWindow=false" />
			<Button :label="$t('common.apply')" icon="pi pi-check"
				@click="moveCopyRule('move')" />
		</template>
	</Dialog>
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		v-model:visible="showDeleteRuleConfirmWindow">
		{{$t("policy_rules.delete_rule")}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showDeleteRuleConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus
				@click="deleteRule" />
		</template>
	</Dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import TieredMenu from "primevue/tieredmenu"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-general-rule",
	props: {
		is_table_header: {
			type: Boolean,
			default: false
		},
		active: String,
		number: Number,
		action: String,
		is_log_packet: String,
		is_log_session: String,
		is_alarm: String,
		protocol: String,
		source: String,
		destination: String,
		comment: String
	},
	components: {
		TieredMenu
	},
	data() {
		return {
			activeToggle: this.active == "enable",
			newRuleNumber: 0,
			showCopyRuleConfirmWindow: false,
			showMoveRuleConfirmWindow: false,
			showDeleteRuleConfirmWindow: false,
			
			contextMenuItems: [
				{
					icon: "pi pi-pencil",
					label: t("common.edit"),
					command: () => {
						this.toggleContextMenu()
						
					}
				},
				{
					visible: () => !this.isGlobalRule,
					icon: "pi pi-copy",
					label: t("common.copy"),
					command: () => {
						this.toggleContextMenu()
						this.newRuleNumber = this.number
						this.showCopyRuleConfirmWindow = true
					}
				},
				{
					visible: () => !this.isGlobalRule,
					icon: "pi pi-arrows-v",
					label: t("common.move"),
					command: () => {
						this.toggleContextMenu()
						this.newRuleNumber = this.number
						this.showMoveRuleConfirmWindow = true
					}
				},
				{
					visible: () => !this.isGlobalRule,
					separator: true
				},
				{
					visible: () => !this.isGlobalRule,
					icon: "pi pi-trash",
					label: t("common.delete"),
					style: "color: red",
					command: () => {
						this.toggleContextMenu()
						this.showDeleteRuleConfirmWindow = true
					}
				}
			]
		}
	},
	methods: {
		toggleContextMenu(event) {
			this.$refs.menu.toggle(event)
		},
		setRuleActive() {
			let formData = new FormData()
			formData.set("cmd", "rule edit rule:" + this.number + " active=" +
				(this.activeToggle ? "disable" : "enable"))
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(response => {
					if (response.status == 200) {
						this.$emit("updateData")
					}
				})
		},
		moveCopyRule(action) {
			if (this.number != this.newRuleNumber) {
				let formData = new FormData()
				formData.set("cmd", "rule " + action + " type=rule srcnum="
					+ this.number + " dstnum=" + this.newRuleNumber)
				this.postRequest({"url": "/php/cli.php", "body": formData})
					.then(response => {
						if (response.status == 200) {
							this.$emit("updateData")
						}
					})
			}
			this.showCopyRuleConfirmWindow = false
			this.showMoveRuleConfirmWindow = false
		},
		deleteRule() {
			let formData = new FormData()
			formData.set("cmd", "rule delete rule:" + this.number)
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(response => {
					if (response.status == 200) {
						this.$emit("updateData")
					}
				})
			this.showDeleteRuleConfirmWindow = false
		},
		...mapActions([
			"postRequest"
		])
	},
	computed: {
		isGlobalRule() {
			return this.number == 0
		},
		...mapGetters([
			"showDesktopVersion",
			"showMobileVersion",
			"isReadPrivilege"
		])
	}
}
</script>

<style scoped>
#general_rule_header {
	display: flex;
	background-color: var(--primary-light-color);
	border: 1px solid black;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px;
}

.header_cell {
	display: flex;
	align-items: center;
	justify-content: center;
	padding-top: 0.5em;
	padding-bottom: 0.5em;
}

.cell_separator {
	border-right: 1px solid black;
}

#general_rule_row_desktop {
	display: flex;
	border-left: 1px solid black;
	border-right: 1px solid black;
	border-bottom: 1px solid black;
	white-space: pre-line;
	background-color: white;
}

.row_cell {
	display: flex;
	align-items: center;
	justify-content: center;
	padding-top: 0.3em;
	padding-bottom: 0.3em;
}

.context_menu_item {
	display: flex;
	padding: 0.4em;
	align-items: center;
}

.context_menu_item:hover {
	background-color: var(--primary-light-light-color);
}

#general_rule_row_mobile {
	display: flex;
	flex-flow: column;
	border-left: 1px solid black;
	border-top: 1px solid black;
	margin-bottom: 1em;
	background-color: white;
}

.vertical_container {
	display: flex;
	flex-flow: column;
	flex: 1;
	border-right: 1px solid black;
	border-bottom: 1px solid black;
}

.horizontal_container {
	display: flex;
	flex: 1;
}

.cell_mobile {
	display: flex;
	align-items: center;
	justify-content: center;
	min-height: 1.5em;
}

.title_cell_mobile {
	background-color: var(--third-color);
}

.red {
	color: red;
}

.yellow {
	color: var(--yellow);
}

.green {
	color: green;
}
</style>
