<?php
require_once "db_connect.php";
//XSS対策
function h($s){
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

//セッションにトークンセット
function setToken(){
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}

//セッション変数のトークンとPOSTされたトークンをチェック
function checkToken(){
    if(empty($_SESSION['token']) || ($_SESSION['token'] != $_POST['token'])){
        echo 'Invalid POST', PHP_EOL;
        exit;
    }
}

//POSTされた値のバリデーション
function validation($datas,$confirm = true)
{
    $errors = [];

    //ユーザー名のチェック
    if(empty($datas['name'])) {
        $errors['name'] = 'IDを入力してください';
    }

    //パスワードのチェック（正規表現）
    if(empty($datas['password'])){
        $errors['password']  = 'パスワードを入力してください';
    }
    else if(!preg_match('/\A[a-z\d]{8,100}+\z/i',$datas["password"])){
        $errors['password'] = "無効なパスワード";
    }
    if($confirm){
        if(empty($datas["confirm_password"])){
            $errors['confirm_password']  = "パスワードを再入力してください";
        }else if(empty($errors['password']) && ($datas["password"] != $datas["confirm_password"])){
            $errors['confirm_password'] = "パスワードは一致しない";
        }
    }
    return $errors;
}

function regvalidation($time,$day,$confirm = true)
{
    $errors = [];

    //time dayのチェック
	if(empty($time)) {
       	$errors['time'] = '時間帯を選びなさい';
    	}
    	if(empty($day)) {
       	$errors['day'] = '日付を選びなさい';
    	}
    return $errors;
}
?>