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
            <tr>
                <td colspan="8">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><i class="fa fa-angle-double-up"></i> 公众号：</a>
                </td>
            </tr>
            <tbody id="collapseOne" class="panel-collapse collapse in">
            <?php if ($list):?>
                <?php foreach($list as $item):?>
                    <tr >
                        <td><input type="checkbox" value="<?=$item['id']?>"></td>
                        <td><a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$item['id']?>"><?=$item['vote_name']?></a></td>
                        <td><?=$item['signup_start_time']?> ~ <?=$item['signup_end_time']?></td>
                        <td><?=$item['vote_start_time']?> ~ <?=$item['vote_end_time']?></td>
                        <td><?php if($item['status'] == 0):?>开启<?php else:?>关闭<?php endif;?></td>
                        <td><?=$item['candi_count']?></td>
                        <td><?=$item['vote_count']?></td>
                        <td><a href="index.php/AdminVoteController/edit?id=<?=$item['id']?>">配置</a>
                            <a href="index.php/AdminVoteController/delete?id=<?=$item['id']?>">解除绑定</a></td>
                    </tr>
                    <tr id="collapse<?=$item['id']?>" class="panel-collapse collapse">
                        <td colspan="8">
                            <div class="panel-body">
                                <a href="index.php/VoteController/index?vote_id=<?=$item['id']?>" target="_blank">查看活动首页</a>
                                <a data-src="index.php/AdminCandidateController/index?vote_id=<?=$item['id']?>" data-id="<?=$item['id']?>" target="#sub_content" data-toggle="collapse" href="#sub_content">管理报名选手</a>
                                <a data-src="index.php/AdminVoteController/viewVoteRecord?vote_id=<?=$item['id']?>" data-id="<?=$item['id']?>" target="#sub_content" data-toggle="collapse" href="#sub_content">查看投票记录</a>
                                <a href="#">奖品设定</a>
                                <a href="#">获奖人员维护</a>
                                <?php foreach($property_groups as $g):?>
                                    <a href="#sub_content" data-toggle="collapse" class="config" data-content="<?=$g['property_group']?>"
                                       data-id="<?=$item['id']?>"><?=$g['property_group']?></a>
                                <?php endforeach;?>
                            </div>
                            <div class="panel-collapse collapse" id="sub_content">

                            </div>
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php endif;?>
            </tbody>
        </table>
    </div>
</div>
