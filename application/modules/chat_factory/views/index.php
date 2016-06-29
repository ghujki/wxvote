<?php
?>
<div class="row">
    <div class="col-xs-12">
        <h3>聊天人数统计</h3>
        <div>参与聊天人数:<span><?=$total?></span></div>
        <div><?php foreach($data as $item) :?>
                <?=$item['app_name']?>:<?=$item['c']?>
            <?php endforeach;?></div>
        <div id="container" style="min-width:700px;height:400px"></div>
        <div id="container-1" style="min-width:700px;height:400px"></div>
    </div>
    <script>
        var chat_data = [<?php foreach ($data as $item) { echo "[ '$item[app_name]',$item[rate]],";}?>];
        var chat_data2 = [<?php foreach ($line_data as $date) {echo "[Date.UTC($date[year],$date[month],$date[day],$date[hour]),$date[c]],";} ?>];
    </script>
</div>
