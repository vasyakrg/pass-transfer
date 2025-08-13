<?php
require_once 'config.php';
require_once 'database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'create':
                if (isset($input['content']) && !empty($input['content'])) {
                    $id = generateUniqueId();
                    $content = $input['content'];
                    $language = isset($input['language']) ? $input['language'] : 'text';
                    $expiration = isset($input['expiration']) ? intval($input['expiration']) : 0;

                    // Calculate expiration time if specified
                    $expiresAt = null;
                    if ($expiration > 0) {
                        $expiresAt = date('Y-m-d H:i:s', time() + $expiration);
                    }

                    if ($db->createNote($id, $content, $language, $expiresAt)) {
                        echo json_encode([
                            'success' => true,
                            'id' => $id,
                            'url' => SITE_URL . '/view.php?id=' . $id,
                            'expires_at' => $expiresAt
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Failed to create note']);
                    }
                } else {
                    echo json_encode(['success' => false, 'error' => 'Content is required']);
                }
                break;

            case 'get':
                if (isset($input['id']) && !empty($input['id'])) {
                    $note = $db->getNote($input['id']);
                    if ($note) {
                        echo json_encode([
                            'success' => true,
                            'note' => $note
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Note not found, already used, or expired']);
                    }
                } else {
                    echo json_encode(['success' => false, 'error' => 'ID is required']);
                }
                break;

            case 'mark_used':
                if (isset($input['id']) && !empty($input['id'])) {
                    if ($db->markNoteAsUsed($input['id'])) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Failed to mark note as used']);
                    }
                } else {
                    echo json_encode(['success' => false, 'error' => 'ID is required']);
                }
                break;

            default:
                echo json_encode(['success' => false, 'error' => 'Invalid action']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Action is required']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
}

$db->close();

function generateUniqueId($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $id = '';
    for ($i = 0; $i < $length; $i++) {
        $id .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $id;
}
?>
