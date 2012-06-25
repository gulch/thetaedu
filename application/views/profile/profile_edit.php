<?php defined('SYSPATH') or die('No direct access allowed.');?>
<script type="text/javascript">
    jQuery.noConflict();
    var oldHtml = '';
    var cdiv = '';
    var spane = '<span onclick="jQuery(this).parent().remove()" style="cursor: pointer; vertical-align:baseline; padding:10px; background-color: #ffc11e; -webkit-border-radius: 5px;"><?php echo __('del'); ?></span>';
    jQuery(document).ready(function()
    {
        jQuery("#imageUp").html5Uploader({
            name: "profileImage",
            postUrl: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/profile_setimage'; ?>",
            onClientLoadStart: function(){
                oldHtml = jQuery('#imageUp').html();
                jQuery('#imageUp').html('<div align="center" class="animation clearfix"><div class="circle one"></div><div class="circle two"></div></div>');
            },
            onSuccess:function(e,file,msg){
                if(msg=='ERROR')
                {
                    jQuery('#imageUp').html('<?php __("error"); ?>');
                }
                if(msg=='DENY')
                {
                    window.location.reload();
                }
                if(msg != 'ERROR')
                {
                    jQuery('#avatar').attr('src',msg);
                    jQuery('#imageUp').html(oldHtml);
                }
            }
        });
        jQuery("#town").autocomplete("<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/profile_getalltown'; ?>",{
            width: 300,
            selectFirst: false,
            minChars:1,
            max: 20,
            formatItem:function(row){
                return row[0] + ', '+row[1];
            },
            formatResult: function(row){
                return row[0];
            }
        });
        jQuery("#town").result(function (evt, data, formatted)
        {
            jQuery("#townid").val(data[2]);
        });

        jQuery("#languages").tokenInput("<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/profile_getalllang'; ?>", {
            preventDuplicates: true,
            hintText: "<?php echo __('input.letsinput'); ?>",
            noResultsText: "<?php echo __('input.nofound'); ?>",
            searchingText: "<?php echo __('input.search'); ?>",
            onAdd: function (item) {
                   if(jQuery('#langids').val()!='')
                   {
                       jQuery('#langids').val(jQuery('#langids').val()+';'+item.id);
                   }
                   else
                   {
                      jQuery('#langids').val(item.id); 
                   }
            },
            onDelete: function (item) {
                   var v = jQuery('#langids').val();
                   var i = v.indexOf(item.id);
                   if(i == v.length-1)
                   {
                       jQuery('#langids').val(v.substring(0,i-1));
                   }
                   else
                   {
                      var j = i+1;
                      while(v[j]!=';') j++;
                       if(i==0)
                       {
                           jQuery('#langids').val(v.substring(j+1,v.length));
                       }
                       else
                       {
                           jQuery('#langids').val(v.substring(0,i)+v.substring(j+1,v.length));
                       }
                   }
            },
            <?php
                if($userlanguages)
                {
                    $langids = array();
                    $pdata = '';
                    foreach($userlanguages as $ul)
                    {
                        $pdata .= '{id: '.$ul["id"].', name: "'.$ul["name"].'"},';
                        $langids[] = $ul["id"];
                    }

                    echo 'prePopulate: ['.substr($pdata,0,strlen($pdata)-1).']';
                }
            ?>

        });
        cdiv = jQuery('#contactdiv').html();
        // images in select
        jQuery('#contactype').msDropDown();
    });

    function addContactDiv(elem)
    {
        var pe= jQuery(elem).parent();
        var tElem = elem;
        var randn = Math.floor(Math.random()*10000);
        jQuery(elem).prev().append(spane);
        jQuery(elem).prev().after('<div id="#contactdiv000">'+cdiv.replace('id="contactype"','id="contactype'+randn+'"')+'</div>');
        jQuery('#contactype'+randn).msDropDown();
        //jQuery.msDropDown.create('#contactype'+randn);
    }

    function isInteger(s) {
        return (s.toString().search(/^-?[0-9]+$/) == 0);
    }

    function saveAll(elem)
    {
        var username = jQuery("#username").val();
        var email = jQuery("#email").val();
        var pass = jQuery("#password").val();
        var passconf = jQuery("#password_confirm").val();
        var fullname = jQuery("#fullname").val();
        var bday = jQuery("#bday").val();
        var bmonth = jQuery("#bmonth").val();
        var byear = jQuery("#byear").val();
        var bio = jQuery("#bio").html();
        var sex = jQuery("#sex").val();
        var town = jQuery("#town").val();
        var townid = jQuery("#townid").val();
        var langids = jQuery("#langids").val();

        var contactVal = '';
        jQuery(":input[name='contact[]']").each(function () {
            contactVal += jQuery(this).val()+'=;=';
        });

        var contactType = '';
        jQuery(":input[name='contactype[]']").each(function () {
            contactType += jQuery(this).val()+';';
        });

        if(pass!='')
        {
            if(pass != passconf)
            {
                alert("<?php echo __('checkpassword'); ?>");
                return 0;
            }
        }

        if(isInteger(bday) && isInteger(bmonth) && isInteger(byear))
        {
            var surl = "username="+username+
                        "&email="+email+
                        "&pass="+pass+
                        "&fullname="+fullname+
                        "&birthday="+byear+"-"+bmonth+"-"+bday+
                        "&bio="+bio+
                        "&sex="+sex+
                        "&town="+town+
                        "&townid="+townid+
                        "&lang="+langids+
                        "&contact="+contactVal+
                        "&contactype="+contactType;
            var old = elem;
            jQuery(elem).replaceWith('<div class="button2" id="saveanim"><div align="center" class="animation clearfix"><div class="circle one"></div><div class="circle two"></div></div></div>');
            jQuery.ajax({type: "POST", url: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/profile_save'; ?>", data: surl, success: function(msg)
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
        else
        {
            alert("<?php echo __('wrongbday'); ?>");
            return 0;
        }
    }
</script>
<div id="profilediv">
    <h2 style="padding: 20px"><?php echo __('profile.edit'); ?></h2>
    <table width="100%" style="border-width: 0px; border-style:none;">
        <tr>
            <td width="70%" style="text-align: left; background-color: rgba(0,0,0,0.05);">
                    <table id="insidetable" align="center" style="border-width: 0px; border-style:none; margin: 20px;">
                        <tr>
                            <td width="180px" align="left">
                                <label for="username"><?php echo __('user.username'); ?></label>
                            </td>
                            <td style="padding-left: 10px" align="left">
                                <input type="text" id="username" name="username" value="<?php echo $username; ?>" placeholder="<?php echo __('input.login'); ?>" required="" tabindex="1">
                            </td>
                        </tr>
                        <tr>
                            <td width="180px" align="left">
                                <label for="email"><?php echo __('user.email'); ?></label>
                            </td>
                            <td style="padding-left: 10px" align="left">
                                <input type="text" id="email" name="email" value="<?php echo $email; ?>" placeholder="<?php echo __('input.email'); ?>" required="" tabindex="2">
                            </td>
                        </tr>
                        <tr>
                            <td width="180px" align="left">
                                <label for="password"><?php echo __('profile.newpassword'); ?></label>
                            </td>
                            <td style="padding-left: 10px" align="left">
                                <input type="password" id="password" name="password" placeholder="<?php echo __('input.password'); ?>" required="" tabindex="3">
                            </td>
                        </tr>
                        <tr>
                            <td width="180px" align="left">
                                <label for="password_confirm"><?php echo __('user.confirmpassword'); ?></label>
                            </td>
                            <td style="padding-left: 10px" align="left">
                                <input type="password" id="password_confirm" name="password_confirm" placeholder="<?php echo __('input.confirmpassword'); ?>" required="" tabindex="4">
                            </td>
                        </tr>
                    </table>
            </td>
            <td id="#imageUpTd" width="30%" style="background-image:url('../assets/images/back1.gif'); background-repeat: repeat;">
               <div style="width: 100%; text-align:center; padding-top: 35px;"><img id="avatar" src="<?php if($avatar_image) echo '../media/uimg/'.$avatar_image; else echo '../assets/images/no_avatar.gif'; ?>"/></div>
               <div id="imageUp" style="width: 100%; text-align: center; vertical-align: middle; height: 40px; padding-top: 10px;"><?php echo __('profile.drophereimage'); ?></div>
            </td>
        </tr>
        <tr style="padding-top: 30px;">
            <td  style="text-align: left; background-color: rgba(0,0,0,0.05);">
                <table id="insidetable" align="center" style="border-width: 0px; border-style:none; margin: 20px;">
                    <tr>
                        <td width="180px" align="left">
                            <label for="fullname"><?php echo __('profile.fullname'); ?></label>
                        </td>
                        <td style="padding-left: 10px" align="left">
                            <input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>" placeholder="<?php echo __('input.fullname'); ?>" required="" tabindex="5">
                        </td>
                    </tr>
                    <tr>
                        <td width="180px" align="left">
                            <label><?php echo __('profile.birthdate'); ?></label>
                        </td>
                        <td style="padding-left: 10px" align="left">
                            <input type="text" style="width: 50px;" value="<?php echo substr($birthday,8,2); ?>" id="bday" name="bday" placeholder="<?php echo __('input.day'); ?>" tabindex="6">
                            <input type="text" style="width: 70px;" value="<?php echo substr($birthday,5,2); ?>" id="bmonth" name="bmonth" placeholder="<?php echo __('input.month'); ?>" tabindex="7">
                            <input type="text" style="width: 50px;" value="<?php echo substr($birthday,0,4); ?>" id="byear" name="byear" placeholder="<?php echo __('input.year'); ?>" tabindex="8">
                        </td>
                    </tr>
                    <tr>
                        <td width="180px" align="left">
                            <label for="bio"><?php echo __('profile.bio'); ?></label>
                        </td>
                        <td style="padding-left: 10px" align="left">
                            <textarea id="bio" name="bio" placeholder="<?php echo __('input.bio'); ?>" tabindex="9"><?php echo $bio; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td width="180px" align="left">
                            <label for="sex"><?php echo __('profile.sex'); ?></label>
                        </td>
                        <td style="padding-left: 10px" align="left">
                            <select id="sex" name="sex" placeholder="<?php echo __('input.sex'); ?>" required="" tabindex="10">
                                <option <?php if($sex == 0) echo 'selected="selected"' ?> value="0"><?php echo __('profile.sex.undef'); ?></option>
                                <option <?php if($sex == 1) echo 'selected="selected"' ?> value="1"><?php echo __('profile.sex.male'); ?></option>
                                <option <?php if($sex == 2) echo 'selected="selected"' ?> value="2"><?php echo __('profile.sex.female'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="180px" align="left">
                            <label for="town"><?php echo __('profile.town'); ?></label>
                        </td>
                        <td style="padding-left: 10px" align="left">
                            <input type="text" value="<?php echo $town; ?>" id="town" name="town" placeholder="<?php echo __('input.letsinput'); ?>" tabindex="11">
                            <input type="hidden" id="townid" value="<?php echo $townid; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td width="180px" align="left">
                            <label for="languages"><?php echo __('profile.languages'); ?></label>
                        </td>
                        <td style="padding-left: 10px" align="left">
                            <input type="text" id="languages" name="languages" placeholder="<?php echo __('input.letsinput'); ?>" tabindex="12">
                            <input type="hidden" id="langids" value="<?php if(isset($langids)) echo implode(';',$langids); ?>" />
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: right; vertical-align: bottom;">
                
            </td>
        </tr>
        <tr style="padding-top: 30px;">
            <td  style="text-align: left; background-color: rgba(0,0,0,0.05);">
                <table id="insidetable" align="center" style="border-width: 0px; border-style:none; margin: 20px;">
                    <tr>
                        <td width="180px" align="left">
                            <label><?php echo __('profile.contacts'); ?></label>
                        </td>
                    </tr>
                    <tr">
                        <td style="padding-top: 20px;" align="left">

                            <?php
                                if($usercontacts)
                                {
                                    foreach($usercontacts as $uc)
                                    {
                                        $tempID = rand(12345,99999);
                                        $tempvar = '<div id="contactdiv'.$tempID.'"><select id="contactype'.$tempID.'" name="contactype[]">';
                                        foreach($contactypes as $ct)
                                        {
                                            if($ct["id"] == $uc["conttypeid"])
                                            {
                                                $tempvar .=  '<option selected="selected" title="../assets/images/'.$ct["ico_image"].'" value="'.$ct["id"].'">'.$ct["name"].'</option>';
                                            }
                                            else
                                            {
                                                $tempvar .=  '<option title="../assets/images/'.$ct["ico_image"].'" value="'.$ct["id"].'">'.$ct["name"].'</option>';
                                            }
                                        }

                                        $tempvar .= '</select><input style="padding: 3px;" type="text" value="'.$uc["value"].'" id="contact" name="contact[]" placeholder="Введіть дані...">
                                            <span onclick="jQuery(this).parent().remove()" style="cursor: pointer; vertical-align:baseline; padding:10px; background-color: #ffc11e; -webkit-border-radius: 5px;">'.__('del').'</span></div>';
                                        echo $tempvar;
                                        echo '<script>jQuery(document).ready(function(){jQuery("#contactype'.$tempID.'").msDropDown();});</script>';
                                    }
                                }
                            ?>
                            <div id="contactdiv"><select id="contactype" name="contactype[]">
                                <?php
                                    foreach($contactypes as $ct)
                                    {
                                        echo '<option title="../assets/images/'.$ct["ico_image"].'" value="'.$ct["id"].'">'.$ct["name"].'</option>';
                                    }
                                ?>
                                </select>
                                <input style="padding: 3px;" type="text" id="contact" name="contact[]" placeholder="<?php echo __('input.data'); ?>">
                            </div>
                            <a style="clear: both;" href="javascript:void(0)" onclick="addContactDiv(this);"><?php echo __('profile.addcontact'); ?></a>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: right; vertical-align: bottom;">
                <a href="javascript:void(0)" onclick="saveAll(this);" class="button2"><?php echo __('Save'); ?></a>
            </td>
        </tr>
    </table>
</div>