<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Workout&nbsp;Shop</title>
    <link link type="text/css" rel="stylesheet" href="./css/admin.css">
</head>
<body>
    <h1>Workout&nbsp;Shop&nbsp;管理ツール</h1>
    <h2>新規商品追加</h2>
    <?php foreach(get_errors() as $error){ ?>
    <p class="err_msg"><?php print h($error); ?></p>
    <?php } ?>
    <form method="post" enctype="multipart/form-data">
        <p><label>名前：<input type="text" name="name"></label></p>
        <p><label>値段：<input type="text" name="price"></label></p>
        <p><label>個数：<input type="text" name="number"></label></p>
        <p><input type="file" name="new_img"></p>
        <p>
            <select name="status">
                <option value="0">非公開</option>
                <option value="1">公開</option>
            </select>
        </p>
        <p><input type="submit" name="new_submit" value="商品を追加"></p>
        <!-- トークンをhiddenで送信 -->
        <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
    </form>
    <h2>商品情報変更</h2>
    <p>商品一覧</p>
    <table border='1'>
        <tr>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫数</th>
            <th>ステータス</th>
            <th>削除</th>
        </tr>
        <?php foreach ($data as $value) { ?>
        <tr>
            <td><img src="<?php print $img_dir . $value['img']; ?>"></td>
            <td><?php print h($value['name']); ?></td>
            <td><?php print number_format($value['price']) . '円'; ?></td>
            <td>
                <form method="post">
                    <label><input type="text" name="update_number" value="<?php print h($value['stock']); ?>">個</label>
                    <input type="hidden" name="stock_id" value="<?php print $value['stock_id']; ?>">
                    <input type="submit" name="update_submit" value="変更">
                    <!-- トークンをhiddenで送信 -->
                    <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
                </form>
            </td>
            <td>
                <form method="post">
                    <?php if ($value['status'] === 1) { ?>
                    <input type="submit" name="status_submit" value="公開→非公開">
                    <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
                    <input type="hidden" name="new_status" value="0">
                    <?php } elseif ($value['status'] === 0) { ?>
                    <input type="submit" name="status_submit" value="非公開→公開">
                    <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
                    <input type="hidden" name="new_status" value="1">
                    <?php } ?>
                    <!-- トークンをhiddenで送信 -->
                    <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
                </form>
            </td>
            <td>
                <form method='post'>
                    <input type="submit" name="delete_submit" value="削除">
                    <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
                    <!-- トークンをhiddenで送信 -->
                    <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>