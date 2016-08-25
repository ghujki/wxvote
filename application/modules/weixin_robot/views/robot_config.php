    <div class="robot-config-panel" id="robot_verify_panel">
        <h5>加好友设置</h5>
        <div class="robot-config-item">
            <input type="checkbox" id="auto_verify" value="1" <?php if ($rule['auto_add']) {echo "checked";}?>><label for="auto_verify">自动通过好友验证</label>
            <a href="javascript:;" onclick="new_verify_rule(this)">并回复</a>
        </div>
        <?php
            foreach ($rule['reply_rules'] as $item) {
                if ($item['msg_type'] == 'add') {
                    foreach ($item['reply'] as $r) {
            ?>
        <div class="robot-config-item">
            <select name="verify_reply_type[]" onchange="reply_type_changed(this)">
                <option value="text" placeholder="回复内容" <?php if ($r['type'] == 'text') {echo "selected";}?>>文本</option>
                <option value="image" <?php if ($r['type'] == 'image') {echo "selected";}?>>图片</option>
            </select>
            <input type="text" name="verify_reply_text[]" value="<?=$r['content']?>" <?php if ($r['type'] == 'image') {echo "style='display:none;'";}?>>
            <img src='/application/modules/weixin_robot/<?=$r['content']?>' data="<?=$r['content']?>" onclick='$(this).next().click()' <?php if ($r['type'] == 'text') {echo "style='display:none'";}?> />
            <input type="file" name="files[]" onchange="uploadImages(this)" >
            <a href="javascript:;" onclick="remove_verify_rule(this)">删除</a>
            <a href="javascript:;" onclick="new_verify_rule(this)">增加</a>
        </div>
        <?php }}}?>
    </div>
    <div class="robot-config-panel" id="robot_reply_panel">
        <h5>消息回复设置</h5>
        <a href="javascript:;" onclick="new_reply_rule(this)">增加规则</a>

        <?php
        foreach ($rule['reply_rules'] as $item) {
            if ($item['msg_type'] == 'text') {
                $r = array_shift($item['reply']);
        ?>
        <div class="robot-config-item">
            <input type="text" name="keywords[]" placeholder="关键字" value="<?=$item['keywords']?>">回复
            <select name="reply_rule_types[]" onchange="reply_rule_changed(this)">
                <option value="text" <?php if ($r['type'] == 'text') {echo "selected";}?>>文本</option>
                <option value="image" <?php if ($r['type'] == 'image') {echo "selected";}?>>图片</option>
            </select>
            <input type="text" placeholder="回复内容" name="reply_text[]" value="<?=$r['content']?>" <?php if ($r['type'] == 'image') {echo "style='display:none;'";}?>>
            <img src='/application/modules/weixin_robot/<?=$r['content']?>' data = "<?=$r['content']?>" onclick='$(this).next().click()' <?php if ($r['type'] == 'text') {echo "style='display:none'";}?> />
            <input type="file" name="files[]" onchange="uploadImages(this)">
            <a href="javascript:;" onclick="remove_reply_rule(this)">删除</a>
            <a href="javascript:;" onclick="new_sub_reply_rule(this)">增加</a>
            <?php foreach ($item['reply'] as $r) {?>
            <div class="sub-item">
                <select name="reply_rule_types[]" onchange="reply_rule_changed(this)">
                    <option value="text"  <?php if ($r['type'] == 'text') {echo "selected";}?>>文本</option>
                    <option value="image"  <?php if ($r['type'] == 'image') {echo "selected";}?>>图片</option>
                </select>
                <input type="text" placeholder="回复内容" name="reply_text[]" value="<?=$r['content']?>" <?php if ($r['type'] == 'image') {echo "style='display:none;'";}?>>
                <img data='<?=$r['content']?>' src='/application/modules/weixin_robot/<?=$r['content']?>' onclick='$(this).next().click()' <?php if ($r['type'] == 'text') {echo "style='display:none'";}?> />
                <input type="file" name="files[]" onchange="uploadImages(this)">
                <a href="javascript:;" onclick="remove_sub_reply_rule(this)">删除</a>
            </div>
        </div>
        <?php }}}?>
        <input type="hidden" id="cuin" value="<?=$uin?>">
    </div>
    <div class="robot-config-panel" id="robot_monitor_panel">
        <h5>下线通知设置</h5>
        <div class="robot-config-item">
        管理员手机号:
        <input type="number" value="<?=$rule['cellphone']?>" id="cellphone" replaceholder="手机号"/>
    </div>