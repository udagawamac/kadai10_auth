<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="css/main.css">
<style>div{padding: 10px;font-size:16px;}</style>
<title>ログイン</title>
</head>
<body>
<?php include("menu.php"); ?>
<header>
  <nav style="background-color: black; color: white; padding: 10px 10px; margin-bottom:20px;">LOGIN</nav>
</header>

<!-- lLOGINogin_act.php は認証処理用のPHPです。 -->
<form name="form1" action="login_act.php" method="post">
ID:<input type="text" name="lid"><br>
PW:<input type="password" name="lpw"><br><br>
<input type="submit" value="ログイン">
</form>

</body>
</html>