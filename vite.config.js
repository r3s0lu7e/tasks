import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"

export default defineConfig({
	plugins: [
		laravel({
			input: ["resources/css/app.css", "resources/js/app.js", "resources/js/icon-picker.js"],
			refresh: true,
		}),
	],
	server: {
		host: "0.0.0.0",
		port: 5173,
		hmr: {
			// For ngrok, this should be your ngrok URL without https://
			// Update this when you get your ngrok URL for port 5173
			host: process.env.VITE_HMR_HOST || "localhost",
			protocol: process.env.VITE_HMR_PROTOCOL || "ws",
			port: process.env.VITE_HMR_PORT || 5173,
		},
		cors: {
			origin: true,
			credentials: true,
		},
	},
	build: {
		outDir: "public/build",
		emptyOutDir: true,
		manifest: true,
		rollupOptions: {
			output: {
				manualChunks: undefined,
			},
		},
	},
})
