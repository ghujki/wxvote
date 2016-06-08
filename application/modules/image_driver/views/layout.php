<?php
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <base href="/application/modules/image_driver/views/" />
    <title><?=$title?></title>
    <link href="css/bootstrap-3.3.5.min.css" rel="stylesheet">
    <link href="css/exam.css" rel="stylesheet">
</head>
<body>

<?=$content?>

<div class="bottom" >
    <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
        更多功能请关注我们的公众号
    </button>
</div>
<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close" ></div>
            <div class="modal-body" >
                <img src="images/qrcode.jpg" class="img-responsive">
                <div class="text-center">长按图片，识别图中二维码即可关注。</div>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/bootstrap-3.3.5.min.js"></script>
<?php if ($jspath) {?>
<?php foreach($jspath as $js) {?>
<script src="<?=$js?>"></script>
<?php }}?>
<script>
    $(".modal-dialog,.modal").click(function(){
        $('#myModal').modal('hide');
    });
</script>
</body>
</html>