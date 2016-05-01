<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<section class="row light-green photo-box">
    <div class="column col-xs-12">
        <div>
            <span><?=$candi['id']?></span>号 <span><?=$candi['name']?></span> <span><?=$count?>票</span> ,<span>排名第<strong><?=$rank?></strong>名</span>
        </div>
        <div>
            <?=$candi['desc']?>
        </div>
    </div>
    <div class="column col-xs-12">
        <a href="javascript:;" onclick="voteFor(<?=$candi['id']?>)">为他/她投票</a>
    </div>
    <?php foreach($candi['gallery'] as $gallery):?>
    <figure class="col-xs-12 col-md-6 album-item">
        <img src="<?=$gallery['pic']?>" class="img-responsive">
        <figcaption><?=$gallery['desc']?></figcaption>
    </figure>
    <?php endforeach;?>
</section>
