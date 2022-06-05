<template>
	<div v-if="is_table_header" id="stats_header">
		<div class="header_cell cell_separator" style="width:100px">
			{{$t("policy_stats.rule")}}
		</div>
		<div class="header_cell cell_separator" style="width:200px">
			{{$t("policy_stats.last_activity")}}
		</div>
		<div class="header_cell cell_separator" style="width:100px">
			{{$t("policy_stats.packets")}}
		</div>
		<div class="header_cell cell_separator" style="width:100px">
			{{$t("policy_stats.bytes")}}
		</div>
		<div class="header_cell" style="flex:1">
			{{$t("policy_rules.comment")}}
		</div>
	</div>
	<div v-else-if="showDesktopVersion" id="stats_row_desktop">
		<div :class="['row_cell', 'cell_separator', {'red': action=='drop',
			'yellow': action=='deny', 'green': action=='accept'}]" style="width:100px">
			{{name}}
		</div>
		<div :class="['row_cell', 'cell_separator', {'red': action=='drop',
			'yellow': action=='deny', 'green': action=='accept'}]" style="width:200px">
			{{time}}
		</div>
		<div :class="['row_cell', 'cell_separator', {'red': action=='drop',
			'yellow': action=='deny', 'green': action=='accept'}]" style="width:100px">
			{{packets}}
		</div>
		<div :class="['row_cell', 'cell_separator', {'red': action=='drop',
			'yellow': action=='deny', 'green': action=='accept'}]" style="width:100px">
			{{bytes}}
		</div>
		<div class="row_cell" style="flex:1">
			{{comment}}
		</div>
	</div>
	<div v-else id="stats_row_mobile">
		<div class="horizontal_container">
			<div class="vertical_container">
				<div class="cell_mobile title_cell_mobile">
					{{$t("policy_stats.rule")}}
				</div>
				<div :class="['cell_mobile', {'red': action=='drop',
					'yellow': action=='deny', 'green': action=='accept'}]">
					{{name}}
				</div>
			</div>
			<div class="vertical_container">
				<div class="cell_mobile title_cell_mobile">
					{{$t("policy_stats.last_activity")}}
				</div>
				<div :class="['cell_mobile', {'red': action=='drop',
					'yellow': action=='deny', 'green': action=='accept'}]">
					{{time}}
				</div>
			</div>
		</div>
		<div class="horizontal_container">
			<div class="vertical_container">
				<div class="cell_mobile title_cell_mobile">
					{{$t("policy_stats.packets")}}
				</div>
				<div :class="['cell_mobile', {'red': action=='drop',
					'yellow': action=='deny', 'green': action=='accept'}]">
					{{packets}}
				</div>
			</div>
			<div class="vertical_container">
				<div class="cell_mobile title_cell_mobile">
					{{$t("policy_stats.bytes")}}
				</div>
				<div :class="['cell_mobile', {'red': action=='drop',
					'yellow': action=='deny', 'green': action=='accept'}]">
					{{bytes}}
				</div>
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
</template>

<script>
import { mapGetters } from "vuex"
export default {
	name: "v-stat-item",
	props: {
		is_table_header: {
			type: Boolean,
			default: false
		},
		name: String,
		action: String,
		time: String,
		packets: String,
		bytes: String,
		comment: String
	},
	computed: {
		...mapGetters([
			"showDesktopVersion"
		])
	}
}
</script>

<style scoped>
#stats_header {
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

#stats_row_desktop {
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

#stats_row_mobile {
	display: flex;
	flex-flow: column;
	border-left: 1px solid black;
	border-top: 1px solid black;
	margin-bottom: 1em;
	background-color: white;
}

.horizontal_container {
	display: flex;
	flex: 1;
}

.vertical_container {
	display: flex;
	flex-flow: column;
	flex: 1;
	border-right: 1px solid black;
	border-bottom: 1px solid black;
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
