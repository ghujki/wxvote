<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <title>{$title}</title>
    <base href="<?=base_url()?>" />
    <link rel="stylesheet" href="application/views/css/bootstrap-3.3.5.min.css">
    <link rel="stylesheet" href="application/views/css/index.css" >

</head>
<body>
<style>
    body { background-color:#4F5155; }
    .container {
        background-color: #fff;
    }
    .green {
        background-color: #4cae4c;
    }
    .enroll {
        background-color:#ce8483;
        border-radius:0.3em;
        font-size: large;
        color:black;
        padding: 0.5em 1.5em;
    }
    .enroll-div {
        padding-top:1em;
        padding-bottom:1em;
        margin: 1em 0;
    }
    .active-last {
        font-size:small;
    }
    .light-green {
        background-color:#a6e1ec;
    }
    .search-form {
        margin-top:1em;
    }
    .search-txt {
        border-radius: 0.3em;
        border: 0;
        padding: 0.2em;
        font-size: small;
    }
    .search-btn {
        cursor: pointer;
        text-decoration: none;
    }
    .rank-1 {
        background-color:#1b6d85;
    }
    .rank-2 {
        background-color:#2e6da4;
    }
    .rank-3 {
        background-color:#8c8c8c;
    }
    .rank-1,.rank-2,.rank-3 {
        padding:.5em;
        border-radius:.3em;
        color:#fff;
    }

    .photo-item {
        margin-bottom:1em;
    }
    .rank-row {
        padding: 1em 0;
    }
    .pink {background-color: pink}

    .user-num {
        color: #fff;
        position: absolute;
        background-color: brown;
        border-bottom-left-radius: 1em;
        border-bottom-right-radius: 1em;
        padding: .1em .2em;
        opacity: 0.7;
        right: 1em;
    }
    td.green ,td.pink{
        font-size: small;
        padding: .5em .2em;
        color:#fff;
    }

    .link-box a,.link-box strong{
        padding:0 .3em;
    }
    .bottom-menu {
        position:fixed;
        bottom:0;
        background-color:#fff;
        width:100%;
        left:0px;
    }
    .bottom-menu li {
        float:left;
        width:30%;
        text-align:center;
    }
    .main-content {
        padding-bottom:2em;
    }
</style>
    <div class="container">
        <header>
            <div class="row">
                <div class="column full">
                    <img src="application/views/images/154493-12050910330720.jpg" class="img-responsive"/>
                </div>
            </div>
        </header>
        <section class="row green text-center">
            <div class="column col-xs-12 enroll-div">
                <a href="javascript:void(0);" class="enroll">我要参赛</a>
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
            <div class="column col-xs-4">
                <div>已报名</div>
                <div>1</div>
            </div>
            <div class="column col-xs-4">
                <div>投票人数</div>
                <div>2</div>
            </div><div class="column col-xs-4">
                <div>访问量</div>
                <div>57</div>
            </div>
        </section>
        <section class="row light-green">
            <div class="column col-xs-12 text-center">
                <form action="search" class="search-form">
                    <input type="text" name="" placeholder="输入名字或编号" class="search-txt"/>
                    <a href="javascript:;" onclick="this.form.submit()" class="search-btn">搜索</a>
                </form>
            </div>
        </section>

        <section class="row light-green rank-row">
            <div class="column col-xs-4 col-sm-3 text-center ">
                <a href="" class="rank-1">最新参赛</a>
            </div>
            <div class="column col-xs-4 col-sm-3 text-center">
                <a href="" class="rank-2">投票排行</a>
            </div>
            <div class="column col-xs-4 col-sm-3 text-center">
                <a href="" class="rank-3">top 50</a>
            </div>
        </section>

        <section class="row light-green photo-box">
            <div class="column col-xs-6 photo-item col-sm-4 col-lg-3">
                <div class="user-num">
                    1126
                </div>
                <table>
                    <tr>
                        <td colspan="2"><img src="application/views/images/214833-120S11GJ543.jpg" class="img-responsive" /></td>
                    </tr>
                    <tr>
                        <td width="75%"class="green">
                            <div >测试</div>
                            <div ><span>1</span>票</div>
                        </td>
                        <td class="pink text-center">
                            <a href="" >投票</a>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="column col-xs-6 photo-item col-sm-4 col-lg-3">
                <div class="user-num">
                    1126
                </div>
                <table>
                    <tr>
                        <td colspan="2"><img src="application/views/images/214833-120S11GJ543.jpg" class="img-responsive" /></td>
                    </tr>
                    <tr>
                        <td width="75%"class="green">
                            <div >测试</div>
                            <div ><span>1</span>票</div>
                        </td>
                        <td class="pink text-center">
                            <a href="" >投票</a>
                        </td>
                    </tr>
                </table>
            </div><div class="column col-xs-6 photo-item col-sm-4 col-lg-3">
                <div class="user-num">
                    1126
                </div>
                <table>
                    <tr>
                        <td colspan="2"><img src="application/views/images/214833-120S11GJ543.jpg" class="img-responsive" /></td>
                    </tr>
                    <tr>
                        <td width="75%"class="green">
                            <div >测试</div>
                            <div ><span>1</span>票</div>
                        </td>
                        <td class="pink text-center">
                            <a href="" >投票</a>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="column col-xs-6 photo-item col-sm-4 col-lg-3">
                <div class="user-num">
                    1126
                </div>
                <table>
                    <tr>
                        <td colspan="2"><img src="application/views/images/214833-120S11GJ543.jpg" class="img-responsive" /></td>
                    </tr>
                    <tr>
                        <td width="75%"class="green">
                            <div >测试</div>
                            <div ><span>1</span>票</div>
                        </td>
                        <td class="pink text-center">
                            <a href="" >投票</a>
                        </td>
                    </tr>
                </table>
            </div>
        </section>

        <section class="row light-green text-center">
            <div class="column col-xs-12 link-box">
                <?=$links?>
            </div>
        </section>

        <section class="row light-green main-content">
            <div class="column col-xs-4 green">
                奖品设置
            </div>
            <div class="column col-xs-12">
                <div>长沙地区小朋友均可参加</div>
            </div>

            <div class="column col-xs-4 green">
                报名规则
            </div>
            <div class="column col-xs-12">
                <div>长沙地区小朋友均可参加</div>
            </div>

            <div class="column col-xs-4 green">
                报名地址
            </div>
            <div class="column col-xs-12">
                <div>长沙地区小朋友均可参加</div>
            </div>
        </section>

        <div class="bottom-menu">
            <ul>
                <li><a href="">首页</a></li>
                <li><a href="">排名</a></li>
                <li><a href="">我的</a></li>
            </ul>
        </div>
    </div>
<script src="application/views/js/jquery-1.11.3.min.js"></script>
<script src="application/views/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
    function getRTime(){
        var EndTime = new Date('2016/05/15 00:00:00');
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
    })();
</script>
</body>
</html>
