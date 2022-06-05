<template>
	<div v-show="!isLoading" id="state_checksum_panel">
		<div id="panel_wrapper" :class="showDesktopVersion ? 'panel_wrapper_desktop' : 'panel_wrapper_mobile'">
			<div v-if="invalid == null" id="summary" class="ok">
				<span>{{$t("state_checksum.ok")}}</span>
				<span class="sign pi pi-check-circle" />
			</div>
			<div v-else id="summary" class="error">
				<span>{{$t("state_checksum.error")}}</span>
				<span class="sign pi pi-times-circle" />
			</div>
			<div id="content_wrapper">
				<div v-if="invalid != null"
					:class="['checksums_wrapper', {'checksums_wrapper_expanded': invalidVisible}]">
					<div :class="['table_header', {'centered': showMobileVersion}]" @click="showInvalid">
						{{$t('state_checksum.invalid_checksums')}}
						<span :class="invalidVisible ? 'pi pi-angle-up': 'pi pi-angle-down'" />
					</div>
					<div :class="invalidVisible ? 'show_checksums' : 'hide_checksums'">
						<div v-for="el in invalid" :key="el.id" class="row">
							<div class="title_cell cell">
								{{$t("state_checksum.file")}} {{el.filename}}
							</div>
							<div class="cell small_text">
								{{el.checksum}}
							</div>
						</div>
					</div>
				</div>
				<div :class="['checksums_wrapper', {'checksums_wrapper_expanded': validVisible}]">
					<div :class="['table_header', {'centered': showMobileVersion}]" @click="showValid">
						{{$t('state_checksum.valid_checksums')}}
						<span :class="validVisible ? 'pi pi-angle-up': 'pi pi-angle-down'" />
					</div>
					<div :class="validVisible ? 'show_checksums' : 'hide_checksums'">
						<div v-for="el in valid" :key="el.id" class="row">
							<div class="title_cell cell">
								{{$t("state_checksum.file")}} {{el.filename}}
							</div>
							<div class="cell small_text">
								{{el.checksum}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
export default {
	name: "v-state-checksum-panel",
	data() {
		return {
			showPanel: false,
			invalid: {},
			invalidVisible: false,
			valid: {},
			validVisible: true
		}
	},
	created() {
		this.setLoading(true)
		this.fetchData()
	},
	methods: {
		async fetchData() {
			this.getRequest("/php/1_status/state_checksum.php")
				.then(async response => {
					if (response.status == 200) {
						let data = await response.json()
						this.invalid = data.invalid
						this.valid = data.valid
						if (this.invalid) this.showInvalid()
						this.setLoading(false)
					}
				})
		},
		showInvalid() {
			this.invalidVisible = !this.invalidVisible
			this.validVisible = false
		},
		showValid() {
			this.validVisible = !this.validVisible
			this.invalidVisible = false
		},
		...mapActions([
			"getRequest",
			"setLoading",
		])
	},
	computed: {
		...mapGetters([
			"isLoading",
			"showDesktopVersion",
			"showMobileVersion"
		])
	}
}
</script>

<style scoped>
#state_checksum_panel {
	display: flex;
	justify-content: center;
	overflow: hidden;
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

#summary {
	display: flex;
	justify-content: center;
	align-items: center;
	font-weight: bold;
	padding-top: 1em;
	padding-bottom: 1em;
}

.ok {
	color: green;
}

.error {
	color: red;
}

.sign {
	margin-left: 0.5em;
	font-weight: bold;
}

#content_wrapper {
	display: flex;
	flex-flow: column;
	height: 100%;
	overflow: hidden;
}

.checksums_wrapper {
	margin-bottom: 1.5em;
	box-shadow: 0px 0px 5px lightgrey;
}

.checksums_wrapper_expanded {
	display: flex;
	flex-flow: column;
	overflow: hidden;
}

.show_checksums {
	display: flex;
	flex-flow: column;
	overflow: auto;
}

.hide_checksums {
	display: none;
}

.table_header {
	display: flex;
	align-items: center;
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
	flex-flow: column;
	border-left: 1px solid black;
	border-bottom: 1px solid black;
	border-right: 1px solid black;
	background-color: white;
}

.title_cell {
	background-color: var(--third-color);
}

.cell {
	display: flex;
	justify-content: center;
	padding: 0.5em;
	word-break: break-all;
}

.small_text {
	font-size: small;
	text-align: center;
	padding: 0.4em;
}
</style>
