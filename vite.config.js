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
		host: "0.0.0.0",
		port: 5173,
		hmr: {
			host: "0.0.0.0", // Changed from localhost to 0.0.0.0 for mobile access
			port: 5173,
		},
		cors: {
			origin: true,
			credentials: true,
		},
		// Add watch options for better file watching on network
		watch: {
			usePolling: true,
			interval: 100,
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
