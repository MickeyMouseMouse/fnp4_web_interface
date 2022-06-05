<template>
	<div v-show="!isLoading" id="policy_stats_panel">
		<div id="stats_panel_header">
			<div id="autoupdate">
				<span>{{$t("policy_stats.update")}}:</span>
				<Dropdown v-model="interval" :options="autoupdateIntervals" />
			</div>
			<Button icon="pi pi-chart-bar" style="margin-right:0.5em"
				@click="showChartWindow=true" />
			<Button icon="pi pi-trash" :label="$t('common.clear')"
				:disabled="isReadPrivilege" class="p-button-rounded p-button-danger"
				@click="showClearStatsConfirmWindow = true" />
		</div>
		<div id="table">
			<StatItem v-if="showDesktopVersion" :is_table_header="true" />
			<div id="table_content">
				<StatItem v-for="item in data.stats" :key="item.id"
					:name="item.name"
					:action="item.action"
					:time="item.time"
					:packets="item.packets"
					:bytes="item.bytes"
					:comment="item.comment" />
			</div>
		</div>
	</div>
	
	<Dialog header=" " :modal="true" :draggable="false" style="width: 21em"
		:position="showDesktopVersion ? 'center' : 'top'"
		v-model:visible="showClearStatsConfirmWindow">
		{{$t("policy_stats.clear_stats")}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="showClearStatsConfirmWindow=false" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="clear()" />
		</template>
	</Dialog>

	<Dialog v-model:visible="showChartWindow" :header="$t('state_filtering.statistics')"
		style="width:90vw" :modal="true" :draggable="false">
		<Chart type="bar" :data="getChartData(10)" width="4" height="1" />
	</Dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import Chart from "primevue/chart"
import StatItem from "./v-stat-item.vue"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-policy-stats-panel",
	components: {
		StatItem,
		Chart
	},
	created() {
		this.setLoading(true)
		this.fetchData()
	},
	data() {
		return {
			autoupdateIntervals: [t("policy_stats.off"), "5", "10", "15", "30", "60"],
			interval: t("policy_stats.off"),
			timer: null,
			showClearStatsConfirmWindow: false,
			showChartWindow: false,
			data: {}
		}
	},
	methods: {
		fetchData() {
			this.getRequest("/php/3_policy/stats.php")
				.then(async response => {
					if (response.status == 200) {
						this.data = await response.json()
						this.setLoading(false)
					}
				})
		},
		getChartData(numberOfRules) {
			let rules = {}
			for (let i = 0; i < this.data["stats"].length; i++)
				rules[this.data["stats"][i].name] = this.data["stats"][i].packets
			let keySorted = Object.keys(rules)
				.sort(function(a,b) {return rules[b]-rules[a]})
				.slice(0, numberOfRules)
			let values = []
			for (let i = 0; i < numberOfRules; i++)
				values.push(rules[keySorted[i]])
			return {
				labels: keySorted,
				datasets: [
					{
						label: "packets",
						backgroundColor: "#42A5F5",
						data: values
					}
				]
			}
		},
		clear() {
			let formData = new FormData()
			formData.set("cmd", "rule stats clear")
			this.postRequest({"url": "/php/cli.php", "body": formData})
				.then(async response => {
					if (response.status == 200) {
						this.fetchData()
					}
				})
			this.showClearStatsConfirmWindow = false
		},
		...mapActions([
			"getRequest",
			"postRequest",
			"setLoading"
		])
	},
	computed: {
		...mapGetters([
			"showDesktopVersion",
			"isReadPrivilege"
		])
	},
	watch: {
		interval: function() {
			clearInterval(this.timer)
			if (this.interval == "5")
				this.timer = setInterval(this.fetchData, 5000)
			else if (this.interval == "10")
				this.timer = setInterval(this.fetchData, 10000)
			else if (this.interval == "15")
				this.timer = setInterval(this.fetchData, 15000)
			else if (this.interval == "30")
				this.timer = setInterval(this.fetchData, 30000)
			else if (this.interval == "60")
				this.timer = setInterval(this.fetchData, 60000)
		}
	},
	beforeUnmount() {
		clearInterval(this.timer)
	}
}
</script>

<style scoped>
#policy_stats_panel {
	display: flex;
	flex-flow: column;
	height: 100%;
	overflow: hidden;
}

#stats_panel_header {
	display: flex;
	margin-bottom: 1em;
}

#autoupdate {
	flex: 1;
}

#table {
	display: flex;
	flex-flow: column;
	height: 100%;
	overflow: hidden;
}

#table_content {
	overflow: auto;
}
</style>
