<?php defined('SYSPATH') or die('No direct access allowed.');?>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function()
    {
        var opts = {
            cssClass : 'el-rte',
            lang     : 'uk',
            height   : 450,
            toolbar  : 'complete',
            cssfiles : ['<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/assets/css/'; ?>elrte-inner.css']
        }
        jQuery('#editor').elrte(opts);

        jQuery("#doneSlider").slider({ from: 0, to: 100, step: 1, round: 1, dimension: '&nbsp;%', skin: "round.plastic" });
    });

    function psave(elem)
    {
        var done = jQuery("#doneSlider").val();
        var text = jQuery('#editor').elrte('val');

        text = text.replace(/\\n/g, "</p><p>");
        text = text.replace(/\n/g, "<br/>");

        var surl = "id=<?php echo $id; ?>&text="+text+"&done="+done;
        var old = elem;
        jQuery(elem).replaceWith('<div class="button2" id="saveanim"><div align="center" class="animation clearfix"><div class="circle one"></div><div class="circle two"></div></div></div>');
        jQuery.ajax({type: "POST", url: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/task_save'; ?>", data: surl, success: function(msg)
        {
            if (msg == 'OK')
            {
                location.href = '<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/publication/tasks/'; ?>';
            }
            if (msg=='ERROR')
            {
                alert("<?php echo __('notsaveerrormessage'); ?>");
                jQuery("#saveanim").replaceWith(old);
            }
        }});
    }
</script>
<div id="profilediv">
    <h2 style="padding: 20px; text-align: right"><?php echo __('publication.editing'); ?></h2>
    <table width="100%" style="border-width: 0px; border-style:none;">
        <tr>
            <td colspan="2" style="padding: 10px;">
                <?php echo __('task.title').' "'.$publicationtitle.'":'; ?> <br/>
                <b><?php echo $title; ?></b>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 10px;">
                <div id="editor"><?php echo $text; ?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 10px;">
                <?php echo __('task.done'); ?>:<br/><br/>
                <div style="width: 200px">
                    <input type="slider" id="doneSlider" value="<?php echo $done; ?>"/>
                </div>
            </td>
        </tr>
    </table>
    <div id="profileMenu" style="text-align: right;">
        <a class="button2" onclick="psave(this)" href="javascript:void(0)"><?php echo __('Save'); ?></a>
    </div>
</div>