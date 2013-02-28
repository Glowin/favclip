<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>用户管理页面</title>
</head>
<body>
<form action="user.php" method="POST">
    <label for="url">用户地址：</label>
    <input type="text" name="url">
    <input type="submit" value="submit" name="submit">
</form>
<?php
set_time_limit(0);
ini_set('display_errors', 'On');
// put_userinfo( file_get_html('http://www.douban.com/people/byvoid/') );
// echo check_user('http://www.douban.com/people/byvoid/');
if (isset($_POST['submit'])) {
    $url = $_POST['url'];
    while( $url ) {
        $url = get_user_relate($url);
    }
}
?>
</body>
</html>
<?php
/**
 * 检查用户的是否存在于数据库
 * @param  string $url 用户个人页面的地址
 * @return bool      如果存在则返回为1，如果不存在则返回为0
 */
function check_user( $url='' ) {
    require_once('get-content.php');
    require_once('db_connect.php');
    $sql = 'SELECT user_name FROM user WHERE user_name ="'. get_user_name($url) .'"';
    echo $sql;
    if ($r = db_query($sql, $db)) {

        if (db_fetch_array($r)) {
            return 1;
        }
        else {
            return 0;
        }
    }
}
echo check_user('http://www.douban.com/people/farmostwood/');

/**
 * 获得用户页面上其收听的用户的信息，并提交到数据库中
 * @param  array    $url_list    一个用户的url地址，如果是一个字符串的话，则自动转换为数组
 * @return array    用户个人首页上的收听的全部用户信息，返回为数组形式
 */
function get_user_relate($url_list) {
    require_once('config.php');
    require_once('get-content.php');
    require_once("include/simple_html_dom.php");

    if ( !is_array($url_list) )
        $url_list = (array)$url_list;

    foreach ( $url_list as $url ) {
        $html = file_get_html($url);
        $fled_user = $html -> find('#friend .obu');
        foreach ($fled_user as $a) {
            $link = $a -> find('a', 0) -> href;
            array_push($url_newlist, $link);
            $user_html = file_get_html($link);
            if( ! check_user($link)) {
                put_userinfo( $user_html );
            }
        }
    }

    return $url_newlist;
/*    $user_follow_page = $html -> find('#friend .pl a', 0) -> href;
    $user_followed_page = $html -> find('.rev-link a', 0) -> href;
   //由于未完成豆瓣cookie登陆功能，此功能暂时搁浅

    //获得用户第一页关注人和被关注人的HTML页面
    $flpage_list_html = file_get_html($user_follow_page);echo $flpage_list_html;
    $fledpage_list_html = file_get_html($user_followed_page);

    //获取用户所有的关注列表页面的url到数组$flpage_url
    $flpage_url[0] = $user_follow_page;
    $flpage_link = $flpage_list_html -> find('.paginator a', 0) -> href;
    if (is_array($flpage_link))
    foreach ($flpage_link as $li) {
        array_push($flpage_url, $li -> href);
        echo $li -> href;
    }

    //获取用户所有的被被关注列表页面的url到数组$fledpage_url
    $fledpage_url[0] = $user_followed_page;
    $fledpage_link = $fledpage_list_html -> find('.paginator a');
    if (is_array($fledpage_link))
    foreach ($fledpage_link as $li) {
        array_push($fledpage_url, $li -> href);
    }
*/
}

//get_user_relate('http://www.douban.com/people/nullcc/');
//数据库调用模板
function db_dosomething( $user_id ) {
    include_once('config.php');
    $sql = "SELECT user_id FROM user";
    if ($r = db_query($sql, $db)) {
        while($m = db_fetch_array($r)) {
            echo $m['user_id'];
        }
    }
}
?>