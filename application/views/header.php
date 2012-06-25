<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="wrapper">
    <a href="<?php echo URL::site('/') ?>" id="logo">THETA EDU</a>
    <ul id="menu" style="float:left">
        <li><a href="<?php echo URL::site('/messages/board/') ?>"><?php echo __('Board') ?></a></li>
        <li><a href="<?php echo URL::site('/messages') ?>"><?php echo __('Messages') ?></a></li>
        <li><a href="<?php echo URL::site('/search?v=all') ?>"><?php echo __('Publications') ?></a></li>
        <li><a href="<?php echo URL::site('/search') ?>"><?php echo __('Search') ?></a></li>
        <li><a href="<?php echo URL::site('/publication/tasks') ?>"><?php echo __('Tasks') ?></a></li>
        <li><a href="<?php echo URL::site('/publication/create') ?>"><?php echo __('Create') ?></a></li>
    </ul>
    <div onclick="showProfileMenu('#profileMenu')" style="float: right; cursor: pointer; padding: 3px; background-color: #FFC11E">
        <img src="<?php if($avatar_image) echo 'http://'.$_SERVER['SERVER_NAME'].'/media/uimg/small_.'.$avatar_image; else echo 'http://'.$_SERVER['SERVER_NAME'].'/assets/images/small_no_avatar.gif'; ?>" />
    </div>
    <script>
        function showProfileMenu(e)
        {
            if(jQuery(e).css('display') == 'none') jQuery(e).show(); else jQuery(e).hide();
        }
    </script>
</div>
<div id="profileMenu" style="display: none; text-align: right; background-color: #FFC11E;">
    <a class="button2" href="/profile"><?php echo __('profile.m.view'); ?></a>
    <a class="button2" href="/profile/edit"><?php echo __('profile.m.edit'); ?></a>
    <a class="button2" href="/logout"><?php echo __('profile.m.logout'); ?></a>
</div>