
    <?php
    ini_set('mbstring.internal_encoding' , 'utf-8');
    mb_regex_encoding('utf-8');
    $words = mb_split(" ", $_POST["text"]);
    var_dump("いいいい");
    $results = [];
    foreach($words as $word){
        # 先頭と末尾の文字列を取得
        $textTop  = $word[0];
        $textLast = $word[-1];
        echo $words ;
        $word = ltrim(rtrim($word, $textLast), $textTop);
        $results[] = $textTop . str_shuffle($word) . $textLast;
    }
    var_dump($results[0]);
    ?>

