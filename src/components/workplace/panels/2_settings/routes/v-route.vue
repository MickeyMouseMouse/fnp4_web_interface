<template>
	<div v-if="is_table_header" id="route_header">
		<div v-show="!isReadPrivilege" class="header_cell" style="width:25px">
		</div>
		<div class="header_cell cell_separator" style="width:30px"
			:title="$t('settings_routes.number')" >
			№
		</div>
		<div class="header_cell cell_separator" style="flex:1">
			{{$t("settings_routes.dest_ip")}}
		</div>
		<div class="header_cell cell_separator" style="flex:1">
			{{$t("settings_routes.gateway_ip")}}
		</div>
		<div class="header_cell" style="flex:1">
			{{$t("settings_routes.interface_name")}}
		</div>
	</div>
	<div v-else-if="showDesktopVersion" id="route_row_desktop">
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
		<div :class="['row_cell', 'cell_separator']"
			style="width:30px" :title="$t('settings_routes.number')">
			{{number}}
		</div>
		<div :class="['row_cell', 'cell_separator']" style="flex:1">
			{{dest_ip}}
		</div>
		<div :class="['row_cell', 'cell_separator']" style="flex:1">
			{{gateway_ip}}
		</div>
		<div class="row_cell" style="flex:1">
			{{interface_name}}
		</div>
	</div>
	<div v-else id="route_row_mobile">
		<div class="container_mobile">
			<div class="container_title">
				{{$t("settings_routes.number")}}
			</div>
			<div class="container_value">
				{{number}}
			</div>
		</div>
		<div class="container_mobile">
			<div class="container_title">
				<span>
					{{$t("settings_routes.dest_ip")}}
				</span>
			</div>
			<div class="container_value">
				{{dest_ip}}
			</div>
		</div>
		<div class="container_mobile">
			<div class="container_title">
				{{$t("settings_routes.gateway_ip")}}
			</div>
			<div class="container_value">
				{{gateway_ip}}
			</div>
		</div>
		<div class="container_mobile">
			<div class="container_title">
				{{$t("settings_routes.interface_name")}}
			</div>
			<div class="container_value">
				{{interface_name}}
			</div>
		</div>
	</div>

	<!--modal windows-->
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		v-model:visible="showDeleteRouteConfirmWindow">
		<div style="display:flex; justify-content:center">
			{{$t("settings_routes.delete_route")}}
		</div>
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showDeleteRouteConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus
				@click="deleteRoute" />
		</template>
	</Dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import TieredMenu from "primevue/tieredmenu"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-route",
	props: {
		is_table_header: {
			type: Boolean,
			default: false
		},
		number: Number,
		dest_ip: String,
		gateway_ip: String,
		interface_name: String
	},
	components: {
		TieredMenu
	},
	data() {
		return {
			showDeleteRouteConfirmWindow: false,
			
			contextMenuItems: [
				{
					icon: "pi pi-pencil",
					label: t("common.edit"),
					command: () => {
						this.toggleContextMenu()
						
					}
				},
				{
					separator: true
				},
				{
					icon: "pi pi-trash",
					label: t("common.delete"),
					style: "color: red",
					command: () => {
						this.toggleContextMenu()
						this.showDeleteRouteConfirmWindow = true
					}
				}
			]
		}
	},
	methods: {
		toggleContextMenu(event) {
			this.$refs.menu.toggle(event)
		},
		deleteRoute() {
			/*
			let formData = new FormData()
			formData.set("cmd", "system route delete number=" + this.number)
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(response => {
					if (response.status == 200) {
						this.$emit("updateData")
					}
				})
			*/
			this.showDeleteRouteConfirmWindow = false
		},
		...mapActions([
			"postRequest"
		])
	},
	computed: {
		...mapGetters([
			"showDesktopVersion",
			"showMobileVersion",
			"isReadPrivilege"
		])
	}
}
</script>

<style scoped>
#route_header {
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

#route_row_desktop {
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

#route_row_mobile {
	display: flex;
	flex-flow: column;
	border-bottom: 1px solid black;
	margin-bottom: 1em;
	background-color: white;
}

.container_mobile {
	display: flex;
	border-left: 1px solid black;
	border-top: 1px solid black;
	border-right: 1px solid black;
	padding: 0.5em;
}

.container_title {
	display: flex;
}

.container_value {
	display: flex;
	flex: 1;
	justify-content: right;
	text-align: right;
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
