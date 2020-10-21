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
                <?php if($username === ''){ ?>
                <p>Welcome&nbsp;!</p>
                <ul>
                    <li><a href="./cart.php" class="cart">View&nbsp;Cart</a></li>
                    <li><a href="./login.php" class="login">Login</a></li>
                    <li><a href="./history.php" class="orders">Your&nbsp;Orders</a></li>
                </ul>
                <?php } else { ?>
                <p>Welcome&nbsp;<?php print h($username); ?>!</p>
                <ul>
                    <li><a href="./cart.php" class="cart">View&nbsp;Cart</a></li>
                    <li><a href="./logout.php" class="logout">Logout</a></li>
                    <li><a href="./history.php" class="orders">Your&nbsp;Orders</a></li>
                </ul>
                <?php } ?>
            </div>
        </div>
    </header>
    <main class="main_itemlist">
        <h2>Products</h2>
        
        <!-- 商品の検索機能 --><!-- 商品の並び替え機能 -->
        <form method="get" class="sort">
            <input type="text" name="search_text" placeholder="Search Products" <?php if($search !== '') {print 'value="'.$search.'"';} ?>>
            <select name="in_order">
            <option value="New" <?php if($in_order === '' || $in_order === 'New') {print 'selected';} ?> >Newest</option>
            <option value="Low" <?php if($in_order === 'Low') {print 'selected';} ?> >Price(Low to High)</option>
            <option value="High" <?php if($in_order === 'High') {print 'selected';} ?> >Price(High to Low)</option>
            </select>
            <input type="submit" value="Search">
        </form>

        <?php foreach(get_errors() as $error){ ?>
        <p class="err_msg"><?php print h($error); ?></p>
        <?php } ?>
        <?php foreach ($data as $value) { ?>
        <form method="post">
            <ul>
                <li class="left">
                    <p><img src="<?php print $img_dir . $value['img']; ?>"></p>
                </li>
                <li class="center">
                    <p><?php print h($value['name']); ?></p>
                    <p>¥<?php print number_format($value['price']); ?></p>
                </li>
                <li class="right">
                    <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
                    <?php if ($value['stock'] === '0') { ?>
                        <p>売り切れ</p>
                    <?php } else { ?>
                    <input type="submit" name="add_to_cart" value="ADD&nbsp;TO&nbsp;CART">
                    <!-- トークンをhiddenで送信 -->
                    <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
                    <?php } ?>
                </li>
            </ul>
        </form>
        <?php } ?>

        <!-- ページネーション -->
        <?php if($page > 1){ ?>
        <a href="?page=<?php print $page - 1 ?>&search_text=<?php print $search ?>&in_order=<?php print $in_order ?>"><?php print 'Previous'; ?></a>
        <?php } ?>

        <?php for ($i=1; $i <= $total_page; $i++) { ?>
        <a href="?page=<?php print $i ?>&search_text=<?php print $search ?>&in_order=<?php print $in_order ?>"><?php print $i; ?></a>
        <?php } ?>

        <?php if($page < $total_page){ ?>
        <a href="?page=<?php print $page + 1 ?>&search_text=<?php print $search ?>&in_order=<?php print $in_order ?>"><?php print 'Next'; ?></a>
        <?php } ?>

    </main>
</body>
</html>