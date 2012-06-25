<?php defined('SYSPATH') or die('No direct access allowed.');?>
<script type="text/javascript">
    jQuery.noConflict();
    function checkread(id)
    {
        if(id!=undefined)
        {
            var surl = "id="+id;
            jQuery.ajax({type: "POST", url: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/message_checkread'; ?>", data: surl, success: function(msg)
            {

            }});
        }
    }

    function delMessage(elem,id)
    {
        if(confirm("<?php echo __('realydelete');?>"))
        {
            jQuery(elem).remove();
            if(id!=undefined)
            {
                var surl = "uid="+id;
                jQuery.ajax({type: "POST", url: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/message_delete'; ?>", data: surl, success: function(msg)
                {

                }});
            }
        }
    }
</script>
<div id="profilediv">
    <h2 style="padding: 20px; text-align: right"><?php echo __('Messages'); ?></h2>
    <?php if(empty($messages_list)) echo '<h1>'.__('nomessages').'</h1>'; else echo $messages_list; ?>
</div>