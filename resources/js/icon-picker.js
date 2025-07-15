// resources/js/icon-picker.js

window.initIconPicker = function ({ iconInputId, iconDropdownId, iconGridId, iconSearchId, iconColorInputId, backgroundColorInputId }) {
	const iconInput = document.getElementById(iconInputId)
	const iconDropdown = document.getElementById(iconDropdownId)
	const iconGrid = document.getElementById(iconGridId)
	const iconSearch = document.getElementById(iconSearchId)
	const iconColorInput = document.getElementById(iconColorInputId)
	const backgroundColorInput = document.getElementById(backgroundColorInputId)

	if (!iconInput || !iconDropdown || !iconGrid || !iconSearch || !iconColorInput || !backgroundColorInput) {
		console.error("Icon picker elements not found")
		return
	}

	const allIcons = [
		"fa-bug",
		"fa-tasks",
		"fa-check-square",
		"fa-exclamation-triangle",
		"fa-lightbulb",
		"fa-code",
		"fa-pencil",
		"fa-trash",
		"fa-save",
		"fa-edit",
		"fa-plus",
		"fa-minus",
		"fa-star",
		"fa-heart",
		"fa-thumbs-up",
		"fa-thumbs-down",
		"fa-eye",
		"fa-eye-slash",
		"fa-lock",
		"fa-unlock",
		"fa-key",
		"fa-user",
		"fa-users",
		"fa-user-plus",
		"fa-user-minus",
		"fa-calendar",
		"fa-clock",
		"fa-calendar-plus",
		"fa-calendar-minus",
		"fa-calendar-check",
		"fa-folder",
		"fa-folder-open",
		"fa-file",
		"fa-file-alt",
		"fa-file-code",
		"fa-file-image",
		"fa-download",
		"fa-upload",
		"fa-link",
		"fa-unlink",
		"fa-external-link-alt",
		"fa-home",
		"fa-cog",
		"fa-wrench",
		"fa-tools",
		"fa-screwdriver",
		"fa-hammer",
		"fa-search",
		"fa-filter",
		"fa-sort",
		"fa-sort-up",
		"fa-sort-down",
		"fa-arrow-up",
		"fa-arrow-down",
		"fa-arrow-left",
		"fa-arrow-right",
		"fa-chevron-up",
		"fa-chevron-down",
		"fa-chevron-left",
		"fa-chevron-right",
		"fa-caret-up",
		"fa-caret-down",
		"fa-caret-left",
		"fa-caret-right",
		"fa-play",
		"fa-pause",
		"fa-stop",
		"fa-forward",
		"fa-backward",
		"fa-volume-up",
		"fa-volume-down",
		"fa-volume-mute",
		"fa-volume-off",
		"fa-image",
		"fa-video",
		"fa-music",
		"fa-film",
		"fa-camera",
		"fa-phone",
		"fa-envelope",
		"fa-comment",
		"fa-comments",
		"fa-reply",
		"fa-share",
		"fa-retweet",
		"fa-bookmark",
		"fa-flag",
		"fa-bell",
		"fa-bell-slash",
		"fa-gift",
		"fa-trophy",
		"fa-medal",
		"fa-fire",
		"fa-bolt",
		"fa-sun",
		"fa-moon",
		"fa-cloud",
		"fa-umbrella",
		"fa-leaf",
		"fa-tree",
		"fa-seedling",
		"fa-apple-alt",
		"fa-carrot",
		"fa-coffee",
		"fa-utensils",
		"fa-pizza-slice",
		"fa-hamburger",
		"fa-car",
		"fa-plane",
		"fa-ship",
		"fa-train",
		"fa-bicycle",
		"fa-walking",
		"fa-running",
		"fa-dumbbell",
		"fa-futbol",
		"fa-basketball-ball",
		"fa-baseball-ball",
		"fa-volleyball-ball",
		"fa-table-tennis",
		"fa-gamepad",
		"fa-chess",
		"fa-puzzle-piece",
		"fa-dice",
		"fa-dice-d6",
		"fa-dice-d20",
		"fa-ghost",
		"fa-dragon",
		"fa-magic",
		"fa-wand-magic-sparkles",
		"fa-crown",
		"fa-gem",
		"fa-coins",
		"fa-dollar-sign",
		"fa-euro-sign",
		"fa-pound-sign",
		"fa-yen-sign",
		"fa-ruble-sign",
		"fa-bitcoin",
		"fa-chart-line",
		"fa-chart-bar",
		"fa-chart-pie",
		"fa-chart-area",
		"fa-percentage",
		"fa-calculator",
		"fa-abacus",
		"fa-microscope",
		"fa-flask",
		"fa-atom",
		"fa-dna",
		"fa-virus",
		"fa-bacteria",
		"fa-brain",
		"fa-heartbeat",
		"fa-lungs",
		"fa-stethoscope",
		"fa-user-md",
		"fa-hospital",
		"fa-clinic-medical",
		"fa-pills",
		"fa-syringe",
		"fa-thermometer-half",
		"fa-thermometer-full",
		"fa-thermometer-empty",
		"fa-weight",
		"fa-ruler",
		"fa-tape",
		"fa-compass",
		"fa-map",
		"fa-map-marker-alt",
		"fa-map-pin",
		"fa-location-arrow",
		"fa-crosshairs",
		"fa-globe",
		"fa-globe-americas",
		"fa-globe-europe",
		"fa-globe-asia",
		"fa-language",
		"fa-translate",
		"fa-spell-check",
		"fa-font",
		"fa-bold",
		"fa-italic",
		"fa-underline",
		"fa-strikethrough",
		"fa-align-left",
		"fa-align-center",
		"fa-align-right",
		"fa-align-justify",
		"fa-list",
		"fa-list-ol",
		"fa-list-ul",
		"fa-indent",
		"fa-outdent",
		"fa-quote-left",
		"fa-quote-right",
		"fa-paragraph",
		"fa-heading",
		"fa-table",
		"fa-columns",
		"fa-sort-numeric-down",
		"fa-sort-numeric-up",
		"fa-sort-alpha-down",
		"fa-sort-alpha-up",
		"fa-sort-amount-down",
		"fa-sort-amount-up",
		"fa-random",
		"fa-shuffle",
		"fa-redo",
		"fa-undo",
		"fa-reply-all",
		"fa-forward",
		"fa-share-alt",
		"fa-expand",
		"fa-compress",
		"fa-expand-arrows-alt",
		"fa-compress-arrows-alt",
		"fa-expand-alt",
		"fa-compress-alt",
		"fa-arrows-alt",
		"fa-arrows-alt-h",
		"fa-arrows-alt-v",
		"fa-mouse-pointer",
		"fa-hand-pointer",
		"fa-hand-paper",
		"fa-hand-rock",
		"fa-hand-scissors",
		"fa-hand-lizard",
		"fa-hand-spock",
		"fa-hand-holding",
		"fa-hand-holding-heart",
		"fa-hand-holding-usd",
		"fa-hands-helping",
		"fa-hands",
		"fa-praying-hands",
		"fa-handshake",
		"fa-point-up",
		"fa-point-down",
		"fa-point-left",
		"fa-point-right",
		"fa-fingerprint",
		"fa-id-card",
		"fa-id-badge",
		"fa-address-card",
		"fa-address-book",
		"fa-user-tie",
		"fa-user-graduate",
		"fa-user-ninja",
		"fa-user-astronaut",
		"fa-user-shield",
		"fa-user-secret",
		"fa-user-slash",
		"fa-user-times",
		"fa-user-check",
		"fa-user-clock",
		"fa-user-cog",
		"fa-user-edit",
		"fa-user-friends",
		"fa-user-tag",
		"fa-user-lock",
		"fa-user-headset",
		"fa-user-hard-hat",
		"fa-user-construction",
		"fa-user-doctor",
		"fa-user-nurse",
		"fa-user-injured",
		"fa-user-alt",
		"fa-user-alt-slash",
		"fa-user-circle",
		"fa-users-cog",
	]
	let filteredIcons = allIcons

	function getReadableTextColor(hexcolor) {
		hexcolor = hexcolor.replace("#", "")
		const r = parseInt(hexcolor.substr(0, 2), 16)
		const g = parseInt(hexcolor.substr(2, 2), 16)
		const b = parseInt(hexcolor.substr(4, 2), 16)
		const yiq = (r * 299 + g * 587 + b * 114) / 1000
		return yiq >= 128 ? "black" : "white"
	}

	function displayIcons() {
		const selectedIconColor = iconColorInput.value
		const selectedBackgroundColor = backgroundColorInput.value
		const iconNameColor = getReadableTextColor(selectedBackgroundColor)
		iconGrid.innerHTML = ""
		filteredIcons.forEach((iconName) => {
			const iconDiv = document.createElement("div")
			iconDiv.className = "flex flex-col items-center justify-center p-2 border border-gray-200 dark:border-gray-600 rounded cursor-pointer hover:opacity-80 transition-opacity"
			iconDiv.style.backgroundColor = selectedBackgroundColor

			iconDiv.innerHTML = `
                <i class="fa-solid ${iconName} text-lg mb-1" style="color: ${selectedIconColor}"></i>
                <span class="text-xs text-center" style="color: ${iconNameColor}">${iconName}</span>
            `
			iconDiv.addEventListener("click", () => {
				iconInput.value = iconName
				iconInput.focus()
			})
			iconGrid.appendChild(iconDiv)
		})
	}

	iconColorInput.addEventListener("input", displayIcons)
	backgroundColorInput.addEventListener("input", displayIcons)

	iconSearch.addEventListener("input", function () {
		const searchTerm = this.value.toLowerCase()
		filteredIcons = allIcons.filter((icon) => icon.toLowerCase().includes(searchTerm))
		displayIcons()
	})

	iconInput.addEventListener("input", function () {
		const searchTerm = this.value.toLowerCase()
		const selectedIconColor = iconColorInput.value
		const selectedBackgroundColor = backgroundColorInput.value

		if (searchTerm.length > 0) {
			const matches = allIcons.filter((icon) => icon.toLowerCase().includes(searchTerm)).slice(0, 10)
			if (matches.length > 0) {
				iconDropdown.innerHTML = matches
					.map(
						(icon) => `
                    <div class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer flex items-center">
                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-md mr-3" style="background-color: ${selectedBackgroundColor};">
                           <i class="fa-solid ${icon}" style="color: ${selectedIconColor};"></i>
                        </span>
                        <span>${icon}</span>
                    </div>
                `
					)
					.join("")
				iconDropdown.classList.remove("hidden")
				iconDropdown.querySelectorAll("div").forEach((item, index) => {
					item.addEventListener("click", () => {
						iconInput.value = matches[index]
						iconDropdown.classList.add("hidden")
					})
				})
			} else {
				iconDropdown.classList.add("hidden")
			}
		} else {
			iconDropdown.classList.add("hidden")
		}
	})

	document.addEventListener("click", function (e) {
		if (!iconInput.contains(e.target) && !iconDropdown.contains(e.target)) {
			iconDropdown.classList.add("hidden")
		}
	})

	// Initial render
	displayIcons()
}
