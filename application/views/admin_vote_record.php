<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="application/views/css/jquery.datetimepicker.css" />
<div class="row">
    <div class="col-xs-12">
        <form class="admin-query" id="vote_record_form">
            <label class="control-label" for="vote_id">活动</label>
            <select name="vote_id" id="vote_id">
                <?php foreach ($votes as $n) :?>
                <option value="<?=$n['id']?>" <?php if($n['id'] == $vote_id) {echo "selected";}?> ><?=$n['vote_name']?></option>
                <?php endforeach;?>
            </select>
            <label class="control-label">
                从
            </label>
            <input type="text" name="start_time" id="start_time" data-toggle="time" value="<?=$start_time?>"/>
            <label>到</label>
            <input type="text" name="end_time" id="end_time" data-toggle="time" value="<?=$end_time?>"/>

            <label for="keywords" class="control-label">关键字</label>
            <input type="text" name="keywords" id="keywords" value="<?=$keywords?>" placeholder="输入选手名称,ip进行查询"/>
            <input type="hidden" name="page" id="page" value="<?=$page?>" />
            <input type="button" value="查询" onclick="submit_query_form()">
        </form>
    </div>
    <div class="col-xs-12">
        <table width="100%" border="1px" class="query-result">
            <thead>
            <tr>
                <td><input type="checkbox" value=""></td>
                <td>选手</td>
                <td>投票人</td>
                <td>投票时间</td>
                <td>来源</td>
                <td>IP</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
            <?php if ($record):?>
            <?php foreach ($record as $item):?>
            <tr>
                <td>
                    <input type="checkbox" value="<?=$item['id']?>" />
                </td>
                <td><?=$item['candi_name']?></td>
                <td><?=$item['user_name']?></td>
                <td><?php echo date('Y-m-d H:i:s',$item['vote_time']);?></td>
                <td><?php if ($item['source'] == 0) {echo "网页";} elseif ($item['source'] == 1) { echo "分享";} elseif ($item['source']==2) {echo "公众号";}?></td>
                <td><?=$item['ip']?></td>
                <td><a href="javascript:;">删除</a></td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
        </tbody>
        </table>
        <div class="text-center"><?=$links?></div>
    </div>
</div>
<script>
    function ajax_page(start) {
        $("#page").val(start);
        submit_query_form();
    }

    function submit_query_form() {
        $("*[data-toggle=time]").datetimepicker('destroy');
        var page = $("#page").val();
        $("#vote_record_form").ajaxSubmit({
            url:"index.php/AdminVoteController/viewVoteRecord/"+page,
            dataType:"text",
            type:"get",
            success:function(data) {
                $("#sub_content").html(data);

                $("*[data-toggle=time]").datetimepicker({
                    lang: "ch",           //语言选择中文
                    format: "Y-m-d H:i",      //格式化日期
                    timepicker: true,    //关闭时间选项
                    yearStart: 2000,     //设置最小年份
                    yearEnd: 2050,        //设置最大年份
                    todayButton: false    //关闭选择今天按钮
                });
            }
        });
    }
</script>
<script src="application/views/js/jquery.datetimepicker.js"/>

