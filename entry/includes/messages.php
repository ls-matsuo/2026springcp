<?php
// エラーコード → 表示文言（form.php / send.php で共通利用）
$errorMessages = [
    'nickname_required'  => 'ニックネームを入力してください。',
    'nickname_duplicate' => '申し訳ございません。このニックネームはすでに使用されています。別のニックネームをお試しください。',
    'email_required'     => 'メールアドレスを入力してください。',
    'email_invalid'      => 'メールアドレスの入力形式が正しくありません。',
    'email_duplicate'    => '既にエントリー済みのメールアドレスです。',
    'agree_required'     => 'キャンペーン規約に同意してください。',
    'db_not_ready'       => 'データベースが準備されていません。スキーマ定義SQLを適用してください。',
    'mail_send_error'    => '申し訳ございません。送信処理中にエラーが発生しました。',
];

// 自動返信メール（申込み者向け）※要件で文言が決まっていなければここで一元管理
$mailSubject   = '【ENEOS Charge Plus】エントリーを受け付けました';
$mailBody = "%s 様\n\nこのたびはエントリーいただきありがとうございます。\n受け付けが完了しました。\n\n※ 本メールは自動送信です。\n";
