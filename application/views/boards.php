<?php defined('SYSPATH') or die('No direct access allowed.');?>
<script type="text/javascript">
    jQuery.noConflict();
</script>
<div id="profilediv">
    <h2 style="padding: 20px; text-align: right"><?php echo __('Board'); ?></h2>
    <?php if(empty($board_list)) echo '<h1>'.__('nomessages').'</h1>'; else echo $board_list; ?>
</div>