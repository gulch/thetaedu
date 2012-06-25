<?php defined('SYSPATH') or die('No direct access allowed.');?>
<script type="text/javascript">
   function dosearch()
   {
       var searchtype = jQuery('#searchtype').val();
       var searchtext = jQuery('#searchtext').val();
       if(searchtype!='' && searchtext!='')
       {
           if(searchtype==1) location.href = '<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/search?sp='; ?>'+searchtext;
           if(searchtype==2) location.href = '<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/search?su='; ?>'+searchtext;
       }
   }
</script>
<div id="profilediv">
    <div style="margin: 0 auto;">
        <input style="width: 68%"  type="text" id="searchtext" name="searchtext" value="<?php echo $searchtext; ?>" placeholder="<?php echo __('input.searchtext'); ?>" required="" tabindex="1">
        <select id="searchtype" name="searchtype" required="" tabindex="2">
            <option <?php if($searchtype == 1) echo 'selected="selected"' ?> value="1"><?php echo __('search.publications'); ?></option>
            <option <?php if($searchtype == 2) echo 'selected="selected"' ?> value="2"><?php echo __('search.users'); ?></option>
        </select>
        <input type="button" class="buttonInp" name="dosearch" onclick="dosearch()" value="<?php echo __('search.dosearch'); ?>" />
    </div>
    <br/><br/>
    <?php if(!empty($publication_list)) echo $publication_list;
           else
           {
               if(!empty($users_list)) echo $users_list;
               else
               {
                   echo __('nosearchresults');
               }
           }
    ?>
</div>