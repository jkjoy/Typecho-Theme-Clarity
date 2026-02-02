<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$htmlClass = '';
if ($this->is('post') || $this->is('page')) {
    $htmlClass = 'page';
} elseif ($this->is('index')) {
    $htmlClass = 'home';
}

$dataMode = '';
if (!empty($this->options->enforceLightMode)) {
    $dataMode = 'lit';
} elseif (!empty($this->options->enforceDarkMode)) {
    $dataMode = 'dim';
}

$maxCodeLines = (int) ($this->options->maxCodeLines ?? 120);
if ($maxCodeLines <= 0) {
    $maxCodeLines = 120;
}
$showCodeLines = ($this->options->showCodeLines ?? 'true') === 'false' ? 'false' : 'true';
?>
<!DOCTYPE html>
<html lang="zh-CN" data-figures="false"<?php if ($htmlClass): ?> class="<?php echo $htmlClass; ?>"<?php endif; ?><?php if ($dataMode): ?> data-mode="<?php echo $dataMode; ?>"<?php endif; ?>>
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php $this->archiveTitle([
            'category' => _t('分类 %s'),
            'search'   => _t('包含关键字 %s 的文章'),
            'tag'      => _t('标签 %s'),
            'author'   => _t('%s 发布的文章')
        ], '', ' - '); ?><?php $this->options->title(); ?></title>

    <link rel="stylesheet" type="text/css" href="<?php $this->options->themeUrl('assets/css/styles.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php $this->options->themeUrl('style.css'); ?>">

    <?php if (!empty($this->options->customHeadHtml)): ?>
        <?php echo (string) $this->options->customHeadHtml; ?>
    <?php endif; ?>

    <?php $this->header(); ?>
</head>
<body
    id="documentTop"
    data-code="<?php echo $maxCodeLines; ?>"
    data-lines="<?php echo $showCodeLines; ?>"
    data-baseurl="<?php $this->options->siteUrl(); ?>"
    data-themeurl="<?php $this->options->themeUrl(''); ?>"
>

<header class="nav_header">
    <nav class="nav">
        <a href="<?php $this->options->siteUrl(); ?>" class="nav_brand nav_item" title="<?php $this->options->title(); ?>">
            <?php if ($this->options->logoUrl): ?>
                <img src="<?php $this->options->logoUrl(); ?>" class="logo" alt="<?php $this->options->title(); ?>">
            <?php else: ?>
                <?php $this->options->title(); ?>
            <?php endif; ?>
            <div class="nav_close">
                <div>
                    <?php clarity_sprite('open-menu'); ?>
                    <?php clarity_sprite('closeme'); ?>
                </div>
            </div>
        </a>

        <div class="nav_body nav_body_left">
            <?php clarity_nav($this); ?>
            <?php clarity_follow($this); ?>
        </div>
    </nav>
</header>

<main>
