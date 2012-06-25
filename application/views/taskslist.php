<?php defined('SYSPATH') or die('No direct access allowed.');?>
<script type="text/javascript">
    function showHide(e)
    {
        if(jQuery(e).css('display') == 'none') jQuery(e).show(); else jQuery(e).hide();
    }
</script>
<?php
if($tasks)
{
    foreach($tasks as $m)
    {
        echo '<div style="cursor:default;" id="dmess'.$m['id'].'" class="messdiv"><div>';
        echo '<span style="font-size: 12px;">'.Date::fuzzy_span(strtotime($m['datetime'])).'</span></div>
            <div class="messdiv" style="background-color: rgba(49, 66, 255, 0.09);" id="msub'.$m['id'].'"><a href="'.URL::site('/publication/edit_task/'.$m['id']).'">'.$m['title'].'</a><div class="messdiv" style="float:right;font-size:14px;" onclick="cancelTask(\'#dmess'.$m['id'].'\','.$m['id'].')">'.__('task.cancel').'</div></div></div>';
    }
}
?>