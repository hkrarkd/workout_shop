<?php

/**
* 購入履歴にある商品の一覧を取得する
*
* @param obj $dbh DBハンドル
* @return array カートにある商品一覧配列データ
*/
function select_history($dbh, $user_id) {

  try {
  // SQL生成
  $sql = 'SELECT name, img, ec_history.create_datetime
          FROM ec_history
          JOIN ec_item_master ON ec_history.item_id = ec_item_master.item_id
          WHERE user_id = ?';
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