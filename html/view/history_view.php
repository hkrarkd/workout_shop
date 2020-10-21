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
    <main class="main_history">
        <h2>Your&nbsp;Orders</h2>
            <?php foreach ($data as $value) { ?>
            <ul>
                <li><img src="<?php print $img_dir . $value['img']; ?>"></li>
                <div>
                    <li><?php print h($value['name']); ?></li>
                    <li class="create_datetime">Ordered&nbsp;<?php print h($value['create_datetime']); ?></li>
                </div>
            </ul>
            <?php } ?>
    </main>
</body>
</html>