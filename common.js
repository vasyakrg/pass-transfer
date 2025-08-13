// Common CodeMirror 5 configuration and utilities
let editor;
let currentLanguage = 'auto';

// Language detection patterns by file header
const languagePatterns = {
	'php': /^<\?php/,
	'javascript': /^\/\/.*\.js$|^function\s+\w+\s*\(/,
	'typescript': /^\/\/.*\.ts$|^interface\s+\w+|^type\s+\w+/,
	'python': /^#.*\.py$|^def\s+\w+\s*\(/,
	'sql': /^--.*\.sql$|^SELECT|^INSERT|^CREATE/,
	'yaml': /^---$|^#.*\.yml$|^#.*\.yaml$/,
	'xml': /^<\?xml/,
	'html': /^<!DOCTYPE|<html/,
	'css': /^\/\*.*\.css$|^[.#]?\w+\s*\{/,
	'json': /^\{|^\[/,
	'markdown': /^#+\s+|^\/\/.*\.md$/,
	'bash': /^#!\/bin\/bash|^#!\/bin\/sh|^#!/,
	'ruby': /^#.*\.rb$|^def\s+\w+/,
	'go': /^package\s+main|^\/\/.*\.go$/,
	'swift': /^\/\/.*\.swift$|^import\s+\w+/,
	'java': /^\/\/.*\.java$|^public\s+class/,
	'cpp': /^\/\/.*\.cpp$|^#include/,
	'csharp': /^\/\/.*\.cs$|^using\s+System/,
	'dart': /^\/\/.*\.dart$|^import\s+'package:/
};

// Language mode mapping for CodeMirror 5
const languageModes = {
	'auto': 'text/plain',
	'text': 'text/plain',
	'javascript': 'javascript',
	'typescript': 'javascript',
	'php': 'php',
	'python': 'python',
	'sql': 'text/x-sql',
	'yaml': 'yaml',
	'xml': 'text/xml',
	'css': 'css',
	'html': 'text/html',
	'json': 'application/json',
	'markdown': 'text/x-markdown',
	'bash': 'text/x-sh',
	'ruby': 'text/x-ruby',
	'go': 'text/x-go',
	'swift': 'text/x-swift',
	'java': 'text/x-java-source',
	'cpp': 'text/x-c++src',
	'csharp': 'text/x-csharp',
	'dart': 'text/x-dart'
};

// Language names mapping
const languageNames = {
	'auto': 'Auto-detect',
	'text': 'Plain Text',
	'javascript': 'JavaScript',
	'typescript': 'TypeScript',
	'php': 'PHP',
	'python': 'Python',
	'sql': 'SQL',
	'yaml': 'YAML',
	'xml': 'XML',
	'css': 'CSS',
	'html': 'HTML',
	'json': 'JSON',
	'markdown': 'Markdown',
	'bash': 'Bash/Shell',
	'ruby': 'Ruby',
	'go': 'Go',
	'swift': 'Swift',
	'java': 'Java',
	'cpp': 'C++',
	'csharp': 'C#',
	'dart': 'Dart'
};

// Auto-detect language from content
function detectLanguage(content) {
	if (!content.trim()) return 'text';

	const lines = content.split('\n');
	const firstFewLines = lines.slice(0, 10).join('\n');

	for (const [lang, pattern] of Object.entries(languagePatterns)) {
		if (pattern.test(firstFewLines)) {
			return lang;
		}
	}

	return 'text';
}

// Update editor language
function updateLanguage(language) {
	if (!editor || language === currentLanguage) return;

	currentLanguage = language;
	const mode = languageModes[language] || 'text/plain';
	editor.setOption('mode', mode);
}

// Get language name
function getLanguageName(languageCode) {
	return languageNames[languageCode] || languageCode;
}

// Copy text to clipboard with fallback
function copyTextToClipboard(text, successMessage = 'Содержимое скопировано в буфер обмена!') {
	if (navigator.clipboard) {
		navigator.clipboard.writeText(text).then(() => {
			showNotification(successMessage, 'success');
		}).catch(() => {
			fallbackCopyTextToClipboard(text, successMessage);
		});
	} else {
		fallbackCopyTextToClipboard(text, successMessage);
	}
}

// Debug logging function
function debugLog(...args) {
	if (window.DEBUG_MODE) {
		console.log(...args);
	}
}

// Show notification
function showNotification(message, type = 'info', duration = 3000) {
	// Remove existing notifications
	const existingNotifications = document.querySelectorAll('.notification');
	existingNotifications.forEach(notification => notification.remove());

	// Create notification element
	const notification = document.createElement('div');
	notification.className = `notification ${type}`;
	notification.textContent = message;

	// Add to page
	document.body.appendChild(notification);

	// Show notification
	setTimeout(() => {
		notification.classList.add('show');
	}, 100);

	// Hide notification after duration
	setTimeout(() => {
		notification.classList.remove('show');
		setTimeout(() => {
			if (notification.parentNode) {
				notification.parentNode.removeChild(notification);
			}
		}, 300);
	}, duration);
}

// Fallback copy function
function fallbackCopyTextToClipboard(text, successMessage = 'Содержимое скопировано в буфер обмена!') {
	const textArea = document.createElement('textarea');
	textArea.value = text;
	textArea.style.position = 'fixed';
	textArea.style.left = '-999999px';
	textArea.style.top = '-999999px';
	document.body.appendChild(textArea);
	textArea.focus();
	textArea.select();

	try {
		document.execCommand('copy');
		showNotification(successMessage, 'success');
	} catch (err) {
		showNotification('Не удалось скопировать содержимое', 'error');
	}

	document.body.removeChild(textArea);
}
