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
                <td>id</td>
                <td>命令</td>
                <td width="10%">操作</td>
            </tr>
            </thead>
            <tbody>

            <?php if ($jobs) {?>
        <?php foreach ($jobs as $job) {?>
        <tr><td><?=$job['id']?></td><td><?=$job['command']?></td><td><a href="javascript:;" onclick="del_job('<?=$job['id']?>')">删除</a></td></tr>
        <?php }}?>
            </tbody>
        </table>
    </div>
</div>
