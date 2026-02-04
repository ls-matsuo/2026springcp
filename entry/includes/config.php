<?php
// メール設定（開発: docker-compose の MAIL_HOST / MAIL_PORT に合わせる）
return [
    'host'      => getenv('MAIL_HOST') ?: 'mailpit',
    'port'      => (int) (getenv('MAIL_PORT') ?: 1025),
    'user'      => '',
    'pass'      => '',
    'secure'    => '',
    'from'      => 'test@example.com',
    'from_name' => 'ENEOS Charge Plus',
];
