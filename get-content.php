<?php

/**
 * @author Glowin
 * @copyright 2013
 */

set_time_limit(0);
ini_set('display_errors', 'On');
require_once("include/simple_html_dom.php");

//根据url获得用户user_name，用户的唯一标识是"user_name"
function get_user_name( $url ) {
    if ( $url == '') return 0;
    $replace = array('http://www.douban.com/people/' => '','/' => '');
    $user_name = (string) strtr($url, $replace);
    return $user_name;
}

//根据url获取相册ID
function get_alum( $url ) {
    if ( $url == '') return 0;
    $replace = array('http://www.douban.com/photos/album/' => '', '/' => '' );
    $id = (int) strtr($url, $replace);
    return $id;
}

//根据url获取相片ID
function get_photo( $url ) {
    if ( $url == '') return 0;
    $replace = array('http://www.douban.com/photos/photo/' => '', '/' => '' );
    $id = (int) strtr($url, $replace);
    return $id;
}

//根据url获得说说ID
function get_shuoshuo( $url ) {
    if ( $url == '') return 0;
    $replace = array('http://www.douban.com/people/' => '');
    $id = (int) strtr($url, $replace);
    return $id;
}

//根据用户user_name获取用户首页HTML
function html_user_name( $user_name ) {
    $url = 'http://www.douban.com/people/'. $user_name. '/';
    $html = file_get_html( $url );
    return $html;
}

//根据用户user_name获取用户所有的相册页面
function html_user_album( $user_name ) {
    $url = 'http://www.douban.com/people/'. $user_name. '/photos';
    $html = file_get_html( $url );
    $list_url = $html -> find('.paginator a') -> href;
    return $list_url;
    $album_html = array('$html');
    $html = file_get_html( $url );

    return $html;
}


//上传用户信息到数据库
function put_userinfo( $html ) {
    include_once('config.php');
    $user_name = $html -> find('.infobox .pl', 0) -> find('text', 0);
    $user_name = trim($user_name);
    $user_nickname = $html -> find('#content .info h1', 0) -> find('text', 0);
    $user_nickname = trim($user_nickname);
    $user_motto = $html -> find('#content .pl', 0) -> find('text', 0);
    //替换备注中的括号
    $user_motto = strtr($user_motto, array('(' => '', ')' =>'' ));
    //获取加入时间
    $user_joinedtime = $html -> find('.user-info .pl', 0) -> plaintext;
    $user_joinedtime = explode(' ',$user_joinedtime);
    $user_joinedtime = strtr($user_joinedtime[2], array('加入' =>''));
   // return $user_joinedtime;
    //用户状态默认为1，开启。如果为0则为加入黑名单
    $user_status = 1;
    //获得用户ID，通过用户首页的头像图片的命名得来
    $user_imgurl = $html -> find('.infobox img', 0) -> src;
    $user_imgurlinfo = explode('/', $user_imgurl);
    $user_id = (int)strtr($user_imgurlinfo[4], array('ul' => '','u' => '', '.jpg' => ''));
    if ($user_id == '') 
        $user_id = $user_name;

    //获取用户添加的附加信息
    $user_intro = $html -> find('.infobox .user-intro', 0) -> innertext;
    //用户级别默认为0，最高为10
    $user_level = 0;
    //关注人数
    $user_follow = $html -> find('#friend .pl a', 0) -> find('text', 0);
    $user_follow = (int)strtr($user_follow, array('全部' =>''));
    $user_follow = trim($user_follow);
    //被关注人数
    $user_followed = $html -> find('#friend a', -1) -> plaintext;
    $user_followed = strtr($user_followed, array('&gt; 被' => '', '人关注' => ''));
    $user_followed = strrev((int)strrev($user_followed));

    //update the user information to database
    //上传信息到数据库
    $insert = 'INSERT INTO user (
    user_status,
    user_id,
    user_name,
    user_nickname,
    user_motto,
    user_intro,
    user_follow,
    user_followed,
    user_joinedtime,
    user_level
    ) VALUES (
    \''.$user_status.'\',
    \''.$user_id.'\',
    \''.$user_name.'\',
    \''.$user_nickname.'\',
    \''.$user_motto.'\',
    \''.$user_intro.'\',
    \''.$user_follow.'\',
    \''.$user_followed.'\',
    \''.$user_joinedtime.'\',
    \''.$user_level.'\'
    )';

    //连接数据库
    $db = mysql_connect(DB_HOST, DB_USER, DB_PWD);
    mysql_select_db(DB_NAME,$db);//
    //mysql_query('SET NAMES \'gbk\'');
    //mysql_query('SET CHARACTER_SET \'gbk\'');

    if(! mysql_query($insert, $db) ) {
        echo '<h1>error!</h1>';
        echo mysql_error().'<br />';
        echo 'the sql is '.$insert."<br />";
    }
}

//上传相册信息到数据库，未完成
function put_albuminfo( $html ) {
    include_once('config.php');
    $user_indexurl = $html -> find('.info li a', 0) -> url;
    return $user_name = get_user_name($user_indexurl);
    $user_nickname = $html -> find('#content .info h1', 0) -> find('text', 0);
    $user_nickname = trim($user_nickname);
    $user_motto = $html -> find('#content .pl', 0) -> find('text', 0);
    //替换备注中的括号
    $user_motto = strtr($user_motto, array('(' => '', ')' =>'' ));
    //获取加入时间
    $user_joinedtime = $html -> find('.user-info .pl', 0) -> plaintext;
    $user_joinedtime = explode(' ',$user_joinedtime);
    $user_joinedtime = strtr($user_joinedtime[2], array('加入' =>''));
   // return $user_joinedtime;
    //用户状态默认为1，开启。如果为0则为加入黑名单
    $user_status = 1;
    //获得用户ID，通过用户首页的头像图片的命名得来
    $user_imgurl = $html -> find('.infobox img', 0) -> src;
    $user_imgurlinfo = explode('/', $user_imgurl);
    $user_id = (int)strtr($user_imgurlinfo[4], array('ul' => '', '.jpg' => ''));

    //获取用户添加的附加信息
    $user_intro = $html -> find('.infobox .user-intro', 0) -> innertext;
    //用户级别默认为0，最高为10
    $user_level = 0;
    //关注人数
    $user_follow = $html -> find('#friend .pl a', 0) -> find('text', 0);
    $user_follow = (int)strtr($user_follow, array('全部' =>''));
    $user_follow = trim($user_follow);
    //被关注人数
    $user_followed = $html -> find('#friend a', -1) -> plaintext;
    $user_followed = strtr($user_followed, array('&gt; 被' => '', '人关注' => ''));
    $user_followed = strrev((int)strrev($user_followed));

    //update the user information to database
    //上传信息到数据库
    $insert = 'INSERT INTO user (
    user_status,
    user_id,
    user_name,
    user_nickname,
    user_motto,
    user_intro,
    user_follow,
    user_followed,
    user_joinedtime,
    user_level
    ) VALUES (
    \''.$user_status.'\',
    \''.$user_id.'\',
    \''.$user_name.'\',
    \''.$user_nickname.'\',
    \''.$user_motto.'\',
    \''.$user_intro.'\',
    \''.$user_follow.'\',
    \''.$user_followed.'\',
    \''.$user_joinedtime.'\',
    \''.$user_level.'\'
    )';

    //连接数据库
    $db = mysql_connect(DB_HOST, DB_USER, DB_PWD);
    mysql_select_db(DB_NAME,$db);//
    //mysql_query('SET NAMES \'gbk\'');
    //mysql_query('SET CHARACTER_SET \'gbk\'');

    if(! mysql_query($insert, $db) ) {
        echo '<h1>error!</h1>';
        echo mysql_error().'<br />';
        echo 'the sql is '.$insert."<br />";
    }
}

//echo put_userinfo(html_user_name('Google.de'));
?>