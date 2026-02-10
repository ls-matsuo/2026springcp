<?php
/**
 * メール設定
 * .env を読み込み、アプリ内の $env に保持したうえで返す。
 */

$env = [];
$envPath = __DIR__ . '/.env';

if (is_readable($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || $line[0] === '#') {
            continue;
        }

        if (strpos($line, '=') === false) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // 行末のインラインコメント（スペース + #）を除去
        if (($pos = strpos($value, ' #')) !== false) {
            $value = trim(substr($value, 0, $pos));
        }

        if ($name === '') {
            continue;
        }

        if ((isset($value[0]) && ($value[0] === '"' || $value[0] === "'")) && strlen($value) >= 2 && $value[0] === $value[strlen($value) - 1]) {
            $value = substr($value, 1, -1);
        }

        $env[$name] = $value;
    }
}

return [
    'host'      => $env['MAIL_HOST'] ?? '',
    'port'      => (int) ($env['MAIL_PORT'] ?? '0'),
    'auth'      => filter_var($env['MAIL_AUTH'] ?? '', FILTER_VALIDATE_BOOLEAN),
    'user'      => $env['MAIL_USER'] ?? '',
    'pass'      => $env['MAIL_PASS'] ?? '',
    'secure'    => $env['MAIL_SECURE'] ?? '',
    'from'      => $env['MAIL_FROM'] ?? '',
    'from_name' => $env['MAIL_FROM_NAME'] ?? '',
];
