<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>管理登陆</title>
    <base href="<?=base_url()?>" />
    <link rel="stylesheet" href="application/views/css/bootstrap-3.3.5.min.css">
    <link rel="stylesheet" href="application/views/css/index.css" >
</head>
<body>
    <div class="container">
        <?php echo validation_errors(); ?>
        <?php echo form_open('AdminController/login'); ?>
        <div class="form-group">
                <label for="username">名称</label>
                <input type="text" class="form-control" id="username" required name="username"
                       placeholder="请输入名称">
            </div>
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="请输入密码" required>
            </div>
            <div class="form-group">
                <label for="remember">记住密码</label>
                <input type="checkbox" id="remember"  />
            </div>
            <div class="form-group">
                <input type="hidden" name="from" value="" />
                <input type="submit" class="form-control" value="登录">
            </div>
        </form>
    </div>
</body>
<script src="application/views/js/jquery-1.11.3.min.js"></script>
<script src="application/views/js/jquery-1.11.3.min.js"></script>
</html>