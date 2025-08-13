-- Create database if not exists
CREATE DATABASE IF NOT EXISTS pass_transfer CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE pass_transfer;

-- Create notes table
CREATE TABLE IF NOT EXISTS notes (
    id VARCHAR(32) PRIMARY KEY,
    content TEXT NOT NULL,
    language VARCHAR(20) DEFAULT 'text',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    is_used BOOLEAN DEFAULT FALSE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Create indexes for better performance
CREATE INDEX idx_notes_created_at ON notes (created_at);

CREATE INDEX idx_notes_expires_at ON notes (expires_at);

CREATE INDEX idx_notes_is_used ON notes (is_used);

-- Insert some sample data for testing
INSERT INTO
    notes (
        id,
        content,
        language,
        created_at
    )
VALUES (
        'sample1',
        'Это тестовая заметка для проверки работы приложения.',
        'text',
        NOW()
    ),
    (
        'sample2',
        '<?php\necho "Hello World!";\n?>',
        'php',
        NOW()
    ),
    (
        'sample3',
        '{\n  "name": "PassTransfer",\n  "version": "1.0.0"\n}',
        'json',
        NOW()
    ) ON DUPLICATE KEY
UPDATE content =
VALUES (content);
