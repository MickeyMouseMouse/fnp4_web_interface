<template>
	<div id="new_time_item">
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
					Месяцы
				</div>
				<div class="value">
					<div style="margin-right: 0.5em">
						<div class="checkbox_container">
							<Checkbox v-model="jan" :binary="true" />
							<div style="margin-left:0.3em">
								Январь
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="feb" :binary="true" />
							<div style="margin-left:0.3em">
								Февраль
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="mar" :binary="true" />
							<div style="margin-left:0.3em">
								Март
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="apr" :binary="true" />
							<div style="margin-left:0.3em">
								Апрель
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="may" :binary="true" />
							<div style="margin-left:0.3em">
								Май
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="jun" :binary="true" />
							<div style="margin-left:0.3em">
								Июнь
							</div>
						</div>
					</div>
					<div>
						<div class="checkbox_container">
							<Checkbox v-model="jul" :binary="true" />
							<div style="margin-left:0.3em">
								Июль
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="aug" :binary="true" />
							<div style="margin-left:0.3em">
								Август
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="sep" :binary="true" />
							<div style="margin-left:0.3em">
								Сентябрь
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="oct" :binary="true" />
							<div style="margin-left:0.3em">
								Октябрь
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="nov" :binary="true" />
							<div style="margin-left:0.3em">
								Ноябрь
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="dec" :binary="true" />
							<div style="margin-left:0.3em">
								Декабрь
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="title">
					Дни месяца
				</div>
				<div class="value">
					<InputText v-model="mdays" />
				</div>
			</div>
			<div class="row">
				<div class="title">
					Дни недели
				</div>
				<div class="value" style="justify-content:center;">
					<div>
						<div class="checkbox_container">
							<Checkbox v-model="mon" :binary="true" />
							<div style="margin-left:0.3em">
								Понедельник
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="tue" :binary="true" />
							<div style="margin-left:0.3em">
								Вторник
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="wed" :binary="true" />
							<div style="margin-left:0.3em">
								Среда
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="thu" :binary="true" />
							<div style="margin-left:0.3em">
								Четверг
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="fri" :binary="true" />
							<div style="margin-left:0.3em">
								Пятница
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="sat" :binary="true" />
							<div style="margin-left:0.3em">
								Суббота
							</div>
						</div>
						<div class="checkbox_container">
							<Checkbox v-model="sun" :binary="true" />
							<div style="margin-left:0.3em">
								Воскресенье
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="title">
					Время
				</div>
				<div class="value">
					<InputText v-model="time" />
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
	name: "v-new-time-item",
	props: {
		directory: Array,
		policies: Array
	},
	data() {
		return {
			policy: this.policies[0],
			
			name: "",
			
			jan: false, feb: false, mar: false, apr: false, may: false, jun: false,
			jul: false, aug: false, sep: false, oct: false, nov: false, dec: false,
			
			mdays: "",
			
			mon: false, tue: false, wed: false, thu: false, fri: false, sat: false, sun: false,
			
			time: "",
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
#new_time_item {
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
