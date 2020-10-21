<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Workout&nbsp;Shop</title>
    <link link type="text/css" rel="stylesheet" href="./css/common.css">
</head>
<body>
    <header>
        <div class="header-box">
            <div class="header-left">
                <a href="./itemlist.php">
                    <img src="images/workout_shop_logo.png" alt="workout_shop">
                </a>
            </div>
            <div class="header-right">
                <p>Welcome&nbsp;<?php print h($username); ?>!</p>
                <ul>
                    <li><a href="./cart.php" class="cart">View&nbsp;Cart</a></li>
                    <li><a href="./logout.php" class="logout">Logout</a></li>
                    <li><a href="./history.php" class="orders">Your&nbsp;Orders</a></li>
                </ul>
            </div>
        </div>
    </header>
    <main class="main_finish">
        <!--エラーが有れば-->
        <?php if (has_error() === TRUE) {
            foreach(get_errors() as $error){ ?>
            <p class="err_msg"><?php print h($error); ?></p>
        <?php } ?>
        
        <!--エラーが無ければ-->
        <?php } else { ?>
        <h2>Thank&nbsp;you&nbsp;for&nbsp;placing&nbsp;your&nbsp;order&nbsp;with&nbsp;us.</h2>
            <?php foreach ($data as $value) { ?>
            <ul>
                <li class="left">
                    <p><img src="<?php print $img_dir . $value['img']; ?>"></p>
                </li>
                <li class="center">
                    <p><?php print h($value['name']); ?></p>
                    <p>￥<?php print number_format($value['price']); ?></p>
                </li>
                <li class="right">
                    <p>Quantity:&nbsp;<?php print number_format($value['amount']); ?></p>
                </li>
            </ul>
            <?php } ?>
            <p class="total">Total:&nbsp;￥<?php print number_format($sum); ?></p>
            <?php } ?>
    </main>
</body>
</html>