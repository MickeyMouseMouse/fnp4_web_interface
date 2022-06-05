<template>
	<div id="workplace">
		<v-header />
		<main>
			<router-view />
		</main>
		<v-footer />
	</div>
	
	<Dialog :header="$t('common.exit')" :visible="isLogoutConfirmWindowVisible"
		:modal="true" :closable="false" :draggable="false" style="width: 21em">
		{{$t('exit.exit_question')}}
		<template #footer>
			<Button :label="$t('common.no')" icon="pi pi-times" class="p-button-text"
				@click="cancel" />
			<Button :label="$t('common.yes')" icon="pi pi-check" autofocus 
				@click="exit" />
		</template>
	</Dialog>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
export default {
	name: "v-workplace",
	methods: {
		exit() {
			this.cancel()
			this.logout(false)
		},
		cancel() {
			this.setLogoutConfirmWindowVisible(false)
		},
		...mapActions([
			"logout",
			"setLogoutConfirmWindowVisible"
		])
	},
	computed: {
		...mapGetters([
			"isLogoutConfirmWindowVisible"
		])
	}
}
</script>

<style scoped>
#workplace {
	display: flex;
	flex-flow: column;
	flex: 1;
	overflow: hidden;
	padding-right: 0.4em;
	padding-left: 0.4em;
	background-color: var(--background-color);
}

main {
	display: flex;
	flex-flow: column;
	flex: 1;
	padding-left: 0.5em;
	padding-right: 0.5em;
	overflow: auto;
}
</style>
