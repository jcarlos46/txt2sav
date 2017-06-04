<h1>txt2sav:<?php echo $pagename ?></h1>
<form action="/newp" method="post">
    <textarea name="content"></textarea>
    <br/>
    <br/>
    <input type="submit" value="<?php echo $i18n['PUBLISH'] ?>"/>
    <?php echo $i18n['CODE'] ?>: <input type="text" name="password" value="<?php echo $password ?>"/>
</form>