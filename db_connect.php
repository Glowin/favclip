<?php
//============================================================+
// File name   : tce_db_dal_mysql.php
// Begin       : 2003-10-12
//
// Description : 
//               
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

if(!$db = @db_connect(DB_HOST, DB_PORT, DB_USER, DB_PWD, DB_NAME)) {
	die('<h2>'.db_error().'</h2>');
}
require_once('db_dal.php'); // Database Abstraction Layer for selected DATABASE type


//============================================================+
// END OF FILE
//============================================================+
