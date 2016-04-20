<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="application/views/css/font-awesome.min.css" >
<link rel="stylesheet" href="application/views/css/font-awesome-ie7.min.css">
<style>
    input[type='file'] {
        /* opacity: 0; */
        text-indent: 0;
        font-size: 100px;
        overflow: hidden;
        display: inline-block;
        height: 0em;
        width: 0em;
        position: absolute;
    }
    .file-label {
        padding:1em;
        background-size: cover;
        border: 1px solid #ccc;
        margin-right: 1em;
        width: 3em;
        height: 3em;
    }
</style>
<section class="row light-green main-content">
    <?php form_open_multipart("index.php/VoteController/join")?>
        <div class="form-group">
            <label for="name">姓名</label>
            <input type="text" class="form-control" id="name" name="name"
                   placeholder="请输入名称" required>
        </div>
        <div class="form-group">
            <label for="phone">联系电话</label>
            <input type="number" class="form-control" id="phone" name="phone"
                   placeholder="请输入手机号" required>
        </div>

        <div class="form-group">
            <label>上传照片</label>
            <div>
                <label class="fa fa-plus file-label" for="file1"></label>
                <input type="file" id="file1" name="file1" onchange="fileChange(this)" />
                <label class="fa fa-plus file-label" for="file2"></label>
                <input type="file" id="file2" name="file1" onchange="fileChange(this)"/>
                <label class="fa fa-plus file-label" for="file3"></label>
                <input type="file" id="file3" name="file1" onchange="fileChange(this)"/>
                <label class="fa fa-plus file-label" for="file4"></label>
                <input type="file" id="file4" name="file1" onchange="fileChange(this)"/>
            </div>
            <p class="help-block">上传1~4张图片，至少一张。</p>
        </div>
        <div class="form-group">
            <label for="desc">描述</label>
            <input type="text" id="desc" name="desc" class="form-control"  multiple>
        </div>
        <button type="submit" class="btn form-control">提交</button>
    </form>
</section>
<script>
    function fileChange(obj) {
        var id = obj.id;
        var value = obj.value;
        if (value) {
            $("label[for='" + id + "']").removeClass("fa").removeClass("fa-plus").css("backgroundImage:url('" + value + "'");
        } else {
            $("label[for='" + id + "']").addClass("fa").addClass("fa-plus");
        }
    }
</script>
