<?php
//ファイルの読み込み
require_once "db_connect.php";
require_once "functions.php";

//セッションの開始
session_start();

//POSTされてきたデータを格納する変数の定義と初期化
$datas = [
    'id'  => '',
    'password'  => '',
	'confirm_password'  => '',
	'name' =>'',
	'faculty' =>'',
	'department' =>'',
	'level'  =>'',
	'phone'  =>''
];

//GET通信だった場合はセッション変数にトークンを追加
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    setToken();
}
//POST通信だった場合はDBへの新規登録処理を開始
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //CSRF対策
    checkToken();

    // POSTされてきたデータを変数に格納
    foreach($datas as $key => $value) {
        if($value = filter_input(INPUT_POST, $key, FILTER_DEFAULT)) {
            $datas[$key] = $value;
        }
    }

    // バリデーション
    $errors = validation($datas);

    //データベースの中に同一ユーザー名が存在していないか確認
    if(empty($errors['id'])){
        $sql = "SELECT username FROM member WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('username',$datas['id'],PDO::PARAM_INT);
        $stmt->execute();
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $errors['id'] = '既に登録しました';
        }
    }
    //エラーがなかったらDBへの新規登録を実行
    if(empty($errors)){
        $params = [
            'username' =>$datas['id'],
		'password' =>password_hash($datas['password'], PASSWORD_DEFAULT),
            'stuname' =>$datas['name'],
            	'faculty' =>$datas['faculty'],
		'department' =>$datas['department'],
		'level' =>$datas['level'],
		'phone' =>$datas['phone']
        ];

        $count = 0;
        $columns = '';
        $values = '';
        foreach (array_keys($params) as $key) {
            if($count > 0){
                $columns .= ',';
                $values .= ',';
            }
            $columns .= $key;
            $values .= ':'.$key;
            $count++;
        }

        $pdo->beginTransaction();//トランザクション処理
        try {
            $sql = 'insert into member ('.$columns .')values('.$values.')';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $pdo->commit();
            header("location: login.php");
            exit;
        } catch (PDOException $e) {
            echo $Exception->getMessage();
            $pdo->rollBack();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>学生登録</title>
    <!-- bootstrap読み込み -->
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
        <h2>学生登録</h2>
	<hr>
	</div>
	<div class="wrapper">
        <p>学生情報を入力してアカウントを有効化しなさい</p>
        <form action="<?php echo $_SERVER ['SCRIPT_NAME']; ?>" method="post">
            <div class="form-group">
                <label>学生番号</label>
                <input type="text" name="id" placeholder="20XX**XXX" maxlength="9" class="form-control <?php echo (!empty(h($errors['id']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['id']); ?>" required>
                <span class="invalid-feedback"><?php echo h($errors['id']); ?></span>
            </div>

            <div class="form-group">
                <label>氏名</label>
                <input type="text" name="name" placeholder="南山ロバート" maxlength="50" class="form-control <?php echo (!empty(h($errors['name']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['name']); ?>" required>
                <span class="invalid-feedback"><?php echo h($errors['name']); ?></span>
            </div> 

            <div class="form-group">
                <label>学部</label>
                <input type="text" name="faculty" maxlength="15" class="form-control <?php echo (!empty(h($errors['faculty']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['faculty']); ?>"required>
                <span class="invalid-feedback"><?php echo h($errors['faculty']); ?></span>
            </div> 

            <div class="form-group">
                <label>学科</label>
                <input type="text" name="department" maxlength="30" class="form-control <?php echo (!empty(h($errors['department']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['department']); ?>"required>
                <span class="invalid-feedback"><?php echo h($errors['department']); ?></span>
            </div>    

            <div class="form-group">
                <label>学年</label>
                <input type="number" name="level" class="form-control <?php echo (!empty(h($errors['level']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['level']); ?>"min="1" max="4" required>
                <span class="invalid-feedback"><?php echo h($errors['level']); ?></span>
            </div>  

            <div class="form-group">
                <label>電話番号</label>
                <input type="text" name="phone" maxlength="11" placeholder="ハイフンなしで入力してください" class="form-control <?php echo (!empty(h($errors['phone']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['phone']); ?>"required>
                <span class="invalid-feedback"><?php echo h($errors['phone']); ?></span>
            </div>  

            <div class="form-group">
                <label>パスワード</label>
                <input type="password" name="password" placeholder="半角英数字8文字以上100文字以下" class="form-control <?php echo (!empty(h($errors['password']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['password']); ?>">
                <span class="invalid-feedback"><?php echo h($errors['password']); ?></span>
            </div>
            <div class="form-group">
                <label>パスワード再入力</label>
                <input type="password" name="confirm_password" placeholder="パスワードをもう一度入力してください" class="form-control <?php echo (!empty(h($errors['confirm_password']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['confirm_password']); ?>">
                <span class="invalid-feedback"><?php echo h($errors['confirm_password']); ?></span>
            </div>

            <div class="form-group">
                <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
                <input type="submit" class="btn btn-primary" value="送信">
		<a id="reset" class="reset ml-3"><input type="reset" value="リセット" ></a>
            </div>
            <p>アカウントを持っている? <a href="login.php">ログイン</a></p>
        </form>
    </div>    
</body>
</html>