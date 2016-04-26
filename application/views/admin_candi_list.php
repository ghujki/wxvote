<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .add-candi {
        line-height: 3em;
        height: 3em;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <a class="float-left add-candi" href="index.php/AdminCandidateController/detail/0?vote_id=<?=$vote['id']?>">增加报名选手</a>
        <form class="admin-query">
            <input type="text" placeholder="查询条件" name="query">
            <input type="submit" value="查询">
        </form>
    </div>

    <div class="col-xs-12">
        <table border="1px" class="query-result" width="100%">
            <thead>
            <tr>
                <td width="10%">编号</td>
                <td width="10%">姓名</td>
                <td width="20%">报名时间</td>
                <td width="5">得票数</td>
                <td width="5">排名</td>
                <td width="10%">状态</td>
                <td width="30%">操作</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($candidates as $candi) :?>
            <tr>
                <td><?=$candi['id']?></td>
                <td><?=$candi['name']?></td>
                <td><?=$candi['enroll_time']?></td>
                <td class="vote_count"><?=$candi['vote_count']?></td>
                <td><?=$candi['rank']?></td>
                <td><?php if($candi['status'] == 0) :?>正常<?php else :?>冻结<?php endif;?></td>
                <td>
                    <a href="index.php/AdminCandidateController/detail/<?=$candi['id']?>">详情</a>
                    <a href="javascript:;" onclick="changePriority('<?=$candi['id']?>',this)">增减票数</a>
                    <a href="">冻结/解冻</a>
                    <a href="">联系</a>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <?=$links?>
    </div>
</div>

<script>
    function changePriority(id,obj) {
        var target = $(obj).parents("tr").children(".vote_count");
        var old_value = $(target).text();
        var str = "<input type='text' value='" + old_value + "' />";
        $(target).empty().append($(str).change(function(){
            var value = $(this).val();
            $.ajax({
                url:'index.php/AdminCandidateController/ajaxUpdatePriority',
                dataType:'json',
                data:{id:id,value: (value - parseInt(old_value))},
                success:function(data) {
                    if (data == 'ok') {
                        $(target).text(value);
                    }
                }
            });
        }));

    }
</script>