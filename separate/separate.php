<?php
#変数の定義
#関数の定義

// 既存のinputの中身を削除
foreach(glob("./input/*") as $file_name){
    //Linuxコマンド
    $command .= "rm -rf " . realpath($file_name); //指定したファイルを再帰的に削除
    //コマンドの実行
    exec($command);
}

// 既存のtmpの中身を削除
$fpath_array = glob('./tmp/*');
foreach ($fpath_array as $filename) {
    unlink($filename);
}

// inputにファイルをおく
$filedir = "./input/";
move_uploaded_file($_FILES["upfile"]["tmp_name"], $filedir.$_FILES["upfile"]["name"]);

// 変数定義
$knot = $_POST['knot'];
$min_minute = $_POST['min_minute'];

// 実行するコマンド
$cmdPath = "python /home/yara-shimizu/www/wp/portfolio/separate/separate.py ${knot} ${min_minute} 2>&1";

// 実行
exec($cmdPath);

// zip化
//Zipクラスロード
$zip = new ZipArchive();

//Zipファイル名指定
$zipFileName = "result.zip";

//Zipファイル一時保存ディレクトリ取得
$zipTmpDir = "./output/";

//Zipファイルオープン
$result = $zip->open($zipTmpDir.$zipFileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);

if ($result !== true) {
    return false;
}
 
//処理制限時間を外す
set_time_limit(0);

//Zip追加処理
foreach ($fpath_array as $filename) {
    //取得ファイルをZipに追加
    $zip->addFromString(basename($filename), file_get_contents($filename));
}

$zip->close();

// ストリームに出力

//-- Content-Type
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
//header('X-Content-Type-Options: nosniff');
header('Content-Transfer-Encoding: binary');
header('Connection: close');
echo file_get_contents($zipTmpDir.$zipFileName);

// 一時ファイルを削除しておく
unlink($zipTmpDir.$zipFileName);

?>