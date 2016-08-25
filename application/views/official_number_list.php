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
                    <a class="fa fa-refresh <?php if($item['sync_disabled']) { echo ' disabled';} if ($item['sync_error']) {echo ' sync_error';} ?>" href="javascript:;" <?php if (!$item['sync_disabled'])
                        {?>onclick="show_sync_user_panel(<?=$item['id']?>,this)" <?php }?> > 同步</a>
                    <a class="fa fa-eye" href="javascript:;" onclick="view_sync('<?=$item['id']?>')">查看同步结果</a>
                </div>
                <div class="col-xs-3">
                    <a class="fa fa-cog" href="index.php/AdminOfficialNumber/edit?id=<?=$item['id']?>">配置</a>&nbsp;
                    <a class="fa fa-unlock" href="index.php/AdminOfficialNumber/remove/<?=$item['id']?>">解除绑定</a>
                </div>
            </div>
            <div id="collapse<?=$item['id']?>" class="row panel-collapse collapse">

                <div class="panel-body" role="tablist">
                    <a href="javascript:;" onclick='showPage("tab1<?=$item['id']?>","index.php/AdminOfficialNumber/ajaxKeywordsPage?id=<?=$item['id']?>")'>自动回复设置</a>
                    <a href="javascript:;" onclick='showPage("tab2<?=$item['id']?>","index.php/AdminOfficialNumber/ajaxMenuPage?id=<?=$item['id']?>")'>菜单设置</a>
                    <a href="javascript:;" onclick='showPage("tab3<?=$item['id']?>","index.php/AdminOfficialNumber/ajaxShowUsers?id=<?=$item['id']?>")'>查看粉丝</a>
                    <a href="javascript:;" onclick='showPage("tab4<?=$item['id']?>","index.php/AdminOfficialNumber/ajaxEventPage?id=<?=$item['id']?>")'>事件配置</a>
                </div>

                <!-- Tab panes -->
                <div class="tab-content panel-body">
                    <div class="tab-pane active" id="tab1<?=$item['id']?>"></div>
                    <div class="tab-pane" id="tab2<?=$item['id']?>"></div>
                    <div class="tab-pane" id="tab3<?=$item['id']?>"></div>
                    <div class="tab-pane" id="tab4<?=$item['id']?>"></div>
                </div>

            </div>
            <?php endforeach;?>
            <?php else:?>
                <div>还没有添加公众号记录，点击<a href="index.php/AdminOfficialNumber/add">这里</a>添加</div>
            <?php endif;?>
        </div>
    </div>

<div class="modal fade" id="syncUserModel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" >
                    同步选项
                </h4>
            </div>
            <div class="modal-body">
                <span>从</span><input type="number" id="number" value="0"><span>开始同步</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="sync_user()">
                    提交
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<script>
    var number_id,target;
    function show_sync_user_panel(id,obj) {
        $("#syncUserModel").modal();
        number_id = id;
        target = obj;
    }

    function sync_user() {
        var num = $("#number").val();
        syncMembers(number_id,num,target);
        $("#syncUserModel").modal("hide");
    }

    function showPage(tabId, url){
        $('#'+tabId).siblings().hide();
        $('#'+tabId).html('<br> 页面加载中，请稍后...'); // 设置页面加载时的loading图片
        $('#'+tabId).load(url); // ajax加载页面
        $('#'+tabId).show();
    }
</script>
