<?php

/**
* 入力されたUsernameのPasswordを取得
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function select_user ($dbh, $username, $password) {
  
  try {
  // SQL文を作成
  $sql = 'SELECT user_id FROM ec_user WHERE user_name = ? AND password = ?';
  // SQL文を実行する準備
  $stmt = $dbh->prepare($sql);
  // SQL文のプレースホルダに値をバインド
  $stmt->bindValue(1, $username, PDO::PARAM_STR);
  $stmt->bindValue(2, $password, PDO::PARAM_STR);
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