<?php
require_once 'config.php';
$noteId = isset($_GET['id']) ? $_GET['id'] : '';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassTransfer - Просмотр заметки</title>
    <link rel="stylesheet" href="assets/codemirror/codemirror.min.css">
    <link rel="stylesheet" href="assets/codemirror/monokai.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>PassTransfer</h1>
            <p>Просмотр заметки</p>
        </header>

        <main>
            <div id="confirmContainer" class="confirm-container">
                <div class="confirm-box">
                    <h3>Подтверждение открытия</h3>
                    <p><?php echo WARNING_CONFIRM_OPEN; ?></p>
                    <div class="confirm-actions">
                        <button id="openBtn" class="btn btn-primary">Открыть заметку</button>
                    </div>
                </div>
            </div>

            <div id="noteContainer" class="note-container" style="display: none;">
                <div class="note-info">
                    <span id="noteLanguage" class="language-badge"></span>
                    <span id="noteDate" class="date-badge"></span>
                </div>

                <div class="editor-wrapper">
                    <textarea id="noteEditor" readonly></textarea>
                </div>

                <div class="actions">
                    <button id="copyNoteBtn" class="btn btn-success">Копировать в буфер</button>
                    <button id="homeBtn" class="btn btn-secondary">На главную</button>
                </div>
            </div>

            <div id="errorContainer" class="error-container" style="display: none;">
                <h3>Ошибка</h3>
                <p id="errorMessage"></p>
                <button id="errorHomeBtn" class="btn btn-primary">На главную</button>
            </div>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> <?php echo COMPANY_WHITELABEL; ?></p>
        </footer>
    </div>

    <script>
        const noteId = '<?php echo $noteId; ?>';
        // Set debug mode based on environment variable
        window.DEBUG_MODE = <?php echo isset($_ENV['DEBUG']) && $_ENV['DEBUG'] == '1' ? 'true' : 'false'; ?>;
    </script>
    <script src="assets/codemirror/codemirror.min.js"></script>
    <script src="assets/codemirror/modes/clike.min.js"></script>
    <script src="assets/codemirror/modes/htmlmixed.min.js"></script>
    <script src="assets/codemirror/modes/javascript.min.js"></script>
    <script src="assets/codemirror/modes/php.min.js"></script>
    <script src="assets/codemirror/modes/python.min.js"></script>
    <script src="assets/codemirror/modes/sql.min.js"></script>
    <script src="assets/codemirror/modes/yaml.min.js"></script>
    <script src="assets/codemirror/modes/xml.min.js"></script>
    <script src="assets/codemirror/modes/css.min.js"></script>
    <script src="assets/codemirror/modes/json.min.js"></script>
    <script src="assets/codemirror/modes/markdown.min.js"></script>
    <script src="assets/codemirror/modes/shell.min.js"></script>
    <script src="assets/codemirror/modes/ruby.min.js"></script>
    <script src="assets/codemirror/modes/go.min.js"></script>
    <script src="assets/codemirror/modes/swift.min.js"></script>
	<script src="assets/codemirror/modes/java.min.js"></script>
	<script src="assets/codemirror/modes/cpp.min.js"></script>
	<script src="assets/codemirror/modes/csharp.min.js"></script>
	<script src="assets/codemirror/modes/dart.min.js"></script>
    <script src="common.js"></script>
    <script src="view.js"></script>
</body>
</html>
