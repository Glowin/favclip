<?php
//通过豆瓣API获取用户的相册信息
function put_albuminfo_api( $user_id ) {
    include_once('config.php');
    $url = 'https://api.douban.com/v2/album/user_created/'. $user_id;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_COOKIE,'__utma=30149280.1478379792.1357749630.1358168367.1358175101.35');
    curl_setopt($ch,CURLOPT_COOKIE,'__utmb=30149280.28.10.1358175101');
    curl_setopt($ch,CURLOPT_COOKIE,'__utmc=30149280__utmv');
    curl_setopt($ch,CURLOPT_COOKIE,'__utmv=30149280.3051');
    curl_setopt($ch,CURLOPT_COOKIE,'__utmz=30149280.1358175101.35.9.utmcsr=douban.fm|utmccn=(referral)|utmcmd=referral|utmcct=/');
    curl_setopt($ch,CURLOPT_COOKIE,'bid="1f6QQoCdCFY"');
    curl_setopt($ch,CURLOPT_COOKIE,'ck="m1pW"');
    curl_setopt($ch,CURLOPT_COOKIE,'ct=y');
    curl_setopt($ch,CURLOPT_COOKIE,'dbcl2="30518086:L6oTdYcT424"');
    curl_setopt($ch,CURLOPT_COOKIE,'ue="jiangbian66@sohu.com"');
    $re = curl_exec($ch);
    curl_close($ch);
    echo $re;
    $album_info = json_decode($info);
    var_dump( $album_info);
/*    //连接数据库
    $db = mysql_connect(DB_HOST, DB_USER, DB_PWD);
    mysql_select_db(DB_NAME,$db);//
    //mysql_query('SET NAMES \'gbk\'');
    //mysql_query('SET CHARACTER_SET \'gbk\'');

    if(! mysql_query($insert, $db) ) {
        echo '<h1>error!</h1>';
        echo mysql_error().'<br />';
        echo 'the sql is '.$insert."<br />";
    }*/
}