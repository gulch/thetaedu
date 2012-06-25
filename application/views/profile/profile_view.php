<?php defined('SYSPATH') or die('No direct access allowed.');?>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function()
    {

    });

    function follow(elem,id)
    {
        var rtype = jQuery("#relationshiptype").val();
        if(id!=undefined && rtype!='')
        {
            var surl = "rtype="+rtype+"&touser="+id;
            var old = elem;
            jQuery(elem).replaceWith('<div class="button2" style="width: 100%; text-align: center; padding: 0;" id="saveanim"><div align="center" class="animation clearfix"><div class="circle one"></div><div class="circle two"></div></div></div>');
            jQuery.ajax({type: "POST", url: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/profile_follow'; ?>", data: surl, success: function(msg)
            {
                if (msg == 'OK')
                {
                    location.reload(true);
                }
                if (msg=='ERROR')
                {
                    alert("<?php echo __('notsaveerrormessage'); ?>");
                    jQuery("#saveanim").replaceWith(old);
                }
            }});
        }
    }

    function unfollow(elem,id)
    {
        if(id!=undefined)
        {
            var surl = "touser="+id;
            var old = elem;
            jQuery(elem).replaceWith('<div class="button2" style="width: 100%; text-align: center; padding: 0;" id="saveanim"><div align="center" class="animation clearfix"><div class="circle one"></div><div class="circle two"></div></div></div>');
            jQuery.ajax({type: "POST", url: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/profile_unfollow'; ?>", data: surl, success: function(msg)
            {
                if (msg == 'OK')
                {
                    location.reload(true);
                }
                if (msg=='ERROR')
                {
                    alert("<?php echo __('notsaveerrormessage'); ?>");
                    jQuery("#saveanim").replaceWith(old);
                }
            }});
        }
    }

    function msend(elem,id)
    {
        var mtext = jQuery("#mtext").val();
        if(id!=undefined && mtext!='')
        {
            var surl = "mtext="+mtext+"&uid="+id;
            var old = elem;
            jQuery(elem).replaceWith('<div class="button2" style="width: 100%; text-align: center; padding: 0;" id="saveanim"><div align="center" class="animation clearfix"><div class="circle one"></div><div class="circle two"></div></div></div>');
            jQuery.ajax({type: "POST", url: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/message_send'; ?>", data: surl, success: function(msg)
            {
                if (msg == 'OK')
                {

                    jQuery("#saveanim").replaceWith(old);
                    jQuery("#mtext").val('');
                    jQuery('#messdiv').hide();
                    alert("<?php echo __('message.send'); ?>");

                }
                if (msg=='ERROR')
                {
                    alert("<?php echo __('notsaveerrormessage'); ?>");
                    jQuery("#saveanim").replaceWith(old);
                }
            }});
        }
    }

    function subscribe(elem,id)
    {
        if(id!=undefined)
        {
            var surl = "touser="+id;
            var old = elem;
            jQuery(elem).replaceWith('<div class="button2" style="width: 100%; text-align: center; padding: 0;" id="saveanim"><div align="center" class="animation clearfix"><div class="circle one"></div><div class="circle two"></div></div></div>');
            jQuery.ajax({type: "POST", url: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/profile_subscribe'; ?>", data: surl, success: function(msg)
            {
                if (msg == 'OK')
                {
                    location.reload(true);
                }
                if (msg=='ERROR')
                {
                    alert("<?php echo __('notsaveerrormessage'); ?>");
                    jQuery("#saveanim").replaceWith(old);
                }
            }});
        }
    }
</script>
<div id="profilediv">
    <h2 style="padding: 20px; text-align: right"><?php echo $fullname; ?></h2>
    <table width="100%" style="border-width: 0px; border-style:none;">
        <tr>
            <td width="70%" style="text-align: left; background-color: rgba(0,0,0,0.05);">
                <table id="insidetable" align="center" style="border-width: 0px; border-style:none; margin: 20px;">
                    <tr>
                        <td style="padding-left: 10px" align="left">
                            <?php echo __('profile.aboutme').': '.$bio.'<br/><br/>'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px" align="left">
                            <?php echo substr($birthday,8,2).'.'.substr($birthday,5,2).'.'.substr($birthday,0,4); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px" align="left">
                                <?php if($sex == 0) echo __('profile.sex.undef'); ?>
                                <?php if($sex == 1) echo __('profile.sex.male'); ?>
                                <?php if($sex == 2) echo __('profile.sex.female'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px" align="left">
                            <?php echo __('profile.town').': '.$town; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px" align="left">
                            <?php
                                if($userlanguages)
                                {
                                    echo '<br/><span style="color:#cccc0c; font-size:22px;">'.__('profile.languages').':</span><br/>';
                                    foreach($userlanguages as $ul)
                                    {
                                        if($ul["ico_image"])
                                        {
                                            echo '<img src="../assets/images/'.$uc["ico_image"].'"/>'.$ul["name"].'<br/>';
                                        }
                                        else
                                        {
                                            echo $ul["name"].'<br/>';
                                        }
                                    }
                                }
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td id="#imageUpTd" width="30%" style="background-image:url('../assets/images/back1.gif'); background-repeat: repeat;">
                <div style="width: 100%; text-align:center; padding-top: 35px;"><img id="avatar" src="<?php if($avatar_image) echo '../media/uimg/'.$avatar_image; else echo '../assets/images/no_avatar.gif'; ?>"/></div>
                <div style="width: 100%; color:#FFFFFF; text-align: center; vertical-align: middle; height: 40px; padding-top: 10px;"><?php echo __('user.ratio').': <b style="color:yellow;">'.$rating.'</b>'; ?></div>
                <?php if(!$ismine){ ?>
                    <a href="javascript:void(0)" style="width: 100%; text-align: center; padding: 0;" onclick="if(jQuery('#reldiv').css('display') == 'none') jQuery('#reldiv').show(); else jQuery('#reldiv').hide();" class="button2"><?php if($relationship) echo __('Youare').'<b style="color:yellow;">'.$relationship[0]['name'].'</b>'; else echo __('profile.follow'); ?></a>
                    <div id="reldiv" style="background-color: #51DFFF; padding: 5px; margin: 0 auto;">
                        <select id="relationshiptype" name="relationshiptype">
                            <?php
                            foreach($relationshiptypes as $rt)
                            {
                                echo '<option title="../assets/images/'.$rt["ico_image"].'" value="'.$rt["id"].'">'.$rt["name"].'</option>';
                            }
                            ?>
                        </select>
                        <script>jQuery(document).ready(function(){jQuery("#relationshiptype").msDropDown(); jQuery("#reldiv").hide();});</script>
                        <a href="javascript:void(0)" style="width: 100%; text-align: center; padding: 0;" onclick="follow(this,<?php echo $uid; ?>)" class="button2"><?php echo __('profile.dofollow'); ?></a>
                        <a href="javascript:void(0)" style="width: 100%; text-align: center; padding: 0;" onclick="unfollow(this,<?php echo $uid; ?>)" class="button2"><?php echo __('profile.dounfollow'); ?></a>
                    </div>
                    <a href="javascript:void(0)" style="width: 100%; text-align: center; padding: 0;" onclick="subscribe(this,<?php echo $uid; ?>)" class="button2"><?php if($subscribe) echo '<b style="color:yellow;">'.__('profile.unsubscribe').'</b>'; else echo __('profile.subscribe'); ?></a>
                    <a href="javascript:void(0)" style="width: 100%; text-align: center; padding: 0;" onclick="if(jQuery('#messdiv').css('display') == 'none') jQuery('#messdiv').show(); else jQuery('#messdiv').hide();" class="button2"><?php echo __('profile.sendmessage'); ?></a>
                    <div id="messdiv" style="display: none; background-color: #51DFFF; padding: 5px; margin: 0 auto;">
                        <textarea id="mtext" name="mtext" placeholder="<?php echo __('input.message'); ?>"></textarea>
                        <a href="javascript:void(0)" style="width: 100%; text-align: center; padding: 0;" onclick="msend(this,<?php echo $uid; ?>)" class="button2"><?php echo __('profile.dosend'); ?></a>
                    </div>
                <?php } ?>
            </td>
        </tr>
        <tr style="padding-top: 30px;">
            <td  style="text-align: left; background-color: rgba(0,0,0,0.05);">
                <table id="insidetable" align="center" style="border-width: 0px; border-style:none; margin: 20px;">
                    <tr>
                        <td>
                           <?php
                                if($usercontacts)
                                {
                                    echo '<br/><span style="color:#cccc0c; font-size:22px;">'.__('profile.contacts').':</span><br/><br/>';
                                    foreach($usercontacts as $uc)
                                    {
                                        echo '<p><img src="../assets/images/'.$uc["ico_image"].'"/> '.$uc["name"].': '.$uc["value"].'</p>';
                                    }
                                }
                            ?>
                        </td>
                    <tr>
                    </tr>
                </table>
                <table id="insidetable" align="center" style="border-width: 0px; border-style:none; margin: 20px;">
                    <tr>
                        <td>
                            <?php
                            if($following_count > 0)
                            {
                                echo '<br/><span style="color:#cccc0c; font-size:22px;">'.__('profile.isyoufollowing').':</span><br/><br/>';
                                $i = 1;
                                foreach($following as $ff)
                                {
                                    if($i==10)
                                    {
                                        echo '<br/>';
                                        $i=1;
                                    }
                                    if($ff['avatar_image']=='') $ff['avatar_image'] = 'no_avatar.gif';
                                    echo '<a class="normaltip" title="'.$ff['fullname'].' ('.$ff['typename'].')" href="uid'.$ff['userid'].'" ><img src="../media/uimg/small_.'.$ff["avatar_image"].'"/></a>';
                                    $i++;
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            if($followers_count > 0)
                            {
                                echo '<br/><span style="color:#cccc0c; font-size:22px;">'.__('profile.isyoufollowers').':</span><br/><br/>';
                                $i = 1;
                                foreach($followers as $ff)
                                {
                                    if($i==10)
                                    {
                                        echo '<br/>';
                                        $i=1;
                                    }
                                    if($ff['avatar_image']=='') $ff['avatar_image'] = 'no_avatar.gif';
                                    echo '<a class="normaltip" title="'.$ff['fullname'].' ('.$ff['typename'].')" href="uid'.$ff['userid'].'" ><img src="../media/uimg/small_.'.$ff["avatar_image"].'"/></a>';
                                    $i++;
                                }
                            }
                            ?>
                        </td>
                    <tr>
                    <tr>
                        <td>
                            <?php
                            if($publications)
                            {
                                echo '<br/><span style="color:#cccc0c; font-size:22px;">'.__('Publications').':</span><br/><br/>';
                                foreach($publications as $pp)
                                {
                                    if($pp['published'] == 0)
                                    {
                                        if($ismine)
                                        echo '<div class="publist"><a style="text-decoration: none;" href="publication/view/'.$pp['id'].'" ><span style="font-size:12px">'.date('H:i:s  d.m.y',strtotime($pp['createdate'])).'&nbsp;&nbsp;&nbsp;'.$pp['galuzname'].'.'.$pp['predmetname'].'</span><br/><span style="color:red;">'.$pp['title'].'</span></a></div>';
                                    }
                                    else
                                    {
                                        echo '<div class="publist"><a style="text-decoration: none;" href="publication/view/'.$pp['id'].'" ><span style="font-size:12px">'.date('H:i:s  d.m.y',strtotime($pp['createdate'])).'&nbsp;&nbsp;&nbsp;'.$pp['galuzname'].'.'.$pp['predmetname'].'</span><br/>'.$pp['title'].'</a></div>';
                                    }
                                }
                            }
                            ?>
                        </td>
                    <tr>
                    <tr>
                        <td>
                            <?php
                            if($subscribes)
                            {
                                echo '<br/><span style="color:#cccc0c; font-size:22px;">'.__('Subscribes').':</span><br/><br/>';
                                $i = 1;
                                foreach($subscribes as $pp)
                                {
                                    if($pp['usersubscribe']==0)
                                    {
                                        echo '<a class = "asubsr" style="background-color: rgba(255, 231, 0, 0.28);" href="search?p='.$pp['kurssubscribe'].'" >'.$pp['kursname'].'</a>';
                                    }
                                    if($pp['kurssubscribe']==0)
                                    {
                                        echo '<a class = "asubsr" href="uid'.$pp['usersubscribe'].'" >'.$pp['fullname'].'</a>';
                                    }
                                }
                            }
                            ?>
                        </td>
                    <tr>
                </table>
            </td>
            <td style="text-align: right; vertical-align: bottom;">

            </td>
        </tr>
    </table>

</div>