<form action="/<?php echo $action ?>" method="post">
    <input type="hidden" value="<?php echo $md5 ?>">
    <textarea name="content"><?php echo $content ?></textarea>
    <input type="submit" value="Ok"/>
</form>