<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<div class="wrap content">
    <div class="never">
        <h1>404</h1>
        <p><?php _e('你访问的页面不存在'); ?></p>
        <p><a class="button" href="<?php $this->options->siteUrl(); ?>"><?php _e('返回首页'); ?></a></p>
    </div>
</div>

<?php $this->need('footer.php'); ?>

