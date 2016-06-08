<?php
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 margin-top">
            <img src=<?php if(isset($img)) echo $img; else echo "images/icon1.jpg";?> id="img" class="img-responsive"/>
        </div>
    </div>
    <div class="row" <?php if($img){echo "style='display:none'";};?>>
        <?php  echo form_open_multipart();?>
            <div class="col-xs-12 margin-top">
                <input type="text" id="name" name="name" class="form-control" placeholder="此处输入您的名字" required/>
            </div>
            <div class="col-xs-6 margin-top">
                <select class="form-control" id="sex" name="sex">
                    <option value="男">男</option>
                    <option value="女">女</option>
                </select>
            </div>
            <div class="col-xs-6 margin-top">
                <select class="form-control" id="subject" name="subject">
                    <option value="文科综合">文科</option>
                    <option value="理科综合">理科</option>
                </select>
            </div>

            <div class="col-xs-12 margin-top">
                <input type="text" id="school" name="school" class="form-control" placeholder="此处输入您的学校" required/>
            </div>

            <div class="col-xs-12 margin-top">
                <input type="file" id="headimage" accept="image/*" name="headimage" class="form-control" value="选择您的头像" required/>
            </div>

            <div class="col-xs-12 margin-top">
                <input type="hidden" name="file" id="file" />
                <button id="submit" class="form-control" >提交</button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade " id="modal_exam" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close" ></div>
            <div class="modal-body text-center" >
                <div id="clipArea"></div>
                <button id="clipBtn" class="btn btn-default">截取图片</button>
                <div id="view" style="margin-top:10px"></div>
                <button id="clipBtn" class="btn btn-default margin-top" data-dismiss="modal" target="#modal_exam">使用截取的部分</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>