<?php
session_start();

if(empty($_SESSION['name'])) {
	header("location: login.php"); //ログインしてない場合にログインページに戻る
} else {

$date = (new DateTime('tomorrow'))->format('Y-m-d');

?>

<!DOCTYPE html>
<html lang = "ja">
<head>
	<meta charset="UTF-8">
	<title>予約登録ページ</title>
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
		<h1>予約登録ページ</h1>
		<hr>
	</div>
	
<br>
<div class="wrapper">
	<form action="top.php" method="post">
		<div>日付<br><input type="date" name="date" list="daylist" min="<?php echo $date; ?>" required></div>
		<br>
		<div>時間<br>
			<select name="time">
			<option value ="" selected>---時間を選択してください---</option>
			<option value = "1"> 9:00〜9:30 </option> 
			<option value = "2"> 10:00〜10:30 </option> 
			<option value = "3"> 11:00〜11:30 </option> 
			<option value = "4"> 13:00〜13:30 </option> 
			<option value = "5"> 14:00〜14:30 </option> 
			<option value = "6"> 15:00〜15:30 </option>
			<option value = "7"> 16:00〜16:30 </option>
			<option value = "8"> 17:00〜17:30 </option>
			</select>
		</div>
		<br>
		<div>備考欄<br><textarea type = "text" name = "comment" cols="30" rows="5" placeholder="200文字以内に纏めてください"></textarea></div>
		<br>
		<br>
		<div class="form-group">
		<a id="submit" class="submit"><input type="submit" value="送信" style="height:25px; width:100px"></a>
		<a id="reset" class="reset ml-3"><input type="reset" value="リセット" style="height:25px; width:100px"></a>
		<a id="back" href="top.php" class="back ml-3"><input type="button"  value="戻る" style="height:25px; width:100px"></a>
		</div>
	</div>
</form>
</body>
</html>


<?php }  ?>

