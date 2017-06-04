<h1>txt2sav</h1>
<h3>Log: <?php echo $content['md5'].' '.$content['create_at'] ?> </h3>
<?php if(!empty($parent)): ?>
<ul class="outer-list">
    <li>&nbsp;
    Origin: 
    <a href="/<?php echo $parent['md5'] ?>/<?php echo $parent['create_at']?>">
    <?php echo $parent['md5'] ?>
    <?php echo $parent['create_at'] ?>
    </a>
<?php endif; ?>
    <ul class="outer-list inner-list <?php if (empty($parent)) echo "no-parent" ?>">
        <?php foreach($contents as $k => $c): ?>
        <li class="<?php if($content['id'] == $c->id) echo "current" ?> <?php if($content['id'] == $c->id AND !empty($children)) echo "has-children" ?>" >
            <a href="/<?php echo $c->md5 ?>/<?php echo $c->create_at ?>">
            <?php echo $c->md5 ?>
            <?php echo $c->create_at ?>
            </a>
        <?php if($content['id'] == $c->id AND !empty($children)): ?>
        <ul class="inner-list">
            <?php foreach($children as $ch): ?>
            <li>
                <a href="/<?php echo $ch->md5 ?>/<?php echo $ch->create_at ?>">
                <?php echo $ch->md5 ?>
                <?php echo $ch->create_at ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
<?php if(!empty($parent)): ?>
    </li>
</ul>
<?php endif; ?>