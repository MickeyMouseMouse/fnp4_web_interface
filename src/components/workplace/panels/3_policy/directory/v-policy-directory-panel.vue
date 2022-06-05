<template>
	<div v-show="!isLoading" id="policy_directory_panel">
		<div v-if="showDesktopVersion" id="panel_wrapper" class="panel_wrapper_desktop">
			<div id="table_of_contents_desktop">
				<TableOfContents :directory="directory"
					@newItem="newItem" @showItem="showItem" />
			</div>
			<div id="content_desktop">
				<router-view :directory="directory" :policies="policies"
					:itemName="itemName" @deleteItem="deleteItem" />
			</div>
		</div>
		<div v-else id="panel_wrapper" class="panel_wrapper_mobile">
			<div v-if="showMobileTableOfContents" id="container_mobile">
				<TableOfContents :directory="directory"
					@showItem="showItem" />
			</div>
			<div v-else id="container_mobile">
				<div>
					<Button :label="$t('policy_directory.table_of_contents')"
						class="p-button-text pi pi-angle-left"
						style="padding-left: 0em"
						@click="showMobileTableOfContents=true" />
				</div>
				<div id="content_mobile">
					<router-view :itemName="itemName" />
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import TableOfContents from "./v-table-of-contents.vue"
export default {
	name: "v-policy-directory-panel",
	components: {
		TableOfContents
	},
	created() {
		this.setLoading(true)
		this.fetchData()
	},
	data() {
		return {
			directory: [],
			policies: [],
			showMobileTableOfContents: true,
			itemName: ""
		}
	},
	methods: {
		fetchData() {
			this.getRequest("/php/3_policy/directory/directory.php")
				.then(async response => {
					if (response.status == 200) {
						let data = await response.json()
						this.directory = data["directory"]
						
						this.policies = data["policies"]
						this.policies.unshift("все политики")
						this.policies.unshift("текущая")
						
						this.itemName = ""
						this.setLoading(false)
					}
				})
		},
		newItem(type) {
			this.$router.push({path: "/dashboard/policy_directory/new_" + type})
		},
		showItem(item) {
			this.itemName = item.name
			this.showMobileTableOfContents = false
			this.$router.push({path: "/dashboard/policy_directory/" + item.type})
		},
		deleteItem(name) {
			let formData = new FormData()
			formData.set("cmd", "directory delete name=" + name)
			this.postRequest({"url": "/php/cli.php", body: formData})
				.then(async response => {
					if (response.status == 200) {
						let r = await response.json()
						if (r["type"] == "Error")
							this.$toast.add({severity: "error", detail: r["result"], life: 5000})
						this.fetchData()
					}
				})
		},
		...mapActions([
			"setLoading",
			"getRequest",
			"postRequest"
		])
	},
	computed: {
		...mapGetters([
			"isLoading",
			"showDesktopVersion"
		])
	}
}
</script>

<style scoped>
#policy_directory_panel {
	display: flex;
	flex-flow: column;
	flex: 1;
	align-items: center;
	height: 100%;
}

#panel_wrapper {
	display: flex;
	flex: 1;
	justify-content: center;
}

.panel_wrapper_desktop {
	width: 95%;
	overflow: hidden;
}

.panel_wrapper_mobile {
	width: 100%;
}

#table_of_contents_desktop {
	width: 15em;
	overflow: auto;
	border-left: 1px solid black;
	border-top: 1px solid black;
	border-bottom: 1px solid black;
	border-top-left-radius: 10px;
	border-bottom-left-radius: 10px;
	box-shadow: 4px 2px 6px grey;
}

#content_desktop {
	display: flex;
	justify-content: center;
	width: 26em;
	padding: 1em 1em;
	border-right: 1px solid black;
	border-top: 1px solid black;
	border-bottom: 1px solid black;
	border-top-right-radius: 10px;
	border-bottom-right-radius: 10px;
}

#container_mobile {
	display: flex;
	flex-flow: column;
	flex: 1;
}

#content_mobile {
	display: flex;
	flex: 1;
	justify-content: center;
	margin-top: 0.5em;
}

#no_item {
	display: flex;
	flex: 1;
	justify-content: center;
	align-items: center;
}

</style>
