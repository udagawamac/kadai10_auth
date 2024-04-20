<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

//0. SESSION開始！！
session_start();

//1. DB接続
include("funcs.php");
// sschk();
$pdo = db_conn();

//２．データ登録SQL作成
$sql = "SELECT * FROM gs_bm_table ORDER BY indate DESC";
$stmt = $pdo->prepare("$sql");
$status = $stmt->execute(); //true or false

//３．データ表示
$values = "";
if($status==false) {
  sql_error($stmt);
}

//全データ取得
$values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
// var_dump($values);
//JSONに値を渡す
$json = json_encode($values,JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0 "></script>
    <link rel="icon" href="./img/parette.png">
    <title>集計結果</title>
</head>

<!-- Main[Start] -->
<body class="haikei">
    <main>
    <?=$_SESSION["name"]?>さん、こんにちは！
    <a href="logout.php">ログアウト</a>
        <h3>アンケート結果</h3>
        <div>
            <table border='1'>
                <tr>
                    <!-- <th>番号</th> -->
                    <th>名前</th>
                    <th>好きな色</th>
                    <?php if($_SESSION["kanri_flg"]=="1"){ ?>
                      <th>入力日時</th>
                      <th>E-mail</th>
                      <th>操作</th>
                      <th>操作</th>
                    <?php } ?>
                </tr>
                <?php
foreach($values as $value){ ?>
                <tr>
                    <!-- <td><?=h($value["id"])?></td> -->
                    <td><?=h($value["name"])?></td>
                    <td><?=h($value["color"])?></td>
                    <?php if($_SESSION["kanri_flg"]=="1"){ ?>
                        <td><?=h($value["indate"])?></td>
                        <td><?=h($value["email"])?></td>
                        <td><a href="detail.php?id=<?=h($value["id"])?>">更新</a></td>
                        <td><a href="delete.php?id=<?=h($value["id"])?>">削除</a></td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </table>
        </div>
        <p><a href="index.php">入力画面に戻る</a></p>
    </main>
    <!-- Main[End] -->
    <!-- グラフを表示するキャンバス -->
    <div style="width:300px;height:300px;margin-left:auto;margin-right:auto;margin-bottom:20px;">
        <canvas id="colorChart"></canvas>
    </div>
    <!-- 合計値を表示する領域 -->
    <h3 id="totalCount"></h3>
 
    <!-- JSON受け取り -->
    <script>
        const a = '<?php echo $json; ?>';
        const data = JSON.parse(a);
        console.log(a);

        // カウントを格納する空のオブジェクトを用意
        const colorCount = {};

        // 配列を走査し、各色の出現回数を数える
        data.forEach(item => {
            const color = item.color;
            if (colorCount[color]) {
                colorCount[color]++;
            } else {
                colorCount[color] = 1;
            }
        });
        // 確認のため結果を出力
        console.log(colorCount);
        //大きい順に並び替える
        const obj = colorCount
        // オブジェクトをキーと値のペアの配列に変換
        const entries = Object.entries(obj);
        // 配列を値の大きい順にソート
        entries.sort((a, b) => b[1] - a[1]);
        // ソートされた配列を元のオブジェクトの形式に戻す
        const sortedObj = Object.fromEntries(entries);
        // 確認のため結果を出力
        console.log(sortedObj);
  
        // グラフのデータを準備
        const labels = Object.keys(sortedObj);
        const counts = Object.values(sortedObj);
        //アンケート回答総数を計算
        const totalCount = counts.reduce((total, count) => total + count, 0);

        // キャンバス要素を取得
        const ctx = document.getElementById('colorChart').getContext('2d');

        // 円グラフを描画
        const colorChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'sortedObj',
                    data: counts,
                    backgroundColor: [
                        'purple',
                        'green',
                        'white',
                        'black',
                        'blue',
                        'red',
                        'pink',
                        'yellow'
                    ],
                    borderColor: 'rgba(0, 0, 0, 0)',
                }]
            },
            plugins: [ChartDataLabels], // pluginとしてchartdatalabelsを追加
            options: {
                plugins: {
                    datalabels: {
                        font: { // フォント設定
                            size: 16, // サイズ変更
                        }
                    }
                }
            }
        });
               // 合計値を表示する要素に合計値を挿入
               document.getElementById('totalCount').innerText = '回答総数: ' + totalCount + '人';
    </script>
</body>
</html>