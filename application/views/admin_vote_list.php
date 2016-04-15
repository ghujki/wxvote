<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>活动列表</title>
    <link rel="stylesheet" href="application/views/css/bootstrap-3.3.5.min.css">
    <link rel="stylesheet" href="application/views/css/admin_vote_list.css" >
</head>
<body>
    <div class="container">
        <?php if($list): ?>
            <?php foreach($list as $item):?>
                <?=$item['pic']?>
            <?php endforeach;?>
        <?php else: ?>
            <div>no data loaded</div>
        <?php endif;?>
    </div>
    <script src="application/views/js/jquery-1.11.3.min.js"></script>
    <script src="application/views/js/jquery-1.11.3.min.js"></script>
</body>
</html>