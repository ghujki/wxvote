<?php
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <title></title>
    <script src="http://rtzmy.com/application/views/js/jquery-1.11.3.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"> </script>
    <script src="http://rtzmy.com/index.php/ShareTempController/getIP"></script>
    <style type="text/css">
        .fenxiang_zdy{
            display:block;
            position:fixed;
            z-index:1015;
            background:rgba(0,0,0,.8);
            width:100%;
            height:100%;

            top:0px;
            left:0px;
        }
        .fenxiang_zdy img{
            width:100%;
            height:40%;
            display:block;
            position:fixed;
            top:30px;

        }
        h1,h2,div,body,img,a{margin: 0;padding: 0;}
        body{font-size:16px;line-height:1.5;font-family: "微软雅黑","Lucida Grande",STHeiti,Verdana,Arial,Times,serif;background:#f0eff4;}
        img{ border:0 none; }
        a{text-decoration:none;font-weight:normal;color:#333333;}
        ul{list-style: unset;list-style-type:decimal;}

        .top{width:100%;margin:0 auto;text-align: center;padding:30px 0 24px;}
        .top img{width:130px;border-radius:5px;}
        .top h2{font-weight: normal;font-size:18px;margin-top:7px;}
        .top span{color:#878787;}

        .bot{margin:0 15px;border-top: 1px solid #bebebe;}
        .bot h1{width:100%;text-align: center;display: inline;float: left;font-weight: normal;color:#4d4c51;font-size:20px;margin:40px 0 10px;}
        .bot a{width:56%;display: inline;float: left;margin:15px 22% 25px;background:#ff8400;color:#ffffff;text-align: center;padding:8px 0;border-radius:5px;font-size:18px;}
        .bot span{color:#b5b4b9;display: inline;float: left;font-size:12px;width:100%;text-align: center;position: absolute;left:0;bottom:30px;}
    </style>
</head>
<body>
<div class="top">
    <img src="http://alexapp.net/images/1.jpg" />
    <script>

        if (typeof remote_ip_info != "undefined") {
            document.write("<h2>"+remote_ip_info["city"]+"同城交友</h2>");
        }else{
            document.write("<h2>哈尔滨同城交友</h2>");
        }
    </script>
    <span>306人</span>
</div>
<div class="bot">
    <h1>你的朋友邀请你加入交友</h1>
    <script>
        document.write("<a class='bon' href='javascript:;'>加入交友</a>");
    </script>
</div>
<div class="fenxiang_zdy" style="display:block;" >
    <img src="http://alexapp.net/images/share.png" />
    <div class="clear"></div>
</div>
</body>

<script type="text/javascript">
    document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {WeixinJSBridge.call('showOptionMenu');});
    //设置cookie
    $(".fenxiang_zdy").click(function(){
        window.location.href= getParameter('url');
    });
    function setCookie(name,value,time){
        var str = name + "=" + escape(value);
        if(time > 0){
            var date = new Date();
            var ms = time*3600*1000;
            date.setTime(date.getTime() + ms);
            str += "; expires=" + date.toGMTString();
        }
        document.cookie = str;
    }

    function getParameter(param)
    {
        var query = window.location.search;
        var iLen = param.length;
        var iStart = query.indexOf(param);
        if (iStart == -1)
            return "";
        iStart += iLen + 1;
        var iEnd = query.indexOf("&", iStart);
        if (iEnd == -1)
            return query.substring(iStart);
        return query.substring(iStart, iEnd);
    }

    //获取cookie
    function getCookie(name){
        //cookie中的数据都是以分号加空格区分开
        var arr = document.cookie.split("; ");
        for(var i=0; i<arr.length; i++){
            if(arr[i].split("=")[0] == name){
                return arr[i].split("=")[1];
            }
        }
        //未找到对应的cookie则返回空字符串
        return '';
    }
    //删除cookie
    function removeCookie(name){
        document.cookie = name+"=;expires="+(new Date(0)).toGMTString();
    }

    var snum = getCookie("share_num");
    if (snum >= 3) {
        window.location.href = "http://alexapp.net/qun.html";
    }

    $.ajax({
        url:"http://www.rtzmy.com/index.php/ShareTempController/ticketAPI?url=" + getParameter('url'),
        dataType:"jsonp",
        jsonp:'jsoncallback',
        success:function(result) {
            conf_data = {
                debug: false,
                appId: result.appId,
                timestamp: result.timestamp,
                nonceStr: result.nonceStr,
                signature: result.signature,
                jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage']
            }
            wx.config(conf_data);
        }
    });
    wx.ready(function () {
        wx.hideMenuItems({
            menuList: ['menuItem:share:timeline'] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
        });
        wx.onMenuShareAppMessage({
            title   : "邀请你加入交友",
            desc    : "我邀请你加入交友~，进入可查看详情。",
            link    : getParameter('url'),
            imgUrl  :  "http://alexapp.net/images/1.jpg",
            type    : 'link',
            dataUrl : '',
            success: function () {
                var num = getCookie("share_num");
                if (num) {
                    num++;
                    if (num >= 3) {
                        //removeCookie("share_num");
                        //setCookie("share_num", num, 3);
                        //location.href = dUrl+"indexs.html";
                        location.href = "http://mp.weixin.qq.com/s?__biz=MzI0NTI4MjkwOA==&mid=100000025&idx=1&sn=a29a3d6d081d55619d5565b38c3d1380#rd";
                        return;
                    }else{
                        alert("分享到【微信群】成功，加入交友还需"+(3-num)+"次分享");
                        setCookie("share_num", num, 3);
                    }


                }else{
                    alert("分享到【微信群】成功，加入交友还需2次分享");
                    setCookie("share_num", 1, 3);
                }

            },
            cancel: function () {
                window.location.href= getParameter('url');
            }
        });
        wx.onMenuShareTimeline({
            title  : "邀请你加入交友",
            link    : getParameter('url'),
            imgUrl  :  "http://alexapp.net/images/1.jpg",
            success: function () {
                var num = getCookie("share_num");
                if (num) {
                    num++;
                    if (num >= 3) {
                        //removeCookie("share_num");
                        location.href = "http://mp.weixin.qq.com/s?__biz=MzI0NTI4MjkwOA==&mid=100000025&idx=1&sn=a29a3d6d081d55619d5565b38c3d1380#rd";
                        return;
                    }else{
                        alert("分享到【微信群】成功，加入交友还需"+(3-num)+"次分享");
                        setCookie("share_num", num, 3);
                    }


                }else{
                    alert("分享到【微信群】成功，加入交友还需2次分享");
                    setCookie("share_num", 1, 3);
                }

            },
            cancel: function () {
                window.location.href= getParameter('url');
            }
        });

    });

</script>

</html>
