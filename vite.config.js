import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"

export default defineConfig({
	plugins: [
		laravel({
			input: ["resources/css/app.css", "resources/js/app.js"],
			refresh: true,
		}),
	],
	server: {
		host: "0.0.0.0", // Allow external connections
		port: 5173, // Default Vite port
		hmr: {
			host: "192.168.31.244", // Replace with your actual IP
		},
	},
})
