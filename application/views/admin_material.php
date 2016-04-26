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
    .grid-item figure {
        padding-bottom:10px;
    }
    .grid-item figcaption {
        padding: .2em;
        font-size: small;
        margin-top: -20px;
        background-color: black;
        width: 100%;
        color: #fff;
        opacity: .8;
    }
    .item-row {border-top:1px solid #ccc;padding:.5em;font-size:small}
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
            <div class="grid-item">
            <?php foreach ($materials as $m) :?>
                <?php $cover = array_shift($m);?>
                <figure>
                    <img src="<?=$cover['picurl']?>" class="img-responsive">
                    <figcaption><a href="<?=$cover['url']?>" target="_blank"><?=$cover['title']?></a></figcaption>
                </figure>
                <?php foreach ($m as $item): ?>
                    <div class="row item-row">
                        <div class="col-xs-8"><a href="<?=$item['url']?>" target="_blank"><?=$item['title']?></a></div>
                        <div class="col-xs-4"><img src="<?=$item['picurl']?>" class="img-responsive"></div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
