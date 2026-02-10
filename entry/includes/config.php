<?php
/**
 * メール設定
 * .env を読み込み $_ENV / putenv に設定したうえで、getenv() で取得する。
 */

$envPath = __DIR__ . '/../../.env';

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

        $_ENV[$name] = $value;
        putenv("{$name}={$value}");
    }
}

return [
    'host'      => getenv('MAIL_HOST') ?: '',
    'port'      => (int) (getenv('MAIL_PORT') ?: '0'),
    'auth'      => filter_var(getenv('MAIL_AUTH') ?: '', FILTER_VALIDATE_BOOLEAN),
    'user'      => getenv('MAIL_USER') ?: '',
    'pass'      => getenv('MAIL_PASS') ?: '',
    'secure'    => getenv('MAIL_SECURE') ?: '',
    'from'      => getenv('MAIL_FROM') ?: '',
    'from_name' => getenv('MAIL_FROM_NAME') ?: '',
];
