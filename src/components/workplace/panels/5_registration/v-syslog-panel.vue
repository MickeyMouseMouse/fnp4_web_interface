<template>
	<div v-show="!isLoading" id="syslog_panel">
		<div id="panel_wrapper" :class="showDesktopVersion ? 'panel_wrapper_desktop' : 'panel_wrapper_mobile'">
			<div id="syslog_header">
				<div id="search_field" @keydown.enter="findNext">
					<span class="p-input-icon-left" style="width: 100%;">
						<i class="pi pi-search" />
						<InputText type="text" v-model="search" style="width: 100%;"
							:placeholder="$t('syslog.search')" />
					</span>
				</div>
				<div id="btn_find">
					<Button :label="$t('syslog.find')"
						@click="findNext" />
				</div>
			</div>
			<div id="syslog_main">
				<textarea id="logs" v-model="logs" readonly />
				<div id="nav_btns">
					<div style="flex: 1">
						<Button icon="pi pi-angle-double-up"
							class="p-button-rounded p-button-text"
							@click="scroll(0)" />
					</div>
					<div>
						<Button icon="pi pi-angle-double-down"
							class="p-button-rounded p-button-text"
							@click="scroll(-1)" />
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-syslog-panel",
	created() {
		this.setLoading(true)
		this.fetchData()
	},
	data() {
		return {
			search: "",
			logs: ""
		}
	},
	methods: {
		async fetchData() {
			this.getRequest("/php/5_registration/syslog.php")
				.then(async response => {
					if (response.status == 200) {
						this.logs = await response.text()
						this.setLoading(false)
					}
				})
		},
		findNext() {
			if (this.search != "") {
				let textArea = document.getElementById("logs")
				let startIndex = this.logs.indexOf(this.search, textArea.selectionEnd)
				if (startIndex == -1) { // no matches
					startIndex = this.logs.indexOf(this.search, 0)
					if (startIndex == -1) // no matches
						this.$toast.add({severity: "info",
							summary: t("syslog.no_matches"), life: 3000})
					else
						this.scroll(0)
				}
				if (startIndex != -1) {
					let endIndex = startIndex + this.search.length
					textArea.setSelectionRange(startIndex, endIndex)
					textArea.focus()
				}
			}
		},
		scroll(index) {
			let textArea = document.getElementById("logs")
			if (index == -1)
				textArea.scroll({top: textArea.scrollHeight, left: 0, behavior: "smooth"})
			else
				textArea.scroll({top: index, left: 0, behavior: "smooth"})
		},
		...mapActions([
			"getRequest",
			"setLoading"
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
#syslog_panel {
	display: flex;
	justify-content: center;
	flex: 1;
}

#panel_wrapper {
	display: flex;
	flex-flow: column;
}

.panel_wrapper_desktop {
	width: 95%;
}

.panel_wrapper_mobile {
	flex: 1;
}

#syslog_header {
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

#syslog_main {
	display: flex;
	flex: 1;
	padding: 1em 0em;
}

#logs {
	width: 100%;
	height: 100%;
	resize: none;
}

#nav_btns {
	display: flex;
	flex-flow: column;
	margin-left: 0.5em;
}

#syslog_footer {
	display: flex;
	justify-content: right;
}
</style>
