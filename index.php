<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassTransfer - Безопасная передача заметок</title>
    <link rel="stylesheet" href="assets/codemirror/codemirror.min.css">
    <link rel="stylesheet" href="assets/codemirror/monokai.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>PassTransfer</h1>
            <p>Безопасная передача паролей и заметок через одноразовые ссылки</p>
        </header>

        <main>
            <div class="editor-container">
                <div class="language-selector">
                    <label for="language">Язык программирования:</label>
                    <select id="language">
                        <?php foreach ($SUPPORTED_LANGUAGES as $key => $name): ?>
                            <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="expiration-selector">
                    <label for="expiration">Время жизни заметки:</label>
                    <select id="expiration">
                        <option value="600">10 минут</option>
                        <option value="3600">1 час</option>
                        <option value="86400">1 день</option>
                    </select>
                </div>

                <div class="editor-wrapper">
                    <textarea id="editor" placeholder="Введите ваш текст, пароль или код здесь..."></textarea>
                </div>

                <div class="actions">
                    <button id="saveBtn" class="btn btn-primary">Создать ссылку</button>
                    <button id="clearBtn" class="btn btn-secondary">Очистить</button>
                </div>
            </div>

            <div id="result" class="result-container" style="display: none;">
                <h3>Ваша ссылка готова!</h3>
                <div class="link-container">
                    <input type="text" id="generatedLink" readonly>
                    <button id="copyBtn" class="btn btn-success">Копировать</button>
                    <button id="shareBtn" class="btn btn-info">Поделиться</button>
                </div>
                <div class="warning-message">
                    <p><?php echo WARNING_ONE_TIME_LINK; ?></p>
                </div>
                <div class="share-options">
                    <button class="share-option" data-platform="telegram">Telegram</button>
                    <button class="share-option" data-platform="whatsapp">WhatsApp</button>
                    <button class="share-option" data-platform="email">Email</button>
                </div>
            </div>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> <?php echo COMPANY_WHITELABEL; ?></p>
        </footer>
    </div>

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
	<script>
		// Set debug mode based on environment variable
		window.DEBUG_MODE = <?php echo isset($_ENV['DEBUG']) && $_ENV['DEBUG'] == '1' ? 'true' : 'false'; ?>;
	</script>
	<script src="common.js"></script>
	<script src="script.js"></script>
</body>
</html>
