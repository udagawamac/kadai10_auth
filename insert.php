<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

//1. POSTデータ取得
//[name,email,color,indate]
$name   = $_POST["name"];
$email  = $_POST["email"];
$color  = $_POST["color"];

//2. DB接続
include("funcs.php"); //外部ファイル読み込み
$pdo = db_conn();

//３．データ登録SQL作成
$sql = "INSERT INTO gs_bm_table(name,email,color,indate)VALUES(:name, :email, :color,sysdate())";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name',   $name,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':email',  $email,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':color',  $color,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //true or false

//４．データ登録処理後
if($status==false){
  sql_error($stmt);
}else{
  
//５．select.phpへリダイレクト
redirect("select.php");
}
?>
