<?php defined('SYSPATH') or die('No direct access allowed.');?>
<script type="text/javascript">
    jQuery.noConflict();

    function cancelTask(elem,id)
    {
        if(confirm("<?php echo __('realycancel');?>"))
        {
            jQuery(elem).remove();
            if(id!=undefined)
            {
                var surl = "id="+id;
                jQuery.ajax({type: "POST", url: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/task_cancel'; ?>", data: surl, success: function(msg)
                {

                }});
            }
        }
    }
</script>
<div id="profilediv">
    <h2 style="padding: 20px; text-align: right"><?php echo __('Tasks'); ?></h2>
    <?php if(empty($tasks_list)) echo '<h1>'.__('notasks').'</h1>'; else echo $tasks_list; ?>
</div>