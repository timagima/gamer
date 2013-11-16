<?php
header('Content-Type: text/html; charset=utf-8'); //
date_default_timezone_set('UTC');
error_reporting(0);
ini_set('display_errors', isset($_GET["debug"]) ? 1 : 1);

require_once 'application/bootstrap.php';
//echo 'Время выполнения скрипта: ' . (microtime(true) - $start) . ' сек.<br />';

/*include_once "smsc_api.php";
$obj = new Sms();
list($sms_id, $sms_cnt, $cost, $balance) = $obj->SendSms("375293073228", "GS11 проверка связи", 0);
*/
?>
<script>
	var cs_time_diff = '1380782324000' - Date.now();
Date.prototype.toServerTime = function() {
	return new Date(this.getTime() + cs_time_diff);
	}
var now = new Date();
now.toServerTime();
</script>