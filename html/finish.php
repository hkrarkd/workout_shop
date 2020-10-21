<?php
// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';
require_once './model/finish_model.php';

// 変数
$img_dir = './img/';    // アップロードした画像ファイルの保存ディレクトリ
$img     = '';
$price   = array();
$amount  = array();
$stock   = array();


session_start();

try {
    // DB接続
    $dbh = get_db_connect();

    // 送信されたトークンを変数に代入
    $token = get_post('csrf_token');

    // 送信されたトークンとセッションに保存のトークンが一致しない場合不正なリクエストとして扱う。
    if(is_valid_csrf_token($token) === FALSE) {
        set_error('Illegal request');
        // リダイレクト
        header('Location: cart.php');
        // プログラムを終了
        exit;
    }

    // トークンを破棄
    unset($_SESSION['csrf_token']);
    
    // セッション変数からuser_id取得
    if (isset($_SESSION['user_id'])) {
        $user_id  = $_SESSION['user_id'];
        $username = $_SESSION['username'];
    } else {
        // 非ログインの場合、ログインページへリダイレクト
        header('Location: login.php');
        exit;
    }
    
    // カートにある商品情報を取得
    $data = select_cart_item($dbh, $user_id);
    
    // 各商品の小計
    foreach ($data as $value) {
        $price[] = $value['price'] * $value['amount'];
    }
    // カート内の合計値(税込み価格)
    $sum = floor(array_sum($price) * 1.10);
    
    // 購入商品の在庫数を、購入数に応じて減らし、カートから商品を消し、購入履歴にデータ作成
    update_stock($dbh, $data, $user_id);
    
} catch (PDOException $e) {
  // 接続失敗した場合
  set_error('DBエラー：'.$e->getMessage());
  $dbh->rollback();
}

// 「ユーザ登録ページ」ファイル読み込み
include_once './view/finish_view.php';