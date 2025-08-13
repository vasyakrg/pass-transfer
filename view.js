// CodeMirror 5 initialization for view page
// Common variables and functions are imported from common.js

// Initialize CodeMirror 5 editor
function initEditor(content, language) {
	const textarea = document.getElementById('noteEditor');

	// Auto-detect language if not specified
	if (language === 'auto' || !language) {
		language = detectLanguage(content);
	}

	currentLanguage = language;
	const mode = languageModes[language] || 'text/plain';

	editor = CodeMirror.fromTextArea(textarea, {
		mode: mode,
		theme: 'monokai',
		lineNumbers: true,
		readOnly: true,
		lineWrapping: true,
		foldGutter: true,
		gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter']
	});

	editor.setValue(content);
	editor.setSize('100%', '400px');
	editor.refresh();
}

// Copy content to clipboard
function copyContent() {
	const content = editor.getValue();
	copyTextToClipboard(content);
}

// Open note directly
async function openNoteDirectly() {
	await openNote();
}

// Open note
async function openNote() {
	try {
		const response = await fetch('api.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({
				action: 'get',
				id: noteId
			})
		});

		const data = await response.json();
		debugLog('API Response:', data);

		if (data.success) {
			// Mark note as used immediately after successful retrieval
			try {
				await fetch('api.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify({
						action: 'mark_used',
						id: noteId
					})
				});
			} catch (markError) {
				debugLog('Error marking note as used:', markError);
			}

			// Hide confirmation section
			document.getElementById('confirmContainer').style.display = 'none';

			// Show note content
			document.getElementById('noteContainer').style.display = 'block';

			// Initialize editor with content
			initEditor(data.note.content, data.note.language);

			// Update language display
			const languageDisplay = document.getElementById('noteLanguage');
			if (languageDisplay) {
				const languageName = getLanguageName(data.note.language);
				languageDisplay.textContent = languageName;
			}

			// Update date display
			const dateDisplay = document.getElementById('noteDate');
			if (dateDisplay && data.note.created_at) {
				const date = new Date(data.note.created_at);
				dateDisplay.textContent = date.toLocaleString('ru-RU');
			}
		} else {
			// Show error
			document.getElementById('confirmContainer').style.display = 'none';
			document.getElementById('errorContainer').style.display = 'block';
			document.getElementById('errorMessage').textContent = data.error || 'Заметка не найдена или уже использована';
		}
	} catch (error) {
		debugLog('Error:', error);
		// Show error
		document.getElementById('confirmContainer').style.display = 'none';
		document.getElementById('errorContainer').style.display = 'block';
		document.getElementById('errorMessage').textContent = 'Ошибка при получении заметки';
	}
}



// Check note status on page load
async function checkNoteStatus() {
	try {
		const response = await fetch('api.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({
				action: 'get',
				id: noteId
			})
		});

		const data = await response.json();
		debugLog('Note status check:', data);

		if (!data.success) {
			// Note is already used or doesn't exist
			document.getElementById('confirmContainer').style.display = 'none';
			document.getElementById('errorContainer').style.display = 'block';
			document.getElementById('errorMessage').textContent = data.error || 'Заметка не найдена или уже использована';
		}
	} catch (error) {
		debugLog('Error checking note status:', error);
		document.getElementById('confirmContainer').style.display = 'none';
		document.getElementById('errorContainer').style.display = 'block';
		document.getElementById('errorMessage').textContent = 'Ошибка при проверке заметки';
	}
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
	// Check note status first
	checkNoteStatus();

	// Add event listeners
	const openBtn = document.getElementById('openBtn');
	const copyNoteBtn = document.getElementById('copyNoteBtn');
	const homeBtn = document.getElementById('homeBtn');
	const errorHomeBtn = document.getElementById('errorHomeBtn');

	if (openBtn) openBtn.addEventListener('click', openNoteDirectly);
	if (copyNoteBtn) copyNoteBtn.addEventListener('click', copyContent);
	if (homeBtn) homeBtn.addEventListener('click', () => window.location.href = '/');
	if (errorHomeBtn) errorHomeBtn.addEventListener('click', () => window.location.href = '/');
});
