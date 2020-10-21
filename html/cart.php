<?php
// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';
require_once './model/cart_model.php';

// 変数
$data    = array();
$img_dir = './img/';    // アップロードした画像ファイルの保存ディレクトリ
$user_id = '';
$username = '';
$amount  = '';
$cart_id = '';
// $price = array();
$price   = 0;
$sum     = '';


session_start();

try {
    // DB接続
    $dbh = get_db_connect();
    
    // 「Change」が押されたら
    if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
        if (isset($_POST['change']) === TRUE) {
        
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

            // 投稿内容を変数に代入
            if (isset($_POST['amount']) === TRUE) {
                $amount = $_POST['amount'];
            }
            if (isset($_POST['cart_id']) === TRUE) {
                $cart_id = $_POST['cart_id'];
            }
            
            // エラーチェック
            if ($amount === '' || preg_match('/^[\s|　]+$/', $amount) === 1) { // Quantityが未入力or空白スペースだったら
                set_error('Enter Quantity');
            } else if (preg_match('/^[0-9]+$/', $amount) !== 1) { // Quantityに半角数字以外が入力されたら
                set_error('Enter Quantity greater than zero');
            } else if ($amount == 0) { // Quantityに0が入力されたら
                // 商品をカートから削除する
                remove_cart($dbh, $cart_id);
            }
            
            // エラーがなければ、個数を更新
            if (has_error() === FALSE) {
                // カートテーブルの個数更新
                update_amount($dbh, $amount, $cart_id);
            }
        }
    }

    // 「Remove」が押されたら
    if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
        if (isset($_POST['remove']) === TRUE) {
    
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

            // 投稿内容を変数に代入
            if (isset($_POST['cart_id']) === TRUE) {
            $cart_id = $_POST['cart_id'];
            }
    
            // 商品をカートから削除する
            remove_cart($dbh, $cart_id);
        }
    }
    
    // セッション変数からuser_id取得
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
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
        // $price[] = $value['price'] * $value['amount'];
        $price += $value['price'] * $value['amount'];
    }
    // カート内の合計値(税込み価格)
    // $sum = floor(array_sum($price) * 1.10);
    $sum = floor($price * 1.10);

    // トークンを生成
    $token = get_csrf_token();
    
} catch (PDOException $e) {
  // 接続失敗した場合
  set_error('DBエラー：'.$e->getMessage());
}

// 「ユーザ登録ページ」ファイル読み込み
include_once './view/cart_view.php';