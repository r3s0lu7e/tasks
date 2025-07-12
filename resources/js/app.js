import "./bootstrap"
import "./image-paste"

import Alpine from "alpinejs"

window.Alpine = Alpine

Alpine.start()

// Dark Mode Toggle
function initTheme() {
	// Check for saved theme preference or respect OS preference
	if (localStorage.theme === "dark" || (!("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
		document.documentElement.classList.add("dark")
	} else {
		document.documentElement.classList.remove("dark")
	}

	// Set initial icon state for both buttons
	updateThemeToggleIcon()
}

// Initialize theme immediately
initTheme()

document.addEventListener("DOMContentLoaded", () => {
	// Re-check icons after DOM is loaded
	updateThemeToggleIcon()
})

// Also update theme icons when Alpine.js initializes
document.addEventListener("alpine:init", () => {
	updateThemeToggleIcon()
})

// Use event delegation for both desktop and mobile theme toggle buttons
// This ensures the event listeners work even when elements are dynamically shown/hidden
document.addEventListener("click", (e) => {
	// Check if the clicked element is a theme toggle button (desktop or mobile)
	if (e.target.closest("#theme-toggle") || e.target.closest("#mobile-theme-toggle")) {
		e.preventDefault()
		toggleTheme()
	}
})

function toggleTheme() {
	// Toggle theme
	if (document.documentElement.classList.contains("dark")) {
		document.documentElement.classList.remove("dark")
		localStorage.theme = "light"
	} else {
		document.documentElement.classList.add("dark")
		localStorage.theme = "dark"
	}

	// Update button icons
	updateThemeToggleIcon()
}

function updateThemeToggleIcon() {
	// Desktop theme toggle icons
	const themeToggleBtn = document.getElementById("theme-toggle")
	const sunIcon = document.getElementById("theme-toggle-light-icon")
	const moonIcon = document.getElementById("theme-toggle-dark-icon")

	// Mobile theme toggle icons
	const mobileSunIcon = document.getElementById("mobile-theme-toggle-light-icon")
	const mobileMoonIcon = document.getElementById("mobile-theme-toggle-dark-icon")

	const isDark = document.documentElement.classList.contains("dark")

	if (isDark) {
		// Show sun icon (light mode button)
		if (sunIcon) sunIcon.classList.remove("hidden")
		if (moonIcon) moonIcon.classList.add("hidden")
		if (mobileSunIcon) mobileSunIcon.classList.remove("hidden")
		if (mobileMoonIcon) mobileMoonIcon.classList.add("hidden")
	} else {
		// Show moon icon (dark mode button)
		if (sunIcon) sunIcon.classList.add("hidden")
		if (moonIcon) moonIcon.classList.remove("hidden")
		if (mobileSunIcon) mobileSunIcon.classList.add("hidden")
		if (mobileMoonIcon) mobileMoonIcon.classList.remove("hidden")
	}
}

// Observe changes to the mobile menu visibility to update icons when it becomes visible
const observer = new MutationObserver(() => {
	updateThemeToggleIcon()
})

// Start observing when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
	// Observe changes to elements that might affect mobile menu visibility
	const mobileMenu = document.querySelector('[x-show="mobileMenuOpen"]')
	if (mobileMenu) {
		observer.observe(mobileMenu, {
			attributes: true,
			attributeFilter: ["style", "class"],
		})
	}
})

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

// Dynamic assignee dropdown for task forms
document.addEventListener("DOMContentLoaded", () => {
	const projectSelect = document.getElementById("project_id")
	const assigneeSelect = document.getElementById("assignee_id")

	if (projectSelect && assigneeSelect) {
		projectSelect.addEventListener("change", async (e) => {
			const projectId = e.target.value

			// Clear current assignee options
			assigneeSelect.innerHTML = '<option value="">Unassigned</option>'

			if (!projectId) {
				return
			}

			try {
				const response = await fetch(`/api/projects/${projectId}/members`)
				if (!response.ok) {
					throw new Error("Failed to fetch project members")
				}

				const members = await response.json()

				// Add project members to assignee dropdown
				members.forEach((member) => {
					const option = document.createElement("option")
					option.value = member.id
					option.textContent = `${member.name} (${member.department || member.role})`
					assigneeSelect.appendChild(option)
				})
			} catch (error) {
				console.error("Error fetching project members:", error)
				window.showNotification("Failed to load project members", "error")
			}
		})
	}
})
