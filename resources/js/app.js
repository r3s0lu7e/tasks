import "./bootstrap"

import Alpine from "alpinejs"

window.Alpine = Alpine

Alpine.start()

// Dark Mode Toggle
document.addEventListener("DOMContentLoaded", () => {
	// Check for saved theme preference or respect OS preference
	if (localStorage.theme === "dark" || (!("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
		document.documentElement.classList.add("dark")
	} else {
		document.documentElement.classList.remove("dark")
	}

	// Add event listener for theme toggle button
	const themeToggleBtn = document.getElementById("theme-toggle")
	if (themeToggleBtn) {
		themeToggleBtn.addEventListener("click", () => {
			// Toggle theme
			if (document.documentElement.classList.contains("dark")) {
				document.documentElement.classList.remove("dark")
				localStorage.theme = "light"
			} else {
				document.documentElement.classList.add("dark")
				localStorage.theme = "dark"
			}

			// Update button icon
			updateThemeToggleIcon()
		})

		// Set initial icon state
		updateThemeToggleIcon()
	}
})

function updateThemeToggleIcon() {
	const themeToggleBtn = document.getElementById("theme-toggle")
	const sunIcon = document.getElementById("theme-toggle-light-icon")
	const moonIcon = document.getElementById("theme-toggle-dark-icon")

	if (document.documentElement.classList.contains("dark")) {
		sunIcon.classList.remove("hidden")
		moonIcon.classList.add("hidden")
	} else {
		sunIcon.classList.add("hidden")
		moonIcon.classList.remove("hidden")
	}
}

// Custom Alpine.js components
Alpine.data("taskBoard", () => ({
	tasks: [],
	showTaskModal: false,
	selectedTask: null,

	init() {
		// Initialize task board
	},

	openTaskModal(task = null) {
		this.selectedTask = task
		this.showTaskModal = true
	},

	closeTaskModal() {
		this.showTaskModal = false
		this.selectedTask = null
	},

	updateTaskStatus(taskId, newStatus) {
		// Update task status via API
		fetch(`/tasks/${taskId}/status`, {
			method: "PATCH",
			headers: {
				"Content-Type": "application/json",
				"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
			},
			body: JSON.stringify({ status: newStatus }),
		})
			.then((response) => response.json())
			.then((data) => {
				if (data.success) {
					// Update task in the UI
					this.tasks = this.tasks.map((task) => (task.id === taskId ? { ...task, status: newStatus } : task))
				}
			})
	},
}))

Alpine.data("projectSelector", () => ({
	projects: [],
	selectedProject: null,

	init() {
		// Load projects
	},

	selectProject(project) {
		this.selectedProject = project
		// Navigate to project or update view
	},
}))

// Utility functions
window.showNotification = function (message, type = "success") {
	const notification = document.createElement("div")
	notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${type === "success" ? "bg-green-500 text-white" : type === "error" ? "bg-red-500 text-white" : "bg-blue-500 text-white"}`
	notification.textContent = message

	document.body.appendChild(notification)

	setTimeout(() => {
		notification.remove()
	}, 3000)
}
