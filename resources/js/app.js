import "./bootstrap"

import Alpine from "alpinejs"

window.Alpine = Alpine

Alpine.start()

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
