-- キャンペーンフォーム申込テーブル（テーブル名 cp_entries）
-- 実行: SQLite で entry/includes/db/2026springcp.db に対して実行する想定
-- 納品時は Zip とは別に本ファイルを渡し、受け側で適用すること。

CREATE TABLE IF NOT EXISTS cp_entries (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  email      TEXT NOT NULL,
  nickname   TEXT NOT NULL COLLATE NOCASE,
  created_at TEXT NOT NULL,
  UNIQUE(nickname),
  UNIQUE(email)
);
