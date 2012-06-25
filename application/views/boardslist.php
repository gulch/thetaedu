<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php
if($boards)
{
    foreach($boards as $m)
    {
        echo '<div id="dmess'.$m['id'].'" class="messdiv">
        <span style="font-size: 12px;">'.Date::fuzzy_span(strtotime($m['datetime'])).'</span>
         <div class="messdiv" style="background-color: rgba(49, 66, 255, 0.09);" id="msub'.$m['id'].'">'.$m['message'].'</div>
        </div>';
    }
}
?>