<!-- START: home_right.php -->
<h3>News Articles</h3>
<ul>
<?php if(count($articles)): ?>
<?php foreach($articles as $article): ?>
    <li>
        <?php echo $article->title ?>
    </li>
<?php endforeach; ?>
<?php else: ?>
    <li>
        No Artcles Found!
    </li>
<?php endif; ?>
</ul>
<!-- END: home_right.php -->