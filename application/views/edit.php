<h1>txt2sav</h1>
<form action="/<?php echo $action ?>" method="post">
    <input type="hidden" value="<?php echo $md5 ?>">
    <textarea name="content"><?php echo $content ?></textarea>
    <br/>
    <br/>
    <input type="submit" value="Publish"/>
</form>