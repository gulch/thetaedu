<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php
if($users)
{
    foreach($users as $uu)
    {
        if($uu['avatar_image']=='') $uu['avatar_image']= 'no_avatar.gif';
        echo '<div class="publist">
               <table>
               <tr>
                <td style="padding: 5px;">
                    <a href="uid'.$uu['userid'].'"><img style="float:left" src="../media/uimg/'.$uu['avatar_image'].'"/></a>
                </td>
                <td style="padding: 5px;">
                    <h2>'.$uu['fullname'].'</h2>
                    R:<span style="font-size:24px; color:blue">'.$uu['rating'].'</span>
                    <br/>'.$uu['townname'].'<br/>'.date('d.m.Y',strtotime($uu['birthday'])).'<br/>
                </td>
               </tr>
               </table>
              </div>';
    }
}
?>