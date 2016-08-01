<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title>管理登陆</title>
    <base href="<?=base_url()?>" />
    <link rel="stylesheet" href="application/views/css/bootstrap-3.3.5.min.css">
    <link rel="stylesheet" href="application/views/css/index.css" >



</head>
<body>   
    <div class="container">
        <?php echo validation_errors(); ?>
        <?php echo form_open('AdminController/login'); ?>
        <div class="title">
            微时光微信管理系统
        </div>
        <form>
            <div class="form-group">
                <label for="username">用户名</label>
               <input type="text" class="form-control" id="username" required name="username"
                       placeholder="username" >
            </div>
            <div class="form-group">
                <label for="password">密码</label>
               <input type="password" id="password" name="password" class="form-control" placeholder="password" required>
            </div>
            <div class="form-group">
                <input type="hidden" name="from" value="" />
                <input type="submit" class="form-control login" value="登录" >
            </div>
             <div class="form-group remember">
                <label for="remember">记住密码</label>
                <input type="checkbox" id="remember"  />
            </div>
         
        </form>
    </div>
</body>
<script src="application/views/js/jquery-1.11.3.min.js"></script>
<script>
    window.onload = function() {
        $("#username").focus();
    }
</script>
</html>