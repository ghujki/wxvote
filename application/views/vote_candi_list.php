<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<section class="row light-green photo-box">
    <?php foreach($list as $item):?>
        <div class="column col-xs-6 photo-item col-sm-4 col-lg-3">
            <div class="user-num">
                <?=$item['id']?>
            </div>
            <table>
                <tr>
                    <td colspan="2"><a href="index.php/VoteController/view?vote_id=<?=$item['vote_id']?>&candi_id=<?=$item['id']?>">
                            <img src="<?=$item['pic']?>" class="img-responsive" /></a></td>
                </tr>
                <tr>
                    <td width="75%"class="green">
                        <div ><?=$item['name']?></div>
                        <div >
                            <span><?=$item['vote_count']?>票</span>
                            <span>第<?=$item['rank']?>名</span>
                        </div>
                    </td>
                    <td class="pink text-center">
                        <a href="javascript:;" onclick="voteFor(<?=$item['id']?>)">投票</a>
                    </td>
                </tr>
            </table>
        </div>
    <?php endforeach;?>
</section>

<!-- pagination-->
<section class="row light-green text-center">
    <div class="column col-xs-12 link-box">
        <?=$links?>
    </div>
</section>
