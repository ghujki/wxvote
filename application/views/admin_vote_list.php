<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-12">
        <form class="admin-query">
            <input type="text" placeholder="查询条件" name="query">
            <input type="submit" value="查询">
        </form>
    </div>
    <div class="col-xs-12">
        <table width="100%" border="1px" class="query-result">
            <thead>
            <tr>
                <td><input type="checkbox" value=""></td>
                <td>投票活动名称</td>
                <td>报名时间段</td>
                <td>投票时间段</td>
                <td>状态</td>
                <td>报名人数</td>
                <td>投票次数</td>
                <td>操作</td>
            </tr>
            </thead>
            <?php if ($list):?>
                <?php foreach($list as $item):?>
                    <tr>
                        <td><input type="checkbox" value="<?=$item['id']?>"></td>
                        <td><?=$item['vote_name']?></td>
                        <td><?=$item['signup_start_time']?> ~ <?=$item['signup_end_time']?></td>
                        <td><?=$item['vote_start_time']?> ~ <?=$item['vote_end_time']?></td>
                        <td><a href="index.php/AdminOfficialNumber/edit?id=<?=$item['id']?>">配置</a><a href="">解除绑定</a></td>
                    </tr>
                <?php endforeach;?>
            <?php else:?>
                <tr>
                    <td colspan="8">还没有投票活动记录，点击<a href="index.php/AdminVoteController/add">这里</a>添加</td>
                </tr>
            <?php endif;?>
        </table>
    </div>
</div>
