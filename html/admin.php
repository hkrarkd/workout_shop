<?php
// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';
require_once './model/admin_model.php';
 
// 変数定義
$name             = '';
$price            = '';
$stock            = '';
$status           = '';
$item_id          = '';
$update_number    = '';
$new_status       = '';
$img_dir          = './img/';    // アップロードした画像ファイルの保存ディレクトリ
$data             = array();
$new_img_filename = '';   // アップロードした新しい画像ファイル名


session_start();

try {
    // DB接続
    $dbh = get_db_connect();

    // 「商品追加」が押されたら
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['new_submit']) === TRUE) {

            // 送信されたトークンを変数に代入
            $token = get_post('csrf_token');

            // 送信されたトークンとセッションに保存のトークンが一致しない場合不正なリクエストとして扱う。
            if(is_valid_csrf_token($token) === FALSE) {
            set_error('Illegal request');
            // リダイレクト
            header('Location: admin.php');
            // プログラムを終了
            exit;
            }

            // トークンを破棄
            unset($_SESSION['csrf_token']);
            
            // 投稿内容を変数に代入
            if (isset($_POST['name']) === TRUE) {
                $name = $_POST['name'];
            }
            if (isset($_POST['price']) === TRUE) {
                $price = $_POST['price'];
            }
            if (isset($_POST['number']) === TRUE) {
                $stock = $_POST['number'];
            }
            if (isset($_POST['status']) === TRUE) {
                $status = $_POST['status'];
            }
            
            // エラーチェック
            if ($name === '' || preg_match('/^[\s|　]+$/', $name) === 1) {
            set_error('「名前」が未入力です。');
            }
            if ($price === '') {
                set_error('「値段」が未入力です。');
            } else if (preg_match('/^[0-9]+$/', $price) !== 1) {
                set_error('「値段」は0以上の整数を入力してください。');
            }
            if ($stock === '') {
                set_error('「個数」が未入力です。');
            } else if (preg_match('/^[0-9]+$/', $stock) !== 1) {
                set_error('「個数」は0以上の整数を入力してください。');
            }
            if ($status !== '0' && $status !== '1') {
                set_error('公開ステータスの値が不正です。');
            }
            
            // HTTP POST でファイルがアップロードされたかどうかチェック
            if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
            // 画像の拡張子を取得
            $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
            // 拡張子を小文字に変換
            $extension = strtolower($extension);
            // 指定の拡張子であるかどうかチェック
            if ($extension === 'jpeg' || $extension === 'jpg' || $extension === 'png') {
              // 保存する新しいファイル名の生成（ユニークな値を設定する）
              $new_img_filename = sha1(uniqid(mt_rand(), true)). '.' . $extension;
              // 同名ファイルが存在するかどうかチェック
              if (is_file($img_dir . $new_img_filename) !== TRUE) {
                // アップロードされたファイルを指定ディレクトリに移動して保存
                if (move_uploaded_file($_FILES['new_img']['tmp_name'], $img_dir . $new_img_filename) !== TRUE) {
                    set_error('ファイルアップロードに失敗しました');
                }
              } else {
                set_error('ファイルアップロードに失敗しました。再度お試しください。');
              }
            } else {
                set_error('ファイル形式が異なります。画像ファイルは「JPEG」「PNG」のみ利用可能です。');
            }
          } else {
            set_error('ファイルを選択してください');
          }
        }
    }
    
    // エラーがなく、「商品追加」が押されたら
    if (has_error() === FALSE && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
        if (isset($_POST['new_submit']) === TRUE) {
            // 商品情報テーブルと在庫情報にデータ作成
            insert_item_stock($dbh,$name,$price,$new_img_filename,$status,$stock);
        }
    }
    
    // 在庫数の「変更」が押されたら
    if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
        if (isset($_POST['update_submit']) === TRUE) {

            // 送信されたトークンを変数に代入
            $token = get_post('csrf_token');

            // 送信されたトークンとセッションに保存のトークンが一致しない場合不正なリクエストとして扱う。
            if(is_valid_csrf_token($token) === FALSE) {
                set_error('Illegal request');
                // リダイレクト
                header('Location: admin.php');
                // プログラムを終了
                exit;
            }

            // トークンを破棄
            unset($_SESSION['csrf_token']);
            
            // 投稿内容を変数に代入
            if (isset($_POST['update_number']) === TRUE) {
                $update_number = $_POST['update_number'];
            }
            if (isset($_POST['stock_id']) === TRUE) {
                $stock_id = $_POST['stock_id'];
            }
            
            // エラーチェック
            if ($update_number === '') {
                set_error('「在庫数」が未入力です。');
            } else if (preg_match('/^[0-9]+$/', $update_number) !== 1) {
                set_error('「在庫数」は0以上の整数を入力してください。');
            }
            
            // エラーがなければ、在庫数を更新
            if (has_error() === FALSE) {
                // 在庫情報テーブルのデータ更新
                update_stock($dbh, $update_number, $stock_id);
            }
        }
    }
    
    // ステータスボタンが押されたら
    if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
        if (isset($_POST['status_submit']) === TRUE) {

            // 送信されたトークンを変数に代入
            $token = get_post('csrf_token');

            // 送信されたトークンとセッションに保存のトークンが一致しない場合不正なリクエストとして扱う。
            if(is_valid_csrf_token($token) === FALSE) {
                set_error('Illegal request');
                // リダイレクト
                header('Location: admin.php');
                // プログラムを終了
                exit;
            }

            // トークンを破棄
            unset($_SESSION['csrf_token']);
            
            // 投稿内容を変数に代入
            if (isset($_POST['item_id']) === TRUE) {
                $item_id = $_POST['item_id'];
            }
            if (isset($_POST['new_status']) === TRUE) {
                $new_status = $_POST['new_status'];
            }
            
            // エラーチェック
            if ($new_status !== '0' && $new_status !== '1') {
                set_error('公開ステータスの値が不正です。');
            }
            
            // エラーが無ければ、公開ステータスを更新
            if (has_error() === FALSE) {
                // 公開ステータスのデータ更新
                update_status($dbh, $new_status, $item_id);
            }
        }
    }
    
    // 「削除」が押されたら
    if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
        if (isset($_POST['delete_submit']) === TRUE) {

            // 送信されたトークンを変数に代入
            $token = get_post('csrf_token');

            // 送信されたトークンとセッションに保存のトークンが一致しない場合不正なリクエストとして扱う。
            if(is_valid_csrf_token($token) === FALSE) {
                set_error('Illegal request');
                // リダイレクト
                header('Location: admin.php');
                // プログラムを終了
                exit;
            }

            // トークンを破棄
            unset($_SESSION['csrf_token']);
            
            // 投稿内容を変数に代入
            if (isset($_POST['item_id']) === TRUE) {
                $item_id = $_POST['item_id'];
            }
            
            // 商品を一覧から削除する
            delete_item_stock($dbh, $item_id);
        }
    }
    
    // データベースから投稿内容を取得
    $data = select_item_stock($dbh);

    // トークンを生成
    $token = get_csrf_token();
  
} catch (PDOException $e) {
    // 接続失敗した場合
    set_error('DBエラー：'.$e->getMessage());
}

// 「管理ページ」ファイル読み込み
include_once './view/admin_view.php';