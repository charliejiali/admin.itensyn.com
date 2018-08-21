<?php
$titleArr = explode("|", $pageTitle);
$titleStr = "OWL SYS";
if (strlen($pageTitle) > 0) {
    $titleStr = $titleStr . " -- " . implode(" -- ", $titleArr);
}
$isDebug = true;
?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta charset="utf-8">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="Description" content="OWL SYS">
<!--<meta name="viewport" content="width=device-width, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">-->

<title><?php echo $titleStr ?></title>

<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css">

<script src="//libs.baidu.com/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.10.2.min.js"><\/script>')</script>
<!--<script language="JavaScript">
    (function () {
        var isMobile = {
            _ua: window.navigator.userAgent,
            Android: function () {
                return this._ua.match(/Android/i) ? true : false;
            },
            BlackBerry: function () {
                return this._ua.match(/BlackBerry/i) ? true : false;
            },
            iOS: function () {
                return this._ua.match(/iPhone|iPad|iPod/i) ? true : false;
            },
            Windows: function () {
                return this._ua.match(/IEMobile/i) ? true : false;
            },
            WeiXin: function () {
                return this._ua.match(/MicroMessenger/i) ? true : false;
            },
            any: function () {
                return (this.Android() || this.BlackBerry() || this.iOS() || this.Windows());
            }
        };
        if (isMobile.any()) {
            document.writeln("<meta name=\'viewport\' content=\'width=640, user-scalable=no, maximum-scale=1\'>");
        } else {
            document.writeln("<meta name=\'viewport\' content=\'width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0\'>");
        }
    })();
</script>-->

<?php if ($isDebug) { ?>
    <script src="./js/libs/modernizr-custom.js" type="text/javascript"></script>
    <script src="./js/libs/jquery.magnific-popup.min.js" type="text/javascript"></script>
    <script src="./js/libs/jquery.lazyload.min.js" type="text/javascript"></script>
    <script src="./js/libs/selectbox-min.js" type="text/javascript"></script>
    <script src="./js/bdq_validator.js" type="text/javascript"></script>
    <script src="./js/bdp_common.js" type="text/javascript"></script>
    <script src="./js/bdp_basic.js" type="text/javascript"></script>
    <script src="./js/bdp_app.js" type="text/javascript"></script>
<?php } else { ?>
    <script src="./js/apps.min.js" type="text/javascript"></script>
<?php } ?>
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="css/style_for_ie.css">
<script src="http://apps.bdimg.com/libs/html5shiv/3.7/html5shiv.min.js"></script>
<![endif]-->
