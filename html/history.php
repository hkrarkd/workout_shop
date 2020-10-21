<?php
// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';
require_once './model/history_model.php';

// 変数
$data     = array();
$img_dir  = './img/';    // アップロードした画像ファイルの保存ディレクトリ
$user_id  = '';
$username = '';
$err_msg  = array();

session_start();

try {
    // DB接続
    $dbh = get_db_connect();
    
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
    $data = select_history($dbh, $user_id);
    
} catch (PDOException $e) {
  // 接続失敗した場合
  set_error('DBエラー：'.$e->getMessage());
}

// 「ユーザ登録ページ」ファイル読み込み
include_once './view/history_view.php';