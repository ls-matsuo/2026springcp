<?php
/**
 * 開発時のみ: .db の状態確認（スキーマのみか・データありか）。
 * 実行例: docker compose exec web php /var/www/html/2026springcp/containers/database/check_db.php
 */
$scriptDir = __DIR__;
$dbPath = $scriptDir . '/../../entry/includes/db/2026springcp.db';

if (!is_readable($dbPath)) {
    fwrite(STDERR, "DB ファイルが存在しないか読めません: {$dbPath}\n");
    exit(1);
}

try {
    $pdo = new PDO('sqlite:' . $dbPath, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    fwrite(STDERR, "接続エラー: " . $e->getMessage() . "\n");
    exit(1);
}

$tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='entries'")->fetchAll(PDO::FETCH_COLUMN);
if (empty($tables)) {
    echo "entries テーブルが存在しません（スキーマ未適用の可能性）。\n";
    exit(0);
}

$count = (int) $pdo->query('SELECT COUNT(*) FROM entries')->fetchColumn();
if ($count === 0) {
    echo "スキーマのみ適用（データなし）\n";
} else {
    echo "データ {$count} 件\n";
}
exit(0);
