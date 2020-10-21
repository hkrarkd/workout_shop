<?php
// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';
require_once './model/login_model.php';

// 変数定義
$username = '';
$password = '';
$data     = array();

session_start();

try {
    // DB接続
    $dbh = get_db_connect();
    
    // 「LOG IN」が押されたら
    if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
        if (isset($_POST['login']) === TRUE) {

            // 送信されたトークンを変数に代入
            $token = get_post('csrf_token');

            // 送信されたトークンとセッションに保存のトークンが一致しない場合不正なリクエストとして扱う。
            if(is_valid_csrf_token($token) === FALSE) {
                set_error('Illegal request');
                // リダイレクト
                header('Location: login.php');
                // プログラムを終了
                exit;
            }

            // トークンを破棄
            unset($_SESSION['csrf_token']);

            // 投稿内容を変数に代入
            if (isset($_POST['username']) === TRUE) {
                $username = $_POST['username'];
            }
            if (isset($_POST['password']) === TRUE) {
                $password = $_POST['password'];
            }
            
            // エラーチェック
            if ($username === '' || preg_match('/^[\s|　]+$/', $username) === 1) { // Usernameが未入力or空白スペースだったら
                set_error('Username is required.');
            }
            if ($password === '' || preg_match('/^[\s|　]+$/', $password) === 1) { // Passwordが未入力or空白スペースだったら
                set_error('Password is required.');
            }
            
            // エラーがなければ
            if (has_error() === FALSE) {
                // UsernameとPasswordが登録されているユーザ情報と一致したとき、「商品一覧ページ」に遷移する。
                // 
                // 入力されたUsernameとPasswordのユーザIDをデータベースから取得
                $data = select_user ($dbh, $username, $password);
                
                // 変数$dataに値が有れば
                if (count($data) !== 0) {
                    // セッションにデータを格納
                    $_SESSION['user_id']  = $data[0]['user_id'];
                    $_SESSION['username'] = $username;
                    // トップページに遷移
                    header('Location: itemlist.php');
                    
                } else {
                    // 変数$dataに値が無ければ
                    set_error('The username or password is incorrect.');
                }
            }
        }
    }

    // トークンを生成
    $token = get_csrf_token();

} catch (PDOException $e) {
    // 接続失敗した場合
    set_error('DBエラー：'.$e->getMessage());
}

// 「ユーザ登録ページ」ファイル読み込み
include_once './view/login_view.php';