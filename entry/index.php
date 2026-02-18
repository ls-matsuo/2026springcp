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
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-5R2JTFTF');</script>
  <!-- End Google Tag Manager -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <title>エントリーフォーム | 会員みんなで挑戦！EV充電ランキングチャレンジ【ENEOS Charge Plus】</title>
  <meta name="description" content=" 会員みんなで挑戦！EV充電ランキングチャレンジ【ENEOS Charge Plus】">
  <meta property="og:title"
        content="エントリーフォーム | 会員みんなで挑戦！EV充電ランキングチャレンジ【ENEOS Charge Plus】">
  <meta property="og:description" content=" 会員みんなで挑戦！EV充電ランキングチャレンジ【ENEOS Charge Plus】">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://eneoschargeplus.com/2026springcp/entry/">
  <meta property="og:image" content="https://eneoschargeplus.com/2026springcp/img/ogp.png">
  <meta property="og:site_name" content="ENEOS Charge Plus">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title"
        content="エントリーフォーム |  会員みんなで挑戦！EV充電ランキングチャレンジ【ENEOS Charge Plus】">
  <meta name="twitter:description" content=" 会員みんなで挑戦！EV充電ランキングチャレンジ【ENEOS Charge Plus】">
  <meta name="twitter:image" content="https://eneoschargeplus.com/2026springcp/img/ogp.png">
  <link rel="icon" href="/2026springcp/img/favicon.ico" sizes="any">
  <link rel="apple-touch-icon" href="/2026springcp/img/apple-touch-icon.png">

  <!-- Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@400;700;900&display=swap"
        rel="stylesheet">

  <!-- Styles -->
  <script type="module" crossorigin src="/2026springcp/assets/main.js"></script>
  <link rel="stylesheet" crossorigin href="/2026springcp/assets/style.css">
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5R2JTFTF"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
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
      <p class="p-entry__lead">参加対象者：ENEOS Charge Plus個人会員の方</p>
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
              <p
                class="c-form__error"><?php echo htmlspecialchars(is_string($msg) ? $msg : ($errorMessages[$msg] ?? $msg), ENT_QUOTES, 'UTF-8'); ?></p>
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
            placeholder="10文字以内でご入力ください"
            maxlength="10"
            value="<?php echo htmlspecialchars($old['nickname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
            required
          >
          <p class="c-form__note">※ 10文字以内でご入力ください</p>
          <p class="c-form__note">※ 不適切なニックネームの場合は参加をお断りする場合がございます</p>
          <p class="c-form__note">※ ひらがな・カタカナ・漢字・英数字・ハイフン・アンダースコアのみ使用できます</p>
          <?php if (isset($errors['nickname'])): ?>
            <p class="c-form__note c-form__note--error">
              × <?php echo htmlspecialchars($errorMessages[$errors['nickname']] ?? $errors['nickname'], ENT_QUOTES, 'UTF-8'); ?></p>
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
          <p class="c-form__note">※ ENEOS Charge
            Plusにご登録されているメールアドレスを入力してください。登録情報と照合できない場合、エントリーは無効となります。</p>
          <?php if (isset($errors['email'])): ?>
            <p class="c-form__note c-form__note--error">
              × <?php echo htmlspecialchars($errorMessages[$errors['email']] ?? $errors['email'], ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>
        </div>

        <!-- 規約 -->
        <div class="c-form__group">
          <div class="c-terms">
            <p class="c-terms__title">キャンペーン規約</p>
            <div class="c-terms__content">
              <p>□キャンペーン名称<br>
                会員みんなで挑戦！EV充電ランキングチャレンジ　～EV充電を通じて、地球にやさしいアクションを～</p>

              <p>□キャンペーン概要<br>
                ENEOS Charge Plus会員のうち、下記キャンペーン実施期間中にENEOS Charge
                Plus急速充電器のご利用時間のランキングによって、表彰盾および最大3万円のカタログギフトをプレゼントするキャンペーン（以下「本キャンペーン」といいます）です。<br>
                ※キャンペーンへのエントリーが必要です。詳細は、以下「応募条件・応募方法」をご確認ください。</p>

              <p>□本キャンペーン実施期間<br>
                2026年3月1日（日）00:00 ～ 2026年3月31日（火）23:59</p>

              <p>□エントリー対象期間<br>
                2026年2月19日（木）～ 2026年3月31日（火）23:59</p>

              <p>□応募条件・応募方法<br>
                応募条件：以下①～③をすべて満たした人が対象です。エントリーと充電利用はどちらが先でも対象となります。</p>
              <ol>
                <li>1. キャンペーンサイト（URL:
                  https://eneoschargeplus.com/2026springcp/）からエントリーが完了していること
                </li>
                <li>2. 2026年4月1日時点で、ENEOS Charge Plus会員であること</li>
                <li>3. キャンペーン期間内に対象決済手段<sup>※1</sup>でENEOS Charge Plus急速充電器<sup>※2</sup>を累計60分<sup>※3</sup>以上利用していること<br>
                  <span>※1対象決済手段：ENEOS Charge Plusアプリ、ENEOS Charge Plus充電会員カード、ENEOS Charge Plus会員情報に連携したEneKeyもしくはモバイルEneKey、会員ワンタイムパスワード</span><br>
                  <span>※2 提携充電器も対象です。</span><br>
                  <span>※3 キャンペーン期間内に充電開始したものが累計対象です。</span></li>
              </ol>
              <p>応募方法：キャンペーンサイトのエントリーボタンより入力フォームへアクセスのうえ、ENEOS Charge
                Plus会員情報にご登録のメールアドレスおよびニックネームを入力いただくことにより、エントリー完了となります。<br>
                ※エントリーは1度していただければ、2度目は必要ありません。<br>
                ※上記エントリー期間中にエントリーいただくことでご応募完了となります。充電利用はエントリーの前と後のいずれであっても本キャンペーンの対象となります。
              </p>

              <p>□賞品および当選者数<br>
                ご契約されている料金プラン別（プレミアムプラン、PHEVプランシンプルプラン<sup>※</sup>）でランキングを集計し、賞品を贈呈いたします。<br>
                TOP賞｜上位10名：表彰盾＋3万円分のカタログギフト<br>
                ②A賞｜上位10％（TOP賞を除く）のうち、抽選で10名：1.5万円分のカタログギフト<br>
                ③B賞｜上位11%～上位20％のうち、抽選で10名：1万円分のデジタルギフト（えらべるPay）<br>
                ④C賞｜上位21%～上位30％のうち、抽選で10名：5000円分のデジタルギフト（えらべるPay）<br>
                ⑤参加賞｜上位31%～60分以上利用した方のうち、抽選で50名：3000円分のデジタルギフト（えらべるPay）<br>
                ※シンプルプランには、過去のキャンペーンプランを含みます。<br>
                なお、TOP賞のうち充電分数が同率となった場合、当社側で抽選した上で対象を決定いたします。</p>

              <p>□デジタルギフト（えらべるPay）について<br>
                ・えらべるPayは、下記のポイントやPay系の商品から自由に選んで交換できるギフトです。<br>
                ・えらべるPayの利用に専用アプリのダウンロードや会員登録は必要ありません。<br>
                ・ラインナップの中から好きな商品と交換していただけます。<br>
                ・ポイント数は受け取ったチケット券面をご確認ください。<br>
                ・ラインナップおよび交換に必要なポイントは付与されたギフトにより異なり、変更になる場合がございます。<br>
                ・ポイント交換レートは商品により異なります。記載された必要ポイント数をよくご確認の上、商品と交換してください。<br>
                ・ラインナップは随時変更となる場合がございます。<br>
                ・ポイントの利用には期限がございます。ホーム画面に表示された期限までにお好きな商品と交換ください。<br>
                ・利用期限終了後、ポイント残高は失効します。ポイントの払い戻しはお受けしておりません。<br>
                ・ポイントの追加チャージはできません。<br>
                ・商品交換後の商品の変更・キャンセルはできません。<br>
                ※各種ポイントの名称およびそれらのロゴは当該ポイントサービスの運営者またはその関連会社の商標です。本キャンペーンはENEOS株式会社による提供です。本キャンペーンについてのお問い合わせはポイントサービスの運営者ではお受けしておりません。
              </p>

              <p>□当選発表および賞品の発送について<br>
                ・当選発表は、賞品発送をもってかえさせていただきます。<br>
                ・抽選結果に関するお問い合わせにはお答えできかねますので、ご了承ください。<br>
                ・賞品の発送は2026年5月中を予定しておりますが、諸般の事情により前後する場合がございます<br>
                ・厳正なる抽選のうえ、当選者様を決定いたします。<br>
                ・賞品につきましては、カタログギフトは会員登録をされているご自宅等宛てに郵送にてお届けし、デジタルギフト（えらべるPay）は、ENEOS
                Charge
                Plus会員情報にご登録いただいたメールアドレス宛てにお送りいたします。なお、転居や長期間のご不在、メールアドレスの使用中止やメール配信サーバー等の不調といった諸事情により、当選者様が賞品をお受け取りになれなかった場合は、当選は取り消しとさせていただきます。<br>
                ・賞品の送付は、日本国内の居住者に限らせていただきます。<br>
                ・やむを得ない事情により賞品発送のご連絡が遅れる場合もございますのであらかじめご了承ください。<br>
                ・当選権利はご本人のみが得られるものとし、他者への譲渡はできません。</p>

              <p>□応募上の注意<br>
                ・本キャンペーンサイトの利用停止、もしくは不能による損害について当社および当社が委託する運営事務局は一切責任を負いません（各種Webサービスのサーバダウン等も含む）。<br>
                ・本キャンペーンのご応募によりいただいた情報は、「ENEOS Charge
                Plus利用規約」および「個人情報の取扱いに関する重要事項」を遵守のうえ、適切に利用させていただきます。<br>
                ・スマートフォンからのご応募の場合、機種によってはご利用できない場合がございます。<br>
                ・フィーチャーフォン（ガラケー）からのご応募はできません。<br>
                ・インターネット接続料および通信料は会員様のご負担となります。<br>
                ・会員ご本人様のご応募に限らせていただきます。他人名義でのご応募はできません。<br>
                ・未成年の方はご応募いただけません。<br>
                ・法人名義ではご応募いただけません。<br>
                ・応募時にご入力いただいたメールアドレスとENEOS Charge Plusの会員登録情報のメールアドレスが一致しない場合は、応募は無効となります。<br>
                これらの事項に違反した場合、当選を取り消させていただきますので予めご了承ください。</p>

              <p>□応募等に関する諸注意<br>
                ・全ての賞品の画像はイメージです。賞品の内容・仕様・デザインはお断りなく変更させていただく場合があります。あらかじめご了承ください。<br>
                ・不正な行為があると判断した場合は、ご応募・ご当選の権利を無効といたします。<br>
                ・本キャンペーンの抽選結果および応募締切後のお問い合わせへの対応は一切できませんのでご了承ください。<br>
                ・ご当選された賞品をインターネットオークション等で出品することはおやめください。<br>
                万が一出品された場合、当社は一切の責任を負いません。<br>
                ・ご当選の権利はご当選er様本人に限り有効で、家族、友人等第三者への譲渡、転売、換金はできません。ご当選者様と第三者との間でトラブルが生じた場合、もしくは第三者に対して損害を与えた場合、ご当選者様は自己の責任と費用において解決するものとし、当社に何等の迷惑もしくは損害を与えないものとします。<br>
                ・運営事務局から当選のご連絡をした場合であっても、本注意事項にご了承いただけない場合や本注意事項の規定に違反する場合などは、当選を無効とさせていただく場合がございます。<br>
                ・通信機器、通信回線、システム障害、瑕疵等により、または誤送信もしくは欠陥が生じた場合に応募者が被った損害については、当社はその責任を負いません。<br>
                ・本キャンペーンは事前の予告なく中断・停止する場合があります。あらかじめご了承ください。<br>
                ・上記キャンペーン概要は予告なく変更になる場合がございます。<br>
                ・著作権法で認められている範囲を超えて、本キャンペーンに掲載されているコンテンツを無断で複製・改変・出版・アップロード・掲示・配布することを禁じます。<br>
                ・キャンペーンにおける当選は、お一人様につき1賞までとし、複数の賞に重複して当選することはありません。なお、いずれの賞に該当するかは、当社の判断により決定します。
              </p>

              <p>□免責事項について<br>
                ・当社は本キャンペーン参加・応募に伴う使用機器の損傷、通信における障害・損害など、本キャンペーンサイト利用に関連して生じた一切の損害について、一切責任を負いません。<br>
                ・当社は本キャンペーンサイトの利用の一部または全部を事前に通知することなく、変更・中止・終了することがあります。なお、変更・中止・終了により生じた損害については、一切責任を負いません。<br>
                ・本キャンペーンサイトに掲載する情報・キャンペーンサイトプログラム提供、その他キャンペーンに関する全ての事項について、細心の注意を払っておりますが、それらの完全性、正確性、安全性について、いかなる保証も行うものではありません。
              </p>

              <p>□個人情報の取り扱いについて<br>
                ・ご応募いただいた方の個人情報は、当社および当社が指定する本キャンペーンの運営業務委託先で厳重に管理し、第三者に開示・提供することはございません（法令などにより開示を求められた場合を除く）。<br>
                ・当社は、本キャンペーンへの応募に関する情報の流出・漏洩の防止、その他個人情報の安全管理のために必要かつ適切な措置を講じるものとし、法令等に基づく正当な理由がある場合を除き、会員様の同意なく目的外での利用、および本キャンペーンの実施に関する業務委託先以外の第三者への提供はいたしません。<br>
                ※ENEOS株式会社個人情報ポリシー（ URL：<a href="https://www.eneos.co.jp/privacy/" target="_blank"
                                                       rel="noopener">https://www.eneos.co.jp/privacy/</a>）<br>
                ・適切な運用を行うために当社が必要と判断した場合には、本キャンペーンの内容は予告なく変更となる場合がございます。またこの応募条件・応募方法等を定めた規約は、当社の判断により随時改定する場合がございます。この場合、当社の本キャンペーンサイトに掲載することにより、本キャンペーン内容の変更またはこの規約の改定の内容を随時告知するものとします。当該告知以降、当該変更または改定がなされた内容が、ご応募されたすべての方と当社との間で適用されるものとしますので予めご了承ください。
              </p>

              <p>□キャンペーンおよびENEOS Charge Plusに関するお問い合わせ先<br>
                ENEOS Charge Plusコールセンター<br>
                TEL:　0570－06－1232<br>
                受付時間：土・日・祝日・年末年始を除く　9:00~18:00</p>
            </div>
          </div>
        </div>

        <!-- 同意チェック -->
        <div class="c-form__group">
          <label class="c-form__checkbox">
            <input type="checkbox" name="agree" id="agree" required>
            <span>キャンペーン規約に同意する</span>
          </label>
          <?php if (isset($errors['agree'])): ?>
            <p class="c-form__note c-form__note--error">
              × <?php echo htmlspecialchars($errorMessages[$errors['agree']] ?? $errors['agree'], ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>
        </div>

        <!-- Submit -->
        <div class="p-entry__submit-area">
          <div class="c-button c-button--submit">
            <button type="submit" class="c-button__link"><span class="c-button__text">エントリーする</span></button>
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
