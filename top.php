<?php
session_start();
?>


<?php

require_once("db_connect.php");



    	$apdata = [];  //ログインしたユーザの予約情報を格納する。
	try {
		$sql= "SELECT * FROM appoint where username = :username";
		$stmh = $pdo->prepare($sql);
		$user=$_SESSION['name'];   //ログイン認証で利用した学生番号を活用
        	$stmh->bindValue(':username',  $user,  PDO::PARAM_STR );
        	$stmh->execute();
        	while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            		foreach( $row as $key => $value){
                		$apdata[$key] = $value;
            		}
        	}
    	} catch (PDOException $Exception) { 
        	print "エラー：" . $Exception->getMessage();
    	}



    	if(empty($_SESSION['name'])) {
    		header("location: login.php"); //ログインしてない場合にログインページに戻る
    	} else if(!empty($apdata['date'])) {  //ログイン後も予約登録後もこの条件で表示できるようにしたい

?>

<!DOCTYPE html>
<html lang = "ja">
<head>
	<meta charset="UTF-8">
	<title>トップページ</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
        body{ 
            font: 14px sans-serif;
            text-align: center; 
        }
    	</style>
</head>
<body>
	<div>
		<hr>
		<h1>トップページ</h1>
		<hr>
		<br>
	</div>	
       <div style="text-align: center; margin-left:15px;">
		<td style="vertical-align:top;">
		<h3><?php echo $_SESSION['name']; ?> さん、こんにちは！<br></h3>
		<h4>
		<?php
			echo '日付: ';     
			echo $apdata['date'];
			echo "\n";
			echo '時間: ';
			switch($apdata['time']){
				case 1:
					echo "9:00~9:30";
					break;
				case 2:
					echo "10:00~10:30";
					break;
				case 3:
					echo "11:00~11:30";
					break;
				case 4:
					echo "13:00~13:30";
					break;
				case 5:
					echo "14:00~14:30";
					break;
				case 6:
					echo "15:00~15:30";
					break;
				case 7:
					echo "16:00~16:30";
					break;
				case 8:
					echo "17:00~17:30";
					break;
			}
			echo 'に予約しています。';
		?><br></h4>
		</td>
	</div>
	<div id="delete"><br><a href="delete.php" class="btn btn-primary ml-3">予約取り消しはこちらから</a>
	<?php
    	}else{
?>
<head>
	<meta charset="UTF-8">
	<title>トップページ</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
        body{ 
            font: 14px sans-serif;
            text-align: center; 
        }
    	</style>
</head>

<body>
	<div style="text-align:center;">
		<hr>
		<h1>トップページ</h1>
		<hr>
		<br>
	</div>
	<div style="text-align: middle; margin-left:15px;">
		<td style="vertical-align:top;">
		<h3><?php echo $_SESSION['name']; ?> さん、こんにちは！<br></h3>
		<h4>*現在登録している予約情報はありません。<br></h4>
		</td>
	</div>
	<div id="res"><br><a href="registform2.php" class="btn btn-primary ml-3">予約登録はこちらから。</a>
	<?php }
?>
	</div>
	

	<div id="logout"><br><a href="logout.php" class="btn btn-danger ml-3">ログアウト</a></div>
</div>
</body>
</html>


<?php 

if(!empty($_POST['date']) && !empty($_POST['time'])) {   //予約登録

	$date = @$_POST['date'];
	$time = @$_POST['time'];
	$sql = "SELECT * FROM appoint WHERE (date='$date') AND (time='$time') AND (status=1)";
	$stmh = $pdo->prepare($sql);
	$stmh->execute();
	$count = $stmh->rowCount();
	//制限１：同じ時間帯の予約上限は３ (>2)
	if($count > 2){
		print "この時間帯はこれ以上予約できません。";
	}elseif($_POST['date'] <= date("Y-m-d")){
		print "本日以降を予約してください。";
	}else{

	try {
		$pdo->beginTransaction();
		$sql = "INSERT  INTO appoint (username, time, date, status, comment) VALUES (:username, :time, :date, 1, :comment )";
 		$stmh = $pdo->prepare($sql);
		$user = $_SESSION['name'];
		$stmh->bindValue(':username',  $user,  PDO::PARAM_STR );
		$stmh->bindValue(':time', $_POST['time'], PDO::PARAM_INT );
		$stmh->bindValue(':date',        $_POST['date'],        PDO::PARAM_STR );
		$stmh->bindValue(':comment',        $_POST['comment'],        PDO::PARAM_STR );
		$stmh->execute();
		$pdo->commit();
		header("location: top.php");


	} catch (PDOException $Exception) {
		$pdo->rollBack();
		print "エラー:" . $Exception->getMessage();
	}
 	}
	} else {
}


if(isset($_POST['action']) && $_POST['action'] == 'decide') {   //予約取り消し


    try {
      $pdo->beginTransaction();
      $sql = "DELETE FROM appoint where username = :username";
      $stmh = $pdo->prepare($sql);
      $user = $_SESSION['name'];
      $stmh->bindValue(':username',  $user,  PDO::PARAM_STR );
      $stmh->execute();
      $pdo->commit();
      header("location: top.php");


    } catch (PDOException $Exception) {
      $pdo->rollBack();
      print "エラー：" . $Exception->getMessage();
    }
} else {
}


?>