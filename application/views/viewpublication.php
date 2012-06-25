<?php defined('SYSPATH') or die('No direct access allowed.');?>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function()
    {
        jQuery('#ratingdiv').opineo('<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/publication_rate/'.$id; ?>', {curvalue:0, view: 'mini', maxvalue:5, callback: opineoCallback});
    });

    function opineoCallback(responseData)
    {
        if(responseData != '' && responseData != undefined)
        {
            jQuery('#rateplease').html(responseData);
        }
    }
    function delaction(link)
    {
        if(confirm("<?php echo __('realydelete');?>"))
        {
            location.href = link;
        }
    }

    function publish(id)
    {
        if(id!=undefined)
        {
            var surl = "id="+id;
            jQuery.ajax({type: "POST", url: "<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/api/publication_publish'; ?>", data: surl, success: function(msg)
            {
                if(msg=="OK") location.reload(true);
            }});
        }
    }
</script>
<div id="profilediv">
    <span style="color:#000000;"><?php echo $authors ?></span>
    <span style="padding: 5px; float: right;">R:<b style="color: blue; font-size: 25px"><?php echo $rating; ?></b></span>
    <h2 style="padding: 20px; text-align: right"><?php echo $title; ?></h2>
    <h5 style="text-align: right"><?php echo $galuzname.'.'.$predmetname; ?></h5>
    <br/>
    <?php if($published == 0)
        {
            echo '<h5 style="margin-bottom: 24px; text-align:right; color: red; font-size: 20px;"><img src="../../assets/images/warningM.png" />'.__('notpublished').'</h5>';
        }
    ?>
    <?php if($ismine){ ?>
    <div id="profileMenu" style="text-align: right; background-color: #FFC11E;">
        <?php if($published == 0)
        {
            echo '<a class="button2" href="javascript:void(0)" onclick="publish(\''.$id.'\')">'.__('publish').'</a>';
        }
        ?>
        <a class="button2" href="/publication/edit/<?php echo $id; ?>"><?php echo __('publication.edit'); ?></a>
        <a class="button2" href="javascript:void(0)" onclick="delaction('/publication/delete/<?php echo $id; ?>')"><?php echo __('publication.delete'); ?></a>
    </div>
    <?php } ?>
    <table width="100%" style="margin-top: 30px; border-width: 0px; border-style:none;">
        <tr>
            <td colspan="2" style="padding: 10px;">
                <?php echo $ptext; ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php if(!$ismine) { ?>
                    <br/><br/><span id="rateplease"><?php echo __('ratethisplease'); ?></span><div id="ratingdiv"></div>
                <?php } ?>
            </td>
            <td>
                <br/><br/><?php echo __('sharethis'); ?><br/><br/><div class="share42init"></div>
                <script type="text/javascript" src="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/assets/script/'; ?>share42.js"></script>
                <script type="text/javascript">share42('<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/assets/script/'; ?>')</script>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 30px;">
                <div style="margin: 0 auto; width:500px">
                    <script>
                        var idcomments_acct = '7f0d9179bbfc0ec2a66056da786fc5f4';
                        var idcomments_post_id = <?php echo $id; ?>;
                        var idcomments_post_url;
                    </script>
                    <span id="IDCommentsPostTitle" style="display:none"></span>
                    <script type='text/javascript' src='http://www.intensedebate.com/js/genericCommentWrapperV2.js'></script>
                </div>
            </td>
        </tr>
    </table>
</div>