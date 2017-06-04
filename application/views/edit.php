<h1>txt2sav</h1>
<form action="/<?php echo $action ?>" method="post">
    <input type="hidden" name="md5" value="<?php echo $md5 ?>">
    <input type="hidden" name="id_parent" value="<?php echo $id_parent ?>">
    <textarea name="content"><?php echo $content ?></textarea>
    <br/>
    <br/>
    <input type="submit" value="<?php echo $i18n['PUBLISH'] ?>"/>
    <?php if($action == 'editp'): ?>
    <input type="text" name="password" placeholder="<?php echo $i18n['CODE']?>" />
    <?php endif; ?>
</form>