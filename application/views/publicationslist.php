<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php
if($publications)
{
    foreach($publications as $pp)
    {
        echo '<div class="publist"><a style="text-decoration: none;" href="publication/view/'.$pp['id'].'" ><span style="font-size:12px">'.date('H:i:s  d.m.y',strtotime($pp['createdate'])).'&nbsp;&nbsp;&nbsp;'.$pp['galuzname'].'.'.$pp['predmetname'].'</span><br/>'.$pp['title'].'</a></div>';
    }
}
?>