<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="authdiv">
<h2><?php echo __('user.logintitle'); ?></h2>
<?php if($message) echo	'<div class="messagediv">'.$message.'</div><br><br>'; ?>
<form method="POST" action="/user/login">
<table align="center" style="border-width: 0px; border-style:none; margin: 0 auto;">
    <tr>
        <td style="padding-right: 10px" align="right">
            <label for="username"><?php echo __('user.username'); ?></label>
        </td>
        <td align="left">
            <input type="text" id="username" name="username" value="<?php echo HTML::chars(Arr::get($_POST, 'username')) ?>" placeholder="<?php echo __('input.login'); ?>" required="" tabindex="1">
        </td>
    </tr>
    <tr>
        <td style="padding-right: 10px" align="right">
            <label for="password"><?php echo __('user.password'); ?></label>
        </td>
        <td align="left">
            <input type="password" id="password" name="password" placeholder="<?php echo __('input.password'); ?>" required="" tabindex="2">
        </td>
    </tr>
    <tr>
        <td style="padding-right: 10px" align="right">

        </td>
        <td align="left">
            <input name="remember" type="checkbox" tabindex="3">
            <?php echo __('rememberme'); ?>
        </td>
    </tr>
    <tr><td height="30px"></td></tr>
    <tr>
        <td style="padding-right: 10px" align="right">

        </td>
        <td align="left">
            <input name="redirect" type="hidden" value="<?php echo HTML::chars(Arr::get($_GET, 'req')) ?>"/>
            <input name="login" type="submit" id="submit" tabindex="4" value="<?php echo __('user.login'); ?>">
            <?php echo __('or').' '.HTML::anchor('create', __('user.cnaccount')); ?>
        </td>
    </tr>
</table>
</form>
<div style="clear: both; margin: 20px; text-align: center;">
    <?php echo __('user.orentersoc'); ?><br/>
    <iframe style="background-color: rgba(15, 13, 13, 1);" width="415px" height="80px" src="http://openapi.klookva.com.ua/index.php?ret=http://<?php echo $_SERVER['SERVER_NAME']; ?>/user/soclogin/"></iframe>
</div>
</div>