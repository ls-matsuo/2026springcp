<?php
// メール設定（開発: docker-compose の MAIL_HOST / MAIL_PORT に合わせる）
return [
    'host'      => getenv('MAIL_HOST') ?: 'mailpit',
    'port'      => (int) (getenv('MAIL_PORT') ?: 1025),
    'user'      => '',
    'pass'      => '',
    'secure'    => '',
    'from'      => 'entry@eneoschargeplus.com',
    'from_name' => 'entry@eneoschargeplus.com',
];
