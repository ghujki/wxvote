<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <style>
        .thead {
            background-color:rgba(46, 60, 72, 0.8);             /* 修改的地方 */
            font-weight:bold;       
            padding-top:.5em;
            margin-top: -1px;
            color: #fff;
        }
        .tbody {
            padding: .5em 0;
            border-bottom:1px solid #000;
            color: #000;
        }
        .panel-body {
            border-top:1px solid #000;      /* 修改的地方 */
            border-left:1px solid #000;
            border-right:1px solid #000;
            border-bottom:1px solid #000;
            text-align: center;
        }
        /* .panel-body>a {
            margin-right:10px;
        } */
        .panel-body>a {
          margin-right: 10px;
          display: block;
          float: left;
          height: 30px;                      /* 修改的地方 */
          width: 88px;
          line-height: 30px;
          background-color: rgba(105,70,65,0.7);
          -webkit-border-radius:10px;
          -moz-border-radius:10px;
          border-radius:10px; 
          color: #fff;
        }
        .panel-body>a:hover{
            background-color: rgba(105,70,65,0.9);
            text-decoration: none;             /* 修改的地方 */
        }
        #accordion {
            font-size:small;
        }
        hr {
          margin-top: 46px;
          margin-bottom: 20px;
          border: 0;                            /* 修改的地方 */
          border-top: 1px solid #000;
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
        .wxuser-list{
            margin-top: 20px;       
        }
        #keywords{
            -webkit-border-radius:10px;
            -moz-border-radius:10px;         /* 修改的地方 */
            border-radius:10px; 
            border: none;
        }
    </style>
    <div class="row">
        <div class="col-xs-12">
            <form class="admin-query">
                <input type="text" placeholder="<?=$query?>" name="query" >
                <input type="submit" value="查询">
            </form>
        </div>
        <div class="col-xs-12 main" id="accordion">
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
                <div class="col-xs-3"><span><?=$item['member_count']?></span>
                    <a class="fa fa-refresh <?php if($item['sync_disabled']) { echo ' disabled';} if ($item['sync_error']) {echo ' sync_error';} ?>" href="javascript:;" <?php if (!$item['sync_disabled']) {?>onclick="syncMembers(<?=$item['id']?>,this)" <?php }?> > 同步</a>
                    <a class="fa fa-eye" href="javascript:;" onclick="view_sync('<?=$item['id']?>')">查看同步结果</a>
                </div>
                <div class="col-xs-3">
                    <a class="fa fa-cog" href="index.php/AdminOfficialNumber/edit?id=<?=$item['id']?>">配置</a>&nbsp;
                    <a class="fa fa-unlock" href="index.php/AdminOfficialNumber/remove/<?=$item['id']?>">解除绑定</a>
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

