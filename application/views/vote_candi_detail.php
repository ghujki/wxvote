<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<section class="row light-green photo-box">
    <div class="column col-xs-12">
        <div>
            <span><?=$candi['id']?></span>号 <span><?=$candi['name']?></span> <span> 票</span> ,<span>排名第<strong><?=$rank?></strong>名</span>
        </div>
        <div>
            <?=$candi['desc']?>
        </div>
    </div>
    <?php foreach($candi['gallery'] as $gallery):?>
    <figure class="col-xs-12 col-md-6">
        <img src="<?=$gallery['pic']?>" class="img-responsive">
        <figcaption><?=$gallery['desc']?></figcaption>
    </figure>
    <?php endforeach;?>
</section>
