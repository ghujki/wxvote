<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <style>
        .thead {
            background-color:#8c8c8c;
            font-weight:bold;
            padding-top:.5em;
        }
        .tbody {
            padding: .5em 0;
            border:1px solid #ccc;
        }
        .panel-body {
            border-left:1px solid #ccc;
            border-right:1px solid #ccc;
            border-bottom:1px solid #ccc;
        }
        .panel-body>a {
            margin-right:10px;
        }
        #accordion {
            font-size:small;
        }
        .wxuser-content {
            margin-bottom:1em ;
        }
        .wxuser-content .wxuser_item {
            width:100px;
            float:left;
            margin-right:10px;
            font-size:smaller;
            word-break: break-all;
        }
        .wxuser-content .wxuser_item img {
            width:100px;
        }
        .wxuser-content .wxuser_item.checked {
            border-color:green;
        }
    </style>
    <div class="row">
        <div class="col-xs-12">
            <form class="admin-query">
                <input type="text" placeholder="<?=$query?>" name="query" >
                <input type="submit" value="查询">
            </form>
        </div>
        <div class="col-xs-12" id="accordion">
            <div class="row thead">
                <div class="col-xs-3"><label>app_id</label></div>
                <div class="col-xs-3"><label>公众号名称</label></div>
                <div class="col-xs-3"><label>关注人数</label></div>
                <div class="col-xs-3"><label>操作</label></div>
            </div>
            <?php if ($numbers):?>
            <?php foreach($numbers as $item):?>
            <div class="row tbody">
                <div class="col-xs-3"><a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$item['id']?>" ><?=$item['app_id']?></a></div>
                <div class="col-xs-3"><?=$item['app_name']?></div>
                <div class="col-xs-3"><span><?=$item['member_count']?></span><a href="javascript:;" onclick="syncMembers(<?=$item['id']?>,this)"> 同步 </div>
                <div class="col-xs-3"><a href="index.php/AdminOfficialNumber/edit?id=<?=$item['id']?>">配置</a>&nbsp;
                    <a href="index.php/AdminOfficialNumber/remove/<?=$item['id']?>">解除绑定</a>
                </div>
            </div>
            <div id="collapse<?=$item['id']?>" class="row panel-collapse collapse">
                <div class="panel-body">
                    <a href="#subContent<?=$item['id']?>" data-toggle="collapse" data-src="index.php/AdminOfficialNumber/ajaxKeywordsPage" target="#subContent<?=$item['id']?>" data-id="<?=$item['id']?>">自动回复设置</a>
                    <a href="#subContent<?=$item['id']?>" data-toggle="collapse" data-src="index.php/AdminOfficialNumber/ajaxMenuPage" target="#subContent<?=$item['id']?>" data-id="<?=$item['id']?>">菜单设置</a>
                    <a href="#subContent<?=$item['id']?>" data-toggle="collapse" data-src="index.php/AdminOfficialNumber/ajaxShowUsers" target="#subContent<?=$item['id']?>" data-id="<?=$item['id']?>">查看粉丝</a>
                    <a href="#subContent<?=$item['id']?>" data-toggle="collapse" data-src="index.php/AdminOfficialNumber/ajaxEventPage" target="#subContent<?=$item['id']?>" data-id="<?=$item['id']?>">事件配置</a>
                    <hr/>
                    <div id="subContent<?=$item['id']?>" class="panel-collapse"></div>
                </div>
            </div>
            <?php endforeach;?>
            <?php else:?>
                <div>还没有添加公众号记录，点击<a href="index.php/AdminOfficialNumber/add">这里</a>添加</div>
            <?php endif;?>
        </div>
    </div>

