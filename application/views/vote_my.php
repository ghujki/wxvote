<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<section class="row light-green photo-box">
    <?php if ($candi):?>
        <div class="col-xs-12">
            <div>
                <h2>我的参赛信息</h2>
                <div>
                    <p>姓名：<?=$candi['name']?> 得票数：<?=$candi['count']?> 排名：<?=$candi['rank']?>
                    <?php if ($candi['rank'] == 1) {?>恭喜您位列第一！<?php } else {?>与上一名差<?=$candi['distance']?>票 <?php }?>
                    </p>
                    电话：<?=$candi['phone']?>
                    住址：<?=$candi['address']?>
                    描述：<?=$candi['desc']?>
                </div>
                <h3>我的相册</h3>
                <div class="container-fluid">
                    <div class="row">
                    <?php foreach($gallery as $pic):?>
                        <div class="col-xs-6 col-md-4">
                                <img src="<?=$pic['pic']?>" class="img-responsive"/>
                        </div>
                     <?php endforeach;?>
                    </div>

                </div>
            </div>
            <h3>谁给我投了票</h3>
            <ul>
                <?php foreach($candi_vote as $item):?>
                <li>
                    <img src="<?=$item['pic']?>" class="img-responsive"/> <?php if ($item['nickname']){echo $item['nickname'];} else {echo "有人";}?>给我投了一票 <?php echo date('Y-m-d H:i:s',$time['vote_time']);?>
                </li>
                <?php endforeach;?>
            </ul>
        </div>
    <?php endif;?>
    <div class="col-xs-12">
        <div>
            <h3>我的投票记录</h3>
            <ul>
                <?php if ($my_vote):?>
                <?php foreach($my_vote as $v):?>
                <li>我给<?=$v['name']?>投了一票 <?php echo date('Y-m-d H:i:s',$v['vote_time']);?></li>
                <?php endforeach;?>
                <?php endif;?>
            </ul>
        </div>
    </div>
</section>