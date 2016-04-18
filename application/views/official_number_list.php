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
                        <td><input type="checkbox" value="">check all</td>
                        <td>app_id</td>
                        <td>公众号名称</td>
                        <td>操作</td>
                        </tr>
                    </thead>
                    <?php if ($numbers):?>
                    <?php foreach($numbers as $item):?>
                    <tr>
                        <td><input type="checkbox" value="<?=$item['id']?>"></td>
                        <td><?=$item['app_id']?></td>
                        <td><?=$item['app_name']?></td>
                        <td><a href="index.php/AdminOfficialNumber/edit?id=<?=$item['id']?>">配置</a><a href="">解除绑定</a></td>
                    </tr>
                    <?php endforeach;?>
                    <?php else:?>
                        <tr>
                            <td colspan="4">还没有添加公众号记录，点击<a href="index.php/AdminOfficialNumber/add">这里</a>添加</td>
                        </tr>
                    <?php endif;?>
            </table>
        </div>
    </div>

