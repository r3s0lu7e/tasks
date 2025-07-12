class ImagePaste {
	constructor(textareaSelector, options = {}) {
		this.textarea = document.querySelector(textareaSelector)
		this.options = {
			uploadUrl: options.uploadUrl || "/upload/image",
			maxFileSize: options.maxFileSize || 10 * 1024 * 1024, // 10MB
			allowedTypes: options.allowedTypes || ["image/png", "image/jpeg", "image/gif", "image/webp"],
			placeholder: options.placeholder || "Uploading image...",
			...options,
		}

		if (this.textarea) {
			this.init()
		}
	}

	init() {
		this.textarea.addEventListener("paste", (e) => this.handlePaste(e))
		this.textarea.addEventListener("drop", (e) => this.handleDrop(e))
		this.textarea.addEventListener("dragover", (e) => e.preventDefault())
	}

	handlePaste(e) {
		const items = e.clipboardData.items

		for (let i = 0; i < items.length; i++) {
			const item = items[i]

			if (item.type.indexOf("image") !== -1) {
				e.preventDefault()
				const file = item.getAsFile()
				this.uploadImage(file)
				break
			}
		}
	}

	handleDrop(e) {
		e.preventDefault()
		const files = e.dataTransfer.files

		for (let i = 0; i < files.length; i++) {
			const file = files[i]

			if (this.options.allowedTypes.includes(file.type)) {
				this.uploadImage(file)
				break // Only handle first image
			}
		}
	}

	uploadImage(file) {
		if (!this.isValidFile(file)) {
			this.showError("Invalid file type or size too large")
			return
		}

		const placeholder = `![${this.options.placeholder}]()`
		const cursorPos = this.textarea.selectionStart

		// Insert placeholder at cursor position
		this.insertTextAtCursor(placeholder)

		// Upload the image
		this.performUpload(file, placeholder, cursorPos)
	}

	isValidFile(file) {
		return this.options.allowedTypes.includes(file.type) && file.size <= this.options.maxFileSize
	}

	insertTextAtCursor(text) {
		const start = this.textarea.selectionStart
		const end = this.textarea.selectionEnd
		const value = this.textarea.value

		this.textarea.value = value.substring(0, start) + text + value.substring(end)
		this.textarea.selectionStart = this.textarea.selectionEnd = start + text.length

		// Trigger input event for any listeners
		this.textarea.dispatchEvent(new Event("input", { bubbles: true }))
	}

	performUpload(file, placeholder, cursorPos) {
		const formData = new FormData()
		formData.append("image", file)

		// Get CSRF token
		const csrfToken = document.querySelector('meta[name="csrf-token"]')

		fetch(this.options.uploadUrl, {
			method: "POST",
			body: formData,
			headers: {
				"X-CSRF-TOKEN": csrfToken ? csrfToken.content : "",
				"X-Requested-With": "XMLHttpRequest",
			},
		})
			.then((response) => response.json())
			.then((data) => {
				if (data.success) {
					// Replace placeholder with actual image markdown
					const imageMarkdown = `![Image](${data.url})`
					this.replacePlaceholder(placeholder, imageMarkdown)
					this.showSuccess("Image uploaded successfully")
				} else {
					this.replacePlaceholder(placeholder, "")
					this.showError(data.message || "Upload failed")
				}
			})
			.catch((error) => {
				console.error("Upload error:", error)
				this.replacePlaceholder(placeholder, "")
				this.showError("Upload failed")
			})
	}

	replacePlaceholder(placeholder, replacement) {
		const value = this.textarea.value
		this.textarea.value = value.replace(placeholder, replacement)

		// Trigger input event
		this.textarea.dispatchEvent(new Event("input", { bubbles: true }))
	}

	showSuccess(message) {
		this.showNotification(message, "success")
	}

	showError(message) {
		this.showNotification(message, "error")
	}

	showNotification(message, type = "info") {
		// Create notification element
		const notification = document.createElement("div")
		notification.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-md shadow-lg transition-all duration-300 ${type === "success" ? "bg-green-500 text-white" : type === "error" ? "bg-red-500 text-white" : "bg-blue-500 text-white"}`
		notification.textContent = message

		document.body.appendChild(notification)

		// Remove after 3 seconds
		setTimeout(() => {
			notification.style.opacity = "0"
			setTimeout(() => {
				if (notification.parentNode) {
					notification.parentNode.removeChild(notification)
				}
			}, 300)
		}, 3000)
	}
}

// Export for use in other files
window.ImagePaste = ImagePaste
