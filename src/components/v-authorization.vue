<template>
	<div v-if="!isLoggedInUsingCookie" id="authorization">
		<div id="logo_panel">
			<v-logo />
		</div>
		<div id="authorization_panel">
			<div id="title">
				{{$t("authorization.title")}}
			</div>
			<div v-show="error" id="notification">
				{{error}}
			</div>
			<div id="form" @keydown.enter="authorize">
				<div id="username_panel">
					<InputText id="username" v-model="form.username"
						:placeholder="$t('authorization.admin_name')" />
				</div>
				<div id="password_panel">
					<Password v-model="form.password"
						:placeholder="$t('authorization.password')"
						:feedback="false" :toggleMask="true"
						inputStyle="width: 14em;" />
				</div>
				<div id="btn_login_panel">
					<Button @click="authorize" :label="$t('authorization.login')" />
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import Password from "primevue/password"
import i18n from "@/i18n/i18n"
const { t } = i18n.global
export default {
	name: "v-authorization",
	components: {
		Password
	},
	created() {
		if (this.isTimerTriggered)
			this.error = t("authorization.timeout")
		this.form.username = ""
		this.form.password = ""
	},
	data() {
		return {
			form: {
				username: "",
				password: ""
			},
			error: ""
		}
	},
	methods: {
		async authorize() {
			this.login(this.form).then(async status => {
				if (status == 401) {
					this.form.username = ""
					this.form.password = ""
					this.error = t("authorization.failed")
				}
			})
		},
		...mapActions([
			"login"
		])
	},
	computed: {
		...mapGetters([
			"isLoggedInUsingCookie",
			"isTimerTriggered"
		])
	}
}
</script>

<style scoped>
#authorization {
	display: flex;
	flex-flow: column;
	flex: 1;
	align-items: center;
	padding-top: 2em;
	background: linear-gradient(var(--primary-light-color) 70%, var(--primary-light-light-color));
}

#authorization_panel {
	padding: 1.5em;
	border-radius: 10px;
	background-color: white;
	box-shadow: 1px 1px 5px #808080;
}

#logo_panel {
	padding-bottom: 0.6em;
	height: 4em;
}

#title {
	margin: 0.3em;
	text-align: center;
	font-size: large;
}

#notification {
	color: red;
	text-align: center;
}

#form {
	margin-top: 1em;
}

#username_panel {
	text-align: center;
}

#username {
	width: 14em;
}

#password_panel {
	margin-top: 0.3em;
	text-align: center;
}

#btn_login_panel {
	margin-top: 0.7em;
	text-align: center;
}
</style>
