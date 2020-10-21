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
                <ul>
                    <li><a href="./cart.php" class="cart">View&nbsp;Cart</a></li>
                </ul>
            </div>
        </div>
    </header>
    <main>
        <form method="post">
            <p><input type="text" name="username" placeholder="Username"></p>
            <p><input type="password" name="password" placeholder="Password"></p>
            <p><input type="submit" name="login" value="LOG&nbsp;IN"></p>
            <!-- トークンをhiddenで送信 -->
            <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
        </form>
        <p>Don't&nbsp;you&nbsp;have&nbsp;an&nbsp;account&nbsp;yet?</p>
        <a href="./register.php">Sign&nbsp;up&nbsp;now</a>
        <?php foreach(get_errors() as $error){ ?>
        <p class="err_msg"><?php print h($error); ?></p>
        <?php } ?>
    </main>
</body>
</html>