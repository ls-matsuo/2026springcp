# アバランチ様 eneoschargeplus 2026spring キャンペーンフォーム

ENEOS Charge Plus キャンペーンのエントリーフォームです。PHP + SQLite で、ニックネーム・メールアドレスの入力、重複チェック、DB 登録、自動返信メール送信までを一通り行います。

## クイックスタート

```bash
# 起動
docker compose up -d --build

# ブラウザで開く
# http://localhost:8080/2026springcp/entry/

#メール確認用 Mailpit
# http://localhost:8026
```

初回環境構築時に、DBスキーマが自動適用されます。

詳細は [環境構築](docs/02_開発環境/環境構築.md) を参照してください。

## プロジェクト構成（抜粋）

```
2026springcp/
├── entry/                 # エントリーポイント
│   ├── index.html        # トップページ
│   ├── form.php          # フォーム表示・送信受付
│   ├── thanks.html       # 完了画面
│   └── includes/         # 処理・設定（send.php, config.php, db/ など）
├── containers/            # Docker まわり（web, database 用スクリプト等）
├── docker-compose.yml
└── docs/                  # ドキュメント
```

## ドキュメント（docs/）

| ディレクトリ | 内容 |
|--------------|------|
| [docs/01_概要/](docs/01_概要/) | [プロジェクト概要](docs/01_概要/プロジェクト概要.md)（要件・納品方法） |
| [docs/02_開発環境/](docs/02_開発環境/) | [環境構築](docs/02_開発環境/環境構築.md)（Docker・DB・メール） |
| [docs/03_設計/](docs/03_設計/) | [基本設計](docs/03_設計/基本設計.md)、[データベース設計](docs/03_設計/データベース設計.md) |
| [docs/04_テスト/](docs/04_テスト/) | [結合テスト](docs/04_テスト/結合テスト.md) |
