<h1>txt2sav</h1>
<form action="/<?php echo $action ?>" method="post">
    <input type="hidden" name="md5" value="<?php echo $md5 ?>">
    <input type="hidden" name="id_parent" value="<?php echo $id_parent ?>">
    <input type="hidden" name="callback" value="<?php echo $callback ?>">
    <textarea name="content"><?php echo $content ?></textarea>
    <br/>
    <br/>
    <input type="submit" value="Publish"/>
    <?php if($action == 'api/edit'): ?>
    Edit Code: <input type="text" name="password" />
    <?php endif; ?>
</form>