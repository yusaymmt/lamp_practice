<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'cart.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($detail) > 0){ ?>
        <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php print $history_id; ?></td>
            <td><?php print $created; ?></td>
            <td><?php print(number_format($price * $amount)); ?>円</td>
          </tr>
        </tbody>
      </table>

      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php print h($name); ?></td>
            <td><?php print number_format($price); ?>円</td>
            <td><?php print $amount; ?>個</td>
            <td><?php print number_format($price * $amount); ?>円</td>
          </tr>
        </tbody>
      </table>

    <?php } else { ?>
      <p>該当する商品はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>