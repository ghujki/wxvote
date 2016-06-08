<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .chat-box {
        width: 100%;
        border: 1px solid #ccc;
        margin-top: 10px;
        height: 15em;
        overflow-y: auto;
        overflow-x: hidden;
        padding: .5em;
    }
    .chat-box .msg-item {
        margin:.5em;
    }
    .chat-box .from {
        background: gray;
        float: left;
    }
    .chat-box .to {
        float: right;
        background: green;
    }
    .chat-box .to ,.chat-box .from {
        color: #fff;
        padding: .2em;
        border-radius: .2em;
        word-break: break-all;
    }

    .ctl-panel {
        background: #999;
        padding: .5em;
        text-align: right;
    }
    .chat-box .msg-item .pointer {
        position: absolute;
    }
    .chat-box .from .pointer {
        border-bottom: 1em solid transparent;
        border-top: 1em solid transparent;
        border-right: 1em solid grey;
        left: 1.5em;
    }
    .chat-box .to .pointer {
        border-bottom: 1em solid transparent;
        border-top: 1em solid transparent;
        border-left: 1em solid green;
        right: 1.5em;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <form class="admin-query">
            <input type="text" placeholder="查询条件" name="query">
            <input type="submit" value="查询">
        </form>
    </div>

    <div class="col-xs-12">
        <div>与<span><?=$user['nickname']?></span>聊天</div>
        <div class="chat-box">
            <div class="msg-item clearfix">
                <div class="from">
                    <div class="pointer"></div>
                    <span>5/10 14:20:10</span>
                    <div>
                        你好!你好!你好!你好!你好!你好!你好!你好!你好!你好!你好!你好!你好!
                        你好!你好!你好!你好!你好!你好!你好!你好!你好!你好!你好!你好!你好!
                    </div>
                </div>
            </div>
            <div class="msg-item clearfix">
                <div class="to">
                    <div class="pointer"></div>
                    <span>5/10 14:20:10</span>
                    <div>你好!你好!你好!你好!你好!你好!你好!你好!你好!你好!你好!你好!你好!</div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-xs-12">
        <div class="ctl-panel">
            <input type="text" name="message" id="message" class="form-controll" >
            <input type="button" value="发送" />
        </div>
    </div>
</div>
