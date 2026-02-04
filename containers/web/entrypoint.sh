#!/bin/bash
set -e
# 起動時にスキーマを適用（CREATE TABLE IF NOT EXISTS のため毎回実行してよい）
php /var/www/html/2026springcp/containers/database/init_db.php 2>/dev/null || true
# Apache（www-data）が DB に書き込めるよう権限を付与（-R は使わず .gitkeep 等のオーナーはそのまま）
# ディレクトリ: SQLite がジャーナルを同じディレクトリに作るため
# .db ファイル: 同上
chown www-data:www-data /var/www/html/2026springcp/entry/includes/db 2>/dev/null || true
chown www-data:www-data /var/www/html/2026springcp/entry/includes/db/2026springcp.db 2>/dev/null || true
exec "$@"
