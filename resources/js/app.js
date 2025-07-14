import "./bootstrap"
import "./image-paste"

// Robust dark mode implementation with defensive checks
document.addEventListener("DOMContentLoaded", function () {
	// Add preload class to prevent transitions during initial load
	document.body.classList.add("preload")

	// Initialize theme first
	initTheme()

	// Setup components only if they exist
	setupThemeToggles()
	setupNavigation()

	// Remove preload class after a short delay to allow transitions
	setTimeout(() => {
		document.body.classList.remove("preload")
	}, 100)
})

function initTheme() {
	try {
		// Check for saved theme preference or respect OS preference
		if (localStorage.theme === "dark" || (!("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
			document.documentElement.classList.add("dark")
		} else {
			document.documentElement.classList.remove("dark")
		}

		// Update icons only if elements exist
		updateThemeToggleIcon()
	} catch (error) {
		console.warn("Theme initialization failed:", error)
	}
}

function setupThemeToggles() {
	try {
		// Desktop theme toggle
		const themeToggle = document.getElementById("theme-toggle")
		if (themeToggle) {
			themeToggle.addEventListener("click", function (e) {
				e.preventDefault()
				toggleTheme()
			})
		}

		// Mobile theme toggle
		const mobileThemeToggle = document.getElementById("mobile-theme-toggle")
		if (mobileThemeToggle) {
			mobileThemeToggle.addEventListener("click", function (e) {
				e.preventDefault()
				toggleTheme()
			})
		}
	} catch (error) {
		console.warn("Theme toggle setup failed:", error)
	}
}

function toggleTheme() {
	try {
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
	} catch (error) {
		console.warn("Theme toggle failed:", error)
	}
}

function updateThemeToggleIcon() {
	try {
		// Desktop theme toggle icons - only update if they exist
		const sunIcon = document.getElementById("theme-toggle-light-icon")
		const moonIcon = document.getElementById("theme-toggle-dark-icon")

		// Mobile theme toggle icons - only update if they exist
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
	} catch (error) {
		console.warn("Theme icon update failed:", error)
	}
}

function setupNavigation() {
	try {
		// User menu dropdown - only setup if elements exist
		const userMenuButton = document.getElementById("user-menu-button")
		const userMenuDropdown = document.getElementById("user-menu-dropdown")

		if (userMenuButton && userMenuDropdown) {
			userMenuButton.addEventListener("click", function (e) {
				e.preventDefault()
				e.stopPropagation()
				userMenuDropdown.classList.toggle("hidden")
			})

			// Close dropdown when clicking outside
			document.addEventListener("click", function (e) {
				if (!userMenuButton.contains(e.target) && !userMenuDropdown.contains(e.target)) {
					userMenuDropdown.classList.add("hidden")
				}
			})

			// Close dropdown when pressing escape
			document.addEventListener("keydown", function (e) {
				if (e.key === "Escape") {
					userMenuDropdown.classList.add("hidden")
				}
			})
		}

		// Mobile menu - only setup if elements exist
		const mobileMenuButton = document.getElementById("mobile-menu-button")
		const mobileMenu = document.getElementById("mobile-menu")
		const mobileMenuIconOpen = document.getElementById("mobile-menu-icon-open")
		const mobileMenuIconClose = document.getElementById("mobile-menu-icon-close")

		if (mobileMenuButton && mobileMenu && mobileMenuIconOpen && mobileMenuIconClose) {
			mobileMenuButton.addEventListener("click", function () {
				mobileMenu.classList.toggle("hidden")
				mobileMenuIconOpen.classList.toggle("hidden")
				mobileMenuIconClose.classList.toggle("hidden")
			})
		}
	} catch (error) {
		console.warn("Navigation setup failed:", error)
	}
}

// Utility functions
window.showNotification = function (message, type = "success") {
	try {
		const notification = document.createElement("div")
		notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${type === "success" ? "bg-green-500 text-white" : type === "error" ? "bg-red-500 text-white" : "bg-blue-500 text-white"}`
		notification.textContent = message

		document.body.appendChild(notification)

		setTimeout(() => {
			if (notification.parentNode) {
				notification.remove()
			}
		}, 3000)
	} catch (error) {
		console.warn("Notification failed:", error)
	}
}

// Dynamic assignee dropdown for task forms
document.addEventListener("DOMContentLoaded", () => {
	try {
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
				}
			})
		}
	} catch (error) {
		console.warn("Project selector setup failed:", error)
	}
})

document.addEventListener("DOMContentLoaded", () => {
	const saveFilterBtn = document.getElementById("save-filter-btn")
	if (saveFilterBtn) {
		saveFilterBtn.addEventListener("click", () => {
			const filterName = prompt("Enter a name for this filter view:")
			if (filterName) {
				const form = document.getElementById("filter-form")
				const formData = new FormData(form)
				const filters = Object.fromEntries(formData.entries())

				fetch("/filters/save", {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
						"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
					},
					body: JSON.stringify({
						name: filterName,
						filters: filters,
					}),
				})
					.then((response) => response.json())
					.then((data) => {
						if (data.success) {
							window.location.reload()
						} else {
							alert(data.message || "Could not save filter.")
						}
					})
					.catch((error) => {
						console.error("Error:", error)
						alert("An error occurred while saving the filter.")
					})
			}
		})
	}
})

document.addEventListener("DOMContentLoaded", () => {
	const savedFiltersList = document.getElementById("saved-filters-list")
	if (savedFiltersList) {
		savedFiltersList.addEventListener("click", (e) => {
			if (e.target.classList.contains("delete-filter-btn")) {
				const filterId = e.target.getAttribute("data-filter-id")
				if (confirm("Are you sure you want to delete this filter?")) {
					fetch(`/filters/${filterId}`, {
						method: "DELETE",
						headers: {
							"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
						},
					})
						.then((response) => response.json())
						.then((data) => {
							if (data.success) {
								e.target.closest(".flex.items-center").remove()
							} else {
								alert(data.message || "Could not delete filter.")
							}
						})
						.catch((error) => {
							console.error("Error:", error)
							alert("An error occurred while deleting the filter.")
						})
				}
			}
		})
	}
})

// Tag autocomplete for personal notes
function setupTagAutocomplete() {
	const tagsInput = document.getElementById("tags")
	if (!tagsInput) return

	// Create dropdown
	const dropdown = document.createElement("div")
	dropdown.className = "autocomplete-dropdown bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded shadow-lg absolute z-50 hidden"
	dropdown.style.minWidth = "200px"
	dropdown.style.maxHeight = "180px"
	dropdown.style.overflowY = "auto"
	dropdown.style.top = "100%"
	dropdown.style.left = "0"
	dropdown.style.right = "0"
	dropdown.style.marginTop = "2px"
	tagsInput.parentNode.style.position = "relative"
	tagsInput.parentNode.appendChild(dropdown)

	let allTags = []
	let currentSuggestions = []
	let selectedIndex = -1

	// Fetch all tags once at page load
	async function loadAllTags() {
		try {
			const resp = await fetch("/api/personal-notes/tag-suggestions")
			if (!resp.ok) throw new Error("Failed to fetch tags")
			const tags = await resp.json()
			allTags = tags.map((t) => t.value)
		} catch (err) {
			console.warn("Failed to load tags:", err)
			allTags = []
		}
	}

	function closeDropdown() {
		dropdown.classList.add("hidden")
		dropdown.innerHTML = ""
		selectedIndex = -1
	}

	function openDropdown() {
		dropdown.classList.remove("hidden")
	}

	function getEnteredTags() {
		const val = tagsInput.value
		return val
			.split(",")
			.map((t) => t.trim().toLowerCase())
			.filter(Boolean)
	}

	function getCurrentTag() {
		const val = tagsInput.value
		const parts = val.split(",")
		return parts[parts.length - 1].trim()
	}

	function getAvailableTags(query = "") {
		const entered = getEnteredTags()
		return allTags.filter((tag) => {
			const tagLower = tag.toLowerCase()
			const queryLower = query.toLowerCase()
			return !entered.includes(tagLower) && (query === "" || tagLower.includes(queryLower))
		})
	}

	function updateDropdown(suggestions) {
		dropdown.innerHTML = ""
		if (!suggestions.length) {
			closeDropdown()
			return
		}

		currentSuggestions = suggestions
		suggestions.forEach((tag, idx) => {
			const option = document.createElement("div")
			option.className = "px-3 py-2 cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100"
			option.textContent = tag
			option.addEventListener("mousedown", (e) => {
				e.preventDefault()
				selectSuggestion(idx)
			})
			dropdown.appendChild(option)
		})
		openDropdown()
	}

	function selectSuggestion(idx) {
		const selectedTag = currentSuggestions[idx]
		const val = tagsInput.value
		const parts = val.split(",")

		// Replace the current partial tag with selected tag
		parts[parts.length - 1] = selectedTag

		// Join with commas and add trailing comma + space for next tag
		tagsInput.value = parts.join(", ") + ", "

		closeDropdown()
		tagsInput.focus()

		// Show remaining available tags immediately
		setTimeout(() => {
			const availableTags = getAvailableTags()
			if (availableTags.length > 0) {
				updateDropdown(availableTags.slice(0, 10)) // Show max 10 suggestions
			}
		}, 50)
	}

	function handleInput() {
		const currentTag = getCurrentTag()

		if (currentTag === "") {
			// Show all available tags when no current input
			const availableTags = getAvailableTags()
			updateDropdown(availableTags.slice(0, 10))
		} else {
			// Filter tags based on current input
			const availableTags = getAvailableTags(currentTag)
			updateDropdown(availableTags.slice(0, 10))
		}
	}

	tagsInput.addEventListener("input", handleInput)

	tagsInput.addEventListener("focus", () => {
		// Show suggestions when focusing on input
		handleInput()
	})

	tagsInput.addEventListener("keydown", function (e) {
		if (dropdown.classList.contains("hidden")) return

		if (e.key === "ArrowDown") {
			e.preventDefault()
			selectedIndex = Math.min(selectedIndex + 1, currentSuggestions.length - 1)
			updateHighlight()
		} else if (e.key === "ArrowUp") {
			e.preventDefault()
			selectedIndex = Math.max(selectedIndex - 1, -1)
			updateHighlight()
		} else if (e.key === "Enter" || e.key === "Tab") {
			if (selectedIndex >= 0 && currentSuggestions[selectedIndex]) {
				e.preventDefault()
				selectSuggestion(selectedIndex)
			} else {
				closeDropdown()
			}
		} else if (e.key === "Escape") {
			closeDropdown()
		}
	})

	function updateHighlight() {
		Array.from(dropdown.children).forEach((el, idx) => {
			if (idx === selectedIndex) {
				el.classList.add("bg-blue-200", "dark:bg-gray-600")
			} else {
				el.classList.remove("bg-blue-200", "dark:bg-gray-600")
			}
		})
	}

	document.addEventListener("click", function (e) {
		if (!dropdown.contains(e.target) && e.target !== tagsInput) {
			closeDropdown()
		}
	})

	// Load all tags when setting up
	loadAllTags()
}

document.addEventListener("DOMContentLoaded", setupTagAutocomplete)
