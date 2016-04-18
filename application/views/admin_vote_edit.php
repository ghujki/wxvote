<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div>
    <?php echo validation_errors(); ?>
    <?php echo form_open('AdminVoteController/save'); ?>
    <table width="100%" border="1px" class="admin-form">
        <tr>
            <td width="30%">投票活动名称</td>
            <td width="70%"><input type="text" required name="vote_name" placeholder="输入活动名称" value="<?=$vote['vote_name']?>"/> </td>
        </tr>
        <tr>
            <td>公众号</td>
            <td>
                <select name="app_id">
                    <?php foreach ($numbers as $number):?>
                    <option value="<?=$number['id']?>"><?=$number['app_name']?></option>
                    <?php endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <td>报名时间</td>
            <td>
                <input type="text"  name="signup_start_time" data-toggle="time" value="<?=$vote['signup_start_time']?>"/> ~
                <input type="text"  name="signup_end_time" data-toggle="time" value="<?=$vote['signup_end_time']?>" />
            </td>
        </tr>
        <tr>
            <td>投票时间</td>
            <td>
                <input type="text"  name="vote_start_time" data-toggle="time" value="<?=$vote['vote_start_time']?>" /> ~
                <input type="text"  name="vote_end_time" data-toggle="time" value="<?=$vote['vote_end_time']?>" />
            </td>
        </tr>
        <tr>
            <td>描述</td>
        </tr>
        <tr>
            <td colspan="2">
                <!-- 加载编辑器的容器 -->
                <script id="container" name="content" type="text/plain">
                    在此输入文本
                </script>
                <!-- 配置文件 -->
                <script type="text/javascript" src="application/third_party/uedit/ueditor.config.js"></script>
                <!-- 编辑器源码文件 -->
                <script type="text/javascript" src="application/third_party/uedit/ueditor.all.js"></script>
                <!-- 实例化编辑器 -->
                <script type="text/javascript">
                    var ue = UE.getEditor('container');
                </script>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="id" value="<?=$vote['id']?>" />
                <input type="submit" value="提交"/>
            </td>
        </tr>
    </table>
    </form>
</div>
<link rel="stylesheet" href="application/views/css/jquery.datetimepicker.css">
