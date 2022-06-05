const appState = {
	state: {
		top_level_index: 0,
		sublevel_index: 0,
		isLoading: false,
		showLogoutConfirmWindow: false,
		mobileMenuOpened: false
	},
	getters: {
		getTopLevelIndex(state) {
			return state.top_level_index
		},
		getSublevelIndex(state) {
			return state.sublevel_index
		},
		isLoading(state) {
			return state.isLoading
		},
		isLogoutConfirmWindowVisible(state) {
			return state.showLogoutConfirmWindow
		},
		isMobileMenuOpened(state) {
			return state.mobileMenuOpened
		}
	},
	mutations: {
		SET_INDEXES(state, indexes) {
			state.top_level_index = indexes.top_level
			state.sublevel_index = indexes.sublevel
		},
		SET_LOADING(state, isLoading) {
			state.isLoading = isLoading
		},
		SET_LOGOUT_CONFIRM_WINDOW_VISIBLE(state, isVisible) {
			state.showLogoutConfirmWindow = isVisible
		},
		SET_MOBILE_MENU_OPENED(state, isOpened) {
			state.mobileMenuOpened = isOpened
		}
	},
	actions: {
		setIndexes({commit}, indexes) {
			commit("SET_INDEXES", indexes)
		},
		setLoading({commit}, isLoading) {
			commit("SET_LOADING", isLoading)
		},
		setLogoutConfirmWindowVisible({commit}, isVisible) {
			commit("SET_LOGOUT_CONFIRM_WINDOW_VISIBLE", isVisible)
		},
		setMobileMenuOpened({commit}, isOpened) {
			commit("SET_MOBILE_MENU_OPENED", isOpened)
		}
	}
}

export default appState
