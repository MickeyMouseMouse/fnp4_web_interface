import { createStore } from "vuex"
import user from "./modules/user"
import appState from "./modules/appState"
import validator from "./modules/validator"

const store = createStore({
	modules: {
		user,
		appState,
		validator
	}
})

export default store
