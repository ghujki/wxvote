<?php
?>
<div>
    <p>选择回复素材</p>
    <div class="margin-top-10">
        <?=$links?> <button class="btn btn-default" onclick="respNews()">确定</button>
    </div>
    <div class="grid clearfix">
        <?php foreach ($materials as $m) :?>
            <div class="grid-item" data-id="<?=$m[0]['media_id']?>">
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
            </div>
        <?php endforeach;?>
    </div>
</div>
