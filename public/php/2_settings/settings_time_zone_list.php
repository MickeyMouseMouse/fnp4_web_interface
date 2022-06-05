<?php
header('Content-Type: application/json; charset=utf-8');

echo json_encode(["tz" => [
	"Africa-Angola",
	"Africa-Burkina Faso",
	"Africa-Burundi",
	"Africa-Benin",
	"Africa-Botswana",
	"Dem. Rep. of Congo (west)",
	"Dem. Rep. of Congo (east)",
	"Africa-Central African Republic",
	"Africa-Congo",
	"Africa-Côte d'Ivoire",
	"Africa-Cameroon",
	"Africa-Djibouti",
	"Africa-Algeria",
	"Africa-Egypt",
	"Africa-Western Sahara",
	"Africa-Eritrea",
	"Africa-Spain",
	"Africa-Ethiopia",
	"Africa-Gabon",
	"Africa-Ghana",
	"Africa-Gambia",
	"Africa-Guinea",
	"Africa-Equatorial Guinea",
	"Africa-Guinea-Bissau",
	"Africa-Kenya",
	"Africa-Liberia",
	"Africa-Lesotho",
	"Africa-Libya",
	"Africa-Morocco",
	"Africa-Mali",
	"Africa-Mauritania",
	"Africa-Malawi",
	"Africa-Mozambique",
	"Africa-Namibia",
	"Africa-Niger",
	"Africa-Nigeria",
	"Africa-Rwanda",
	"Africa-Sudan",
	"Africa-Sierra Leone",
	"Africa-Senegal",
	"Africa-Somalia",
	"Africa-South Sudan",
	"Africa-Sao Tome and Principe",
	"Africa-Eswatini",
	"Africa-Chad",
	"Africa-Togo",
	"Africa-Tunisia",
	"Africa-Tanzania United Republic",
	"Africa-Uganda",
	"Africa-South Africa",
	"Africa-Zambia",
	"Africa-Zimbabwe",
	"America-Antigua and Barbuda",
	"America-Anguilla",
	"Buenos Aires (BA, CF)",
	"Argentina (most areas: CB, CC, CN, ER, FM, MN, SE, SF)",
	"Salta (SA, LP, NQ, RN)",
	"Jujuy (JY)",
	"Tucuman (TM)",
	"Catamarca (CT); Chubut (CH)",
	"La Rioja (LR)",
	"San Juan (SJ)",
	"Mendoza (MZ)",
	"San Luis (SL)",
	"Santa Cruz (SC)",
	"Tierra del Fuego (TF)",
	"America-Aruba",
	"America-Barbados",
	"America-Saint Barthélemy",
	"America-Bolivia, Plurinational State",
	"America-Bonaire, Sint Eustatius and Saba",
	"Atlantic islands",
	"Para (east); Amapa",
	"Brazil (northeast: MA, PI, CE, RN, PB)",
	"Pernambuco",
	"Tocantins",
	"Alagoas, Sergipe",
	"Bahia",
	"Brazil (southeast: GO, DF, MG, ES, RJ, SP, PR, SC, RS)",
	"Mato Grosso do Sul",
	"Mato Grosso",
	"Para (west)",
	"Rondonia",
	"Roraima",
	"Amazonas (east)",
	"Amazonas (west)",
	"Acre",
	"America-Bahamas",
	"America-Belize",
	"Newfoundland; Labrador (southeast)",
	"Atlantic - NS (most areas); PE",
	"Atlantic - NS (Cape Breton)",
	"Atlantic - New Brunswick",
	"Atlantic - Labrador (most areas)",
	"AST - QC (Lower North Shore)",
	"Eastern - ON, QC (most areas)",
	"Eastern - ON, QC (no DST 1967-73)",
	"Eastern - ON (Thunder Bay)",
	"Eastern - NU (most east areas)",
	"Eastern - NU (Pangnirtung)",
	"EST - ON (Atikokan); NU (Coral H)",
	"Central - ON (west); Manitoba",
	"Central - ON (Rainy R, Ft Frances)",
	"Central - NU (Resolute)",
	"Central - NU (central)",
	"CST - SK (most areas)",
	"CST - SK (midwest)",
	"Mountain - AB; BC (E); SK (W)",
	"Mountain - NU (west)",
	"Mountain - NT (central)",
	"Mountain - NT (west)",
	"MST - BC (Creston)",
	"MST - BC (Dawson Cr, Ft St John)",
	"MST - BC (Ft Nelson)",
	"MST - Yukon (east)",
	"MST - Yukon (west)",
	"Pacific - BC (most areas)",
	"Chile (most areas)",
	"Region of Magallanes",
	"America-Colombia",
	"America-Costa Rica",
	"America-Cuba",
	"America-Curaçao",
	"America-Dominica",
	"America-Dominican Republic",
	"America-Ecuador",
	"America-Grenada",
	"America-French Guiana",
	"Greenland (most areas)",
	"National Park (east coast)",
	"Scoresbysund/Ittoqqortoormiit",
	"Thule/Pituffik",
	"America-Guadeloupe",
	"America-Guatemala",
	"America-Guyana",
	"America-Honduras",
	"America-Haiti",
	"America-Jamaica",
	"America-Saint Kitts and Nevis",
	"America-Cayman Islands",
	"America-Saint Lucia",
	"America-Saint Martin (French part)",
	"America-Martinique",
	"America-Montserrat",
	"Central Time",
	"Eastern Standard Time - Quintana Roo",
	"Central Time - Campeche, Yucatan",
	"Central Time - Durango; Coahuila, Nuevo Leon, Tamaulipas (most areas)",
	"Central Time US - Coahuila, Nuevo Leon, Tamaulipas (US border)",
	"Mountain Time - Baja California Sur, Nayarit, Sinaloa",
	"Mountain Time - Chihuahua (most areas)",
	"Mountain Time US - Chihuahua (US border)",
	"Mountain Standard Time - Sonora",
	"Pacific Time US - Baja California",
	"Central Time - Bahia de Banderas",
	"America-Nicaragua",
	"America-Panama",
	"America-Peru",
	"America-Saint Pierre and Miquelon",
	"America-Puerto Rico",
	"America-Paraguay",
	"America-Suriname",
	"America-El Salvador",
	"America-Sint Maarten (Dutch part)",
	"America-Turks and Caicos Islands",
	"America-Trinidad and Tobago",
	"Eastern (most areas)",
	"Eastern - MI (most areas)",
	"Eastern - KY (Louisville area)",
	"Eastern - KY (Wayne)",
	"Eastern - IN (most areas)",
	"Eastern - IN (Da, Du, K, Mn)",
	"Eastern - IN (Pulaski)",
	"Eastern - IN (Crawford)",
	"Eastern - IN (Pike)",
	"Eastern - IN (Switzerland)",
	"Central (most areas)",
	"Central - IN (Perry)",
	"Central - IN (Starke)",
	"Central - MI (Wisconsin border)",
	"Central - ND (Oliver)",
	"Central - ND (Morton rural)",
	"Central - ND (Mercer)",
	"Mountain (most areas)",
	"Mountain - ID (south); OR (east)",
	"MST - Arizona (except Navajo)",
	"Pacific",
	"Alaska (most areas)",
	"Alaska - Juneau area",
	"Alaska - Sitka area",
	"Alaska - Annette Island",
	"Alaska - Yakutat",
	"Alaska (west)",
	"Aleutian Islands",
	"America-Uruguay",
	"America-Saint Vincent and the Grenadines",
	"America-Venezuela, Bolivarian Republic of",
	"America-Virgin Islands (British)",
	"America-Virgin Islands (U.S.)",
	"New Zealand time - McMurdo, South Pole",
	"Casey",
	"Davis",
	"Dumont-d'Urville",
	"Mawson",
	"Palmer",
	"Rothera",
	"Syowa",
	"Troll",
	"Vostok",
	"Antarctica-Australia",
	"Arctic-Svalbard and Jan Mayen",
	"Asia-United Arab Emirates",
	"Asia-Afghanistan",
	"Asia-Armenia",
	"Asia-Azerbaijan",
	"Asia-Bangladesh",
	"Asia-Bahrain",
	"Asia-Brunei Darussalam",
	"Asia-Bhutan",
	"Beijing Time",
	"Xinjiang Time",
	"Cyprus (most areas)",
	"Northern Cyprus",
	"Asia-Georgia",
	"Asia-Hong Kong",
	"Java, Sumatra",
	"Borneo (west, central)",
	"Borneo (east, south); Sulawesi/Celebes, Bali, Nusa Tengarra; Timor (west)",
	"New Guinea (West Papua / Irian Jaya); Malukus/Moluccas",
	"Asia-Israel",
	"Asia-India",
	"Asia-Iraq",
	"Asia-Iran (Islamic Republic of)",
	"Asia-Jordan",
	"Asia-Japan",
	"Asia-Kyrgyzstan",
	"Asia-Cambodia",
	"Asia-Korea (Democratic People's Republic of)",
	"Asia-Korea (Republic of)",
	"Asia-Kuwait",
	"Kazakhstan (most areas)",
	"Qyzylorda/Kyzylorda/Kzyl-Orda",
	"Qostanay/Kostanay/Kustanay",
	"Aqtobe/Aktobe",
	"Mangghystau/Mankistau",
	"Atyrau/Atirau/Gur'yev",
	"West Kazakhstan",
	"Asia-Lao People's Democratic Republic",
	"Asia-Lebanon",
	"Asia-Sri Lanka",
	"Asia-Myanmar",
	"Mongolia (most areas)",
	"Bayan-Olgiy, Govi-Altai, Hovd, Uvs, Zavkhan",
	"Dornod, Sukhbaatar",
	"Asia-Macao",
	"Malaysia (peninsula)",
	"Sabah, Sarawak",
	"Asia-Nepal",
	"Asia-Oman",
	"Asia-Philippines",
	"Asia-Pakistan",
	"Gaza Strip",
	"West Bank",
	"Asia-Qatar",
	"MSK+02 - Urals",
	"MSK+03 - Omsk",
	"MSK+04 - Novosibirsk",
	"MSK+04 - Altai",
	"MSK+04 - Tomsk",
	"MSK+04 - Kemerovo",
	"MSK+04 - Krasnoyarsk area",
	"MSK+05 - Irkutsk, Buryatia",
	"MSK+06 - Zabaykalsky",
	"MSK+06 - Lena River",
	"MSK+06 - Tomponsky, Ust-Maysky",
	"MSK+07 - Amur River",
	"MSK+07 - Oymyakonsky",
	"MSK+08 - Magadan",
	"MSK+08 - Sakhalin Island",
	"MSK+08 - Sakha (E); North Kuril Is",
	"MSK+09 - Kamchatka",
	"MSK+09 - Bering Sea",
	"Asia-Saudi Arabia",
	"Asia-Singapore",
	"Asia-Syrian Arab Republic",
	"Asia-Thailand",
	"Asia-Tajikistan",
	"Asia-Timor-Leste",
	"Asia-Turkmenistan",
	"Asia-Taiwan",
	"Uzbekistan (west)",
	"Uzbekistan (east",
	"Asia-Viet Nam",
	"Asia-Yemen",
	"Atlantic-Bermuda",
	"Atlantic-Cabo Verde",
	"Atlantic-Spain",
	"Atlantic-Falkland Islands (Malvinas)",
	"Atlantic-Faroe Islands",
	"Atlantic-South Georgia and the South Sandwich Islands",
	"Atlantic-Iceland",
	"Madeira Islands",
	"Azores",
	"Atlantic-Saint Helena Ascension and Tristan da Cunha",
	"Lord Howe Island",
	"Tasmania",
	"Victoria",
	"New South Wales (most areas)",
	"New South Wales (Yancowinna)",
	"Queensland (most areas)",
	"Queensland (Whitsunday Islands)",
	"South Australia",
	"Northern Territory",
	"Western Australia (most areas)",
	"Western Australia (Eucla)",
	"Europe-Andorra",
	"Europe-Albania",
	"Europe-Austria",
	"Europe-Åland Islands",
	"Europe-Bosnia and Herzegovina",
	"Europe-Belgium",
	"Europe-Bulgaria",
	"Europe-Belarus",
	"Europe-Switzerland",
	"Europe-Czech Republic",
	"Germany (most areas)",
	"Busingen",
	"Europe-Denmark",
	"Europe-Estonia",
	"Europe-Spain",
	"Europe-Finland",
	"Europe-France",
	"Europe-United Kingdom of Great Britain and Northern Ireland",
	"Europe-Guernsey",
	"Europe-Gibraltar",
	"Europe-Greece",
	"Europe-Croatia",
	"Europe-Hungary",
	"Europe-Ireland",
	"Europe-Isle of Man",
	"Europe-Italy",
	"Europe-Jersey",
	"Europe-Liechtenstein",
	"Europe-Lithuania",
	"Europe-Luxembourg",
	"Europe-Latvia",
	"Europe-Monaco",
	"Europe-Moldova (Republic of)",
	"Europe-Montenegro",
	"Europe-Macedonia (the former Yugoslav Republic of)",
	"Europe-Malta",
	"Europe-Netherlands",
	"Europe-Norway",
	"Europe-Poland",
	"Europe-Portugal",
	"Europe-Romania",
	"Europe-Serbia",
	"MSK-01 - Kaliningrad",
	"MSK+00 - Moscow area",
	"MSK+00 - Kirov",
	"MSK+00 - Volgograd",
	"MSK+01 - Astrakhan",
	"MSK+01 - Saratov",
	"MSK+01 - Ulyanovsk",
	"MSK+01 - Samara, Udmurtia",
	"Crimea",
	"Ukraine (most areas)",
	"Transcarpathia",
	"Zaporozhye and east Lugansk",
	"Europe-Sweden",
	"Europe-Slovenia",
	"Europe-Slovakia",
	"Europe-San Marino",
	"Europe-Turkey",
	"Europe-Holy See",
	"Indian-Cocos (Keeling) Islands",
	"Indian-Christmas Island",
	"Indian-British Indian Ocean Territory",
	"Indian-Comoros",
	"Indian-Madagascar",
	"Indian-Mauritius",
	"Indian-Maldives",
	"Indian-Réunion",
	"Indian-Seychelles",
	"Indian-French Southern Territories",
	"Indian-Mayotte",
	"Pacific-American Samoa",
	"Pacific-Cook Islands",
	"Pacific-Chile",
	"Pacific-Ecuador",
	"Pacific-Fiji",
	"Chuuk/Truk, Yap",
	"Pohnpei/Ponape",
	"Kosrae",
	"Pacific-Guam",
	"Gilbert Islands",
	"Phoenix Islands",
	"Line Islands",
	"Marshall Islands (most areas)",
	"Kwajalein",
	"Pacific-Northern Mariana Islands",
	"Pacific-New Caledonia",
	"Pacific-Norfolk Island",
	"Pacific-Nauru",
	"Pacific-Niue",
	"New Zealand (most areas)",
	"Chatham Islands",
	"Society Islands",
	"Marquesas Islands",
	"Gambier Islands",
	"Papua New Guinea (most areas)",
	"Bougainville",
	"Pacific-Pitcairn",
	"Pacific-Palau",
	"Pacific-Solomon Islands",
	"Pacific-Tokelau",
	"Pacific-Tonga",
	"Pacific-Tuvalu",
	"Midway Islands",
	"Wake Island",
	"Pacific-United States of America",
	"Pacific-Vanuatu",
	"Pacific-Wallis and Futuna",
	"Pacific-Samoa",
	"UTC"
]]);

?>