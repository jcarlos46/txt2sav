<h1>txt2sav</h1>
<form action="/<?php echo $action ?>" method="post">
    <input type="hidden" name="md5" value="<?php echo $md5 ?>">
    <input type="hidden" name="id_parent" value="<?php echo $id_parent ?>">
    <textarea name="content"><?php echo $content ?></textarea>
    <br/>
    <br/>
    <input type="submit" value="Publish"/>
</form>