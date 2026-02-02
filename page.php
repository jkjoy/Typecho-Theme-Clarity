<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<div class="grid-inverse wrap content">
    <article class="post_content">
        <h1 class="post_title"><?php $this->title(); ?></h1>
        <div class="post_body">
            <?php $this->content(); ?>
        </div>
        <?php if ($this->allow('comment')): ?>
            <?php $this->need('comments.php'); ?>
        <?php endif; ?>
    </article>
    <?php $this->need('sidebar.php'); ?>
</div>

<?php $this->need('footer.php'); ?>

