<?php
/**
 * Project Memory Management CLI
 * Open Source & 100% Free Memory Logger for PT Nihon Plast Indonesia CMS
 *
 * Usage:
 *   php scripts/memory.php list [category]
 *   php scripts/memory.php add --category=<cat> --title="<title>" --content="<content>" [--meta='{"key":"val"}']
 *   php scripts/memory.php show <id>
 *   php scripts/memory.php export-md [target_file.md]
 *   php scripts/memory.php stats
 */

if (php_sapi_name() !== 'cli' && PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Forbidden: CLI execution only.');
}

require_once __DIR__ . '/../config/database.php';

function printHelp() {
    echo <<<HELP
Project Memory CLI (MySQL + Markdown Persistent Store)
------------------------------------------------------
Commands:
  php scripts/memory.php list [category]
      List all stored memory entries (optional filter: findings, architecture, stack, changelog, todo, security)

  php scripts/memory.php add --category=<category> --title="<title>" --content="<content>" [--meta='<json>']
      Add a new memory record to the database

  php scripts/memory.php show <id>
      Display full details of a specific memory record

  php scripts/memory.php export-md [file_path]
      Export all memories from MySQL to a formatted Markdown file (defaults to PROJECT_MEMORY.md)

  php scripts/memory.php stats
      Show memory statistics by category

HELP;
    exit(0);
}

// Parse argv
$action = $argv[1] ?? 'list';

if (in_array($action, ['help', '--help', '-h'], true)) {
    printHelp();
}

switch ($action) {
    case 'stats':
        $res = $conn->query("SELECT category, COUNT(*) as total, MAX(updated_at) as last_updated FROM project_memory GROUP BY category");
        echo "=== PROJECT MEMORY STATS ===" . PHP_EOL;
        $totalAll = 0;
        while ($row = $res->fetch_assoc()) {
            echo sprintf(" - %-15s : %3d entries (Last updated: %s)\n", $row['category'], $row['total'], $row['last_updated']);
            $totalAll += (int)$row['total'];
        }
        echo "----------------------------------------" . PHP_EOL;
        echo " Total Memory Records : " . $totalAll . PHP_EOL;
        break;

    case 'list':
        $catFilter = $argv[2] ?? null;
        if ($catFilter) {
            $stmt = $conn->prepare("SELECT id, category, title, created_at FROM project_memory WHERE category = ? ORDER BY id DESC");
            $stmt->bind_param('s', $catFilter);
            $stmt->execute();
            $res = $stmt->get_result();
            echo "=== MEMORY ENTRIES (Category: $catFilter) ===" . PHP_EOL;
        } else {
            $res = $conn->query("SELECT id, category, title, created_at FROM project_memory ORDER BY id DESC");
            echo "=== ALL MEMORY ENTRIES ===" . PHP_EOL;
        }

        if ($res->num_rows === 0) {
            echo "No memory records found." . PHP_EOL;
            break;
        }

        while ($row = $res->fetch_assoc()) {
            echo sprintf("[%3d] [%-12s] %s (%s)\n", $row['id'], $row['category'], $row['title'], $row['created_at']);
        }
        break;

    case 'show':
        $id = (int)($argv[2] ?? 0);
        if (!$id) {
            echo "Error: Missing memory ID. Example: php scripts/memory.php show 1\n";
            exit(1);
        }
        $stmt = $conn->prepare("SELECT * FROM project_memory WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $entry = $stmt->get_result()->fetch_assoc();
        if (!$entry) {
            echo "Memory entry #$id not found.\n";
            exit(1);
        }
        echo "========================================================\n";
        echo "ID       : " . $entry['id'] . PHP_EOL;
        echo "Category : " . $entry['category'] . PHP_EOL;
        echo "Title    : " . $entry['title'] . PHP_EOL;
        echo "Author   : " . $entry['author'] . PHP_EOL;
        echo "Created  : " . $entry['created_at'] . PHP_EOL;
        echo "Updated  : " . $entry['updated_at'] . PHP_EOL;
        if (!empty($entry['metadata'])) {
            echo "Metadata : " . $entry['metadata'] . PHP_EOL;
        }
        echo "--------------------------------------------------------\n";
        echo "Content:\n" . $entry['content'] . PHP_EOL;
        echo "========================================================\n";
        break;

    case 'add':
        $category = 'notes';
        $title = '';
        $content = '';
        $metadata = null;
        $author = 'antigravity-ai';

        for ($i = 2; $i < count($argv); $i++) {
            $arg = $argv[$i];
            if (str_starts_with($arg, '--category=')) {
                $category = substr($arg, 11);
            } elseif (str_starts_with($arg, '--title=')) {
                $title = substr($arg, 8);
            } elseif (str_starts_with($arg, '--content=')) {
                $content = substr($arg, 10);
            } elseif (str_starts_with($arg, '--meta=')) {
                $metadata = substr($arg, 7);
            } elseif (str_starts_with($arg, '--author=')) {
                $author = substr($arg, 9);
            }
        }

        if (empty($title) || empty($content)) {
            echo "Error: --title and --content are required.\n";
            exit(1);
        }

        if (!empty($metadata)) {
            $trimmed = trim($metadata, "'\"");
            $decoded = json_decode($trimmed);
            if ($decoded !== null) {
                $metadata = json_encode($decoded);
            } else {
                $decodedRaw = json_decode($metadata);
                if ($decodedRaw !== null) {
                    $metadata = json_encode($decodedRaw);
                } else {
                    $metadata = null;
                }
            }
        } else {
            $metadata = null;
        }

        $stmt = $conn->prepare("INSERT INTO project_memory (category, title, content, metadata, author) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $category, $title, $content, $metadata, $author);

        if ($stmt->execute()) {
            $newId = $conn->insert_id;
            echo "Memory entry added successfully! ID: $newId [$category] $title\n";
        } else {
            echo "Failed to add memory entry: " . $conn->error . "\n";
            exit(1);
        }
        break;

    case 'export-md':
        $targetFile = $argv[2] ?? __DIR__ . '/../PROJECT_MEMORY.md';
        $res = $conn->query("SELECT * FROM project_memory ORDER BY category ASC, id ASC");

        $md = "# 🧠 CMS Company Profile - Project Memory Bank\n\n";
        $md .= "> Auto-exported from MySQL Database `db_cms_compro.project_memory`\n";
        $md .= "> Generated on: " . date('Y-m-d H:i:s') . "\n\n";

        $currentCat = '';
        while ($row = $res->fetch_assoc()) {
            if ($row['category'] !== $currentCat) {
                $currentCat = $row['category'];
                $md .= "\n## 📂 Category: " . strtoupper($currentCat) . "\n\n";
            }
            $md .= "### #" . $row['id'] . " - " . htmlspecialchars($row['title']) . "\n";
            $md .= "- **Recorded At:** " . $row['created_at'] . "\n";
            $md .= "- **Author:** " . $row['author'] . "\n";
            if (!empty($row['metadata'])) {
                $md .= "- **Metadata:** `" . $row['metadata'] . "`\n";
            }
            $md .= "\n" . $row['content'] . "\n\n---\n\n";
        }

        file_put_contents($targetFile, $md);
        echo "Exported memories to $targetFile successfully!\n";
        break;

    default:
        echo "Unknown command: $action\n";
        printHelp();
}
