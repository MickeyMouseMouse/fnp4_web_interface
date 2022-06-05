<template>
	<div id="service_item">
		<div v-if="showDesktopVersion" id="btn_panel">
			<Button class="p-button-rounded p-button-danger"
				icon="pi pi-trash"
				:disabled="isReadPrivilege"
				:label="$t('common.delete')"
				@click="showDeleteConfirmWindow=true" />
		</div>
		<div class="table_header">
			{{data["item_name"]}}
		</div>
		<div class="row">
			<div class="title">
				Протокол
			</div>
			<div class="value">
				{{data["protocol"]}}
			</div>
		</div>
		<div class="row">
			<div class="title">
				Порт
			</div>
			<div class="value">
				{{data["port"]}}
			</div>
		</div>
		<div class="row">
			<div class="title">
				Тип/код ICMP
			</div>
			<div class="value">
				<span v-if="data['icmp4']!=''" >
					{{data["icmp4"]}}
				</span>
				<span v-else >
					{{data["icmp6"]}}
				</span>
			</div>
		</div>
		<div class="row">
			<div class="title">
				Комментарий
			</div>
			<div class="value">
				{{data["comment"]}}
			</div>
		</div>
	</div>
	
	<Dialog header="Удалить" :modal="true" :draggable="false" style="width: 21em"
		v-model:visible="showDeleteConfirmWindow">
		Удалить элемент каталога?
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showDeleteConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="deleteItem" />
		</template>
	</Dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
export default {
	name: "v-service-item",
	props: {
		itemName: String
	},
	created() {
		this.fetchData()
	},
	data() {
		return {
			data: {},
			showDeleteConfirmWindow: false
		}
	},
	methods: {
		async fetchData() {
			if (this.itemName == "") {
				this.$router.push({path: "/dashboard/policy_directory"})
			} else {
				this.getRequest("/php/3_policy/directory/directory_service_item.php?name=" + this.itemName)
					.then(async response => {
						if (response.status == 200) {
							this.data = await response.json()
						}
					})
			}
		},
		deleteItem() {
			this.$emit("deleteItem", this.data["item_name"])
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
		itemName: function() {
			this.fetchData()
		}
	}
}
</script>

<style scoped>
#service_item {
	width: 80%;
}

#btn_panel {
	display: flex;
	justify-content: right;
	align-items: center;
	margin-bottom: 0.5em;
}

.table_header {
	display: flex;
	justify-content: center;
	background-color: var(--primary-light-color);
	padding-top: 0.5em;
	padding-bottom: 0.5em;
	padding-left: 0.4em;
	border: 1px solid black;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px;
}

.row {
	display: flex;
	padding: 0.5em;
	border-left: 1px solid black;
	border-right: 1px solid black;
	border-bottom: 1px solid black;
	background-color: white;
}

.title {
	display: flex;
	align-items: center;
}

.value {
	display: flex;
	flex: 1;
	align-items: center;
	justify-content: right;
	text-align: right;
}
</style>
