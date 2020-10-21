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
  $sql = 'SELECT cart_id, amount, name, price, img, stock, ec_cart.item_id
          FROM ec_cart
          JOIN ec_item_master ON ec_cart.item_id = ec_item_master.item_id
          JOIN ec_item_stock ON ec_cart.item_id = ec_item_stock.item_id
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
* 在庫数を更新する
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function update_stock($dbh, $data, $user_id) {
  // トランザクション開始
  $dbh->beginTransaction();
  
  try {
    //
    // 在庫数を更新
    //
    foreach ($data as $value) {
      // SQL文を作成
      $sql = 'UPDATE ec_item_stock
              SET stock = stock - ?, update_datetime = now()
              WHERE item_id = ?';
      // SQL文を実行する準備
      $stmt = $dbh->prepare($sql);
      // SQL文のプレースホルダに値をバインド
      $stmt->bindValue(1, $value['amount'],  PDO::PARAM_INT);
      $stmt->bindValue(2, $value['item_id'], PDO::PARAM_INT);
      // SQLを実行
      $stmt->execute();
    }
    // 
    // カートからデータを消去
    // 
    // SQL文を作成
    $sql = 'DELETE FROM ec_cart
            WHERE user_id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    // 
    // 購入履歴にデータ作成
    // 
    foreach ($data as $value) {
      $sql = 'INSERT INTO ec_history (user_id, item_id, create_datetime)
              VALUES (?, ?, ?)';
      // SQL文を実行する準備
      $stmt = $dbh->prepare($sql);
      // SQL文のプレースホルダに値をバインド
      $stmt->bindValue(1, $user_id,            PDO::PARAM_INT);
      $stmt->bindValue(2, $value['item_id'],   PDO::PARAM_INT);
      $stmt->bindValue(3, date('Y-m-d H:i:s'), PDO::PARAM_STR);
      // SQLを実行
      $stmt->execute();
    }
    
    // コミット処理
    $dbh->commit();

  } catch (PDOException $e) {
    // ロールバック処理
    $dbh->rollback();
    // 例外をスロー
    throw $e;
  }
}