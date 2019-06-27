<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>集合写真検索システム</title>
  <style>
    /* 仕切り線 */
    hr {
      border-width: 2px 0px 0px 0px;
      border-style: solid;
      border-color: #dddddd;
      height: 1px;
    }
    /* 文字のデコレーション */
    h1 {font-family:arial,helvetica,"ヒラギノ角ゴ Pro W3","Hiragino Kaku Gothic Pro",Osaka,"メイリオ",Meiryo,"MS Pゴシック",clean,sans-serif;
    font-size:17px;
    margin-bottom: 10px;
    text-shadow:  1px 1px 2px #ccc;
  }
  h2 {font-family:arial,helvetica,"ヒラギノ角ゴ Pro W3","Hiragino Kaku Gothic Pro",Osaka,"メイリオ",Meiryo,"MS Pゴシック",clean,sans-serif;
  font-size:20px;
  text-shadow:  1px 1px 2px #666;
}
/* 余白 */
h3 {margin: 20px;}
/* table 表 */
h4 {
  width: 400px;
  margin: 0px;
  padding: 4px;
  text-align: left;
  vertical-align: top;
  font-weight: normal;
  color: #333;
  background-color: #eee;
  border: 1px solid #b9b9b9;
}
h5 {
  width: 500px;
  padding: 0px;
  border-bottom: 1px solid #ccc;
}

/* 色つきのボックス */
/***
    div { padding: 10px;
        background-color: #aaffaa;
    }
    ***/
    .title { 
      padding: 10px;
      background-color: #aaffaa;
    }
    .download{
     text-align: center; 
   }
   /* 画像最大サイズ設定 */
   p img {
    max-width: 200px;
    max-height: 200px;
  }
</style>
</head>
<body>
  <?php if(!isset($_POST["image_data"])){ ?>
  <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
   <p>
    <div class="title"><h2>集合写真検索システム</h2></div>
    <br>
    <h1>検索キーワード<SPAN STYLE="color:#555;font-size:12px;">(例：結婚式,人)</SPAN>：<input type="text" name="keyword" size=20 style="font-size:15px;height:30px;text-align: center;border:solid 1px #0088bb;border-radius:5px;"/></h1></h5><h5><h1>

    写真中の人の数<SPAN STYLE="color:#555;font-size:12px;">(3ケタまで)</SPAN>：<input type="text" name="number" size=5 style="font-size:15px;height:30px;text-align: center;border:solid 1px #0088bb;border-radius:5px;" maxlength="3"/>
    〜 <input type="text" name="number2" size=5 style="font-size:15px;height:30px;text-align: center;border:solid 1px #0088bb;border-radius:5px;"/></h1><h5><h1>

    検索オプション：以上<input type="checkbox" name="over"/>
    ：以下<input type="checkbox" name="under"/>
    ：and<input type="checkbox" name="and"/>
    ：or<input type="checkbox" name="or"/></h1></h5>
    <br>

    <input type="submit" style="width:250px;height:50px;font-size:18px;color:#fff;border-radius:5px;border-color:#ccddff;border-width:4px;background-color:#44aaff;" value=" search! " />
  </p>
</form>

<?php
// tfファイルの読み込み　１行ずつ　最後まで
$tf_data = array( array());
$tffile = "tfimg.all";
$f1 = fopen($tffile, "r");
while (! feof ($f1)) {
  $line = fgets($f1);
  $tf_line = preg_split( "/\t/" , $line );
  @$tf_line[2] = preg_replace("/\r|\n/","",$tf_line[2]);
  @$tf_data[$tf_line[0]][$tf_line[2]] = $tf_line[1];
}
fclose($f1);
// tfファイルの読み込み　ここまで


// fcファイルの読み込み　１行ずつ　最後まで
$fc_data = array();
$fcfile = "fcimg.all";
$f2 = fopen($fcfile, "r");
while (! feof ($f2)) {
  $line = fgets($f2);
  $fc_line = preg_split( "/\t/" , $line );
  @$fc_line[1] = preg_replace("/\r|\n/","",$fc_line[1]);
  $fc_data[$fc_line[1]] = $fc_line[0];
}
fclose($f2);
// fcファイルの読み込み　ここまで


// 以下、検索処理
$result_num = 0;
$keys_array = array_keys($tf_data);
$keys_string = implode($keys_array);
$keyword1 = $_POST["keyword"];  // 追加
$keyword2 = mb_convert_kana($keyword1, "s");
$keywords = explode(" ",$keyword2);; //追加
$keyarrays1 = array();
$keyarrays2 = array();

for($i = 0 ; $i < count($keywords); $i++){
  if ($_POST["keyword"] && $_POST["number"]) {
    if($_POST["keyword"] <>null && stristr($keys_string, @$keywords[$i]) == TRUE){
        //全角数字を半角に変換
      if(!preg_match("/^[0-9]+$/", $_POST["number"]) || !preg_match("/^[0-9]+$/", $_POST["number2"])){
        $_POST["number"] = mb_convert_kana($_POST["number"],"n");
        $_POST["number2"] = mb_convert_kana($_POST["number2"],"n");
      }
      if(array_key_exists($keywords[$i], $tf_data) && $_POST["keyword"] <>null ){
        if ($_POST["number"]==null ) {
         echo "人数を正しく入力して下さい。";
       }
       if($_POST["over"] && $_POST["under"] && !$_POST["number2"] == null){
        echo "あれもこれもは無理。";
      }
      if ($_POST["and"]==null && $_POST["or"]==null && count($keywords) > 1 || $_POST["and"] && $_POST["or"]) {
        echo "検索方法を正しく選んでください。\n";
        break;
      } else {
        echo "キーワード「".$_POST["keyword"]."」　人数「";
          //通常
        if(!$_POST["over"] && !$_POST["under"] && $_POST["number2"] == null){
          echo $_POST["number"]."人」での検索結果<br>\n";
        }
          //以上
        if($_POST["over"] && $_POST["number2"] == null){
          echo $_POST["number"]."人以上」での検索結果<br>\n";
        }
          //以下
        if($_POST["under"] && $_POST["number2"] == null){
          echo $_POST["number"]."人以下」での検索結果<br>\n";
        }
          //から
        if(!$_POST["number"] == null && !$_POST["number2"] == null){
          echo $_POST["number"]."人〜";
          echo $_POST["number2"]."人」での検索結果<br>\n";
        }
      }
      echo "<hr><br>\n";


      foreach($keys_array as $word) {
        if(stristr($word, @$keywords[$i]) == TRUE){
          foreach($tf_data[$word] as $key => $val ) {

          //以上の検索結果
            if(!$_POST["under"] && $_POST["over"] && $_POST["number2"] == null){
              if (@$_POST["number"] <= @$fc_data[$key] && @$_POST["number"]<>null){
                //form
                ?> <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"><?php
                $keep_key = $_POST["keyword"];
                $keep_num = $_POST["number"];
                $keep_over = $_POST["over"];
                echo "<input type='hidden' name='keyword_keeping' value=$keep_key>";
                echo "<input type='hidden' name='number_keeping' value=$keep_num>";
                echo "<input type='hidden' name='keep_over' value=$keep_over>";
                echo "<input type='hidden' name='image_data' value=$key>";
                echo "<h4><p><img src='$key'>\t";
                echo "<input type='submit' value='表示'><br>\n</p></h4>";
                ?></form><?php
                echo "<h4>キーワード名=$word<br>\n</h4>";
                echo "<h4>キーワード出現回数＝".$val."回<br>\n</h4>";
                echo "<h4>写真中の人の数＝".@$fc_data[$key]."人<br>\n</h4>";
                echo "<h4>$key<br>\n</h4>";
                $_POST["key"] = $key;
                ?>
                <form action="download.php" method='post'>
                	<h4><div class="download"><input type="submit" value="download"></div></h4>
                	<input type="hidden" name="dlfile" value="<?php echo $_POST['key']; ?>" >   
                </form>
                <?php
                echo "<br><br>\n";
                $result_num++;
              }
            }
    //以下の検索結果
            if($_POST["under"] && !$_POST["over"] && $_POST["number2"] == null && !$_POST["or"]){
              if (@$fc_data[$key] <= @$_POST["number"] && @$fc_data[$key] > 0 && @$_POST["number"]<>null){
                //form
                ?> <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"><?php
                $keep_key = $_POST["keyword"];
                $keep_num = $_POST["number"];
                $keep_under = $_POST["under"];
                echo "<input type='hidden' name='keyword_keeping' value=$keep_key>";
                echo "<input type='hidden' name='number_keeping' value=$keep_num>";
                echo "<input type='hidden' name='keep_under' value=$keep_under>";
                echo "<input type='hidden' name='image_data' value=$key>";
                echo "<h4><p><img src='$key'>\t";
                echo "<input type='submit' value='表示'><br>\n</p></h4>";
                ?></form><?php
                echo "<h4>キーワード名=$word<br>\n</h4>";
                echo "<h4>キーワード出現回数＝".$val."回<br>\n</h4>";
                echo "<h4>写真中の人の数＝".@$fc_data[$key]."人<br>\n</h4>";
                echo "<h4>$key<br>\n</h4>";
                $_POST["key"] = $key;
                ?>
                <form action="download.php" method='post'>
                  <h4><div class="download"><input type="submit" value="download"></div></h4>
                  <input type="hidden" name="dlfile" value="<?php echo $_POST['key']; ?>" >   
                </form>
                <?php
                echo "<br><br>\n";
                $result_num++;
              }
            }
    //〜から〜までの検索結果
            if(!$_POST["under"] && !$_POST["over"] && !$_POST["number2"] == null && !$_POST["or"]){
              if (@$_POST["number"] <= @$fc_data[$key] && @$fc_data[$key] <= $_POST["number2"] && @$_POST["number"]<>null){
                //form
                ?> <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"><?php
                $keep_key = $_POST["keyword"];
                $keep_num = $_POST["number"];
                $keep_num2 = $_POST["number2"];
                echo "<input type='hidden' name='keyword_keeping' value=$keep_key>";
                echo "<input type='hidden' name='number_keeping' value=$keep_num>";
                echo "<input type='hidden' name='number2_keeping' value=$keep_num2>";
                echo "<input type='hidden' name='image_data' value=$key>";
                echo "<h4><p><img src='$key'>\t";
                echo "<input type='submit' value='表示'><br>\n</p></h4>";
                ?></form><?php
                echo "<h4>キーワード名=$word<br>\n</h4>";
                echo "<h4>キーワード出現回数＝".$val."回<br>\n</h4>";
                echo "<h4>写真中の人の数＝".@$fc_data[$key]."人<br>\n</h4>";
                echo "<h4>$key<br>\n</h4>";
                $_POST["key"] = $key;
                ?>
                <form action="download.php" method='post'>
                  <h4><div class="download"><input type="submit" value="download"></div></h4>
                  <input type="hidden" name="dlfile" value="<?php echo $_POST['key']; ?>" >   
                </form>
                <?php
                echo "<br><br>\n";
                $result_num++;
              }
            }
              //or
            if(!$_POST["under"] && !$_POST["over"] && $_POST["or"] && !$_POST["number2"] == null){
              if (@$_POST["number"] == @$fc_data[$key] && @$_POST["number"]<>null){
                //form
                ?> <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"><?php
                $keep_key = $_POST["keyword"];
                $keep_num = $_POST["number"];
                $keep_or = $_POST["or"];
                echo "<input type='hidden' name='keyword_keeping' value=$keep_key>";
                echo "<input type='hidden' name='number_keeping' value=$keep_num>";
                echo "<input type='hidden' name='keep_or' value=$keep_or>";
                echo "<input type='hidden' name='image_data' value=$key>";
                echo "<h4><p><img src='$key'>\t";
                echo "<input type='submit' value='表示'><br>\n</p></h4>";
                ?></form><?php
                echo "<h4>キーワード名=$word<br>\n</h4>";
                echo "<h4>キーワード出現回数＝".$val."回<br>\n</h4>";
                echo "<h4>写真中の人の数＝".@$fc_data[$key]."人<br>\n</h4>";
                echo "<h4>$key<br>\n</h4>";
                $_POST["key"] = $key;
                ?>
                <form action="download.php" method='post'>
                  <h4><div class="download"><input type="submit" value="download"></div></h4>
                  <input type="hidden" name="dlfile" value="<?php echo $_POST['key']; ?>" >   
                </form>
                <?php
                echo "<br><br>\n";
                $result_num++;
              }
            }
    //オプションなしの検索結果
            if(!$_POST["under"] && !$_POST["over"] && $_POST["number2"] == null){
              if (@$_POST["number"] == @$fc_data[$key] && @$_POST["number"]<>null){
                //form
                ?> <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"><?php
                $keep_key = $_POST["keyword"];
                $keep_num = $_POST["number"];
                echo "<input type='hidden' name='keyword_keeping' value=$keep_key>";
                echo "<input type='hidden' name='number_keeping' value=$keep_num>";
                echo "<input type='hidden' name='image_data' value=$key>";
                echo "<h4><p><img src='$key'>\t";
                echo "<input type='submit' value='表示'><br>\n</p></h4>";
                ?></form><?php
                echo "<h4>キーワード名=$word<br>\n</h4>";
                echo "<h4>キーワード出現回数＝".$val."回<br>\n</h4>";
                echo "<h4>写真中の人の数＝".@$fc_data[$key]."人<br>\n</h4>";
                echo "<h4>$key<br>\n</h4>";
                $_POST["key"] = $key;
                ?>
                <form action="download.php" method='post'>
                  <h4><div class="download"><input type="submit" value="download"></div></h4>
                  <input type="hidden" name="dlfile" value="<?php echo $_POST['key']; ?>" >   
                </form>
                <?php
                echo "<br><br>\n";
                $result_num++;
              }
            }
          }
        }
      }
    }
  }
} else if (@$_POST["keyword"]==null) {
  echo '検索キーワードを入力して下さい。';
} else {
  echo '検索キーワードに合致する写真はありません。';
}
}
/*
//and
    elseif (count($keywords) > 1 && isset($_POST["check1"])) {
      for($i = 0 ; $i < count($keywords); $i++){
        if (isset($_POST["keyword"]) && isset($_POST["number"])) {
          if(array_key_exists($keywords[$i], $tf_data) && $_POST["keyword"] <>null ){
            if ($_POST["number"]==null || !preg_match("/^[0-9]+$/", $_POST["number"])) {
             echo "人数を正しく入力して下さい。";
           }
           if ($_POST["check1"]==null && $_POST["check2"]==null && count($keywords) > 1 || isset($_POST["check1"]) && isset($_POST["check2"])) {
            echo "検索方法を正しく選んでください。\n";
            echo "<hr><br>\n";
            break;
          } else {
            if($i == 0){
              echo "キーワード「".$_POST["keyword"]."」　人数「";
              echo $_POST["number"]."人」での検索結果<br>\n";
            }
          }

          foreach($tf_data[$keywords[$i]] as $key) {

            if(array_key_exists($keywords[$i], $keyarrays1)){
              $keyarrays2[] = $key;
            }
            else{
              $keyarrays1[] = $key;
            }
          }
          if($i==count($keywords)-1){
            for($i = 0 ; $i < count($keyarrays2); $i++){
              foreach($tf_data[$keyarrays2[$i]] as $key => $val ) {
                if (@$_POST["number"] == @$fc_data[$key] && @$_POST["number"]<>null){
                  echo "<img src='$key'><br>\n";
                  echo "キーワード出現回数＝".$val."回<br>\n";
                  echo "写真中の人の数＝".@$fc_data[$key]."人<br>\n";
                  echo "$key<br><br><br>\n";
                  echo $keyarrays2[$i];
                  $result_num++;
                }
              }
            }
          }
        }
      } elseif (@$_POST["keyword"]==null) {
        echo '検索キーワードを入力して下さい。';
      } else {
        echo '検索キーワードに合致する写真はありません。';
      }
    }
  }*/

  echo "検索結果は".$result_num."件でした。";

}else{
  $image = (binary) $_POST['image_data'];
  echo "<img src= $image><br>";
    //form
  ?><form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"><?php
  $keyword = $_POST["keyword_keeping"];
  $number = $_POST["number_keeping"];
  echo "<input type='hidden' name='keyword' value=$keyword>";
  echo "<input type='hidden' name='number' value=$number>";
  if(isset($_POST["number2_keeping"])){
    $number2 =  $_POST["number2_keeping"];
    echo "<input type='hidden' name='number2' value=$number2>";
  }
  if($_POST["keep_over"]){
    $over = $_POST["keep_over"];
    echo "<input type='hidden' name='over' value=$over>";
  }
  if($_POST["keep_under"]){
    $under = $_POST["keep_under"];
    echo "<input type='hidden' name='under' value=$under>";
  }
  if($_POST["keep_and"]){
    $and = $_POST["keep_and"];
    echo "<input type='hidden' name='and' value=$and>";
  }
  if($_POST["keep_or"]){
    $or = $_POST["keep_or"];
    echo "<input type='hidden' name='or' value=$or>";
  }
  echo "<input type='submit' value='戻る'>";
  ?></form><?php
}
?>
</body>
</html>
