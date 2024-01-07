<!-- https://www.youtube.com/watch?v=3QxtIrakwKk 19:30 -->
<?php

//変数宣言
$comment_array = array();


//PDO接続処理
function connect_db(){

    $host = "mysql_container";
    $db = "test";
    $charset = "utf8";
    $dsn = "mysql:host=$host; dbname=$db; charset=$charset";

    $user = "test";
    $pass = "test";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try{
        //DB接続
        $pdo = new PDO($dsn, $user, $pass, $options);
        // echo "接続成功";
    }catch(PDOException $e){
        echo $e->getMessage();
        // echo "接続失敗";
    }


    return $pdo;

}

$pdo = connect_db();

//データの追加
if(!empty($_POST["submitButton"])){

    $postDate = date("y-m-d H:i:s");

    try{
        $stmt = $pdo->prepare("INSERT INTO `bb-table` (`username`, `comment`, `postDate`) VALUES (:username, :comment, :postDate)");
        $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
        $stmt->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
        $stmt->bindParam(':postDate', $postDate, PDO::PARAM_STR);
        $stmt->execute();
    }catch(PDOException $e){
        echo $e->getMessage();
        // echo "接続失敗";
    }
}

//DBからコメントデータを取得する処理
$sql = "SELECT * FROM `bb-table`;";
$comment_array = $pdo->query($sql);

//DBの接続を閉じる
$pdo = null;


?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="./stle.css">
    <title>test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <h1 class="title">PHPで掲示板アプリ</h1>
    <hr>
    <div class="boardWrapper">
        <section>
            <?php foreach($comment_array as $comment): ?>
                <article>
                    <div class="wrapper">
                        <div class="nameArea">
                            <span>名前:</span>
                            <p class="username"><?php echo $comment["username"] ?></p>
                            <time>:<?php echo $comment["postDate"] ?></time>
                        </div>
                        <p class="comment"><?php echo $comment["comment"] ?></p>
                    </div>
                </article>
            <?php endforeach;?>
        </section>

        <form method="POST" action="" class="formWrapper">
            <div>
                <input type="submit" value="書き込む" name="submitButton">
                <label for="usernameLabel">名前：</label>
                <input type="text" name="username">
            </div>
            <div>
                <textarea name="comment" class="commentTextArea" ></textarea>
            </div>
        </form>
    </div>

</body>
</html>