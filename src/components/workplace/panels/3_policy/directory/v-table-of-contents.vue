<template>
	<div id="table_of_contents">
		<div id="table_of_contents_header">
			<div id="search_field" @keydown.enter="findDirectoryItem">
				<span class="p-input-icon-left" style="width: 100%;">
					<i class="pi pi-search" />
					<InputText type="text" v-model="search" style="width: 100%;"
						:placeholder="$t('syslog.search')" />
				</span>
			</div>
			<div id="btn_find">
				<Button icon="pi pi-search" @click="findDirectoryItem()" />
			</div>
		</div>
		<div id="table_of_contents_main">
			<PanelMenu :model="menuItems" v-model:expandedKeys="expandedKeys" />
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import PanelMenu from "primevue/panelmenu"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-table-of-contents",
	components: {
		PanelMenu
	},
	props: {
		directory: Object
	},
	data() {
		return {
			menuItems: [],
			expandedKeys: {},
			search: ""
		}
	},
	methods: {
		makeMenu() {
			this.menuItems = []
			for (let i = 0; i < this.directory.length; i++) {
				let subItems = []
				for (let j = 0; j < this.directory[i].items.length; j++) {
					subItems.push({
						"type": this.directory[i].label,
						"label": this.directory[i].items[j].label,
						"command": () => {
							this.$emit("showItem", {
								"type": this.directory[i].label,
								"name": this.directory[i].items[j].label
							})
						}
					})
				}
				subItems.unshift({
					visible: this.showDesktopVersion && !this.isReadPrivilege,
					icon: "pi pi-plus",
					label: t("policy_directory.new"),
					command: () => {
						this.$emit("newItem", this.directory[i].label)
					}
				})
				this.menuItems.push({
					"key": i,
					"label": t("policy_directory." + this.directory[i].label),
					"items": subItems
				})
			}
		},
		findDirectoryItem() {
			for (let i = 0; i < this.menuItems.length; i++) {
				for (let j = 1; j < this.menuItems[i].items.length; j++) {
					if (this.menuItems[i].items[j].label == this.search) {
						this.expandedKeys = {}
						this.expandedKeys[this.menuItems[i].key] = true
						this.$emit("showItem", {
							"type": this.menuItems[i].items[j].type,
							"name": this.menuItems[i].items[j].label
						})
						return
					}
				}
			}
			// no matches
			this.$toast.add({severity: "info",
				summary: t("syslog.no_matches"), life: 3000})
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
	},
	watch: {
		directory: function() {
			this.makeMenu()
		}
	}
}
</script>

<style scoped>
#table_of_contents {
	display: flex;
	flex-flow: column;
	height: 100%;
	overflow: hidden;
}

#table_of_contents_header {
	margin: 0.5em;
	display: flex;
}

#search_field {
	flex: 1;
}

#btn_find {
	display: flex;
	align-items: center;
	margin-left: 0.5em;
}

#table_of_contents_main {
	overflow: auto;
}
</style>
