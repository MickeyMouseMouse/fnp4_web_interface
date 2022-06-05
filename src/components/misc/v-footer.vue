<template>
	<footer>
		<div id="admin">
			{{$t("footer.admin")}}: {{getUsername}} ({{getPrivilege}})
		</div>
		<div v-show="showDesktopVersion">
			{{$t("footer.company")}}
		</div>
	</footer>
</template>

<script>
import { mapGetters } from "vuex"
export default {
	name: "v-footer",
	computed: {
		...mapGetters([
			"getUsername",
			"getPrivilege",
			"showDesktopVersion"
		])
	}
}
</script>

<style scoped>
footer {
	display: flex;
	font-weight: bold;
	font-size: small;
	margin-top: 0.2em;
}

#admin {
	flex: 1;
}

/*
@media (-webkit-min-device-pixel-ratio: 2) {
	mobile device
}
*/
</style>
