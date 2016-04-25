<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .m-head {
        margin-top:20px;
        border-bottom:1px solid #ccc;
        padding:1em;
    }
    .grid {
        margin-top:10px;
    }
    .grid-item { width: 200px;margin-bottom:10px; border:1px solid #ccc;padding:1em;}
    .grid-item figcaption {
        padding:.2em;
        font-size:small;
    }

</style>
<div class="row">
    <div class="col-xs-12">
        <form class="admin-query">
            <input type="text" placeholder="查询条件" name="query">
            <input type="submit" value="查询">
        </form>
    </div>
    <div class="col-xs-12">
        <div class="m-head">
            <select id="number_id" class="btn">
                <?php foreach ($numbers as $number) : ?>
                <option value="<?=$number['id']?>"><?=$number['app_name']?></option>
                <?php endforeach;?>
            </select>
            <button class="btn btn-default" onclick="syncNewsMessages()">同步图文消息</button>
            <button class="btn btn-default" onclick="addNewsMessages()">新建图文消息</button>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="grid">
            
            <figure class="grid-item">
                <img src="application/views/images/214833-120S11GJ543.jpg" class="img-responsive">
                <figcaption>测试一些数据</figcaption>
            </figure>

        </div>
    </div>
</div>