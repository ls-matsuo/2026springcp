<?php
session_start();

// POST かつ mode=send のときは送信処理（includes/send.php で完結）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['mode'] ?? '') === 'send') {
    require_once __DIR__ . '/includes/send.php';
    exit;
}

// CSRF トークン生成
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;

// エラー表示（送信処理から戻ってきた場合。$errors は 表示場所 => 表示文言）
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

require_once __DIR__ . '/includes/messages.php';
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title>エントリーフォーム | 30日間 充電ランキングチャレンジ | ENEOS Charge Plus</title>
    <meta name="description" content="ENEOS Charge Plus 30日間充電ランキングチャレンジにエントリーしよう！">
    <meta property="og:title" content="エントリーフォーム | 30日間 充電ランキングチャレンジ">
    <meta property="og:description" content="ENEOS Charge Plus 30日間充電ランキングチャレンジにエントリーしよう！">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://eneoschargeplus.com/2026springcp/entry/">
    <meta property="og:image" content="https://eneoschargeplus.com/2026springcp/assets/img/ogp.png">
    <meta property="og:site_name" content="ENEOS Charge Plus">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="エントリーフォーム | 30日間 充電ランキングチャレンジ">
    <meta name="twitter:description" content="ENEOS Charge Plus 30日間充電ランキングチャレンジにエントリーしよう！">
    <meta name="twitter:image" content="https://eneoschargeplus.com/2026springcp/assets/img/ogp.png">
    <link rel="icon" href="/img/common/favicon.ico" sizes="any">
    <link rel="icon" href="/img/common/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/img/common/apple-touch-icon.png">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@400;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <script type="module" crossorigin src="../assets/main.js"></script>
    <link rel="stylesheet" crossorigin href="../assets/style.css">
  </head>
  <body>
    <!-- Header -->
    <header class="l-header">
      <div class="l-header__logo">
        <img src="../img/logo_eneos.svg" alt="ENEOS" width="103" height="24">
      </div>
    </header>

    <main class="l-main l-main--entry">
      <article class="p-entry">
        <!-- Hero -->
        <header class="p-entry__hero">
          <h1 class="p-entry__logo">
            <img src="../img/logo_challenge.svg" alt="ENEOS Charge Plus 30日間 充電ランキングチャレンジ">
          </h1>
          <p class="p-entry__subtitle">エントリーフォーム</p>
          <p class="p-entry__lead">参加対象者：ENEOS Charge Plus会員の方</p>
        </header>

        <!-- Info Box -->
        <div class="p-entry__section">
          <div class="c-info-box">
            <p>エントリー受付期間：2026年2月19日(木)〜3月31日(火)23:59</p>
            <p>ランキング集計対象充電期間：2026年3月1日(日)〜2026年3月31日(火)23:59まで</p>
          </div>

          <!-- Form -->
          <form class="c-form" action="index.php" method="post">
            <input type="hidden" name="mode" value="send">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">

            <?php
            $summaryErrors = array_diff_key($errors, array_flip(['nickname', 'email']));
            if (!empty($summaryErrors)):
            ?>
            <div class="c-form__errors" role="alert">
              <?php foreach ($summaryErrors as $location => $msg): ?>
              <p class="c-form__error"><?php echo htmlspecialchars(is_string($msg) ? $msg : ($errorMessages[$msg] ?? $msg), ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- ニックネーム -->
            <div class="c-form__group">
              <label class="c-form__label" for="nickname">ニックネーム</label>
              <input
                type="text"
                id="nickname"
                name="nickname"
                class="c-form__input"
                placeholder="全角10文字以内でご入力ください"
                maxlength="10"
                value="<?php echo htmlspecialchars($old['nickname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                required
              >
              <p class="c-form__note">※ 全角10文字以内でご入力ください</p>
              <p class="c-form__note">※ 不適切なニックネームの場合は参加をお断りする場合がございます</p>
              <?php if (isset($errors['nickname'])): ?>
              <p class="c-form__note c-form__note--error">× <?php echo htmlspecialchars($errorMessages[$errors['nickname']] ?? $errors['nickname'], ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endif; ?>
            </div>

            <!-- メールアドレス -->
            <div class="c-form__group">
              <label class="c-form__label" for="email">メールアドレス</label>
              <input
                type="email"
                id="email"
                name="email"
                class="c-form__input"
                placeholder="example@email.com"
                value="<?php echo htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                required
              >
              <p class="c-form__note">※ ENEOS Charge Plusにご登録されているメールアドレスを入力してください。登録情報と照合できない場合、エントリーは無効となります。</p>
              <?php if (isset($errors['email'])): ?>
              <p class="c-form__note c-form__note--error">× <?php echo htmlspecialchars($errorMessages[$errors['email']] ?? $errors['email'], ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endif; ?>
            </div>

            <!-- 規約 -->
            <div class="c-form__group">
              <div class="c-terms">
                <p class="c-terms__title">キャンペーン規約</p>
                <div class="c-terms__content">
                  <p>ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。</p>
                  <p>ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。</p>
                  <p>ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。ここに規約が入ります。これはダミーです。</p>
                </div>
              </div>
            </div>

            <!-- 同意チェック -->
            <div class="c-form__group">
              <label class="c-form__checkbox">
                <input type="checkbox" name="agree" id="agree" required>
                <span>キャンペーン規約に同意する</span>
              </label>
            </div>

            <!-- Submit -->
            <div class="p-entry__submit-area">
              <div class="c-button c-button--submit">
                <button type="submit" class="c-button__link">エントリーする</button>
              </div>
            </div>
          </form>
        </div>
      </article>
    </main>

    <!-- Footer -->
    <footer class="l-footer">
      <p>Copyright© ENEOS Corporation All Rights Reserved.</p>
    </footer>

    <!-- Scripts -->
  </body>
</html>
