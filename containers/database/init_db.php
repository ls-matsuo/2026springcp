<?php
/**
 * 開発時のみ: DB を初期化する（既存 DB はタイムスタンプ付きでバックアップしてから新規作成）。
 * 納品物には含めない。
 * 実行例: docker compose exec web php /var/www/html/2026springcp/containers/database/init_db.php
 */
$scriptDir = __DIR__;
$dbDir = $scriptDir . '/../../entry/includes/db';
$dbPath = $dbDir . '/2026springcp.db';
$schemaPath = $scriptDir . '/schema.sql';

if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}
if (!is_readable($schemaPath)) {
    fwrite(STDERR, "schema.sql が見つかりません: {$schemaPath}\n");
    exit(1);
}

// 既存 DB があればタイムスタンプ付きでバックアップしてから削除
if (file_exists($dbPath)) {
    $backupPath = $dbPath . '.' . date('Ymd_His') . '.bak';
    if (!copy($dbPath, $backupPath)) {
        fwrite(STDERR, "バックアップに失敗しました: {$dbPath} -> {$backupPath}\n");
        exit(1);
    }
    echo "バックアップしました: {$backupPath}\n";

    unlink($dbPath);
    foreach (['-wal', '-shm'] as $suffix) {
        $extra = $dbPath . $suffix;
        if (file_exists($extra)) {
            unlink($extra);
        }
    }
}

$schema = file_get_contents($schemaPath);
$pdo = new PDO('sqlite:' . $dbPath, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pdo->exec($schema);
unset($pdo);

echo "DB を初期化しました: {$dbPath}\n";

// Apache (www-data) が DB に書き込めるようオーナーを変更（root 実行時のみ有効）
if (function_exists('chown')) {
    $user = 'www-data';
    if (@chown($dbPath, $user) && @chgrp($dbPath, $user)) {
        echo "オーナーを www-data に変更しました: {$dbPath}\n";
        foreach (glob($dbDir . '/*.bak') ?: [] as $f) {
            @chown($f, $user);
            @chgrp($f, $user);
        }
        @chown($dbDir, $user);
        @chgrp($dbDir, $user);
    }
}
