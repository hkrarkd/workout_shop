<?php
// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';
require_once './model/itemlist_model.php';

// 変数
$data = array();
$data_cart = array();
$img_dir     = './img/';    // アップロードした画像ファイルの保存ディレクトリ
$item_id     = '';
$user_id     = '';
$amount      = '';


session_start();

try {
    // DB接続
    $dbh = get_db_connect();

    // セッション変数からusernameを取得
    $username = get_session('username');

    // search_textフォームの入力値を取得
    $search = get_get('search_text');
    
    // 選択されている並び替えフォームの値を取得
    $in_order = get_get('in_order');

    // ページ番号を取得
    $page = get_get('page');
    if ($page === '') {
        $page = 1;
    }

    // 何番目の商品(配列の何番目)から表示するかを計算　(ページ番号 ＊ 表示数) - 表示数
    $start = ($page * 3) - 3;

    // 商品情報を取得
    $data = select_items($dbh, $search, $in_order, $start);

    // 商品テーブルの商品件数を取得する
    $data_num = count_items($dbh, $search);

    // 総ページ数を計算
    $total_page = ceil($data_num / 3);

    // 「ADD TO CART」ボタンがクリックされた場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_to_cart']) === TRUE) {

            // 送信されたトークンを変数に代入
            $token = get_post('csrf_token');

            // 送信されたトークンとセッションに保存のトークンが一致しない場合不正なリクエストとして扱う。
            if(is_valid_csrf_token($token) === FALSE) {
                set_error('Illegal request');
                // リダイレクト
                header('Location: itemlist.php');
                // プログラムを終了
                exit;
            }

            // トークンを破棄
            unset($_SESSION['csrf_token']);

            // 非ログインの場合
            if ($username === '') {
                // ログインページへリダイレクト
                header('Location: login.php');
                // プログラムを終了
                exit;
            }
        
            // 投稿内容を変数に代入
            if (isset($_POST['item_id']) === TRUE) {
                $item_id = $_POST['item_id'];
            }
            
            // ユーザIDをセッションから取得
            $user_id = $_SESSION['user_id'];
            
            // カート情報を取得
            $data_cart = select_cart($dbh, $user_id, $item_id);
            
            // カートテーブルに商品が有れば
            if (count($data_cart) !== 0) {
                $amount  = $data_cart[0]['amount'] + 1;
                $cart_id = $data_cart[0]['cart_id'];
                update_amount($dbh, $cart_id, $amount);
            } else {
                // カートテーブルに商品が無ければ
                $amount = 1;
                insert_cart($dbh, $user_id, $item_id, $amount);
            }
        }
    }

    // トークンを生成
    $token = get_csrf_token();

} catch (PDOException $e) {
    // 接続失敗した場合
    set_error('DBエラー：'.$e->getMessage());
}

// 「商品一覧ページ」ファイル読み込み
include_once './view/itemlist_view.php';