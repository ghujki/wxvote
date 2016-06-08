<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div>
    <?php echo validation_errors(); ?>
    <?php echo form_open_multipart('AdminOfficialNumber/save'); ?>
    <table width="100%" border="1px" class="admin-form">
        <tr>
            <td width="30%">公众号名称</td>
            <td width="70%"><input type="text" required name="app_name" placeholder="输入公众号名称" value="<?=$number['app_name']?>"/> </td>
        </tr>
        <tr>
            <td width="30%">微信号</td>
            <td width="70%"><input type="text" required name="alias_name" placeholder="输入微信号" value="<?=$number['alias_name']?>"/> </td>
        </tr>
        <tr>
            <td>APP_ID</td>
            <td><input type="text" required name="app_id" placeholder="appid,在微信后台找" value="<?=$number['app_id']?>"/> </td>
        </tr>
        <tr>
            <td>secretkey</td>
            <td><input type="text" required name="secretkey" value="<?=$number['secretkey']?>" placeholder="secretkey,在微信后台找"/> </td>
        </tr>
        <tr>
            <td>公众号类型</td>
            <td>
                <select name="app_type">
                    <option value="0" <?php if($number['app_type'] == 0):echo "selected"; ?> >订阅号</option>
                    <option value="1" <?php else: echo "selected"; endif; ?> >服务号</option>
                </select>
                <input type="checkbox" value="1" name="authorized" id="authorized" <?php if($number['authorized'] == 1):echo "checked"; endif;?> />
                <label for="authorized">已认证</label>
            </td>
        </tr>
        <tr>
            <td>原始ID</td>
            <td><input type="text" required name="original_id" placeholder="原始ID" id="original_id" value="<?=$number['original_id']?>"/>
             </td>
        </tr>
        <tr>
            <td>token</td>
            <td><input type="text" required name="token" placeholder="token,自动生成一个32位字符串" id="token" value="<?=$number['token']?>"/>
                <a herf="javascript:;" onclick="generateRandomString()">自动生成</a>
                <div>将 http://<?php echo $_SERVER['HTTP_HOST']?>/index.php/Response/index/<span id="token_str"><?=$number['token']?></span> 设置到微信服务器url中.</div>
            </td>
        </tr>

        <tr>
            <td>二维码</td>
            <td>
                <img src="<?=$number['qrcode']?>" class="img-responsive">
                <input type="file" name="qrcode" class="form-control" />
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="hidden" name="id" value="<?=$number['id']?>" />
                <input type="submit" value="提交"/>
            </td>
        </tr>
    </table>
    </form>
</div>
<script>
    function randomString(len) {
        len = len || 32;
        var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
        var maxPos = $chars.length;
        var pwd = '';
        for (i = 0; i < len; i++) {
            pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
        }
        return pwd;
    }

    function generateRandomString() {
        var str = randomString(16);
        document.getElementById('token').value = str;
        document.getElementById('token_str').value = str;

    }

</script>

