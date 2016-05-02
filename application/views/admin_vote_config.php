<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/5/2
 * Time: 21:09
 */
?>
<?php echo form_open_multipart("AdminVoteController/saveConfig",array("class"=>"form-horizontal","id"=>"config-form"))?>
    <?php foreach ($properties as $p):?>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="<?=$p['property_code']?>"><?=$p['property_name']?></label>
        <div class="col-sm-10 col-md-9">
        <?php if ($p['value_type'] == 0 || $p['value_type'] == 1) {?>
        <input type="text" name="<?=$p['property_code']?>" id="<?=$p['property_code']?>"
            class="form-control"   value="<?=$p['default_value']?>" />
        <?php } elseif ($p['value_type'] == 2) {?>
             <?php if ($p['default_value']) :?>
                <img src="<?=$p['default_value']?>" class="img-responsive">
             <?php endif;?>
            <input type="file" name="<?=$p['property_code']?>" id="<?=$p['property_code']?>" class="form-control" />
        <?php } elseif ($p['value_type'] == 3) {?>
            <input type="text" name="<?=$p['property_code']?>" id="<?=$p['property_code']?>"
                   class="form-control" data-toggle="time"  value="<?php echo date('Y-m-d H:i:s',$p['default_value']);?>" />
        <?php }?>
        </div>
    </div>
    <?php endforeach;?>
    <div class="form-group">
        <div class="col-xs-0 col-md-2"> </div>
        <div class="col-xs-12 col-md-10">
            <input type="hidden" name="vote_id" value="<?=$vote_id?>" />
            <input type="hidden" name="group" value="<?=$group?>"/>
            <input type="button" class="btn btn-default" onclick="submitForm()" value="确认" />
        </div>
    </div>
</form>
<script>
    function submitForm() {
        $("#config-form").ajaxSubmit();
        $("#sub_content").collapse("hide");
    }
</script>