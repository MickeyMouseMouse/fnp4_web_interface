<template>
	<div id="menu">
		<div v-for="(el, i) in getMenu" :key="el.id" v-show="isItemVisible(el)"
			class="top_level_section">
			<div :class="{'top_level_wrapper': showDesktopVersion}">
				<div v-if="isFolder(el)" class="menu_object" @click="selectFolder(i)">
					<div :class="{'folder_name_mobile': showMobileVersion}">
						{{$t('sidebar.' + el.name)}}
					</div>
					<span :class="isFolderExpanded(i) ? 'pi pi-angle-up': 'pi pi-angle-down'" />
				</div>
				<div v-else :class="['menu_object', 'menu_item', 
					{'menu_item_selected': isItemSelected(i, null)}]"
					@click="selectItem(el)">
					{{$t('sidebar.' + el.name)}}
				</div>
			</div>
			<div v-if="isFolder(el)"
				:class="[isFolderExpanded(i) ? 'sublevel_section' : 'hidden_sublevel_section']">
				<div v-for="(sub_el, j) in el.submenu" :key="sub_el.id"
					v-show="isItemVisible(sub_el)"
					:class="{'sublevel_wrapper': showDesktopVersion}">
					<div :class="['menu_object', 'menu_item', 
						{'menu_item_selected': isItemSelected(i, j)}]"
						@click="selectItem(sub_el)">
						{{$t("sidebar." + sub_el.name)}}
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
export default {
	name: "v-menu",
	created() {
		this.top_level_index = this.getTopLevelIndex
	},
	data() {
		return {
			top_level_index: null
		}
	},
	methods: {
		isFolder(el) {
			return el.submenu != null
		},
		isFolderExpanded(i) {
			return this.top_level_index == i
		},
		selectFolder(i) {
			if (this.isFolderExpanded(i))
				this.top_level_index = null
			else
				this.top_level_index = i
		},
		isItemSelected(i, j) {
			if (this.getTopLevelIndex == i)
				return this.getSublevelIndex == j
			return false
		},
		selectItem(el) {
			this.setMobileMenuOpened(false)
			this.$router.push({path: "/dashboard/" + el.route})
		},
		isItemVisible(el) {
			if (this.showDesktopVersion)
				return el.visible != false
			else
				return el.mobile == true && el.visible != false
		},
		...mapActions([
			"setMobileMenuOpened"
		])
	},
	computed: {
		...mapGetters([
			"getMenu",
			"showDesktopVersion",
			"showMobileVersion",
			"getTopLevelIndex",
			"getSublevelIndex"
		])
	}
}
</script>

<style scoped>
#menu {
	display: flex;
	flex-flow: column;
	flex: 1;
	font-size: large;
	width: 100%;
	margin: auto;
	overflow: auto;
}

.top_level_wrapper:hover {
	background-color: var(--primary-color);
	color: white;
	transition-duration: 0.3s;
}

.sublevel_wrapper:hover {
	background-color: var(--primary-color);
	color: white;
}

.menu_object {
	display: flex;
	align-items: center;
	padding: 0.4em 0.5em 0.4em 0.6em;
}

.folder_name_mobile {
	display: flex;
	flex: 1;
}

.menu_item:hover {
	padding-left: 1em;
	transition-duration: 0.3s;
}

.menu_item_selected {
	font-weight: bold;
}

.top_level_section {
	display: flex;
	flex-flow: column;
	margin-top: 0.5em;
	cursor: pointer;
}

.hidden_sublevel_section {
	display: none;
}

.sublevel_section {
	display: flex;
	flex-flow: column;
	background-color: var(--primary-light-light-color);
	box-shadow: inset 0px 0px 4px 1px var(--primary-light-color);
}
</style>
