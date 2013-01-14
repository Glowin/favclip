-- phpMyAdmin SQL Dump
-- version 3.4.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 01 月 14 日 11:04
-- 服务器版本: 5.1.53
-- PHP 版本: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `favclip`
--

-- --------------------------------------------------------

--
-- 表的结构 `album`
--

CREATE TABLE IF NOT EXISTS `album` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `album_id` bigint(20) NOT NULL,
  `album_name` varchar(60) NOT NULL,
  `album_createdtime` datetime NOT NULL,
  `album_updatedtime` datetime NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `note`
--

CREATE TABLE IF NOT EXISTS `note` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `note_id` bigint(20) NOT NULL,
  `note_name` varchar(200) NOT NULL,
  `note_content` longtext NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `photo_id` bigint(20) NOT NULL,
  `photo_large` tinyint(1) NOT NULL,
  `photo_download` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_status` binary(1) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(60) NOT NULL,
  `user_nickname` varchar(50) NOT NULL,
  `user_motto` varchar(60) NOT NULL,
  `user_intro` longtext NOT NULL,
  `user_follow` int(5) NOT NULL DEFAULT '0',
  `user_followed` int(10) NOT NULL,
  `user_joinedtime` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  `user_updatedtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_level` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`ID`, `user_status`, `user_id`, `user_name`, `user_nickname`, `user_motto`, `user_intro`, `user_follow`, `user_followed`, `user_joinedtime`, `user_updatedtime`, `user_level`) VALUES
(39, '1', 1397711, 'Google.de', 'Tony Yet', 'instigator at large', '       <span class="pl">所属部落: <a href="http://www.douban.com/tribe/1000294/?m=6">λ</a></span><br/>              <span class="pl">Tony Yet的blog:         &nbsp; <a href="http://9.douban.com/subject/9342974/" target="_blank" rel="me nofollow">Accentuate the Positive</a>         &nbsp; <a href="http://9.douban.com/subject/9122521/" target="_blank" rel="me nofollow">Inspired5</a>      </span><br/>             <div id="edit_intro"  class="j edtext pl">         <span id="intro_display" >i am an instigator at large<br /><br /><a href="http://www.tonyyet.com" target="_blank" rel="nofollow">www.tonyyet.com</a><br /><a href="http://www.twitter.com/tony_yet" target="_blank" rel="nofollow">www.twitter.com/tony_yet</a><br /><a href="http://weibo.com/TonyYet" target="_blank" rel="nofollow">http://weibo.com/TonyYet</a><br /><br /><br />我做过/参与过的一些项目：<br /><a href="http://www.TEDtoChina.com" target="_blank" rel="nofollow">www.TEDtoChina.com</a><br /><a href="http://www.1kg.org" target="_blank" rel="nofollow">www.1kg.org</a><br /><a href="http://www.maotouying.net" target="_blank" rel="nofollow">www.maotouying.net</a><br /><br /><br />推荐阅读：<br />我们能听到未来吗？<br /><a href="http://tonyyet.com/?p=163" target="_blank" rel="nofollow">http://tonyyet.com/?p=163</a><br /><br /><br /><br />===========  联系信息  ============<br /><br />需要联系请发email, 豆邮我不怎么看。<br />tony@<a href="http://tonyyet.com" target="_blank" rel="nofollow">tonyyet.com</a><br />    <br /><br />============= 箴言 ==============<br />只有事实才能证明你的心意，只有行动才能表明你的心迹。——傅雷<br /></span>                  <form style="display: none;" action="/j/people/Google.de/edit_intro"             name="edit_intro" mothod="post">             <textarea name="intro" >i am an instigator at large  www.tonyyet.com www.twitter.com/tony_yet http://weibo.com/TonyYet   我做过/参与过的一些项目： www.TEDtoChina.com www.1kg.org www.maotouying.net   推荐阅读： 我们能听到未来吗？ http://tonyyet.com/?p=163    ===========  联系信息  ============  需要联系请发email, 豆邮我不怎么看。 tony@tonyyet.com       ============= 箴言 ============== 只有事实才能证明你的心意，只有行动才能表明你的心迹。——傅雷 </textarea><br/>             <input id="intro_submit" class="submit" type="submit" value="保存" />             <input id="intro_cancel" class="cancel" type="button" value="取消" />         </form>          <span id="intro_error" class="attn" style="display: none;"></span>     </div>  ', 463, 4476, '2007-01-23 00:00:00', '2013-01-13 16:11:17', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
