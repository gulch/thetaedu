<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="authdiv">
 <h2><?php echo __('user.registertitle'); ?></h2>
 <?php if($message) echo	'<div class="messagediv">'.$message.'</div><br><br>'; ?>
 <form method="POST" action="/user/create">
 <table align="center" style="border-width: 0px; border-style:none; margin: 0 auto;">
     <tr>
         <td style="padding-right: 10px" align="right">
             <label for="username"><?php echo __('user.username'); ?></label>
         </td>
         <td align="left">
             <input type="text" id="username" name="username" value="<?php echo HTML::chars(Arr::get($_POST, 'username')) ?>" placeholder="<?php echo __('input.login'); ?>" required="" tabindex="1">
             <div class="error">
             	<?php echo Arr::get($errors, 'username'); ?>
             </div>
         </td>
     </tr>
     <tr>
         <td style="padding-right: 10px" align="right">
             <label for="email"><?php echo __('user.email'); ?></label>
         </td>
         <td align="left">
             <input type="text" id="email" name="email" value="<?php echo HTML::chars(Arr::get($_POST, 'email')) ?>" placeholder="<?php echo __('input.email'); ?>" required="" tabindex="2">
             <div class="error">
             	<?php echo Arr::get($errors, 'email'); ?>
             </div>
         </td>
     </tr>
     <tr>
         <td style="padding-right: 10px" align="right">
             <label for="password"><?php echo __('user.password'); ?></label>
         </td>
         <td align="left">
             <input type="password" id="password" name="password" placeholder="<?php echo __('input.password'); ?>" required="" tabindex="3">
             <div class="error">
             	<?php echo Arr::path($errors, '_external.password'); ?>
             </div>
         </td>
     </tr>
     <tr>
         <td style="padding-right: 10px" align="right">
             <label for="password_confirm"><?php echo __('user.confirmpassword'); ?></label>
         </td>
         <td align="left">
             <input type="password" id="password_confirm" name="password_confirm" placeholder="<?php echo __('input.confirmpassword'); ?>" required="" tabindex="4">
             <div class="error">
             	<?php echo Arr::path($errors, '_external.password_confirm'); ?>
             </div>
         </td>
     </tr>
     <tr><td height="30px"></td></tr>
     <tr>
         <td style="padding-right: 10px" align="right">
             <input name="register" type="submit" id="submit" tabindex="5" value="<?php echo __('user.register'); ?>">
         </td>
         <td align="left">
             <?php echo __('or').' '.HTML::anchor('login', __('user.todologin')).' '.__('ifhaveaccalready'); ?>
         </td>
     </tr>
 </table>
 </form>
 </div>