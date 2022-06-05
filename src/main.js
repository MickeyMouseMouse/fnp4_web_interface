import { createApp } from "vue"
import App from "@/App.vue"
import router from "@/router/router"
import store from "@/vuex/vuex"
import i18n from "@/i18n/i18n"
import PrimeVue from "primevue/config"
import "primevue/resources/themes/saga-blue/theme.css"
import "primevue/resources/primevue.min.css"
import "primeicons/primeicons.css"
import ToastService from "primevue/toastservice"
import vComponents from "@/components/misc/misc"
import "@/main.css"

const app = createApp(App)

// add components from "misc" folder
vComponents.forEach(component => {
	app.component(component.name, component)
})

app.use(router).use(store).use(i18n).use(PrimeVue).use(ToastService).mount("#v-app")
