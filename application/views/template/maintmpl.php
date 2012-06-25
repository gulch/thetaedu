<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo substr(I18n::$lang, 0, 2); ?>" lang="<?php echo substr(I18n::$lang, 0, 2); ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="language" content="<?php echo I18n::$lang ?>" />
    <title><?php echo $title ?></title>
    <script type="text/javascript" src="/assets/script/jquery.js"></script>
    <?php foreach ($styles as $file => $type) echo HTML::style($file, array('media' => $type)), PHP_EOL ?>
    <?php foreach ($scripts as $file) echo HTML::script($file), PHP_EOL ?>
</head>
<!--[if lt IE 9]>
<script type="text/javascript" src="../assets/script/html5.js"></script>
<style type="text/css">
    .box1 figure {behavior:url(../assets/script/PIE.htc)}
</style>
<![endif]-->
<!--[if lt IE 7]>
<div style='clear:both;text-align:center;position:relative'>
    <a href="http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode"><img src="http://www.theie6countdown.com/images/upgrade.jpg" border="0" alt="" /></a>
</div>
<![endif]-->
<body id="page1">
<div class="body1">
    <div class="main">
        <!-- header -->
        <header>
            <?php echo $header; ?>
        </header>
        <!-- / header -->
        <div id="wrapper">
            <?php echo $content ?>
        </div>
        <!-- footer -->
        <footer>
            <div class="wrapper">
                <a href="index.html" id="footer_logo"><span>THETA</span>EDU</a>
                <ul id="icons">
                    <li><a href="http://www.facebook.com/ThetaEdu" class="normaltip" title="Facebook"><img src="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/assets/images/'; ?>icon1.gif" alt=""></a></li>
                    <li><a href="https://twitter.com/#!/ThetaEdu" class="normaltip" title="Twitter"><img src="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/assets/images/'; ?>icon2.gif" alt=""></a></li>
                    <li><a href="http://vk.com/thetaedu" class="normaltip" title="VKontakte"><img src="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/assets/images/'; ?>icon3.gif" alt=""></a></li>
                </ul>
            </div>
            <div class="wrapper">
                <nav>
                    <ul id="footer_menu">
                        <li class="active"><a href="<?php echo URL::site('/') ?>"><?php echo __('Home'); ?></a></li>
                        <li><a href="<?php echo URL::site('/contact') ?>"><?php echo __('Contact'); ?></a></li>
                        <li class="end"><a href="<?php echo URL::site('/about') ?>"><?php echo __('Aboutproject'); ?></a></li>
                    </ul>
                </nav>
                <div style="cursor:pointer;" onclick="location.href='<?php echo URL::site('/donate') ?>'" class="tel"><span>DO</span>NATE</div>
            </div>
        </footer>
        <!-- / footer -->
    </div>
</div>
</body>
</html>