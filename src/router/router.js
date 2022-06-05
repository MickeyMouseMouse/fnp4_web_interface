import { createRouter, createWebHistory } from "vue-router"
import store from "@/vuex/vuex"

const empty = () => import("@/components/workplace/panels/v-empty.vue")

const routes = [
	{
		path: "/",
		component: () => import("@/components/v-authorization.vue") // lazy loading
	},
	{
		path: "/dashboard",
		component: () => import("@/components/v-dashboard.vue"),
		children: [
			{
				path: "state_device",
				component: () => import("@/components/workplace/panels/1_status/v-state-device-panel.vue")
			},
			{
				path: "state_filtering",
				component: () => import("@/components/workplace/panels/1_status/v-state-filtering-panel.vue")
			},
			{
				path: "state_checksum",
				component: () => import("@/components/workplace/panels/1_status/v-state-checksum-panel.vue")
			},
			{
				path: "settings_device",
				component: () => import("@/components/workplace/panels/2_settings/v-settings-device-panel.vue")
			},
			{
				path: "settings_users",
				component: empty
			},
			{
				path: "settings_eth",
				component: empty
			},
			{
				path: "settings_nat",
				component: empty
			},
			{
				path: "settings_nusers",
				component: empty
			},
			{
				path: "settings_log_settings",
				component: empty
			},
			{
				path: "settings_reserv",
				component: empty
			},
			{
				path: "settings_radius",
				component: empty
			},
			{
				path: "settings_routes",
				component: () => import("@/components/workplace/panels/2_settings/routes/v-settings-routes-panel.vue")
			},
			{
				path: "settings_proxy",
				component: () => import("@/components/workplace/panels/2_settings/v-settings-http-proxy-panel.vue")
			},
			{
				path: "policy_control",
				component: empty
			},
			{
				path: "policy_directory",
				component: () => import("@/components/workplace/panels/3_policy/directory/v-policy-directory-panel.vue"),
				children: [
					{
						path: "",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-no-item.vue")
					},
					{
						path: "time_interval",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-time-item.vue")
					},
					{
						path: "new_time_interval",
						component: () => import("@/components/workplace/panels/3_policy/directory/wizards/v-new-time-item.vue")
					},
					{
						path: "domain",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-domain-item.vue")
					},
					{
						path: "new_domain",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-no-item.vue")
					},
					{
						path: "vlan",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-vlan-item.vue")
					},
					{
						path: "new_vlan",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-no-item.vue")
					},
					{
						path: "host",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-host-item.vue")
					},
					{
						path: "new_host",
						component: () => import("@/components/workplace/panels/3_policy/directory/wizards/v-new-host-item.vue")
					},
					{
						path: "net",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-net-item.vue")
					},
					{
						path: "new_net",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-no-item.vue")
					},
					{
						path: "net_group",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-net-group-item.vue")
					},
					{
						path: "new_net_group",
						component: () => import("@/components/workplace/panels/3_policy/directory/wizards/v-new-net-group-item.vue")
					},
					{
						path: "service",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-service-item.vue")
					},
					{
						path: "new_service",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-no-item.vue")
					},
					{
						path: "resource",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-resource-item.vue")
					},
					{
						path: "new_resource",
						component: () => import("@/components/workplace/panels/3_policy/directory/items/v-no-item.vue")
					}
				]
			},
			{
				path: "policy_rules",
				component: () => import("@/components/workplace/panels/3_policy/rules/v-policy-rules-panel.vue"),
				children: [
					{
						path: "",
						redirect: "/dashboard/policy_rules/general"
					},
					{
						path: "general",
						component: () => import("@/components/workplace/panels/3_policy/rules/general/v-general-rules-tab.vue"),
						children: [
							{
								path: "wizard",
								component: () => import("@/components/workplace/panels/3_policy/rules/general/wizard/v-general-rule-wizard-panel.vue"),
								children: [
									{
										path: "rule_attributes",
										component: () => import("@/components/workplace/panels/3_policy/rules/general/wizard/v-rule-attributes.vue")
									},
									{
										path: "source_parameters",
										component: () => import("@/components/workplace/panels/3_policy/rules/general/wizard/v-src-params.vue")
									},
									{
										path: "destination_parameters",
										component: () => import("@/components/workplace/panels/3_policy/rules/general/wizard/v-dest-params.vue")
									}
								]
							}
						]
					}
				]
			},
			{
				path: "policy_stats",
				component: () => import("@/components/workplace/panels/3_policy/stats/v-policy-stats-panel.vue")
			},
			{
				path: "sessions_settings",
				component: () => import("@/components/workplace/panels/4_sessions/v-sessions-settings-panel.vue")
			},
			{
				path: "sessions_table",
				component: empty
			},
			{
				path: "log_events",
				component: empty
			},
			{
				path: "log_packets",
				component: empty
			},
			{
				path: "log_sessions",
				component: empty
			},
			{
				path: "log_syslog",
				component: () => import("@/components/workplace/panels/5_registration/v-syslog-panel.vue")
			},
			{
				path: "log_clear",
				component: () => import("@/components/workplace/panels/5_registration/v-clear-log-panel.vue")
			},
			{
				path: "cli",
				component: () => import("@/components/workplace/panels/v-cli-panel.vue")
			},
			{
				path: "web_interface",
				component: () => import("@/components/workplace/panels/v-interface-settings-panel.vue")
			},
			{
				path: "setup",
				component: () => import("@/components/workplace/panels/setup_wizard/v-setup-panel.vue"),
				children: [
					{
						path: "",
						redirect: "/dashboard/setup/language"
					},
					{
						path: "language",
						component: () => import("@/components/workplace/panels/setup_wizard/v-setup-language.vue")
					},
					{
						path: "device_name",
						component: () => import("@/components/workplace/panels/setup_wizard/v-setup-device-name.vue")
					},
					{
						path: "control_interface",
						component: () => import("@/components/workplace/panels/setup_wizard/v-setup-control-interface.vue")
					},
					{
						path: "default_gateway",
						component: () => import("@/components/workplace/panels/setup_wizard/v-setup-default-gateway.vue")
					},
					{
						path: "dns",
						component: () => import("@/components/workplace/panels/setup_wizard/v-setup-dns.vue")
					},
					{
						path: "ntp",
						component: () => import("@/components/workplace/panels/setup_wizard/v-setup-ntp.vue")
					},
				]
			}
		]
	},
	{
		path: "/:pathMatch(.*)*",
		redirect: "/dashboard/state_device"
	}
]

const router = createRouter({
	history: createWebHistory(),
	routes
})

router.beforeEach((to, from, next) => {
	if (store.getters.isAuthorized) {
		store.dispatch("setIndexes", indexes[to.path.split("/").at(2)])
	} else { // unauthorized
		if (to.path != "/") {
			store.dispatch("setNextPage", to.path)
			next({path: "/"})
		}
	}
	next()
})

const indexes = {
	"state_device": {"top_level": 0, "sublevel": 0},
	"state_filtering": {"top_level": 0, "sublevel": 1},
	"state_checksum": {"top_level": 0, "sublevel": 2},
	"settings_device": {"top_level": 1, "sublevel": 0},
	"settings_users": {"top_level": 1, "sublevel": 1},
	"settings_eth": {"top_level": 1, "sublevel": 2},
	"settings_nat": {"top_level": 1, "sublevel": 3},
	"settings_nusers": {"top_level": 1, "sublevel": 4},
	"settings_log_settings": {"top_level": 1, "sublevel": 5},
	"settings_reserv": {"top_level": 1, "sublevel": 6},
	"settings_radius": {"top_level": 1, "sublevel": 7},
	"settings_routes": {"top_level": 1, "sublevel": 8},
	"settings_proxy": {"top_level": 1, "sublevel": 9},
	"policy_control": {"top_level": 2, "sublevel": 0},
	"policy_directory": {"top_level": 2, "sublevel": 1},
	"policy_rules": {"top_level": 2, "sublevel": 2},
	"policy_stats": {"top_level": 2, "sublevel": 3},
	"sessions_settings": {"top_level": 3, "sublevel": 0},
	"sessions_table": {"top_level": 3, "sublevel": 1},
	"log_events": {"top_level": 4, "sublevel": 0},
	"log_packets": {"top_level": 4, "sublevel": 1},
	"log_sessions": {"top_level": 4, "sublevel": 2},
	"log_syslog": {"top_level": 4, "sublevel": 3},
	"log_clear": {"top_level": 4, "sublevel": 4},
	"cli": {"top_level": 5},
	"web_interface": {"top_level": 6},
	"setup": {"top_level": 7}
}

export default router
