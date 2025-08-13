<?php
// Database configuration
define('DB_HOST', 'db');
define('DB_USER', 'passuser');
define('DB_PASS', 'pass123');
define('DB_NAME', 'pass_transfer');

// Application settings
define('SITE_URL', 'http://localhost:8080');
define('COMPANY_WHITELABEL', 'PassTransfer - Secure Note Sharing');

// Warning messages
define('WARNING_ONE_TIME_LINK', 'Внимание! Эта ссылка одноразовая. После открытия заметки она будет автоматически удалена.');
define('WARNING_CONFIRM_OPEN', 'После открытия заметки она будет удалена и больше не будет доступна. Продолжить?');

// Supported code languages for syntax highlighting with auto-detection
$SUPPORTED_LANGUAGES = [
	'auto' => 'Auto-detect',
	'text' => 'Plain Text',
	'javascript' => 'JavaScript',
	'typescript' => 'TypeScript',
	'php' => 'PHP',
	'python' => 'Python',
	'sql' => 'SQL',
	'yaml' => 'YAML',
	'xml' => 'XML',
	'css' => 'CSS',
	'html' => 'HTML',
	'json' => 'JSON',
	'markdown' => 'Markdown',
	'bash' => 'Bash/Shell',
	'ruby' => 'Ruby',
	'go' => 'Go',
	'swift' => 'Swift',
	'java' => 'Java',
	'cpp' => 'C++',
	'csharp' => 'C#',
	'dart' => 'Dart'
];
?>
