// CodeMirror 5 initialization with auto-detection
// Common variables and functions are imported from common.js

// Initialize CodeMirror 5 editor
function initEditor() {
	const textarea = document.getElementById('editor');

	editor = CodeMirror.fromTextArea(textarea, {
		mode: 'text/plain',
		theme: 'monokai',
		lineNumbers: true,
		autoCloseBrackets: true,
		matchBrackets: true,
		indentUnit: 4,
		tabSize: 4,
		indentWithTabs: false,
		lineWrapping: true,
		foldGutter: true,
		gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter'],
		extraKeys: {
			'Ctrl-Space': 'autocomplete'
		}
	});

	editor.setSize('100%', '400px');

	// Auto-detect language on content change
	editor.on('change', function () {
		if (currentLanguage === 'auto') {
			const content = editor.getValue();
			const detectedLang = detectLanguage(content);
			if (detectedLang !== 'text') {
				updateLanguage(detectedLang);
				updateLanguageSelection(detectedLang);
			}
		}
	});

	// Auto-detect language on initial load
	if (currentLanguage === 'auto') {
		const content = editor.getValue();
		const detectedLang = detectLanguage(content);
		if (detectedLang !== 'text') {
			updateLanguage(detectedLang);
			updateLanguageSelection(detectedLang);
		}
	}
}



// Update language selection
function updateLanguageSelection(detectedLang) {
	const languageSelect = document.getElementById('language');
	if (languageSelect) {
		languageSelect.value = detectedLang;
	}
}

// Handle language selection change
function onLanguageChange() {
	const languageSelect = document.getElementById('language');
	if (languageSelect) {
		const selectedLanguage = languageSelect.value;
		updateLanguage(selectedLanguage);
	}
}

// Create note
async function createNote() {
	const content = editor.getValue();
	const languageSelect = document.getElementById('language');
	const language = languageSelect ? languageSelect.value : 'auto';

	if (!content.trim()) {
		showNotification('Пожалуйста, введите содержимое заметки', 'warning');
		return;
	}

	try {
		const response = await fetch('api.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({
				action: 'create',
				content: content,
				language: language
			})
		});

		const data = await response.json();
		debugLog('API Response:', data);

		if (data.success) {
			const noteUrl = `${window.location.origin}/view.php?id=${data.id}`;
			const generatedLink = document.getElementById('generatedLink');
			const resultContainer = document.getElementById('result');

			if (generatedLink) generatedLink.value = noteUrl;
			if (resultContainer) resultContainer.style.display = 'block';

			// Clear editor
			editor.setValue('');

			// Reset language to auto
			if (languageSelect) languageSelect.value = 'auto';
			currentLanguage = 'auto';
			editor.setOption('mode', 'text/plain');
		} else {
			showNotification('Ошибка при создании заметки: ' + (data.error || data.message), 'error');
		}
	} catch (error) {
		debugLog('Error:', error);
		showNotification('Ошибка при создании заметки', 'error');
	}
}

// Copy URL to clipboard
function copyUrl() {
	const urlInput = document.getElementById('generatedLink');
	if (!urlInput) return;

	urlInput.select();
	urlInput.setSelectionRange(0, 99999);

	try {
		document.execCommand('copy');
		showNotification('Ссылка скопирована в буфер обмена!', 'success');
	} catch (err) {
		debugLog('Failed to copy: ', err);
		showNotification('Не удалось скопировать ссылку', 'error');
	}
}

// Share note
function shareNote() {
	const urlInput = document.getElementById('generatedLink');
	if (!urlInput) return;

	const url = urlInput.value;

	if (navigator.share) {
		navigator.share({
			title: 'PassTransfer - Заметка',
			text: 'Вам отправлена заметка через PassTransfer',
			url: url
		});
	} else {
		copyUrl();
	}
}

// Clear editor
function clearEditor() {
	// Clear editor content
	editor.setValue('');

	// Reset language to auto
	const languageSelect = document.getElementById('language');
	if (languageSelect) {
		languageSelect.value = 'auto';
	}

	// Reset editor mode to plain text
	currentLanguage = 'auto';
	editor.setOption('mode', 'text/plain');

	// Focus editor for better UX
	editor.focus();
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
	initEditor();

	// Add event listeners with checks
	const languageSelect = document.getElementById('language');
	const saveBtn = document.getElementById('saveBtn');
	const clearBtn = document.getElementById('clearBtn');
	const copyBtn = document.getElementById('copyBtn');
	const shareBtn = document.getElementById('shareBtn');

	if (languageSelect) languageSelect.addEventListener('change', onLanguageChange);
	if (saveBtn) saveBtn.addEventListener('click', createNote);
	if (clearBtn) clearBtn.addEventListener('click', clearEditor);
	if (copyBtn) copyBtn.addEventListener('click', copyUrl);
	if (shareBtn) shareBtn.addEventListener('click', shareNote);
});
