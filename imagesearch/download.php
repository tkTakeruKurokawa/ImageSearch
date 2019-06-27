<?php
 $file_name=basename($_POST["dlfile"]);  //ファイル名取得
 $dir=dirname($_POST["dlfile"]);    //ディレクトリ名取得
 $file = $dir."/".$file_name;	  //ファイルの場所 
 //echo $file;
 header("Content-Disposition: attachment; filename=$file_name");
 header("Content-Type: application/force-download");
 header("Content-Length: ".filesize($file));
 readfile($file);
 exit;
?>