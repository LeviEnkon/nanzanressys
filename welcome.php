<?php
session_start();
// セッション変数 $_SESSION["loggedin"]を確認。ログイン済だったらウェルカムページへリダイレクト
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome <?php echo htmlspecialchars($_SESSION["name"]); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ 
            font: 14px sans-serif;
            text-align: center; 
        }
    </style>
</head>
<body>
    <h1 class="my-5"><b><?php echo htmlspecialchars($_SESSION["name"]); ?></b>.予約システムへようこそ！</h1>
    <p>
	<a href="top.php" class="btn btn-primary ml-3">予約はここから</a>
        <a href="logout.php" class="btn btn-danger ml-3">ログアウト</a>
    </p>
</body>
</html>