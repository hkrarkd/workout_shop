<?php

/**
* ユーザテーブルにデータ作成
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function insert_user($dbh, $username, $password) {
 
  try {
  // SQL文を作成
  $sql = 'INSERT INTO ec_user (user_name, password, create_datetime, update_datetime)
          VALUES(?, ?, ?, ?)';
  // SQL文を実行する準備
  $stmt = $dbh->prepare($sql);
  // SQL文のプレースホルダに値をバインド
  $stmt->bindValue(1, $username,           PDO::PARAM_STR);
  $stmt->bindValue(2, $password,           PDO::PARAM_STR);
  $stmt->bindValue(3, date('Y-m-d H:i:s'), PDO::PARAM_STR);
  $stmt->bindValue(4, date('Y-m-d H:i:s'), PDO::PARAM_STR);
  // SQLを実行
  $stmt->execute();
  echo 'アカウント作成が正常に完了';
  } catch (PDOException $e) {
    echo '接続できませんでした。理由：'.$e->getMessage();
  }
}

/**
* 入力されたUsernameと同じUsernameがあれば取得
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function select_username ($dbh, $username) {
  
  try {
  // SQL文を作成
  $sql = 'SELECT user_name FROM ec_user WHERE user_name = ?';
  // SQL文を実行する準備
  $stmt = $dbh->prepare($sql);
  // SQL文のプレースホルダに値をバインド
  $stmt->bindValue(1, $username, PDO::PARAM_STR);
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