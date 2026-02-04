# PHP 8.2 + Apache（SQLite は標準で利用可能）
FROM php:8.2-apache

# 起動時にスキーマ適用（初回のみ有効、以降は IF NOT EXISTS でスキップ）
COPY containers/web/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
