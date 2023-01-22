<?php
//ファイルの読み込み
require_once "db_connect.php";
require_once "functions.php";

//セッション開始
session_start();

// セッション変数 $_SESSION["loggedin"]を確認。ログイン済だったらtop.phpへリダイレクト
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: top.php");
    exit;
}


//POSTされてきたデータを格納する変数の定義と初期化
$datas = [
    'name'  => '',
    'password'  => '',
];
$login_err = "";

//GET通信だった場合はセッション変数にトークンを追加
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    setToken();
}

//POST通信だった場合はログイン処理を開始
if($_SERVER["REQUEST_METHOD"] == "POST"){
    ////CSRF対策
    checkToken();

    // POSTされてきたデータを変数に格納
    foreach($datas as $key => $value) {
        if($value = filter_input(INPUT_POST, $key, FILTER_DEFAULT)) {
            $datas[$key] = $value;
        }
    }

    // バリデーション
    $errors = validation($datas,false);
    if(empty($errors)){
        //学生番号から該当するユーザー情報を取得
        $sql = "SELECT username,password,stuname FROM member WHERE username=:username";
        $stmt = $pdo->prepare($sql);http://localhost/logout.php
        $stmt->bindValue('username',$datas['name'],PDO::PARAM_STR);
        $stmt->execute();

        //ユーザー情報があれば変数に格納
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            //パスワードがあっているか確認
            if (password_verify($datas['password'],$row['password']) && $datas['name']==$row['username']) {
                //セッションIDをふりなおす
                session_regenerate_id(true);
                //セッション変数にログイン情報を格納
                $_SESSION["loggedin"] = true;
                $_SESSION["name"] =  $row['username'];
                //ウェルカムページへリダイレクト
                header("location:top.php");
                exit();
            } else {
                $login_err = 'Invalid password.';
            }
        }else {
            $login_err = 'Invalid username or password.';
        }
    }
  
}
?>


<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            font: 14px sans-serif;
        }
        .wrapper{
            width: 400px;
            padding: 20px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
	<div style="text-align:center;">
	<hr>
	<h1>ログイン</h1>
	<hr>
	</div>
    <div class="wrapper">
        <p>学生番号とパスワードでログインしてください。</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo $_SERVER ['SCRIPT_NAME']; ?>" method="post">
            <div class="form-group">
                <label>学生番号</label>
                <input type="text" name="name" class="form-control <?php echo (!empty(h($errors['name']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['name']); ?>">
                <span class="invalid-feedback"><?php echo h($errors['name']); ?></span>
            </div>    
            <div class="form-group">
                <label>パスワード</label>
                <input type="password" name="password" class="form-control <?php echo (!empty(h($errors['password']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['password']); ?>">
                <span class="invalid-feedback"><?php echo h($errors['password']); ?></span>
            </div>

            <div class="form-group">
                <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
                <input type="submit" class="btn btn-primary" value="ログイン">
            </div>
            <p>アカウントを作成 <a href="register.php">新規登録</a></p>

        </form>
    </div>
</body>
</html>