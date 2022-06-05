<template>
	<div v-show="!isLoading" id="state_filtering_panel">
		<div id="panel_wrapper" :class="showDesktopVersion ? 'panel_wrapper_desktop' : 'panel_wrapper_mobile'">
			<div v-if="!packetFiltering">
				<div class="table">
					<div :class="['table_header', {'centered': showMobileVersion}]">
						{{$t("state_filtering.filtering_status")}}
					</div>
					<div class="row">
						<div :class="['title_cell', {'title_cell_mobile': showMobileVersion}]">
							{{$t("state_filtering.filtering")}}
						</div>
						<div :class="['cell', showDesktopVersion ? 'cell_desktop' : 'cell_mobile']">
							<InputSwitch v-model="packetFiltering" :disabled="isReadPrivilege"
								@click="packetFilteringToggle" />
						</div>
					</div>
				</div>
			</div>
			<div v-else>
				<div class="table">
					<div :class="['table_header', {'centered': showMobileVersion}]">
						{{$t("state_filtering.filtering_status")}}
					</div>
					<div class="row">
						<div :class="['title_cell', {'title_cell_mobile': showMobileVersion}]">
							{{$t("state_filtering.filtering")}}
						</div>
						<div :class="['cell', showDesktopVersion ? 'cell_desktop' : 'cell_mobile']">
							<InputSwitch v-model="packetFiltering" :disabled="isReadPrivilege"
								@click="packetFilteringToggle" />
						</div>
					</div>
					<div class="row">
						<div :class="['title_cell', {'title_cell_mobile': showMobileVersion}]">
							{{$t("state_filtering.start_time")}}
						</div>
						<div :class="['cell', showDesktopVersion ? 'cell_desktop' : 'cell_mobile']">
							{{data["start_time"]}}
						</div>
					</div>
					<div class="row">
						<div :class="['title_cell', {'title_cell_mobile': showMobileVersion}]">
							{{$t("state_filtering.uptime")}}
						</div>
						<div :class="['cell', showDesktopVersion ? 'cell_desktop' : 'cell_mobile']">
							{{getUptime}}
						</div>
					</div>
				</div>
				<div id="btn_chart">
					<Button icon="pi pi-chart-bar" @click="showChartWindow=true" />
				</div>
				<div class="table">
					<div :class="['table_header', {'centered': showMobileVersion}]">
						{{$t("state_filtering.traffic_info")}}
					</div>
					<div class="row">
						<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
							{{$t("state_filtering.filtering_interfaces")}}
						</div>
						<div v-if="showDesktopVersion" class="cell_container">
							<div v-for="name in data['ethf_names']" :key="name.id"
								class="inner_cell">
								{{name}}
							</div>
						</div>
						<div v-else class="cell">
							<Dropdown v-model="eth_name" :options="data['ethf_names']" />
						</div>
					</div>
					<div class="row">
						<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
							{{$t("state_filtering.status")}}
						</div>
						<div v-if="showDesktopVersion" class="cell_container">
							<div v-for="name in data['ethf_names']" :key="name.id"
								class="inner_cell">
								<span v-if="data[name]['status']=='on'"
									class="on pi pi-check-circle" />
								<span v-else-if="data[name]['status']=='off'"
									class="off pi pi-times-circle" />
								<span v-else>
									{{data[name]["status"]}}
								</span>
							</div>
						</div>
						<div v-else class="cell">
							<span v-if="data[eth_name]['status']=='on'"
								class="on pi pi-check-circle" />
							<span v-else-if="data[eth_name]['status']=='off'"
								class="off pi pi-times-circle" />
							<span v-else>
								{{data[eth_name]["status"]}}
							</span>
						</div>
					</div>
				</div>
				<div class="table">
					<div class="row" style="border-top: 1px solid black"
						@click="showNestedTable = !showNestedTable" >
						<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
							{{$t("state_filtering.frames_bytes")}} {{$t("state_filtering.received")}}
							<span :class="showNestedTable ? 'pi pi-angle-up': 'pi pi-angle-down'" />
						</div>
						<div v-if="showDesktopVersion" class="cell_container">
							<div v-for="name in data['ethf_names']" :key="name.id"
								class="inner_cell" style="color: blue;">
								{{data[name]["total_received_packets"]}}/{{data[name]["total_received_bytes"]}}
							</div>
						</div>
						<div v-else class="cell" style="color: blue;">
							{{data[eth_name]["total_received_packets"]}}/{{data[eth_name]["total_received_bytes"]}}
						</div>
					</div>
					<div v-show="showNestedTable" id="nestedTable" >
						<div class="row">
							<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
								{{$t("state_filtering.frames_bytes")}} Ethernet II
							</div>
							<div v-if="showDesktopVersion" class="cell_container">
								<div v-for="name in data['ethf_names']" :key="name.id"
									class="inner_cell" style="color: blue;">
									<span v-if="data[name]['eth2_packets']==null">–</span>
									<span v-else>
										{{data[name]["eth2_packets"]}}/{{data[name]["eth2_bytes"]}}
									</span>
								</div>
							</div>
							<div v-else class="cell" style="color: blue;">
								<span v-if="data[eth_name]['eth2_packets']==null">–</span>
								<span v-else>
									{{data[eth_name]["eth2_packets"]}}/{{data[eth_name]["eth2_bytes"]}}
								</span>
							</div>
						</div>
						<div class="row">
							<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
								{{$t("state_filtering.frames_bytes")}} IEEE 802.3 LLC
							</div>
							<div v-if="showDesktopVersion" class="cell_container">
								<div v-for="name in data['ethf_names']" :key="name.id"
									class="inner_cell" style="color: blue;">
									<span v-if="data[name]['e802311c_packets']==null">–</span>
									<span v-else>
										{{data[name]["e802311c_packets"]}}/{{data[name]["e802311c_bytes"]}}
									</span>
								</div>
							</div>
							<div v-else class="cell" style="color: blue;">
								<span v-if="data[eth_name]['e802311c_packets']==null">–</span>
								<span v-else>
									{{data[eth_name]["e802311c_packets"]}}/{{data[eth_name]["e802311c_bytes"]}}
								</span>
							</div>
						</div>
						<div class="row">
							<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
								{{$t("state_filtering.frames_bytes")}} IEEE 802.3 RAW
							</div>
							<div v-if="showDesktopVersion" class="cell_container">
								<div v-for="name in data['ethf_names']" :key="name.id"
									class="inner_cell" style="color: blue;">
									<span v-if="data[name]['e8023raw_packets']==null">–</span>
									<span v-else>
										{{data[name]["e8023raw_packets"]}}/{{data[name]["e8023raw_bytes"]}}
									</span>
								</div>
							</div>
							<div v-else class="cell" style="color: blue;">
								<span v-if="data[eth_name]['e8023raw_packets']==null">–</span>
								<span v-else>
									{{data[eth_name]["e8023raw_packets"]}}/{{data[eth_name]["e8023raw_bytes"]}}
								</span>
							</div>
						</div>
						<div class="row">
							<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
								{{$t("state_filtering.frames_bytes")}} IEEE 802.3 SNAP
							</div>
							<div v-if="showDesktopVersion" class="cell_container">
								<div v-for="name in data['ethf_names']" :key="name.id"
									class="inner_cell" style="color: blue;">
									<span v-if="data[name]['e8023snap_packets']==null">–</span>
									<span v-else>
										{{data[name]["e8023snap_packets"]}}/{{data[name]["e8023snap_bytes"]}}
									</span>
								</div>
							</div>
							<div v-else class="cell" style="color: blue;">
								<span v-if="data[eth_name]['e8023snap_packets']==null">–</span>
								<span v-else>
									{{data[eth_name]["e8023snap_packets"]}}/{{data[eth_name]["e8023snap_bytes"]}}
								</span>
							</div>
						</div>
						<div class="row">
							<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
								{{$t("state_filtering.packets_bytes")}} ARP
							</div>
							<div v-if="showDesktopVersion" class="cell_container">
								<div v-for="name in data['ethf_names']" :key="name.id"
									class="inner_cell" style="color: blue;">
									<span v-if="data[name]['arp_packets']==null">–</span>
									<span v-else>
										{{data[name]["arp_packets"]}}/{{data[name]["arp_bytes"]}}
									</span>
								</div>
							</div>
							<div v-else class="cell" style="color: blue;">
								<span v-if="data[eth_name]['arp_packets']==null">–</span>
								<span v-else>
									{{data[eth_name]["arp_packets"]}}/{{data[eth_name]["arp_bytes"]}}
								</span>
							</div>
						</div>
						<div class="row">
							<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
								{{$t("state_filtering.packets_bytes")}} RARP
							</div>
							<div v-if="showDesktopVersion" class="cell_container">
								<div v-for="name in data['ethf_names']" :key="name.id"
									class="inner_cell" style="color: blue;">
									<span v-if="data[name]['rarp_packets']==null">–</span>
									<span v-else>
										{{data[name]["rarp_packets"]}}/{{data[name]["rarp_bytes"]}}
									</span>
								</div>
							</div>
							<div v-else class="cell" style="color: blue;">
								<span v-if="data[eth_name]['rarp_packets']==null">–</span>
								<span v-else>
									{{data[eth_name]["rarp_packets"]}}/{{data[eth_name]["rarp_bytes"]}}
								</span>
							</div>
						</div>
						<div class="row">
							<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
								{{$t("state_filtering.datagrams_bytes")}} IPv4
							</div>
							<div v-if="showDesktopVersion" class="cell_container">
								<div v-for="name in data['ethf_names']" :key="name.id"
									class="inner_cell" style="color: blue;">
									<span v-if="data[name]['ip_packets']==null">–</span>
									<span v-else>
										{{data[name]["ip_packets"]}}/{{data[name]["ip_bytes"]}}
									</span>
								</div>
							</div>
							<div v-else class="cell" style="color: blue;">
								<span v-if="data[eth_name]['ip_packets']==null">–</span>
								<span v-else>
									{{data[eth_name]["ip_packets"]}}/{{data[eth_name]["ip_bytes"]}}
								</span>
							</div>
						</div>
						<div class="row">
							<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
								{{$t("state_filtering.datagrams_bytes")}} IPv6
							</div>
							<div v-if="showDesktopVersion" class="cell_container">
								<div v-for="name in data['ethf_names']" :key="name.id"
									class="inner_cell" style="color: blue;">
									<span v-if="data[name]['ipv6_packets']==null">–</span>
									<span v-else>
										{{data[name]["ipv6_packets"]}}/{{data[name]["ipv6_bytes"]}}
									</span>
								</div>
							</div>
							<div v-else class="cell" style="color: blue;">
								<span v-if="data[eth_name]['ipv6_packets']==null">–</span>
								<span v-else>
									{{data[eth_name]["ipv6_packets"]}}/{{data[eth_name]["ipv6_bytes"]}}
								</span>
							</div>
						</div>
						<div class="row">
							<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
								{{$t("state_filtering.segments_bytes")}} TCP
							</div>
							<div v-if="showDesktopVersion" class="cell_container">
								<div v-for="name in data['ethf_names']" :key="name.id"
									class="inner_cell" style="color: blue;">
									<span v-if="data[name]['tcp_packets']==null">–</span>
									<span v-else>
										{{data[name]["tcp_packets"]}}/{{data[name]["tcp_bytes"]}}
									</span>
								</div>
							</div>
							<div v-else class="cell" style="color: blue;">
								<span v-if="data[eth_name]['tcp_packets']==null">–</span>
								<span v-else>
									{{data[eth_name]["tcp_packets"]}}/{{data[eth_name]["tcp_bytes"]}}
								</span>
							</div>
						</div>
						<div class="row">
							<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
								{{$t("state_filtering.datagrams_bytes")}} UDP
							</div>
							<div v-if="showDesktopVersion" class="cell_container">
								<div v-for="name in data['ethf_names']" :key="name.id"
									class="inner_cell" style="color: blue;">
									<span v-if="data[name]['udp_packets']==null">–</span>
									<span v-else>
										{{data[name]["udp_packets"]}}/{{data[name]["udp_bytes"]}}
									</span>
								</div>
							</div>
							<div v-else class="cell" style="color: blue;">
								<span v-if="data[eth_name]['udp_packets']==null">–</span>
								<span v-else>
									{{data[eth_name]["udp_packets"]}}/{{data[eth_name]["udp_bytes"]}}
								</span>
							</div>
						</div>
						<div class="row">
							<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
								{{$t("state_filtering.messages_bytes")}} ICMPv4
							</div>
							<div v-if="showDesktopVersion" class="cell_container">
								<div v-for="name in data['ethf_names']" :key="name.id"
									class="inner_cell" style="color: blue;">
									<span v-if="data[name]['icmp_packets']==null">–</span>
									<span v-else>
										{{data[name]["icmp_packets"]}}/{{data[name]["icmp_bytes"]}}
									</span>
								</div>
							</div>
							<div v-else class="cell" style="color: blue;">
								<span v-if="data[eth_name]['icmp_packets']==null">–</span>
								<span v-else>
									{{data[eth_name]["icmp_packets"]}}/{{data[eth_name]["icmp_bytes"]}}
								</span>
							</div>
						</div>
						<div class="row">
							<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
								{{$t("state_filtering.messages_bytes")}} ICMPv6
							</div>
							<div v-if="showDesktopVersion" class="cell_container">
								<div v-for="name in data['ethf_names']" :key="name.id"
									class="inner_cell" style="color: blue;">
									<span v-if="data[name]['icmpv6_packets']==null">–</span>
									<span v-else>
										{{data[name]["icmpv6_packets"]}}/{{data[name]["icmpv6_bytes"]}}
									</span>
								</div>
							</div>
							<div v-else class="cell" style="color: blue;">
								<span v-if="data[eth_name]['icmpv6_packets']==null">–</span>
								<span v-else>
									{{data[eth_name]["icmpv6_packets"]}}/{{data[eth_name]["icmpv6_bytes"]}}
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="table">
					<div class="row" style="border-top: 1px solid black">
						<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
							{{$t("state_filtering.frames_bytes")}} {{$t("state_filtering.sent")}}
						</div>
						<div v-if="showDesktopVersion" class="cell_container">
							<div v-for="name in data['ethf_names']" :key="name.id"
								class="inner_cell" style="color: green;">
								{{data[name]["total_sent_packets"]}}/{{data[name]["total_sent_bytes"]}}
							</div>
						</div>
						<div v-else class="cell" style="color: green;">
							{{data[eth_name]["total_sent_packets"]}}/{{data[eth_name]["total_sent_bytes"]}}
						</div>
					</div>
					<div class="row">
						<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
							{{$t("state_filtering.frames_bytes")}} {{$t("state_filtering.dropped")}}
						</div>
						<div v-if="showDesktopVersion" class="cell_container">
							<div v-for="name in data['ethf_names']" :key="name.id"
								class="inner_cell" style="color: red;">
								{{data[name]["total_dropped_packets"]}}/{{data[name]["total_dropped_bytes"]}}
							</div>
						</div>
						<div v-else class="cell" style="color: red;">
							{{data[eth_name]["total_dropped_packets"]}}/{{data[eth_name]["total_dropped_bytes"]}}
						</div>
					</div>
					<div class="row">
						<div :class="['title_cell', {'title_cell_desktop': showDesktopVersion}]">
							{{$t("state_filtering.broken")}} {{$t("state_filtering.frames_bytes")}}
						</div>
						<div v-if="showDesktopVersion" class="cell_container">
							<div v-for="name in data['ethf_names']" :key="name.id"
								class="inner_cell">
								{{data[name]["total_broken_packets"]}}/{{data[name]["total_broken_bytes"]}}
							</div>
						</div>
						<div v-else class="cell">
							{{data[eth_name]["total_broken_packets"]}}/{{data[eth_name]["total_broken_bytes"]}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<Dialog v-model:visible="showChartWindow" :header="$t('state_filtering.statistics')"
		style="width:90vw" :modal="true" :draggable="false">
		<div v-for="name in data['ethf_names']" :key="name.id">
			<div class="inner_cell" style="font-weight: bold;">
				{{name}}
			</div>
			<Chart type="bar" :data="getChartData(name)" width="4" height="1" />
		</div>
	</Dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import Chart from "primevue/chart"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-state-filtering-panel",
	components: {
		Chart
	},
	data() {
		return {
			showPanel: false,
			data: {},
			packetFiltering: Boolean,
			eth_name: "", // certain eth for mobile version
			showChartWindow: false,
			showNestedTable: false,
		}
	},
	created() {
		this.setLoading(true)
		this.fetchData()
	},
	methods: {
		async fetchData() {
			this.getRequest("/php/1_status/state_filtering.php")
				.then(async response => {
					if (response.status == 200) {
						this.packetFiltering = true
						this.data = await response.json()
						if (this.showMobileVersion) {
							this.eth_name = this.data["ethf_names"][0]
						}
					} else {
						this.packetFiltering = false
					}
					this.setLoading(false)
				})
		},
		getChartData(ethName) {
			return {
				labels: [
					t("state_filtering.received"),
					"Ethernet II", "IEEE 802.3 LLC", "IEEE 802.3 RAW",
					"IEEE 802.3 SNAP", "ARP", "RARP", "IPv4", "IPv6",
					"TCP", "UDP", "ICMPv4", "ICMPv6",
					t("state_filtering.sent"), 
					t("state_filtering.dropped"),
					t("state_filtering.broken")
				],
				datasets: [
					{
						label: "packets",
						backgroundColor: "#42A5F5",
						data: [
							this.data[ethName]["total_received_packets"],
							this.data[ethName]["eth2_packets"],
							this.data[ethName]["e802311c_packets"],
							this.data[ethName]["e8023raw_packets"],
							this.data[ethName]["e8023snap_packets"],
							this.data[ethName]["arp_packets"],
							this.data[ethName]["rarp_packets"],
							this.data[ethName]["ip_packets"],
							this.data[ethName]["ipv6_packets"],
							this.data[ethName]["tcp_packets"],
							this.data[ethName]["udp_packets"],
							this.data[ethName]["icmp_packets"],
							this.data[ethName]["icmpv6_packets"],
							this.data[ethName]["total_sent_packets"],
							this.data[ethName]["total_dropped_packets"],
							this.data[ethName]["total_broken_packets"]
						]
					},
					{
						label: "bytes",
						backgroundColor: "#FFA726",
						data: [
							this.data[ethName]["total_received_bytes"],
							this.data[ethName]["eth2_bytes"],
							this.data[ethName]["e802311c_bytes"],
							this.data[ethName]["e8023raw_bytes"],
							this.data[ethName]["e8023snap_bytes"],
							this.data[ethName]["arp_bytes"],
							this.data[ethName]["rarp_bytes"],
							this.data[ethName]["ip_bytes"],
							this.data[ethName]["ipv6_bytes"],
							this.data[ethName]["tcp_bytes"],
							this.data[ethName]["udp_bytes"],
							this.data[ethName]["icmp_bytes"],
							this.data[ethName]["icmpv6_bytes"],
							this.data[ethName]["total_sent_bytes"],
							this.data[ethName]["total_dropped_bytes"],
							this.data[ethName]["total_broken_bytes"]
						]
					}
				]
			}
		},
		packetFilteringToggle() {
			let cmd = (this.packetFiltering) ? "stop" : "start"
			this.getRequest("/php/1_status/fstart.php?cmd=" + cmd)
				.then(async response => {
					if (response.status == 200) {
						await new Promise(resolve => setTimeout(resolve, 500))
						this.fetchData()
					}
				})
		},
		...mapActions([
			"getRequest",
			"setLoading"
		])
	},
	computed: {
		getUptime() {
			let uptime = parseInt(this.data["uptime"], 10)
			let days = Math.floor(uptime / 86400)
			let hours = Math.floor((uptime - 86400 * days) / 3600)
			let minutes = Math.floor((uptime - 86400 * days - 3600 * hours) / 60)
			let seconds = uptime - 86400 * days - 3600 * hours - 60 * minutes
			let result = ""
			if (days != 0)
				result += days + t("state_filtering.day") + " "
			if (hours != 0)
				result += hours + t("state_filtering.hour") + " "
			if (minutes != 0)
				result += minutes + t("state_filtering.minute") + " "
			if (seconds != 0)
				result += seconds + t("state_filtering.second")
			return result
		},
		...mapGetters([
			"showDesktopVersion",
			"showMobileVersion",
			"isLoading"
		])
	}
}
</script>

<style scoped>
#state_filtering_panel {
	display: flex;
	justify-content: center;
}

#panel_wrapper {
	display: flex;
	flex-flow: column;
}

.panel_wrapper_desktop {
	width: 90%;
}

.panel_wrapper_mobile {
	flex: 1;
}

#btn_chart {
	display: flex;
	justify-content: right;
	margin-bottom: 0.4em;
}

.table {
	margin-bottom: 1.5em;
	box-shadow: 0px 10px 10px lightgrey;
}

.table_header {
	display: flex;
	background-color: var(--primary-light-color);
	padding-top: 0.5em;
	padding-bottom: 0.5em;
	padding-left: 0.4em;
	border: 1px solid black;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px;
}

.centered {
	justify-content: center;
}

.row {
	display: flex;
	padding: 0.5em;
	border-left: 1px solid black;
	border-right: 1px solid black;
	border-bottom: 1px solid black;
	background-color: white;
}

.title_cell {
	display: flex;
	align-items: center;
}

.title_cell_desktop {
	width: 15.5em;
}

.cell {
	display: flex;
	flex: 1;
	align-items: center;
	justify-content: right;
	text-align: right;
}

.cell_container {
	display: flex;
	flex: 1;
}

.inner_cell {
	display: flex;
	flex: 1;
	justify-content: center;
	align-items: center;
}

.on {
	color: green;
	font-weight: bold;
}

.off {
	color: red;
	font-weight: bold;
}
</style>
