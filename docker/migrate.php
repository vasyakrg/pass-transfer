<?php
/**
 * Database Migration Script for PassTransfer
 * Run this script to test database connectivity and operations
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../database.php';

echo "🗄️ Testing PassTransfer database connection...\n";

try {
    $db = new Database();
    echo "✅ Database connection established\n";

    // Test database operations
    $testId = 'migration_test_' . time();

    // Test creating a note
    if ($db->createNote($testId, 'Migration test note', 'text')) {
        echo "✅ Note creation test passed\n";
    } else {
        echo "❌ Note creation test failed\n";
        exit(1);
    }

    // Test retrieving a note
    $note = $db->getNote($testId);
    if ($note && $note['content'] === 'Migration test note') {
        echo "✅ Note retrieval test passed\n";
    } else {
        echo "❌ Note retrieval test failed\n";
        exit(1);
    }

    // Test marking note as used
    if ($db->markNoteAsUsed($testId)) {
        echo "✅ Note usage marking test passed\n";
    } else {
        echo "❌ Note usage marking test failed\n";
        exit(1);
    }

    // Test that used note cannot be retrieved
    $usedNote = $db->getNote($testId);
    if (!$usedNote) {
        echo "✅ Used note protection test passed\n";
    } else {
        echo "❌ Used note protection test failed\n";
        exit(1);
    }

    // Clean up test data
    $db->deleteExpiredNotes();
    echo "✅ Database cleanup completed\n";

    $db->close();
    echo "🎉 Database migration completed successfully!\n";
    echo "📊 Database schema is ready for use\n";

} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>
