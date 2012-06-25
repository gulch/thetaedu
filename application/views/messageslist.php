<?php defined('SYSPATH') or die('No direct access allowed.');?>
<script type="text/javascript">
    function showHide(e)
    {
        if(jQuery(e).css('display') == 'none') jQuery(e).show(); else jQuery(e).hide();
    }
</script>
<?php
    if($messages)
    {
        foreach($messages as $m)
        {
            if($m['isread'] == 0)
            {
                echo '<div id="dmess'.$m['id'].'" class="messdiv_unr"><div onclick="showHide(\'#msub'.$m['id'].'\'); checkread('.$m['id'].'); jQuery(this).parent().attr(\'class\',\'messdiv\')">';
            }
            else
            {
                echo '<div id="dmess'.$m['id'].'" class="messdiv"><div onclick="showHide(\'#msub'.$m['id'].'\');">';
            }
            echo '<span style="font-size: 12px;">'.Date::fuzzy_span(strtotime($m['createdatetime'])).'</span><br/>'.__('From').': <a style="font-size:18px; text-decoration:none;" href="uid'.$m['fromuserid'].'">'.$m['fullname'].'</a></div>
            <div class="messdiv" style="background-color: rgba(49, 66, 255, 0.09); display:none" id="msub'.$m['id'].'">'.$m['text'].'<div class="messdiv" style="float:right;font-size:14px;" onclick="delMessage(\'#dmess'.$m['id'].'\','.$m['id'].')">'.__('message.delete').'</div></div></div>';
        }
    }
?>