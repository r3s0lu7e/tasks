/** @type {import('tailwindcss').Config} */
export default {
	content: ["./resources/**/*.blade.php", "./resources/**/*.js", "./resources/**/*.vue"],
	theme: {
		extend: {
			colors: {
				"jira-blue": "#0052CC",
				"jira-green": "#36B37E",
				"jira-red": "#DE350B",
				"jira-yellow": "#FFAB00",
				"jira-purple": "#6554C0",
			},
		},
	},
	plugins: [require("@tailwindcss/forms")],
}
