<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <title><?=$vote['name']?></title>
    <base href="<?=base_url()?>" />
    <link rel="stylesheet" href="application/views/css/bootstrap-3.3.5.min.css">
    <link rel="stylesheet" href="application/views/css/index.css" >
</head>
<body>
    <div class="container">
        <header>
            <div class="row">
                <div class="column full">
                    <img src="application/views/images/154493-12050910330720.jpg" class="img-responsive"/>
                </div>
            </div>
        </header>
        <section class="row green text-center">

            <?php if (strtotime($vote['signup_start_time']) > time()){ ?>
            <div class="column col-xs-12">
                <h2>活动还未开始</h2>
            </div>
            <?php } elseif(strtotime($vote['vote_end_time']) < time()) {?>
                <div class="column col-xs-12">
                    <h2>活动已经结束</h2>
                </div>
            <?php } else {?>
            <div class="column col-xs-12 enroll-div">
                <a href="index.php/VoteController/enroll?vote_id=<?=$vote['id']?>" class="enroll">我要参赛</a>
            </div>
            <div class="column col-xs-12">
                <div class="active-last">距离活动结束还有</div>
            </div>
            <div class="column col-xs-12">
                <div class="active-last-time">
                    <span id="t_d">00天</span>
                    <span id="t_h">00时</span>
                    <span id="t_m">00分</span>
                    <span id="t_s">00秒</span>
                </div>
            </div>
            <?php }?>
            <div class="column col-xs-4">
                <div>已报名</div>
                <div><?=$candi_count?></div>
            </div>
            <div class="column col-xs-4">
                <div>投票人数</div>
                <div><?=$vote_count?></div>
            </div><div class="column col-xs-4">
                <div>访问量</div>
                <div><?=$visit_count?></div>
            </div>
        </section>
        <section class="row light-green">
            <div class="column col-xs-12 text-center">
                <form action="index.php/VoteController/search" class="search-form">
                    <input type="text" name="keywords" placeholder="输入名字或编号" class="search-txt"/>
                    <input type="hidden" name="vote_id" value="<?=$vote['id']?>" />
                    <a href="javascript:;" onclick="this.form.submit()" class="search-btn">搜索</a>
                </form>
            </div>
        </section>

        <section class="row light-green rank-row">
            <div class="column col-xs-4 col-sm-3 text-center ">
                <a href="index.php/VoteController/index?vote_id=<?=$vote_id?>&orderby=enroll_time" class="rank-1">最新参赛</a>
            </div>
            <div class="column col-xs-4 col-sm-3 text-center">
                <a href="index.php/VoteController/index?vote_id=<?=$vote_id?>&orderby=count" class="rank-2">投票排行</a>
            </div>
            <div class="column col-xs-4 col-sm-3 text-center">
                <a href="index.php/VoteController/index?vote_id=<?=$vote_id?>&orderby=top" class="rank-3">top 50</a>
            </div>
        </section>

        <?=$content?>

        <!-- rules content -->
        <section class="row light-green main-content">
            <?=$vote['content']?>
        </section>

        <!-- menu -->
        <div class="bottom-menu">
            <ul>
                <li><a href="index.php/VoteController/index?vote_id=<?=$vote_id?>">首页</a></li>
                <li><a href="index.php/VoteController/index?vote_id=<?=$vote_id?>&orderby=count">排名</a></li>
                <li><a href="index.php/VoteController/my?vote_id=<?=$vote_id?>">我的</a></li>
            </ul>
        </div>
    </div>

<div class="modal fade " id="modal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close" ></div>
            <div class="modal-body" >
                <div class="modal-text">投票成功！</div>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div class="modal fade " id="modal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close" ></div>
            <div class="modal-body" >
                <img src="<?=$number['qrcode']?>" class="img-responsive">
                <div class="text-center">
                    长按图片，识别图中二维码关注<strong><?=$number['app_name']?></strong>，进入公众号后输入<strong class="modal-text"></strong>即可为他(她)投票。
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<script src="application/views/js/jquery-1.11.3.min.js"></script>
<script src="application/views/js/bootstrap-3.3.5.min.js"></script>
<?php if ($scripts):?>
<?php foreach ($scripts as $script):?>
<script src="<?=$script?>"></script>
<?php endforeach;?>
<?php endif;?>
<script type="text/javascript">
    function getRTime(){
        var EndTime = new Date('<?=$vote["vote_end_time"]?>:00');
        var NowTime = new Date();
        var t = EndTime.getTime() - NowTime.getTime();
        var d = Math.floor(t/1000/60/60/24);
        var h = Math.floor(t/1000/60/60%24);
        var m = Math.floor(t/1000/60%60);
        var s = Math.floor(t/1000%60);

        $("#t_d").text(d + "天");
        $("#t_h").text(h + "时");
        $("#t_m").text(m + "分");
        $("#t_s").text(s + "秒");
    }
    $(function(){
        setInterval(getRTime,1000);
    });

    function voteFor(candi_id) {
        $.ajax({
            url:'index.php/voteController/vote',
            type:'get',
            dataType:'json',
            data:{'id':candi_id,"vote_id":'<?=$vote['id']?>'},
            success:function(data) {
                if (data['err'] == 0 ) {
                    //succeed in voting
                    $("#modal2 .modal-text").text(data['info']);
                    $("#modal2").modal();
                } else if(data['err'] == 1) {
                    //duplicating voting
                    $("#modal2 .modal-text").text(data['info']);
                    $("#modal2").modal();
                } else {
                    //vote in the account
                    $("#modal1 .modal-text").text(data['info']);
                    $("#modal1").modal();
                }
            },
            failure:function(data) {
                alert(data);
            },
            error:function(data) {
                alert(data);
            }
        });
    }
</script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js" />
<script>
    wx.config({
        debug: true,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            // 所有要调用的 API 都要加到这个列表中
            "onMenuShareTimeline",
            "onMenuShareAppMessage",
            "onMenuShareQQ",
            "onMenuShareWeibo",
            "onMenuShareQZone"
        ]
    });
    wx.ready(function () {
        wx.onMenuShareTimeline({
            title: 'abc', // 分享标题
            link: '', // 分享链接
            imgUrl: '', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
                $.ajax({
                    url:"index.php/VoteController/"
                });
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
</script>
</body>
</html>
