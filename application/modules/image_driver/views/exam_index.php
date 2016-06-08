<?php
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 margin-top">
            <img src=<?php if(isset($img)) echo $img; else echo "images/icon1.jpg";?> id="img" class="img-responsive"/>
        </div>
    </div>
    <div class="row">
        <form>
            <div class="col-xs-12 margin-top">
                <input type="text" id="name" name="name" class="form-control" placeholder="此处输入您的名字"/>
            </div>
            <div class="col-xs-12 margin-top">
                <select class="form-control" id="sex" name="sex">
                    <option value="男">男</option>
                    <option value="女">女</option>
                </select>
            </div>
            <div class="col-xs-12 margin-top">
                <input type="text" id="school" name="school" class="form-control" placeholder="此处输入您的学校"/>
            </div>
            <div class="col-xs-12 margin-top">
                <button id="submit" class="form-control" >提交</button>
            </div>
        </form>
    </div>
</div>
