import router from "@/router/router"
import navMenu from "./navMenu.js"

const user = {
	state: {
		loggedInUsingCookie: false,
		logoutByTimer: false,
		timer: false, // inactivity timer = 9 minutes 55 second (5 seconds for graceful logout)
		nextPage: "/dashboard/state_device",
		isDesktop: navigator.userAgent.search(/iPhone|Android/) == -1,
		desktopVersionForced: false, // user can switch to the desktop version on a mobile device
		username: null,
		privilege: null,
		menu: navMenu
	},
	getters: {
		isLoggedInUsingCookie(state) {
			return state.loggedInUsingCookie
		},
		isTimerTriggered(state) {
			let tmp = state.logoutByTimer
			state.logoutByTimer = false
			return tmp
		},
		isDesktop(state) {
			return state.isDesktop
		},
		isDesktopVersionForced(state) {
			return state.desktopVersionForced
		},
		showDesktopVersion(state) {
			return state.isDesktop || state.desktopVersionForced
		},
		showMobileVersion(state) {
			return !state.isDesktop && !state.desktopVersionForced
		},
		getUsername(state) {
			return state.username
		},
		isAuthorized(state) {
			return state.username != null
		},
		getPrivilege(state) {
			return state.privilege
		},
		isReadPrivilege(state) {
			return state.privilege == "read"
		},
		getMenu(state) {
			return state.menu
		}
	},
	mutations: {
		SET_LOGGED_IN_USING_COOKIE(state) {
			state.loggedInUsingCookie = true
		},
		SET_NEXT_PAGE(state, nextPage) {
			state.nextPage = nextPage
		},
		SET_DESKTOP_VERSION_FORCED(state, isForced) {
			state.desktopVersionForced = isForced
		},
		SET_TIMER(state) {
			state.timer = 595 // 9 minutes 55 second
		},
		SET_USERNAME(state, username) {
			state.username = username
		},
		SET_PRIVILEGE(state, privilege) {
			state.privilege = privilege
		},
		SET_MENU_ITEM_VISIBLE(state, data) {
			if (data.sublevel == null)
				state.menu[data.top_level].visible = data.visible
			else
				state.menu[data.top_level]
					.submenu[data.sublevel].visible = data.visible
		},
		EXIT(state, isTimerTriggered) {
			state.loggedInUsingCookie = false,
			state.logoutByTimer = isTimerTriggered,
			state.timer = false,
			state.nextPage = "/dashboard/state_device",
			state.username = null
			router.replace({path: "/"})
		}
	},
	actions: {
		async getRequest({commit}, url) {
			let response = await fetch(url)
			if (response.status == 401)
				this.dispatch("logout", true)
			else
				commit("SET_TIMER")
			return response
		},
		async postRequest({commit}, {url, body}) {
			let response = await fetch(url, {method: "POST", body: body})
			if (response.status == 401)
				this.dispatch("logout", true)
			else
				commit("SET_TIMER")
			return response
		},
		async loginUsingCookie({commit}) {
			let success = false
			if (document.cookie.search(/FNP4Session=([A-Z0-9]){128}/) != -1) {
				success = await this.dispatch("login", {}) == 200
				if (success)
					commit("SET_LOGGED_IN_USING_COOKIE")
			}
			return success
		},
		async login({state, commit}, form) {
			let formData = new FormData()
			formData.set("uname", form.username)
			formData.set("upwd", form.password)
			let login_response = await fetch("/php/login.php", {
				method: "POST", body: formData
			})
			if (login_response.status == 200) {
				let data = await login_response.json()
				commit("SET_USERNAME", data.username)
				commit("SET_PRIVILEGE", data.privilege)
				
				this.dispatch("getRequest", "/php/interface_settings.php")
					.then(async response => {
						if (response.status == 200) {
							let data = await response.json()
							this.dispatch("setMenuItemVisible", {
								"top_level": 1,
								"sublevel": 3,
								"visible": data.natVisible
							})
							this.dispatch("setMenuItemVisible", {
								"top_level": 5,
								"visible": data.debugVisible
							})
						}
					})
				
				router.replace({path: state.nextPage})
			}
			return login_response.status
		},
		async startTimer({state, commit}) {
			commit("SET_TIMER")
			while (state.timer > 0) {
				await new Promise(resolve => setTimeout(resolve, 1000))
				state.timer--
			}
			if (state.timer == 0)
				await this.dispatch("logout", true)
		},
		async logout({commit}, isTimerTriggered) {
			fetch("/php/logout.php")
				.then(async () => {
					commit("EXIT", isTimerTriggered)
				})
		},
		exit({commit}) {
			commit("EXIT", false)
		},
		setNextPage({commit}, nextPage) {
			commit("SET_NEXT_PAGE", nextPage)
		},
		setDesktopVersionForced({commit}, isForced) {
			commit("SET_DESKTOP_VERSION_FORCED", isForced)
		},
		setMenuItemVisible({commit}, data) {
			commit("SET_MENU_ITEM_VISIBLE", data)
		}
	}
}

export default user
