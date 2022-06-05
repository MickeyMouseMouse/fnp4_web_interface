const navMenu = [
	{
		name: "status",
		mobile: true,
		submenu: [
			{
				name: "device",
				route: "state_device",
				help: "/help/ru_RU/m_info/status.html",
				mobile: true
			},
			{
				name: "filtering",
				route: "state_filtering",
				help: "/help/ru_RU/m_info/filtering.html",
				mobile: true
			},
			{
				name: "integrity",
				route: "state_checksum",
				help: "/help/ru_RU/m_info/icheck.html",
				mobile: true
			}
		]
	},
	{
		name: "settings",
		mobile: true,
		submenu: [
			{
				name: "device",
				route: "settings_device",
				help: "/help/ru_RU/m_sets/system.html",
				mobile: true
			},
			{
				name: "administrators",
				route: "settings_users",
				help: "/help/ru_RU/m_sets/users.html"
			},
			{
				name: "interfaces",
				route: "settings_eth",
				help: "/help/ru_RU/m_sets/eth.html"
			},
			{
				name: "nat",
				route: "settings_nat",
				help: "/help/ru_RU/m_sets/nat.html"
			},
			{
				name: "network_users",
				route: "settings_nusers",
				help: "/help/ru_RU/m_sets/nusers.html"
			},
			{
				name: "registration",
				route: "settings_log_settings",
				help: "/help/ru_RU/m_sets/log.html"
			},
			{
				name: "high_availability",
				route: "settings_reserv",
				help: "/help/ru_RU/m_sets/reserv.html"
			},
			{
				name: "radius",
				route: "settings_radius",
				help: "/help/ru_RU/m_sets/radius.html"
			},
			{
				name: "routes",
				route: "settings_routes",
				help: "/help/ru_RU/m_sets/routes.html",
				mobile: true
			},
			{
				name: "http_proxy",
				route: "settings_proxy",
				help: "/help/ru_RU/m_sets/proxy.html",
				mobile: true
			}
		]
	},
	{
		name: "policies",
		mobile: true,
		submenu: [
			{
				name: "control",
				route: "policy_control",
				help: "/help/ru_RU/m_rules/policy.html"
			},
			{
				name: "directory",
				route: "policy_directory",
				help: "/help/ru_RU/m_rules/dir.html",
				mobile: true
			},
			{
				name: "rules",
				route: "policy_rules",
				help: "/help/ru_RU/m_rules/rules.html",
				mobile: true
			},
			{
				name: "statistics",
				route: "policy_stats",
				help: "/help/ru_RU/m_rules/stats.html",
				mobile: true
			}
		]
	},
	{
		name: "sessions",
		mobile: true,
		submenu: [
			{
				name: "settings",
				route: "sessions_settings",
				help: "/help/ru_RU/m_sess/sess.html",
				mobile: true
			},
			{
				name: "session_table",
				route: "sessions_table",
				help: "/help/ru_RU/m_sess/sess_tab.html"
			}
		]
	},
	{
		name: "registration",
		mobile: true,
		submenu: [
			{
				name: "events",
				route: "log_events",
				help: "/help/ru_RU/m_log/event.html"
			},
			{
				name: "packets",
				route: "log_packets",
				help: "/help/ru_RU/m_log/packet.html"
			},
			{
				name: "sessions",
				route: "log_sessions",
				help: "/help/ru_RU/m_log/session.html"
			},
			{
				name: "system_messages",
				route: "log_syslog",
				mobile: true
			},
			{
				name: "clear_logs",
				route: "log_clear",
				mobile: true
			}
		],
	},
	{
		name: "debug",
		route: "cli"
	},
	{
		name: "web_interface",
		route: "web_interface",
		mobile: true
	},
	{
		name: "setup",
		route: "setup",
	}
]

export default navMenu