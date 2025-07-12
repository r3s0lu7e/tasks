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
		host: "localhost",
		port: 5173,
		hmr: {
			host: "localhost",
		},
		cors: true,
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
