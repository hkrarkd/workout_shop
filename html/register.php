<?php
// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';
require_once './model/register_model.php';

// 変数定義
$username = '';
$password = '';
$data     = array();

session_start();

try {
    // DB接続
    $dbh = get_db_connect();

    // 「SIGN UP」が押されたら
    if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {

        if (isset($_POST['sign_up']) === TRUE) {

            // 送信されたトークンを変数に代入
            $token = get_post('csrf_token');

            // 送信されたトークンとセッションに保存のトークンが一致しない場合不正なリクエストとして扱う。
            if(is_valid_csrf_token($token) === FALSE) {
                set_error('Illegal request');
                // リダイレクト
                header('Location: register.php');
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
            } else if (preg_match('/^[a-zA-Z0-9]+$/', $username) !== 1) { // Usernameが半角英数字じゃなかったら
                set_error('Enter Username in half-width characters.');
            } else if (mb_strlen($username) < 6) { // Usernameが6文字以下だったら
                set_error('Enter Username more than 6 characters.');
            }
            
            if ($password === '' || preg_match('/^[\s|　]+$/', $password) === 1) { // Passwordが未入力or空白スペースだったら
                set_error('Password is required.');
            } else if (preg_match('/^[a-zA-Z0-9]+$/', $password) !== 1) { // Passwordが半角英数字じゃなかったら
                set_error('Enter Password in half-width characters.');
            } else if (mb_strlen($password) < 6) { // Passwordが6文字以下だったら
                set_error('Enter Password more than 6 characters.');
            }
            
            // Usernameが既に登録されていないかチェック
            // 
            // 入力されたUsernameと同じUsernameがあれば取得
            $data = select_username ($dbh, $username);
            // もし取得できればエラー
            if (isset($data[0]) === TRUE) {
                if ($data[0]['user_name'] == $username) {
                    set_error('The username already exists.');
                }
            }
            
            // エラーがなければ
            if (has_error() === FALSE) {
                // ユーザテーブルにデータ作成
                insert_user($dbh, $username, $password);
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
include_once './view/register_view.php';