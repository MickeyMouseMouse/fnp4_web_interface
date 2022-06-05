<template>
	<div id="new_host_item">
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
					IPv4-адрес
				</div>
				<div class="value">
					<InputText v-model="mdays" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					IPv6-адрес
				</div>
				<div class="value">
					<InputText v-model="mdays" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					MAC-адрес
				</div>
				<div class="value">
					<InputText v-model="time" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					Использование VLAN
				</div>
				<div class="value">
					<InputText v-model="time" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					Интерфейс
				</div>
				<div class="value" style="justify-content:center;">
					<div>
						<div class="checkbox_container">
							<Checkbox v-model="eth0" :binary="true" />
							<div style="margin-left:0.3em">
								eth0
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="eth1" :binary="true" />
							<div style="margin-left:0.3em">
								eth1
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="eth2" :binary="true" />
							<div style="margin-left:0.3em">
								eth2
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="eth3" :binary="true" />
							<div style="margin-left:0.3em">
								eth3
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="eth4" :binary="true" />
							<div style="margin-left:0.3em">
								eth4
							</div>
						</div>
					</div>
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
	name: "v-new-host-item",
	props: {
		directory: Array,
		policies: Array
	},
	data() {
		return {
			policy: this.policies[0],
			
			name: "",
			ip4: "",
			ip6: "",
			mac: "",
			vlan: "",
			
			eth0: false, eth1: false, eth2: false, eth3: false, eth4: false,
			
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
#new_host_item {
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

.checkbox_container {
	display: flex;
	margin-bottom: 0.3em;
}

#btn_add {
	display: flex;
	justify-content: center;
	margin-top: 1em;
}
</style>
