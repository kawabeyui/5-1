<?php
    #DB接続設定
    $dsn = 'データベース名';
    $user ="ユーザー名";
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

     #新規投稿
    if(!empty($_POST["submit1"])&&!empty($_POST["name"])&&!empty($_POST["str"])&&!empty($_POST["pass"])){
        $name=$_POST["name"];
        $str=$_POST["str"];
        $pass=$_POST["pass"];
        $date=date("Y/m/d H:i:s");
        
        if(empty($_POST["editnumber"])){
            $sql = $pdo -> prepare("INSERT INTO M51 (name, comment,date,password) VALUES (:name, :comment,:date,:password)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $str, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
            $sql -> execute();
            
            
            
        }else{
            $id = $_POST["editnumber"];
            $sql = "UPDATE M51 SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $str, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
          } 
            
    }
    
    
    #削除
    if(!empty($_POST["submit2"])&&!empty($_POST["delnum"])&&!empty($_POST["pass1"])){
        $sql = 'SELECT * FROM M51';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
          if ($row['id'] == $_POST["delnum"] && $row['password']==$_POST["pass1"]){ 
               $id = $row["id"];
               $sql = 'delete from M51 where id=:id';
               $stmt = $pdo->prepare($sql);
               $stmt->bindParam(':id', $id, PDO::PARAM_INT);
               $stmt->execute();
         break;
          }
        }
    }
    
    #編集
    if(!empty($_POST["submit3"])&&!empty($_POST["edit"])&&!empty($_POST["pass2"])){
        $sql = 'SELECT * FROM M51';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
          if($row['id'] == $_POST["edit"] && $row['password']==$_POST["pass2"]){
              $ednum=$row["id"];
              $edname=$row["name"];
              $edcom=$row["comment"];
         break;
          }
        }
    } 
    
    
    #表示
    $sql = 'SELECT * FROM M51';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].",";
        echo $row["date"]."<br>";
    echo "<hr>";
    }

?>


<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5-1</title>
</head>
<body>
    <span style="font-size:50px;">みんなの最近ハマってること教えて！</span>
    <br>
   　<span style="color:red">削除しないで残しておいてね</span>
   
   
    <form action="" method="post">
    名前：<input type="text"  
    value="<?php
        if(!empty($_POST["submit3"])&&!empty($_POST["edit"])&&!empty($_POST["pass2"])){
            if($row['id'] == $_POST["edit"] && $row['password']==$_POST["pass2"]){
                echo $edname;
            }
        }?>"
    name="name"><br>

    コメント：<input type="text" 
    value="<?php
        if(!empty($_POST["submit3"])&&!empty($_POST["edit"])&&!empty($_POST["pass2"])){
            if($row['id'] == $_POST["edit"] && $row['password']==$_POST["pass2"]){
                echo $edcom;
            }
        }?>"
    name="str"><br>
    
    <input type="hidden" 
    value="<?php
        if(!empty($_POST["submit3"])&&!empty($_POST["edit"])){
            if($row['id'] == $_POST["edit"] && $row['password']==$_POST["pass2"]){
                echo $ednum;
            }
        }?>" 
    name="editnumber">
    
    パスワード：<input type="password" name="pass"><br>
    
    <input type="submit" value="送信" name="submit1"><br>
    </form>
    
    <form action="" method="post">

    削除対象番号：<input type="number" name="delnum"><br>
    パスワード：<input type="password" name="pass1"><br>
    <input type="submit" value="削除" name="submit2"><br>
    </form>
 
   
    <form action="" method="post">
    編集対象番号：<input type="number" name="edit"><br>
    パスワード：<input type="password" name="pass2"><br>
    <input type="submit" value="編集" name="submit3"><br>
    
    </form>

</body>
</html>

　