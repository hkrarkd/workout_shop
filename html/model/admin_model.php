<?php

/**
* 商品情報テーブルと在庫情報にデータ作成
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function insert_item_stock($dbh, $name, $price, $new_img_filename, $status, $stock) {
    // トランザクション開始
    $dbh->beginTransaction();
    try {
    //
    // 商品情報テーブルにデータ作成
    //  
    // SQL文を作成
    $sql = 'INSERT INTO ec_item_master (name, price, img, status, create_datetime, update_datetime)
            VALUES(?, ?, ?, ?, ?, ?)';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $name,               PDO::PARAM_STR);
    $stmt->bindValue(2, $price,              PDO::PARAM_INT);
    $stmt->bindValue(3, $new_img_filename,   PDO::PARAM_STR);
    $stmt->bindValue(4, $status,             PDO::PARAM_INT);
    $stmt->bindValue(5, date('Y-m-d H:i:s'), PDO::PARAM_STR);
    $stmt->bindValue(6, date('Y-m-d H:i:s'), PDO::PARAM_STR);
    // SQLを実行
    $stmt->execute();
    // 直近にINSERTされたIDを取得
    $item_id = $dbh->lastInsertId();
    //
    // 在庫情報テーブルにデータ作成
    //
    // SQL文を作成
    $sql = 'INSERT INTO ec_item_stock (stock_id, item_id, stock, create_datetime, update_datetime)
            VALUES(?, ?, ?, ?, ?)';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $item_id,            PDO::PARAM_INT);
    $stmt->bindValue(2, $item_id,            PDO::PARAM_INT);
    $stmt->bindValue(3, $stock,              PDO::PARAM_INT);
    $stmt->bindValue(4, date('Y-m-d H:i:s'), PDO::PARAM_STR);
    $stmt->bindValue(5, date('Y-m-d H:i:s'), PDO::PARAM_STR);
    // SQLを実行
    $stmt->execute();
    
    // コミット処理
    $dbh->commit();
    echo '商品の追加が正常に完了しました。';
    } catch (PDOException $e) {
    // ロールバック処理
    $dbh->rollback();
    // 例外をスロー
    throw $e;
    }
}

/**
* 在庫数を更新する
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function update_stock($dbh, $update_number, $stock_id) {
 
  try {
  // SQL文を作成
  $sql = 'UPDATE ec_item_stock SET stock = ?
          WHERE stock_id = ?';
  // SQL文を実行する準備
  $stmt = $dbh->prepare($sql);
  // SQL文のプレースホルダに値をバインド
  $stmt->bindValue(1, $update_number, PDO::PARAM_INT);
  $stmt->bindValue(2, $stock_id,      PDO::PARAM_INT);
  // SQLを実行
  $stmt->execute();
  echo '在庫数の変更が正常に完了しました。';
  } catch (PDOException $e) {
    echo '接続できませんでした。理由：'.$e->getMessage();
  }
}

/**
* ステータスを更新する
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function update_status($dbh, $new_status, $item_id) {
 
  try {
  // SQL文を作成
  $sql = 'UPDATE ec_item_master SET status = ?
          WHERE item_id = ?';
  // SQL文を実行する準備
  $stmt = $dbh->prepare($sql);
  // SQL文のプレースホルダに値をバインド
  $stmt->bindValue(1, $new_status, PDO::PARAM_INT);
  $stmt->bindValue(2, $item_id,    PDO::PARAM_INT);
  // SQLを実行
  $stmt->execute();
  echo '公開ステータスの変更が正常に完了しました。';
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
function delete_item_stock($dbh, $item_id) {
  // トランザクション開始
    $dbh->beginTransaction();
    try {
    //
    // 商品情報テーブルから商品を削除
    //  
    // SQL文を作成
    $sql = 'DELETE
            FROM ec_item_master
            WHERE item_id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    //
    // 在庫情報テーブルから商品を削除
    //
    // SQL文を作成
    $sql = 'DELETE
            FROM ec_item_stock
            WHERE item_id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    // コミット処理
    $dbh->commit();
    echo '商品の削除が正常に完了しました。';
    } catch (PDOException $e) {
    // ロールバック処理
    $dbh->rollback();
    // 例外をスロー
    throw $e;
    }
}

/**
* 商品の一覧を取得する
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function select_item_stock($dbh) {
 
  // SQL生成
  $sql = 'SELECT ec_item_master.item_id, name, price, img, status, stock_id, stock
          FROM ec_item_master JOIN ec_item_stock ON ec_item_master.item_id = ec_item_stock.item_id';
  // クエリ実行
  return get_as_array($dbh, $sql);
}