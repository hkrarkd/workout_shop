<?php

/**
* カートにある商品の一覧を取得する
*
* @param obj $dbh DBハンドル
* @return array カートにある商品一覧配列データ
*/
function select_cart_item($dbh, $user_id) {

  try {
  // SQL生成
  $sql = 'SELECT cart_id, amount, name, price, img, user_name
          FROM ec_cart
          JOIN ec_item_master ON ec_cart.item_id = ec_item_master.item_id
          JOIN ec_user ON ec_cart.user_id = ec_user.user_id 
          WHERE ec_cart.user_id = ?';
  // SQL文を実行する準備
  $stmt = $dbh->prepare($sql);
  // SQL文のプレースホルダに値をバインド
  $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
  // SQLを実行
  $stmt->execute();
  // レコードの取得
  $rows = $stmt->fetchAll();
  } catch (PDOException $e) {
    // 例外をスロー
    throw $e;
  }
  return $rows;
}

/**
* 個数を更新する
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function update_amount($dbh, $amount, $cart_id) {
 
  try {
  // SQL文を作成
  $sql = 'UPDATE ec_cart SET amount = ? WHERE cart_id = ?';
  
  // SQL文を実行する準備
  $stmt = $dbh->prepare($sql);
  // SQL文のプレースホルダに値をバインド
  $stmt->bindValue(1, $amount,  PDO::PARAM_INT);
  $stmt->bindValue(2, $cart_id, PDO::PARAM_INT);
  // SQLを実行
  $stmt->execute();
  } catch (PDOException $e) {
    echo '接続できませんでした。理由：'.$e->getMessage();
  }
}

/**
* 商品を削除する
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function remove_cart($dbh, $cart_id) {
  
    try {
    // SQL文を作成
    $sql = 'DELETE FROM ec_cart WHERE cart_id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $cart_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    } catch (PDOException $e) {
      echo '接続できませんでした。理由：'.$e->getMessage();
    }
}