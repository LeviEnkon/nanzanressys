<?php
session_start();

if(empty($_SESSION['name'])) {
	header("location: login.php"); //ログインしてない場合にログインページに戻る
?>
   [ <a href="login.php">ログイン</a> ]

<?php 
} else {

?>



<!DOCTYPE html>
<html lang = "ja">
<head>
	<meta charset="UTF-8">
	<title>予約取り消しページ</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
        body{
            font: 14px sans-serif;
        }
    </style>
</head>
<body>
<div style="text-align:center;">
	<hr>
	<h1>予約取り消しページ</h1>
	<hr>
	<br>
</div>
<div style="text-align: center; margin-left:15px;">
	<td style="vertical-align:top;"><form action="top.php" method="post">
	<h5>お持ちの予約を取り消しますか？</h5>
	<br>
	<br>                              
	<div class="submit"><input type="submit" value="確定" style="height:50px; width:200px"><input type = "hidden" name = "action" value = "decide" style="height:50px; width:200px">
	<a id="back" href="top.php" class="back ml-3"><input type="button"  value="戻る" style="height:50px; width:200px"></a>
	</div>
	<br>
</form>

</body>
</html>

<?php  }  ?>