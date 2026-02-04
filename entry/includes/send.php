<?php
require_once __DIR__ . '/lib/PHPMailer.php';
require_once __DIR__ . '/lib/SMTP.php';
require_once __DIR__ . '/lib/Exception.php';
require_once __DIR__ . '/lib/TimeProvider.php';
require_once __DIR__ . '/messages.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// CSRF チェック
if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
    die('不正なアクセスです。');
}

// 入力取得（重複判定は正規化で比較、保存はトリムした値でランキング表示用）
$nicknameRaw = $_POST['nickname'] ?? '';
$emailRaw    = $_POST['email'] ?? '';
$agree        = isset($_POST['agree']);

$errors = [];

// 前後の空白（全角半角問わず）を除いた上で必須・形式チェック
$trimFn = function ($s) {
    return preg_replace('/^[\s\x{3000}]+|[\s\x{3000}]+$/u', '', $s);
};
$nicknameTrimmed = $trimFn($nicknameRaw);
$emailTrimmed    = $trimFn($emailRaw);

if ($nicknameTrimmed === '') {
    $errors['nickname'] = $errorMessages['nickname_required'];
}
if ($emailTrimmed === '') {
    $errors['email'] = $errorMessages['email_required'];
} elseif (!filter_var($emailTrimmed, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = $errorMessages['email_invalid'];
}
if (!$agree) {
    $errors['agree'] = $errorMessages['agree_required'];
}

// エラー時はフォームへ戻す
if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = ['nickname' => $nicknameRaw, 'email' => $emailRaw];
    header('Location: form.php');
    exit;
}

// 重複判定用の正規化（小文字化・全角→半角・トリム）
$normalize = function ($s) {
    $s = preg_replace('/^[\s\x{3000}]+|[\s\x{3000}]+$/u', '', $s);
    $s = mb_convert_kana($s, 'a', 'UTF-8');
    return mb_strtolower($s, 'UTF-8');
};

// DB: 接続（スキーマは別途 schema.sql を適用すること）
$dbPath = __DIR__ . '/db/2026springcp.db';
$pdo = new PDO('sqlite:' . $dbPath, null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// テーブル存在チェック（存在しなければエラーで終了）
$stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='cp_entries'");
if ($stmt->fetch() === false) {
    $_SESSION['errors'] = ['general' => $errorMessages['db_not_ready']];
    $_SESSION['old'] = ['nickname' => $nicknameRaw, 'email' => $emailRaw];
    header('Location: form.php');
    exit;
}

// 重複チェック（正規化した値で比較）
$normNickname = $normalize($nicknameRaw);
$normEmail    = $normalize($emailRaw);
$dupNickname  = false;
$dupEmail     = false;

$stmt = $pdo->query("SELECT nickname, email FROM cp_entries");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($normalize($row['nickname']) === $normNickname) {
        $dupNickname = true;
    }
    if ($normalize($row['email']) === $normEmail) {
        $dupEmail = true;
    }
}

if ($dupNickname) {
    $errors['nickname'] = $errorMessages['nickname_duplicate'];
}
if ($dupEmail) {
    $errors['email'] = $errorMessages['email_duplicate'];
}

if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = ['nickname' => $nicknameRaw, 'email' => $emailRaw];
    header('Location: form.php');
    exit;
}

// 登録（トリムした値を保存。created_at は TimeProvider から供給）
// メール送信成功時のみ COMMIT し、失敗時は ROLLBACK して登録を取り消す
$timeProvider = new TimeProvider();
$createdAt = $timeProvider->now();
$pdo->beginTransaction();
$stmtInsert = $pdo->prepare("INSERT INTO cp_entries (email, nickname, created_at) VALUES (?, ?, ?)");
$stmtInsert->execute([$emailTrimmed, $nicknameTrimmed, $createdAt]);

// 設定ファイル読み込み
$config = require __DIR__ . '/config.php';

$mail = new PHPMailer(true);

try {
    // SMTP 設定
    $mail->isSMTP();
    $mail->Host = $config['host'];
    $mail->SMTPAuth = !empty($config['user']);
    $mail->Username = $config['user'];
    $mail->Password = $config['pass'];
    $mail->SMTPSecure = $config['secure'];
    $mail->Port = $config['port'];

    // 送信元
    $mail->setFrom($config['from'], $config['from_name']);
    $mail->CharSet = 'UTF-8';

    // 事務局向け：（必要なら有効化）
    // $mail->clearAddresses();
    // $mail->addAddress($config['from']);
    // $mail->Subject = '【キャンペーン】エントリーがありました';
    // $mail->Body    =
    //     "ニックネーム: {$nicknameRaw}\n" .
    //     "メールアドレス: {$emailRaw}\n";
    // $mail->send();

    // 申込み者向け：自動返信（登録メールアドレス宛に完了メール）
    $mail->clearAddresses();
    $mail->addAddress($emailTrimmed);
    $mail->Subject = $mailSubject;
    $mail->Body = sprintf($mailBody, $nicknameTrimmed);

    $mail->send();

    $pdo->commit();
    unset($_SESSION['token']);
    header('Location: thanks.html');
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    $detail = $mail->ErrorInfo ?? $e->getMessage();
    error_log('[send.php] メール送信失敗: ' . $detail . ' | ' . $e->getTraceAsString());
    echo htmlspecialchars($errorMessages['mail_send_error'], ENT_QUOTES, 'UTF-8');
}
