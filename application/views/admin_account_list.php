<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-12">
        <form class="admin-query">
            <input type="text" placeholder="查询条件" name="keywords" value="<?=$keywords?>">
            <input type="hidden" id="page" value="<?=$page?>" />
            <input type="submit" value="查询">
        </form>
    </div>

    <div class="col-xs-12">
        <table width="100%" border="1px" class="query-result">
            <thead>
            <tr>
                <td>id</td>
                <td>用户名</td>
                <td>最后登录时间</td>
                <td>最后登录IP</td>
                <td width="30%">操作</td>
            </tr>
            </thead>
            <tbody>

            <?php if ($accounts) {?>
                <?php foreach ($accounts as $account) {?>
                    <tr>
                        <td><?=$account['id']?></td>
                        <td><?=$account['username']?></td>
                        <td><?=date('Y-m-d H:i:s',$account['lastlogin'])?></td>
                        <td><?=$account['lastIp']?></td>
                        <td>
                            <a href="javascript:;" onclick="remove_account('<?=$account['id']?>')">删除</a>
                            <a href="index.php/AdminAccountController/editAccount?id=<?=$account['id']?>">修改密码</a>
                            <a href="javascript:;" onclick="edit_privilege('<?=$account['id']?>')" data-toggle="modal" data-target="#privilege_panel">分配权限</a>
                        </td>
                    </tr>
                <?php }}?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="privilege_panel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    权限分配
                </h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="submit_form()">
                    提交
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<script>
    function remove_account($id) {
        if ($id == '1') {
            alert("管理员账号不能删除");
        }
    }

    function edit_privilege ($id) {
        $.ajax({
            url:"index.php/AdminAccountController/getPrivilege",
            dataType:"text",
            data:{id:$id},
            success:function (data) {
                $(".modal-body").html(data);
            }
        });
    }

    function submit_form() {
        $("#form1").ajaxSubmit({
            success:function() {
                $("#privilege_panel").modal("hide");
            }
        });
    }
</script>
