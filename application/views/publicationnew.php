<?php defined('SYSPATH') or die('No direct access allowed.');?>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function()
    {
        jQuery('div.groupWrapper').Sortable(
            {
                accept: 'groupItem',
                helperclass: 'sortHelper',
                activeclass: 'sortableactive',
                hoverclass: 'sortablehover',
                handle: 'div.itemHeader',
                tolerance: 'pointer',
                onChange: function(ser)
                {
                },
                onStart: function()
                {
                    jQuery.iAutoscroller.start(this, document.getElementsByTagName('body'));
                },
                onStop: function()
                {
                    jQuery.iAutoscroller.stop();
                }
            }
        );

        var opts = {
            cssClass : 'el-rte',
            lang     : 'uk',
            height   : 450,
            toolbar  : 'complete',
            cssfiles : ['<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/assets/css/'; ?>elrte-inner.css']
        }
        jQuery('#editor').elrte(opts);
    });

    function serialize(s)
    {
        serial = jQuery.SortSerialize(s);
        if (serial.hash != '')
        {
            //window.location.href = 'index.php?option=com_hotel&task=savetophotels&' + serial.hash;
        }
    }

    function get_predmets(predm)
    {
        var id = jQuery('#galuz').val();
        if(id!=undefined && id!='')
        {
            var surl = "id="+id+"&pid="+predm;
            jQuery.ajax({type: "POST", url: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/get_predmets'; ?>", data: surl, success: function(msg)
            {
                jQuery("#forpredmet").html(msg);
            }});
        }
    }

    function delTaskNode(elem)
    {
        if(confirm("<?php echo __('realydelete');?>"))
        {
            jQuery(elem).parent().parent().remove();
        }
    }

    function newTaskNode(elem)
    {
        var mynode = '';
    <?php
    if($following)
    {
        $nn = '<select name="foruserid[]">';
        foreach($following as $f)
        {
            $nn .= '<option value="'.$f['userid'].'">'.$f['fullname'].'('.$f['rtype'].')</option>';
        }
        $nn .= '</select>';
        echo 'mynode = \''.$nn.'\';';
    }
    ?>
        var r = Math.random()*1000;
        jQuery('.groupWrapper').append('<div id="'+r+'" class="groupItem"><div class="itemHeader" style="-webkit-user-select: none; "><?php echo __('For').':';?>'+
            mynode+'</div><div class="itemContent"><ul><textarea style="height: 100px; width: 96%" name="tasktext[]"></textarea></ul><input type="checkbox" value="1" name="publish[]"/> <?php echo __('publish'); ?><br/><a href="javascript:void(0)" onclick="delTaskNode(this)"><?php echo __('publication.delete'); ?></a></div></div>');
    }

    function psave(elem)
    {
        var title = jQuery("#title").val();
        var galuz = jQuery("#galuz").val();
        var predmet = jQuery("#predmet").val();
        var ptext = jQuery('#editor').elrte('val');

        var foruserid = '';
        jQuery(":input[name='foruserid[]']").each(function () {
            foruserid += jQuery(this).val()+';';
        });

        var tasktext = '';
        jQuery("[name='tasktext[]']").each(function () {
            tasktext += jQuery(this).val()+'=;=';
        });

        var publish = '';
        jQuery("[name='publish[]']").each(function () {
            if(jQuery(this).attr('checked')=='checked') publish += '1;'; else publish += '0;';
        });

        var surl = "title="+title+
            "&galuz="+galuz+
            "&predmet="+predmet+
            "&ptext="+ptext+
            "&foruserid="+foruserid+
            "&tasktext="+tasktext+
            "&publish="+publish;
        var old = elem;
        jQuery(elem).replaceWith('<div class="button2" id="saveanim"><div align="center" class="animation clearfix"><div class="circle one"></div><div class="circle two"></div></div></div>');
        jQuery.ajax({type: "POST", url: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/publication_new'; ?>", data: surl, success: function(msg)
        {
            if (msg=='ERROR')
            {
                alert("<?php echo __('notsaveerrormessage'); ?>");
                jQuery("#saveanim").replaceWith(old);
            }
            else
            {
                location.href = msg;
            }
        }});
    }
</script>
<div id="profilediv">
    <h2 style="padding: 20px; text-align: right"><?php echo __('publication.create'); ?></h2>
    <table width="100%" style="border-width: 0px; border-style:none;">
        <tr>
            <td colspan="2" style="padding: 10px;">
                <?php echo __('publ.title'); ?><br/>
                <input style="width: 96%" type="text" id="title" name="title" value="" placeholder="<?php echo __('input.title'); ?>" required="" tabindex="1">
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 10px;">
                <?php echo __('publ.galuz'); ?><br/>
                <select onchange="get_predmets(0)" id="galuz" name="galuz" placeholder="<?php echo __('input.galuz'); ?>" required="" tabindex="2">
                    <?php
                    foreach($galuz as $g)
                    {
                            echo '<option value="'.$g['id'].'">'.$g['name'].'</option>';
                    }
                    ?>
                </select>
                <br/>
                <?php
                echo __('publ.predmet').'<br/>';
                ?>
                <div id="forpredmet"></div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 10px;">
                <div id="editor"></div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 10px;">
                <?php echo __('Tasks').':'; ?><br/>
                <div id="sort1" class="groupWrapper"></div>
                <a href="javascript:void(0)" onclick="newTaskNode(this)"><?php echo __('newNode'); ?></a>
            </td>
        </tr>
    </table>
    <div id="profileMenu" style="text-align: right;">
        <a class="button2" onclick="psave(this)" href="javascript:void(0)"><?php echo __('Save'); ?></a>
    </div>
</div>