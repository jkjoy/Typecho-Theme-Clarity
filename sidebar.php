<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$sidebarWidgets = $this->options->sidebarWidgets ?? null;
$enabledWidgets = null;

if ($sidebarWidgets !== null) {
    if (is_array($sidebarWidgets)) {
        $enabledWidgets = array_fill_keys($sidebarWidgets, true);
    } else {
        $enabledWidgets = array_fill_keys([(string) $sidebarWidgets], true);
    }
}

$isEnabled = function (string $key) use ($enabledWidgets): bool {
    if ($enabledWidgets === null) {
        return true;
    }

    return isset($enabledWidgets[$key]);
};

$popularLimit = (int) ($this->options->popularPostsLimit ?? 5);
if ($popularLimit <= 0) {
    $popularLimit = 5;
}

$tagsLimit = (int) ($this->options->tagsLimit ?? 20);
if ($tagsLimit <= 0) {
    $tagsLimit = 20;
}
?>
<aside class="sidebar">
    <section class="sidebar_inner">
        <br>

        <?php if ($isEnabled('toc') && ($this->is('post') || $this->is('page'))): ?>
            <h2 class="mt-4"><?php _e('目录'); ?></h2>
            <nav class="toc_nav" aria-label="<?php _e('目录'); ?>">
                <ol class="toc_list"></ol>
            </nav>
        <?php endif; ?>

        <?php if ($isEnabled('search')): ?>
            <form class="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                <input class="search_field" type="text" name="s" placeholder="<?php _e('搜索'); ?>">
            </form>
        <?php endif; ?>

        <?php if ($isEnabled('intro')): ?>
            <?php if (!empty($this->options->introTitle) || !empty($this->options->introDescription)): ?>
                <?php if (!empty($this->options->introTitle)): ?>
                    <h2><?php echo htmlspecialchars((string) $this->options->introTitle, ENT_QUOTES, 'UTF-8'); ?></h2>
                <?php endif; ?>
                <?php if (!empty($this->options->introDescription)): ?>
                    <div class="author_bio">
                        <?php echo (string) $this->options->introDescription; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($this->options->introUrl)): ?>
                    <a href="<?php echo htmlspecialchars((string) $this->options->introUrl, ENT_QUOTES, 'UTF-8'); ?>" class="button mt-1" role="button" title="<?php _e('Read more'); ?>"><?php _e('更多信息'); ?></a>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($isEnabled('recent')): ?>
            <h2 class="mt-4"><?php _e('最近文章'); ?></h2>
            <ul class="flex-column">
                <?php \Widget\Contents\Post\Recent::alloc()->to($recent); ?>
                <?php while ($recent->next()): ?>
                    <li>
                        <a href="<?php $recent->permalink(); ?>" class="nav-link" title="<?php $recent->title(); ?>"><?php $recent->title(); ?></a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>

        <?php if ($isEnabled('popular')): ?>
            <h2 class="mt-4"><?php _e('热门文章'); ?></h2>
            <ul class="flex-column">
                <?php
                $popularQuery = $this->select(
                    'table.contents.cid',
                    'table.contents.title',
                    'table.contents.slug',
                    'table.contents.created',
                    'table.contents.modified',
                    'table.contents.type',
                    'table.contents.status',
                    'table.contents.commentsNum',
                    'table.contents.allowComment',
                    'table.contents.allowPing',
                    'table.contents.allowFeed',
                    'table.contents.template',
                    'table.contents.password',
                    'table.contents.authorId',
                    'table.contents.parent'
                )
                    ->where('table.contents.status = ?', 'publish')
                    ->where('table.contents.created < ?', $this->options->time)
                    ->where('table.contents.type = ?', 'post')
                    ->order('table.contents.commentsNum', \Typecho\Db::SORT_DESC)
                    ->order('table.contents.created', \Typecho\Db::SORT_DESC)
                    ->limit($popularLimit);

                \Widget\Contents\From::alloc(['query' => $popularQuery])->to($popular);
                ?>
                <?php while ($popular->next()): ?>
                    <li>
                        <a href="<?php $popular->permalink(); ?>" class="nav-link" title="<?php $popular->title(); ?>">
                            <?php $popular->title(); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>

        <?php if ($isEnabled('categories')): ?>
            <h2 class="mt-4"><?php _e('分类'); ?></h2>
            <nav class="tags_nav categories_nav">
                <?php \Widget\Metas\Category\Rows::alloc()->to($categories); ?>
                <?php while ($categories->next()): ?>
                    <?php
                    $level = isset($categories->levels) ? (int) $categories->levels : 0;
                    $indent = $level > 0 ? str_repeat('&nbsp;&nbsp;', $level) : '';
                    ?>
                    <a href="<?php $categories->permalink(); ?>" class="post_tag button button_translucent" title="<?php echo htmlspecialchars((string) $categories->name, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo $indent . htmlspecialchars((string) $categories->name, ENT_QUOTES, 'UTF-8'); ?>
                        <span class="button_tally"><?php echo (int) ($categories->count ?? 0); ?></span>
                    </a>
                <?php endwhile; ?>
            </nav>
        <?php endif; ?>

        <?php if ($isEnabled('tags')): ?>
            <div>
                <h2 class="mt-4 taxonomy" id="tags-section"><?php _e('标签'); ?></h2>
                <nav class="tags_nav">
                    <?php \Widget\Metas\Tag\Cloud::alloc('ignoreZeroCount=1&limit=' . $tagsLimit)->to($tags); ?>
                    <?php while ($tags->next()): ?>
                        <a href="<?php $tags->permalink(); ?>" class="post_tag button button_translucent" title="<?php $tags->name(); ?>">
                            <?php echo strtoupper($tags->name); ?>
                            <span class="button_tally"><?php echo (int) $tags->count; ?></span>
                        </a>
                    <?php endwhile; ?>
                </nav>
            </div>
        <?php endif; ?>
    </section>
</aside>
