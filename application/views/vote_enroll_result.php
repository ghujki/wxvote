<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="application/views/css/font-awesome.min.css" >
<link rel="stylesheet" href="application/views/css/font-awesome-ie7.min.css" >
<style>
    .suc {
        color:green;
    }
    .alert {
        color:yellow;
    }
    .info {
        color:#00a2d4;
    }
    .error {
        color:red;
    }
    .jumbotron {
        padding:1em;
    }
</style>
<section class="row light-green main-content">
    <div class="col-xs-12">
        <?php if ($result == 'success') {?>
        <div class="jumbotron suc">
            <h2><i class="fa  fa-check-circle" />报名成功</h2>
            <p>恭喜您报名成功了！马上分享给朋友吧</p>
        </div>
        <?php } elseif ($result == 'duplicate'){?>
            <div class="jumbotron alert">
                <h2><i class="fa  fa-exclamation-circle" />不能重复报名哦！</h2>
                <p>您已经报了名了，不能重复报名哦。</p>
            </div>
        <?php } elseif ($result == 'need_like'){?>
            <div class="jumbotron info">
                <h2><i class="fa fa-info-circle"></i>只要一步</h2>
                <p>请先关注我们的微信号再报名吧！</p>
                <p><img src="<?=$number['qrcode']?>" class="img-responsive"></p>
            </div>
        <?php } else {?>
            <div class="jumbotron error">
                <h2><i class="fa fa-times-circle">报名出错了</h2>
                <p>请将资料发给我们的客服，由他们帮您处理。</p>
            </div>
        <?php }?>
    </div>
</section>
