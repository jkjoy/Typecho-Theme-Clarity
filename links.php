<?php
/**
 * 友情链接
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<?php $this->need('header.php'); ?>

<div class="grid-inverse wrap content">
    <article class="post_content links_page">
        <h1 class="post_title"><?php $this->title(); ?></h1>

        <div class="post_body">
            <?php $this->content(); ?>

            <?php if (!class_exists('Links_Plugin') || empty($this->options->plugins['activated']['Links'])): ?>
                <p class="notice warning"><?php _e('Links 友情链接插件未启用。'); ?></p>
            <?php else: ?>
                <?php
                $pattern = <<<HTML
<a class="link_card" href="{url}" target="_blank" rel="nofollow noopener">
    <span class="link_avatar">
        <img src="{image}" alt="" loading="lazy" width="{size}" height="{size}" />
    </span>
    <span class="link_body">
        <span class="link_name">{name}</span>
        <span class="link_desc">{description}</span>
    </span>
</a>

HTML;

                $db = \Typecho\Db::get();
                $prefix = $db->getPrefix();
                $rows = $db->fetchAll(
                    $db->select('sort')
                        ->from($prefix . 'links')
                        ->where('state = ?', 1)
                        ->order($prefix . 'links.order', \Typecho\Db::SORT_ASC)
                );

                $sorts = [];
                foreach ($rows as $row) {
                    $sorts[] = (string) ($row['sort'] ?? '');
                }
                $sorts = array_values(array_unique($sorts));
                ?>

                <?php if (empty($sorts)): ?>
                    <p class="notice info"><?php _e('暂无友情链接。'); ?></p>
                <?php else: ?>
                    <div class="links_wrap">
                        <?php foreach ($sorts as $sort): ?>
                            <?php
                            $label = trim($sort) === '' ? _t('未分类') : $sort;
                            $linksHtml = \Links_Plugin::output($pattern, 0, (trim($sort) === '' ? '' : $sort), 64, 'HTML');
                            if (trim((string) $linksHtml) === '') {
                                continue;
                            }
                            ?>
                            <h2 class="mt-4 links_group_title"><?php echo htmlspecialchars((string) $label, ENT_QUOTES, 'UTF-8'); ?></h2>
                            <div class="links_grid">
                                <?php echo $linksHtml; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if ($this->allow('comment')): ?>
            <?php $this->need('comments.php'); ?>
        <?php endif; ?>
    </article>

    <?php $this->need('sidebar.php'); ?>
</div>

<?php $this->need('footer.php'); ?>

