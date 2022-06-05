<template>
	<div id="new_net_group_item">
		<div id="item_data">
			<div class="row">
				<div class="title">
					Политика
				</div>
				<div class="value">
					<Dropdown v-model="policy" :options="policies" style="width:12.4em" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					Имя
				</div>
				<div class="value">
					<InputText v-model="name" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					Хост
				</div>
				<div class="value">
					<Dropdown v-model="host" :options="hosts" style="width:12.4em" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					Сеть
				</div>
				<div class="value">
					<Dropdown v-model="net" :options="nets" style="width:12.4em" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					Комментарий
				</div>
				<div class="value">
					<InputText v-model="comment" />
				</div>
			</div>
		</div>
		<div id="btn_add">
			<Button :label="$t('policy_directory.new')" />
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
export default {
	name: "v-new-net-group-item",
	props: {
		directory: Array,
		policies: Array
	},
	data() {
		return {
			policy: this.policies[0],
			name: "",
			host: "",
			hosts: [],
			net: "",
			nets: [],
			comment: ""
		}
	},
	methods: {
		...mapActions([
			"getRequest"
		])
	},
	computed: {
		...mapGetters([
		])
	}
}
</script>

<style scoped>
#new_net_group_item {
	display: flex;
	flex-flow: column;
	width: 100%;
}

#item_data {
	border-top: 1px solid black;
	overflow: auto;
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
}

#btn_add {
	display: flex;
	justify-content: center;
	margin-top: 1em;
}
</style>
