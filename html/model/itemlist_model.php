<?php

/**
* 「公開」商品の一覧を取得する
*
* @param obj $dbh DBハンドル
* @return array 「公開」商品一覧配列データ
*/
function select_items($dbh, $search, $in_order, $start) {
 
  try {
  // SQL生成
  $sql = 'SELECT ec_item_master.item_id, name, price, img, stock, ec_item_master.create_datetime
          FROM ec_item_master JOIN ec_item_stock ON ec_item_master.item_id = ec_item_stock.item_id
          WHERE status = 1 AND name LIKE ?';
          if($in_order === '' || $in_order === 'New'){
            $sql .= ' ORDER BY create_datetime DESC';
          } elseif ($in_order === 'Low') {
            $sql .= ' ORDER BY price';
          } elseif ($in_order === 'High') {
            $sql .= ' ORDER BY price DESC';
          }
          $sql .= ' LIMIT ?, 3';
  // SQL文を実行する準備
  $stmt = $dbh->prepare($sql);
  // SQL文のプレースホルダに値をバインド
  $stmt->bindValue(1, '%'.$search.'%', PDO::PARAM_STR);
  $stmt->bindValue(2, $start, PDO::PARAM_INT);
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

function count_items($dbh, $search) {

  try {
  // SQL生成
  $sql = 'SELECT COUNT(*)
          FROM ec_item_master JOIN ec_item_stock ON ec_item_master.item_id = ec_item_stock.item_id
          WHERE status = 1 AND name LIKE ?';

  // SQL文を実行する準備
  $stmt = $dbh->prepare($sql);
  // SQL文のプレースホルダに値をバインド
  $stmt->bindValue(1, '%'.$search.'%', PDO::PARAM_STR);
  // SQLを実行
  $stmt->execute();
  // レコードの取得
  return $stmt->fetchColumn();
  } catch (PDOException $e) {
    // 例外をスロー
    throw $e;
  }
}

/**
* 「公開」商品の一覧を取得する
*
* @param obj $dbh DBハンドル
* @return array 「公開」商品一覧配列データ
*/
function select_cart($dbh, $user_id, $item_id) {
  
  try {
  // SQL生成
  $sql = 'SELECT cart_id, user_id, item_id, amount
          FROM ec_cart WHERE user_id = ? AND item_id = ?';
  // SQL文を実行する準備
  $stmt = $dbh->prepare($sql);
  // SQL文のプレースホルダに値をバインド
  $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
  $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
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
* カートテーブルにデータ作成
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function insert_cart($dbh, $user_id, $item_id, $amount) {
 
  try {
  // SQL文を作成
  $sql = 'INSERT INTO ec_cart (user_id, item_id, amount, create_datetime, update_datetime)
          VALUES(?, ?, ?, ?, ?)';
  // SQL文を実行する準備
  $stmt = $dbh->prepare($sql);
  // SQL文のプレースホルダに値をバインド
  $stmt->bindValue(1, $user_id,            PDO::PARAM_INT);
  $stmt->bindValue(2, $item_id,            PDO::PARAM_INT);
  $stmt->bindValue(3, $amount,             PDO::PARAM_INT);
  $stmt->bindValue(4, date('Y-m-d H:i:s'), PDO::PARAM_STR);
  $stmt->bindValue(5, date('Y-m-d H:i:s'), PDO::PARAM_STR);
  // SQLを実行
  $stmt->execute();
  } catch (PDOException $e) {
    echo '接続できませんでした。理由：'.$e->getMessage();
  }
}

/**
* カートの個数を更新する
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function update_amount($dbh, $cart_id, $amount) {
 
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