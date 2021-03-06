<?php
//============================================================+
// File name   : get-content.php
// Begin       : 2003-01-14
//
// Description : 
//               
// Author: Glow Chiang
//
// (c) Copyright:
//               Glow Chiang
//               www.glowface.net
//               jiangbian66@gmail.com
//
// License: MIT License
//============================================================+

set_time_limit(0);
ini_set('display_errors', 'On');
require_once("include/simple_html_dom.php");

/**
 * 根据url获得用户user_name，用户的唯一标识是"user_name"
 * @param  string $url 用户个人页面的链接地址，包括http://www.douban.com
 * @return int      用户ID
 */
function get_user_name( $url ) {
    if ( $url == '') return 0;
    $replace = array('http://www.douban.com/people/' => '','/' => '');
    $user_name = (string) strtr($url, $replace);
    return $user_name;
}

/**
 * 通过用户的相册地址得到相册的ID
 * @param  string $url 相册的链接地址，包括http://www.douban.com
 * @return int      相册的ID
 */
function get_album( $url ) {
    if ( $url == '') return 0;
    $replace = array('http://www.douban.com/photos/album/' => '', '/' => '' );
    $id = (int) strtr($url, $replace);
    return $id;
}

/**
 * 根据url获取相片ID
 * @param  string $url 相片的url地址
 * @return int      相片的id
 */
function get_photo( $url ) {
    if ( $url == '') return 0;
    $replace = array('http://www.douban.com/photos/photo/' => '', '/' => '' );
    $id = (int) strtr($url, $replace);
    return $id;
}

/**
 * 根据url获得说说的ID
 * @param  string $url 说说的url地址
 * @return int      说说的ID
 */
function get_shuoshuo( $url ) {
    if ( $url == '') return 0;
    $replace = array('http://www.douban.com/people/' => '');
    $id = (int) strtr($url, $replace);
    return $id;
}

/**
 * 根据用户的user_name即他的名称来得到他的首页HTML页面
 * @param  string $user_name 用户名称
 * @return string            用户个人首页的HTML页面
 */
function html_user_name( $user_name ) {
    $url = 'http://www.douban.com/people/'. $user_name. '/';
    $html = file_get_html( $url );
    return $html;
}

/**
 * 根绝相册列表页面获得相册信息
 * @param  string $html 相册列表页面的HTML
 * @return array       相册的信息
 */
function get_album_info( $html) {
    $content = $html -> find('.albumlst');
    if (is_array($content))
    foreach ($content as $c) {
        $url = $c -> find('a', 0) -> href;
        $info['album_id'][] = get_album( $url );
        $coverpic = $c -> find('img', 0) -> src;
        $info['album_coverid'][] = get_photo( $coverpic );
        $info['album_name'][] = $c -> find('a', 1) -> plaintext;
        $info['album_descri'][] = $c -> find('.albumlst_descri', 0) -> plaintext;

        //SHA-1保存用户的相册更改信息，$album_pl格式为“54张照片 2013-01-06创建”
        // 如果相册更新的话，时间会发生变化，如果是第一次更新的话，字符串中的"创建"会换为"更新"
        $album_pl = $c -> find('.pl', 0) -> plaintext;
        $album_pl = strtr($album_pl, array("\r\n" => "", "\n" => "", "\t" => "", " " => ""));
        $info['album_sha1'][] = sha1($info['album_id'] . $album_pl);
        //提取相册中图片数和时间
        $album_pl = strtr($album_pl, array('张照片' => '', '创建' => '', '更新' => ''));
        $album_pl = explode('&nbsp;', $album_pl);
        $info['album_photonum'][] = (int)$album_pl[0];
        $info['album_createdtime'][] = trim($album_pl[1]);
    }
    return $info;
}
//get_album_info(file_get_html('http://www.douban.com/people/fugen/photos'));

/**
 * 根据用户user_name来获取用户所有的相册页面的地址
 * @param  string $user_name 用户名
 * @return [type]            [description]
 */
function html_user_album( $user_name ) {
    $url = 'http://www.douban.com/people/'. $user_name. '/photos';
    //获取用户所有的相册列表页面的url到数组$page_url
    $page_url[0] = $url;
    $html = file_get_html( $url );
    $link = $html -> find('.paginator a');
    if (is_array($link))
    foreach ($link as $li) {
        array_push($page_url, $li -> href);
    }

    //TDDO:上传每个页面的相册信息

}
// html_user_album('fugen');

/**
 * 根据用户页面的HTML来上传用户信息到数据库
 * @param  string $html 用户页面的HTML
 * @return none       [description]
 */
function put_userinfo( $html ) {
    require_once('config.php');
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
    preg_match_all("/\d+/is",$user_follow,$user_fl);
    $user_follow = $user_fl[0][0];
    //被关注人数
    $user_followed = $html -> find('.rev-link a', 0) -> plaintext;
    preg_match_all("/\d+/is",$user_followed,$user_fled);
    $user_followed = $user_fled[0][0];

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
    } else {
        echo '用户'. $user_name .'已添加到数据库<br />';
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