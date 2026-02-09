<?php
// エラーコード → 表示文言（entry/index.php | entry/includes/send.php で共通利用）
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

// 自動返信メール
// 件名
$mailSubject = '【エントリー完了】60分以上の充電で賞品抽選のチャンス！（ENEOS Charge Plus充電ランキングチャレンジ）';

// HTML本文（%s = ニックネーム）
$mailBody = 
  '<p>この度は、【ENEOS Charge Plus充電ランキングチャレンジ】にエントリーいただきありがとうございます。<br>エントリーが正常に完了しました！</p>'
. '<p>＼ 抽選参加のチャンス！ ／</p>'
. '<p>▼会員登録がお済みの方<br>キャンペーン期間中に【60分以上の充電】を行うことで、賞品抽選にご参加いただけます。<br>充電すればするほど賞品が豪華に！<br>まずは、60分以上の充電をして抽選に備えましょう！</p>'
. '<p>▼会員登録がまだの方は<a href="https://member.eneos-chargeplus.com/encms/eusers/register/email/">こちら</a><br>会員登録後、60分以上の充電を行うことで抽選対象となります。</p>'
. '<p>▼賞品の詳細は<a href="https://eneoschargeplus.com/2026springcp/">こちら</a><br>充電ランキングに応じて、当たる賞品が変わります！<br>キャンペーン期間中にたくさん充電して、豪華賞品をゲットしよう！</p>'
. '<p>※本メールは送信専用のメールアドレスで送信しております。本メールに返信いただいてもご回答できませんので、あらかじめご了承ください。</p>'
. '<hr>'
. '<p><a href="https://eneos.jp/company/privacy/">個人情報保護方針</a><br>ENEOS株式会社<br>URL：www.eneos.co.jp<br>〒100-8162東京都千代田区大手町一丁目1番2号</p>';
