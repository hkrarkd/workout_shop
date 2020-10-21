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
    <main class="main_cart">
        <h2>Your&nbsp;Cart</h2>
        <?php foreach(get_errors() as $error){ ?>
        <p class="err_msg"><?php print h($error); ?></p>
        <?php } ?>
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
                <form method="post">
                    <label>Quantity:&nbsp;<input class="quantity" type="text" name="amount" value="<?php print h($value['amount']); ?>"></label>
                    <input type="hidden" name="cart_id" value="<?php print $value['cart_id']; ?>">
                    <p><input type="submit" name="change" value="Change"></p>
                    <!-- トークンをhiddenで送信 -->
                    <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
                </form>
                <form method="post">
                    <input type="hidden" name="cart_id" value="<?php print $value['cart_id']; ?>">
                    <p><input type="submit" name="remove" value="Remove"></p>
                    <!-- トークンをhiddenで送信 -->
                    <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
                </form>
            </li>
        </ul>
        <?php } ?>
        <p class="total">Total:&nbsp;¥<?php print number_format($sum); ?></p>
        <form class="place_your_order" method="post" action="./finish.php">
            <p><input type="submit" name="place_your_order" value="Place&nbsp;your&nbsp;order"></p>
            <!-- トークンをhiddenで送信 -->
            <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
        </form>
    </main>
</body>
</html>