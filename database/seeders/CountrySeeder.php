<?php
namespace Database\Seeders;
use App\Classes\Arrays;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder{
	protected array $countries = [
		[
			"id" => 1,
			"initials" => "AF",
			"name" => "Afghanistan",
			"phoneCode" => 93,
		],
		[
			"id" => 2,
			"initials" => "AL",
			"name" => "Albania",
			"phoneCode" => 355,
		],
		[
			"id" => 3,
			"initials" => "DZ",
			"name" => "Algeria",
			"phoneCode" => 213,
		],
		[
			"id" => 4,
			"initials" => "AS",
			"name" => "American Samoa",
			"phoneCode" => 1684,
		],
		[
			"id" => 5,
			"initials" => "AD",
			"name" => "Andorra",
			"phoneCode" => 376,
		],
		[
			"id" => 6,
			"initials" => "AO",
			"name" => "Angola",
			"phoneCode" => 244,
		],
		[
			"id" => 7,
			"initials" => "AI",
			"name" => "Anguilla",
			"phoneCode" => 1264,
		],
		[
			"id" => 8,
			"initials" => "AQ",
			"name" => "Antarctica",
			"phoneCode" => 0,
		],
		[
			"id" => 9,
			"initials" => "AG",
			"name" => "Antigua And Barbuda",
			"phoneCode" => 1268,
		],
		[
			"id" => 10,
			"initials" => "AR",
			"name" => "Argentina",
			"phoneCode" => 54,
		],
		[
			"id" => 11,
			"initials" => "AM",
			"name" => "Armenia",
			"phoneCode" => 374,
		],
		[
			"id" => 12,
			"initials" => "AW",
			"name" => "Aruba",
			"phoneCode" => 297,
		],
		[
			"id" => 13,
			"initials" => "AU",
			"name" => "Australia",
			"phoneCode" => 61,
		],
		[
			"id" => 14,
			"initials" => "AT",
			"name" => "Austria",
			"phoneCode" => 43,
		],
		[
			"id" => 15,
			"initials" => "AZ",
			"name" => "Azerbaijan",
			"phoneCode" => 994,
		],
		[
			"id" => 16,
			"initials" => "BS",
			"name" => "Bahamas The",
			"phoneCode" => 1242,
		],
		[
			"id" => 17,
			"initials" => "BH",
			"name" => "Bahrain",
			"phoneCode" => 973,
		],
		[
			"id" => 18,
			"initials" => "BD",
			"name" => "Bangladesh",
			"phoneCode" => 880,
		],
		[
			"id" => 19,
			"initials" => "BB",
			"name" => "Barbados",
			"phoneCode" => 1246,
		],
		[
			"id" => 20,
			"initials" => "BY",
			"name" => "Belarus",
			"phoneCode" => 375,
		],
		[
			"id" => 21,
			"initials" => "BE",
			"name" => "Belgium",
			"phoneCode" => 32,
		],
		[
			"id" => 22,
			"initials" => "BZ",
			"name" => "Belize",
			"phoneCode" => 501,
		],
		[
			"id" => 23,
			"initials" => "BJ",
			"name" => "Benin",
			"phoneCode" => 229,
		],
		[
			"id" => 24,
			"initials" => "BM",
			"name" => "Bermuda",
			"phoneCode" => 1441,
		],
		[
			"id" => 25,
			"initials" => "BT",
			"name" => "Bhutan",
			"phoneCode" => 975,
		],
		[
			"id" => 26,
			"initials" => "BO",
			"name" => "Bolivia",
			"phoneCode" => 591,
		],
		[
			"id" => 27,
			"initials" => "BA",
			"name" => "Bosnia and Herzegovina",
			"phoneCode" => 387,
		],
		[
			"id" => 28,
			"initials" => "BW",
			"name" => "Botswana",
			"phoneCode" => 267,
		],
		[
			"id" => 29,
			"initials" => "BV",
			"name" => "Bouvet Island",
			"phoneCode" => 0,
		],
		[
			"id" => 30,
			"initials" => "BR",
			"name" => "Brazil",
			"phoneCode" => 55,
		],
		[
			"id" => 31,
			"initials" => "IO",
			"name" => "British Indian Ocean Territory",
			"phoneCode" => 246,
		],
		[
			"id" => 32,
			"initials" => "BN",
			"name" => "Brunei",
			"phoneCode" => 673,
		],
		[
			"id" => 33,
			"initials" => "BG",
			"name" => "Bulgaria",
			"phoneCode" => 359,
		],
		[
			"id" => 34,
			"initials" => "BF",
			"name" => "Burkina Faso",
			"phoneCode" => 226,
		],
		[
			"id" => 35,
			"initials" => "BI",
			"name" => "Burundi",
			"phoneCode" => 257,
		],
		[
			"id" => 36,
			"initials" => "KH",
			"name" => "Cambodia",
			"phoneCode" => 855,
		],
		[
			"id" => 37,
			"initials" => "CM",
			"name" => "Cameroon",
			"phoneCode" => 237,
		],
		[
			"id" => 38,
			"initials" => "CA",
			"name" => "Canada",
			"phoneCode" => 1,
		],
		[
			"id" => 39,
			"initials" => "CV",
			"name" => "Cape Verde",
			"phoneCode" => 238,
		],
		[
			"id" => 40,
			"initials" => "KY",
			"name" => "Cayman Islands",
			"phoneCode" => 1345,
		],
		[
			"id" => 41,
			"initials" => "CF",
			"name" => "Central African Republic",
			"phoneCode" => 236,
		],
		[
			"id" => 42,
			"initials" => "TD",
			"name" => "Chad",
			"phoneCode" => 235,
		],
		[
			"id" => 43,
			"initials" => "CL",
			"name" => "Chile",
			"phoneCode" => 56,
		],
		[
			"id" => 44,
			"initials" => "CN",
			"name" => "China",
			"phoneCode" => 86,
		],
		[
			"id" => 45,
			"initials" => "CX",
			"name" => "Christmas Island",
			"phoneCode" => 61,
		],
		[
			"id" => 46,
			"initials" => "CC",
			"name" => "Cocos (Keeling) Islands",
			"phoneCode" => 672,
		],
		[
			"id" => 47,
			"initials" => "CO",
			"name" => "Colombia",
			"phoneCode" => 57,
		],
		[
			"id" => 48,
			"initials" => "KM",
			"name" => "Comoros",
			"phoneCode" => 269,
		],
		[
			"id" => 49,
			"initials" => "CG",
			"name" => "Republic Of The Congo",
			"phoneCode" => 242,
		],
		[
			"id" => 50,
			"initials" => "CD",
			"name" => "Democratic Republic Of The Congo",
			"phoneCode" => 242,
		],
		[
			"id" => 51,
			"initials" => "CK",
			"name" => "Cook Islands",
			"phoneCode" => 682,
		],
		[
			"id" => 52,
			"initials" => "CR",
			"name" => "Costa Rica",
			"phoneCode" => 506,
		],
		[
			"id" => 53,
			"initials" => "CI",
			"name" => "Cote D'Ivoire (Ivory Coast)",
			"phoneCode" => 225,
		],
		[
			"id" => 54,
			"initials" => "HR",
			"name" => "Croatia (Hrvatska)",
			"phoneCode" => 385,
		],
		[
			"id" => 55,
			"initials" => "CU",
			"name" => "Cuba",
			"phoneCode" => 53,
		],
		[
			"id" => 56,
			"initials" => "CY",
			"name" => "Cyprus",
			"phoneCode" => 357,
		],
		[
			"id" => 57,
			"initials" => "CZ",
			"name" => "Czech Republic",
			"phoneCode" => 420,
		],
		[
			"id" => 58,
			"initials" => "DK",
			"name" => "Denmark",
			"phoneCode" => 45,
		],
		[
			"id" => 59,
			"initials" => "DJ",
			"name" => "Djibouti",
			"phoneCode" => 253,
		],
		[
			"id" => 60,
			"initials" => "DM",
			"name" => "Dominica",
			"phoneCode" => 1767,
		],
		[
			"id" => 61,
			"initials" => "DO",
			"name" => "Dominican Republic",
			"phoneCode" => 1809,
		],
		[
			"id" => 62,
			"initials" => "TP",
			"name" => "East Timor",
			"phoneCode" => 670,
		],
		[
			"id" => 63,
			"initials" => "EC",
			"name" => "Ecuador",
			"phoneCode" => 593,
		],
		[
			"id" => 64,
			"initials" => "EG",
			"name" => "Egypt",
			"phoneCode" => 20,
		],
		[
			"id" => 65,
			"initials" => "SV",
			"name" => "El Salvador",
			"phoneCode" => 503,
		],
		[
			"id" => 66,
			"initials" => "GQ",
			"name" => "Equatorial Guinea",
			"phoneCode" => 240,
		],
		[
			"id" => 67,
			"initials" => "ER",
			"name" => "Eritrea",
			"phoneCode" => 291,
		],
		[
			"id" => 68,
			"initials" => "EE",
			"name" => "Estonia",
			"phoneCode" => 372,
		],
		[
			"id" => 69,
			"initials" => "ET",
			"name" => "Ethiopia",
			"phoneCode" => 251,
		],
		[
			"id" => 70,
			"initials" => "XA",
			"name" => "External Territories of Australia",
			"phoneCode" => 61,
		],
		[
			"id" => 71,
			"initials" => "FK",
			"name" => "Falkland Islands",
			"phoneCode" => 500,
		],
		[
			"id" => 72,
			"initials" => "FO",
			"name" => "Faroe Islands",
			"phoneCode" => 298,
		],
		[
			"id" => 73,
			"initials" => "FJ",
			"name" => "Fiji Islands",
			"phoneCode" => 679,
		],
		[
			"id" => 74,
			"initials" => "FI",
			"name" => "Finland",
			"phoneCode" => 358,
		],
		[
			"id" => 75,
			"initials" => "FR",
			"name" => "France",
			"phoneCode" => 33,
		],
		[
			"id" => 76,
			"initials" => "GF",
			"name" => "French Guiana",
			"phoneCode" => 594,
		],
		[
			"id" => 77,
			"initials" => "PF",
			"name" => "French Polynesia",
			"phoneCode" => 689,
		],
		[
			"id" => 78,
			"initials" => "TF",
			"name" => "French Southern Territories",
			"phoneCode" => 0,
		],
		[
			"id" => 79,
			"initials" => "GA",
			"name" => "Gabon",
			"phoneCode" => 241,
		],
		[
			"id" => 80,
			"initials" => "GM",
			"name" => "Gambia The",
			"phoneCode" => 220,
		],
		[
			"id" => 81,
			"initials" => "GE",
			"name" => "Georgia",
			"phoneCode" => 995,
		],
		[
			"id" => 82,
			"initials" => "DE",
			"name" => "Germany",
			"phoneCode" => 49,
		],
		[
			"id" => 83,
			"initials" => "GH",
			"name" => "Ghana",
			"phoneCode" => 233,
		],
		[
			"id" => 84,
			"initials" => "GI",
			"name" => "Gibraltar",
			"phoneCode" => 350,
		],
		[
			"id" => 85,
			"initials" => "GR",
			"name" => "Greece",
			"phoneCode" => 30,
		],
		[
			"id" => 86,
			"initials" => "GL",
			"name" => "Greenland",
			"phoneCode" => 299,
		],
		[
			"id" => 87,
			"initials" => "GD",
			"name" => "Grenada",
			"phoneCode" => 1473,
		],
		[
			"id" => 88,
			"initials" => "GP",
			"name" => "Guadeloupe",
			"phoneCode" => 590,
		],
		[
			"id" => 89,
			"initials" => "GU",
			"name" => "Guam",
			"phoneCode" => 1671,
		],
		[
			"id" => 90,
			"initials" => "GT",
			"name" => "Guatemala",
			"phoneCode" => 502,
		],
		[
			"id" => 91,
			"initials" => "XU",
			"name" => "Guernsey and Alderney",
			"phoneCode" => 44,
		],
		[
			"id" => 92,
			"initials" => "GN",
			"name" => "Guinea",
			"phoneCode" => 224,
		],
		[
			"id" => 93,
			"initials" => "GW",
			"name" => "Guinea-Bissau",
			"phoneCode" => 245,
		],
		[
			"id" => 94,
			"initials" => "GY",
			"name" => "Guyana",
			"phoneCode" => 592,
		],
		[
			"id" => 95,
			"initials" => "HT",
			"name" => "Haiti",
			"phoneCode" => 509,
		],
		[
			"id" => 96,
			"initials" => "HM",
			"name" => "Heard and McDonald Islands",
			"phoneCode" => 0,
		],
		[
			"id" => 97,
			"initials" => "HN",
			"name" => "Honduras",
			"phoneCode" => 504,
		],
		[
			"id" => 98,
			"initials" => "HK",
			"name" => "Hong Kong S.A.R.",
			"phoneCode" => 852,
		],
		[
			"id" => 99,
			"initials" => "HU",
			"name" => "Hungary",
			"phoneCode" => 36,
		],
		[
			"id" => 100,
			"initials" => "IS",
			"name" => "Iceland",
			"phoneCode" => 354,
		],
		[
			"id" => 101,
			"initials" => "IN",
			"name" => "India",
			"phoneCode" => 91,
		],
		[
			"id" => 102,
			"initials" => "ID",
			"name" => "Indonesia",
			"phoneCode" => 62,
		],
		[
			"id" => 103,
			"initials" => "IR",
			"name" => "Iran",
			"phoneCode" => 98,
		],
		[
			"id" => 104,
			"initials" => "IQ",
			"name" => "Iraq",
			"phoneCode" => 964,
		],
		[
			"id" => 105,
			"initials" => "IE",
			"name" => "Ireland",
			"phoneCode" => 353,
		],
		[
			"id" => 106,
			"initials" => "IL",
			"name" => "Israel",
			"phoneCode" => 972,
		],
		[
			"id" => 107,
			"initials" => "IT",
			"name" => "Italy",
			"phoneCode" => 39,
		],
		[
			"id" => 108,
			"initials" => "JM",
			"name" => "Jamaica",
			"phoneCode" => 1876,
		],
		[
			"id" => 109,
			"initials" => "JP",
			"name" => "Japan",
			"phoneCode" => 81,
		],
		[
			"id" => 110,
			"initials" => "XJ",
			"name" => "Jersey",
			"phoneCode" => 44,
		],
		[
			"id" => 111,
			"initials" => "JO",
			"name" => "Jordan",
			"phoneCode" => 962,
		],
		[
			"id" => 112,
			"initials" => "KZ",
			"name" => "Kazakhstan",
			"phoneCode" => 7,
		],
		[
			"id" => 113,
			"initials" => "KE",
			"name" => "Kenya",
			"phoneCode" => 254,
		],
		[
			"id" => 114,
			"initials" => "KI",
			"name" => "Kiribati",
			"phoneCode" => 686,
		],
		[
			"id" => 115,
			"initials" => "KP",
			"name" => "Korea North",
			"phoneCode" => 850,
		],
		[
			"id" => 116,
			"initials" => "KR",
			"name" => "Korea South",
			"phoneCode" => 82,
		],
		[
			"id" => 117,
			"initials" => "KW",
			"name" => "Kuwait",
			"phoneCode" => 965,
		],
		[
			"id" => 118,
			"initials" => "KG",
			"name" => "Kyrgyzstan",
			"phoneCode" => 996,
		],
		[
			"id" => 119,
			"initials" => "LA",
			"name" => "Laos",
			"phoneCode" => 856,
		],
		[
			"id" => 120,
			"initials" => "LV",
			"name" => "Latvia",
			"phoneCode" => 371,
		],
		[
			"id" => 121,
			"initials" => "LB",
			"name" => "Lebanon",
			"phoneCode" => 961,
		],
		[
			"id" => 122,
			"initials" => "LS",
			"name" => "Lesotho",
			"phoneCode" => 266,
		],
		[
			"id" => 123,
			"initials" => "LR",
			"name" => "Liberia",
			"phoneCode" => 231,
		],
		[
			"id" => 124,
			"initials" => "LY",
			"name" => "Libya",
			"phoneCode" => 218,
		],
		[
			"id" => 125,
			"initials" => "LI",
			"name" => "Liechtenstein",
			"phoneCode" => 423,
		],
		[
			"id" => 126,
			"initials" => "LT",
			"name" => "Lithuania",
			"phoneCode" => 370,
		],
		[
			"id" => 127,
			"initials" => "LU",
			"name" => "Luxembourg",
			"phoneCode" => 352,
		],
		[
			"id" => 128,
			"initials" => "MO",
			"name" => "Macau S.A.R.",
			"phoneCode" => 853,
		],
		[
			"id" => 129,
			"initials" => "MK",
			"name" => "Macedonia",
			"phoneCode" => 389,
		],
		[
			"id" => 130,
			"initials" => "MG",
			"name" => "Madagascar",
			"phoneCode" => 261,
		],
		[
			"id" => 131,
			"initials" => "MW",
			"name" => "Malawi",
			"phoneCode" => 265,
		],
		[
			"id" => 132,
			"initials" => "MY",
			"name" => "Malaysia",
			"phoneCode" => 60,
		],
		[
			"id" => 133,
			"initials" => "MV",
			"name" => "Maldives",
			"phoneCode" => 960,
		],
		[
			"id" => 134,
			"initials" => "ML",
			"name" => "Mali",
			"phoneCode" => 223,
		],
		[
			"id" => 135,
			"initials" => "MT",
			"name" => "Malta",
			"phoneCode" => 356,
		],
		[
			"id" => 136,
			"initials" => "XM",
			"name" => "Man (Isle of)",
			"phoneCode" => 44,
		],
		[
			"id" => 137,
			"initials" => "MH",
			"name" => "Marshall Islands",
			"phoneCode" => 692,
		],
		[
			"id" => 138,
			"initials" => "MQ",
			"name" => "Martinique",
			"phoneCode" => 596,
		],
		[
			"id" => 139,
			"initials" => "MR",
			"name" => "Mauritania",
			"phoneCode" => 222,
		],
		[
			"id" => 140,
			"initials" => "MU",
			"name" => "Mauritius",
			"phoneCode" => 230,
		],
		[
			"id" => 141,
			"initials" => "YT",
			"name" => "Mayotte",
			"phoneCode" => 269,
		],
		[
			"id" => 142,
			"initials" => "MX",
			"name" => "Mexico",
			"phoneCode" => 52,
		],
		[
			"id" => 143,
			"initials" => "FM",
			"name" => "Micronesia",
			"phoneCode" => 691,
		],
		[
			"id" => 144,
			"initials" => "MD",
			"name" => "Moldova",
			"phoneCode" => 373,
		],
		[
			"id" => 145,
			"initials" => "MC",
			"name" => "Monaco",
			"phoneCode" => 377,
		],
		[
			"id" => 146,
			"initials" => "MN",
			"name" => "Mongolia",
			"phoneCode" => 976,
		],
		[
			"id" => 147,
			"initials" => "MS",
			"name" => "Montserrat",
			"phoneCode" => 1664,
		],
		[
			"id" => 148,
			"initials" => "MA",
			"name" => "Morocco",
			"phoneCode" => 212,
		],
		[
			"id" => 149,
			"initials" => "MZ",
			"name" => "Mozambique",
			"phoneCode" => 258,
		],
		[
			"id" => 150,
			"initials" => "MM",
			"name" => "Myanmar",
			"phoneCode" => 95,
		],
		[
			"id" => 151,
			"initials" => "NA",
			"name" => "Namibia",
			"phoneCode" => 264,
		],
		[
			"id" => 152,
			"initials" => "NR",
			"name" => "Nauru",
			"phoneCode" => 674,
		],
		[
			"id" => 153,
			"initials" => "NP",
			"name" => "Nepal",
			"phoneCode" => 977,
		],
		[
			"id" => 154,
			"initials" => "AN",
			"name" => "Netherlands Antilles",
			"phoneCode" => 599,
		],
		[
			"id" => 155,
			"initials" => "NL",
			"name" => "Netherlands The",
			"phoneCode" => 31,
		],
		[
			"id" => 156,
			"initials" => "NC",
			"name" => "New Caledonia",
			"phoneCode" => 687,
		],
		[
			"id" => 157,
			"initials" => "NZ",
			"name" => "New Zealand",
			"phoneCode" => 64,
		],
		[
			"id" => 158,
			"initials" => "NI",
			"name" => "Nicaragua",
			"phoneCode" => 505,
		],
		[
			"id" => 159,
			"initials" => "NE",
			"name" => "Niger",
			"phoneCode" => 227,
		],
		[
			"id" => 160,
			"initials" => "NG",
			"name" => "Nigeria",
			"phoneCode" => 234,
		],
		[
			"id" => 161,
			"initials" => "NU",
			"name" => "Niue",
			"phoneCode" => 683,
		],
		[
			"id" => 162,
			"initials" => "NF",
			"name" => "Norfolk Island",
			"phoneCode" => 672,
		],
		[
			"id" => 163,
			"initials" => "MP",
			"name" => "Northern Mariana Islands",
			"phoneCode" => 1670,
		],
		[
			"id" => 164,
			"initials" => "NO",
			"name" => "Norway",
			"phoneCode" => 47,
		],
		[
			"id" => 165,
			"initials" => "OM",
			"name" => "Oman",
			"phoneCode" => 968,
		],
		[
			"id" => 166,
			"initials" => "PK",
			"name" => "Pakistan",
			"phoneCode" => 92,
		],
		[
			"id" => 167,
			"initials" => "PW",
			"name" => "Palau",
			"phoneCode" => 680,
		],
		[
			"id" => 168,
			"initials" => "PS",
			"name" => "Palestinian Territory Occupied",
			"phoneCode" => 970,
		],
		[
			"id" => 169,
			"initials" => "PA",
			"name" => "Panama",
			"phoneCode" => 507,
		],
		[
			"id" => 170,
			"initials" => "PG",
			"name" => "Papua new Guinea",
			"phoneCode" => 675,
		],
		[
			"id" => 171,
			"initials" => "PY",
			"name" => "Paraguay",
			"phoneCode" => 595,
		],
		[
			"id" => 172,
			"initials" => "PE",
			"name" => "Peru",
			"phoneCode" => 51,
		],
		[
			"id" => 173,
			"initials" => "PH",
			"name" => "Philippines",
			"phoneCode" => 63,
		],
		[
			"id" => 174,
			"initials" => "PN",
			"name" => "Pitcairn Island",
			"phoneCode" => 0,
		],
		[
			"id" => 175,
			"initials" => "PL",
			"name" => "Poland",
			"phoneCode" => 48,
		],
		[
			"id" => 176,
			"initials" => "PT",
			"name" => "Portugal",
			"phoneCode" => 351,
		],
		[
			"id" => 177,
			"initials" => "PR",
			"name" => "Puerto Rico",
			"phoneCode" => 1787,
		],
		[
			"id" => 178,
			"initials" => "QA",
			"name" => "Qatar",
			"phoneCode" => 974,
		],
		[
			"id" => 179,
			"initials" => "RE",
			"name" => "Reunion",
			"phoneCode" => 262,
		],
		[
			"id" => 180,
			"initials" => "RO",
			"name" => "Romania",
			"phoneCode" => 40,
		],
		[
			"id" => 181,
			"initials" => "RU",
			"name" => "Russia",
			"phoneCode" => 70,
		],
		[
			"id" => 182,
			"initials" => "RW",
			"name" => "Rwanda",
			"phoneCode" => 250,
		],
		[
			"id" => 183,
			"initials" => "SH",
			"name" => "Saint Helena",
			"phoneCode" => 290,
		],
		[
			"id" => 184,
			"initials" => "KN",
			"name" => "Saint Kitts And Nevis",
			"phoneCode" => 1869,
		],
		[
			"id" => 185,
			"initials" => "LC",
			"name" => "Saint Lucia",
			"phoneCode" => 1758,
		],
		[
			"id" => 186,
			"initials" => "PM",
			"name" => "Saint Pierre and Miquelon",
			"phoneCode" => 508,
		],
		[
			"id" => 187,
			"initials" => "VC",
			"name" => "Saint Vincent And The Grenadines",
			"phoneCode" => 1784,
		],
		[
			"id" => 188,
			"initials" => "WS",
			"name" => "Samoa",
			"phoneCode" => 684,
		],
		[
			"id" => 189,
			"initials" => "SM",
			"name" => "San Marino",
			"phoneCode" => 378,
		],
		[
			"id" => 190,
			"initials" => "ST",
			"name" => "Sao Tome and Principe",
			"phoneCode" => 239,
		],
		[
			"id" => 191,
			"initials" => "SA",
			"name" => "Saudi Arabia",
			"phoneCode" => 966,
		],
		[
			"id" => 192,
			"initials" => "SN",
			"name" => "Senegal",
			"phoneCode" => 221,
		],
		[
			"id" => 193,
			"initials" => "RS",
			"name" => "Serbia",
			"phoneCode" => 381,
		],
		[
			"id" => 194,
			"initials" => "SC",
			"name" => "Seychelles",
			"phoneCode" => 248,
		],
		[
			"id" => 195,
			"initials" => "SL",
			"name" => "Sierra Leone",
			"phoneCode" => 232,
		],
		[
			"id" => 196,
			"initials" => "SG",
			"name" => "Singapore",
			"phoneCode" => 65,
		],
		[
			"id" => 197,
			"initials" => "SK",
			"name" => "Slovakia",
			"phoneCode" => 421,
		],
		[
			"id" => 198,
			"initials" => "SI",
			"name" => "Slovenia",
			"phoneCode" => 386,
		],
		[
			"id" => 199,
			"initials" => "XG",
			"name" => "Smaller Territories of the UK",
			"phoneCode" => 44,
		],
		[
			"id" => 200,
			"initials" => "SB",
			"name" => "Solomon Islands",
			"phoneCode" => 677,
		],
		[
			"id" => 201,
			"initials" => "SO",
			"name" => "Somalia",
			"phoneCode" => 252,
		],
		[
			"id" => 202,
			"initials" => "ZA",
			"name" => "South Africa",
			"phoneCode" => 27,
		],
		[
			"id" => 203,
			"initials" => "GS",
			"name" => "South Georgia",
			"phoneCode" => 0,
		],
		[
			"id" => 204,
			"initials" => "SS",
			"name" => "South Sudan",
			"phoneCode" => 211,
		],
		[
			"id" => 205,
			"initials" => "ES",
			"name" => "Spain",
			"phoneCode" => 34,
		],
		[
			"id" => 206,
			"initials" => "LK",
			"name" => "Sri Lanka",
			"phoneCode" => 94,
		],
		[
			"id" => 207,
			"initials" => "SD",
			"name" => "Sudan",
			"phoneCode" => 249,
		],
		[
			"id" => 208,
			"initials" => "SR",
			"name" => "Suriname",
			"phoneCode" => 597,
		],
		[
			"id" => 209,
			"initials" => "SJ",
			"name" => "Svalbard And Jan Mayen Islands",
			"phoneCode" => 47,
		],
		[
			"id" => 210,
			"initials" => "SZ",
			"name" => "Swaziland",
			"phoneCode" => 268,
		],
		[
			"id" => 211,
			"initials" => "SE",
			"name" => "Sweden",
			"phoneCode" => 46,
		],
		[
			"id" => 212,
			"initials" => "CH",
			"name" => "Switzerland",
			"phoneCode" => 41,
		],
		[
			"id" => 213,
			"initials" => "SY",
			"name" => "Syria",
			"phoneCode" => 963,
		],
		[
			"id" => 214,
			"initials" => "TW",
			"name" => "Taiwan",
			"phoneCode" => 886,
		],
		[
			"id" => 215,
			"initials" => "TJ",
			"name" => "Tajikistan",
			"phoneCode" => 992,
		],
		[
			"id" => 216,
			"initials" => "TZ",
			"name" => "Tanzania",
			"phoneCode" => 255,
		],
		[
			"id" => 217,
			"initials" => "TH",
			"name" => "Thailand",
			"phoneCode" => 66,
		],
		[
			"id" => 218,
			"initials" => "TG",
			"name" => "Togo",
			"phoneCode" => 228,
		],
		[
			"id" => 219,
			"initials" => "TK",
			"name" => "Tokelau",
			"phoneCode" => 690,
		],
		[
			"id" => 220,
			"initials" => "TO",
			"name" => "Tonga",
			"phoneCode" => 676,
		],
		[
			"id" => 221,
			"initials" => "TT",
			"name" => "Trinidad And Tobago",
			"phoneCode" => 1868,
		],
		[
			"id" => 222,
			"initials" => "TN",
			"name" => "Tunisia",
			"phoneCode" => 216,
		],
		[
			"id" => 223,
			"initials" => "TR",
			"name" => "Turkey",
			"phoneCode" => 90,
		],
		[
			"id" => 224,
			"initials" => "TM",
			"name" => "Turkmenistan",
			"phoneCode" => 7370,
		],
		[
			"id" => 225,
			"initials" => "TC",
			"name" => "Turks And Caicos Islands",
			"phoneCode" => 1649,
		],
		[
			"id" => 226,
			"initials" => "TV",
			"name" => "Tuvalu",
			"phoneCode" => 688,
		],
		[
			"id" => 227,
			"initials" => "UG",
			"name" => "Uganda",
			"phoneCode" => 256,
		],
		[
			"id" => 228,
			"initials" => "UA",
			"name" => "Ukraine",
			"phoneCode" => 380,
		],
		[
			"id" => 229,
			"initials" => "AE",
			"name" => "United Arab Emirates",
			"phoneCode" => 971,
		],
		[
			"id" => 230,
			"initials" => "GB",
			"name" => "United Kingdom",
			"phoneCode" => 44,
		],
		[
			"id" => 231,
			"initials" => "US",
			"name" => "United States",
			"phoneCode" => 1,
		],
		[
			"id" => 232,
			"initials" => "UM",
			"name" => "United States Minor Outlying Islands",
			"phoneCode" => 1,
		],
		[
			"id" => 233,
			"initials" => "UY",
			"name" => "Uruguay",
			"phoneCode" => 598,
		],
		[
			"id" => 234,
			"initials" => "UZ",
			"name" => "Uzbekistan",
			"phoneCode" => 998,
		],
		[
			"id" => 235,
			"initials" => "VU",
			"name" => "Vanuatu",
			"phoneCode" => 678,
		],
		[
			"id" => 236,
			"initials" => "VA",
			"name" => "Vatican City State (Holy See)",
			"phoneCode" => 39,
		],
		[
			"id" => 237,
			"initials" => "VE",
			"name" => "Venezuela",
			"phoneCode" => 58,
		],
		[
			"id" => 238,
			"initials" => "VN",
			"name" => "Vietnam",
			"phoneCode" => 84,
		],
		[
			"id" => 239,
			"initials" => "VG",
			"name" => "Virgin Islands (British)",
			"phoneCode" => 1284,
		],
		[
			"id" => 240,
			"initials" => "VI",
			"name" => "Virgin Islands (US)",
			"phoneCode" => 1340,
		],
		[
			"id" => 241,
			"initials" => "WF",
			"name" => "Wallis And Futuna Islands",
			"phoneCode" => 681,
		],
		[
			"id" => 242,
			"initials" => "EH",
			"name" => "Western Sahara",
			"phoneCode" => 212,
		],
		[
			"id" => 243,
			"initials" => "YE",
			"name" => "Yemen",
			"phoneCode" => 967,
		],
		[
			"id" => 244,
			"initials" => "YU",
			"name" => "Yugoslavia",
			"phoneCode" => 38,
		],
		[
			"id" => 245,
			"initials" => "ZM",
			"name" => "Zambia",
			"phoneCode" => 260,
		],
		[
			"id" => 246,
			"initials" => "ZW",
			"name" => "Zimbabwe",
			"phoneCode" => 263,
		],
	];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		Country::truncate();
		Arrays::each($this->countries, fn($country) => Country::create($country));
	}
}