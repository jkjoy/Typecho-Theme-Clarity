<?php
/**
 * 文章归档
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<?php $this->need('header.php'); ?>

<div class="grid-inverse wrap content">
    <article class="post_content archives_page">
        <h1 class="post_title"><?php $this->title(); ?></h1>

        <div class="post_body">
            <?php $this->content(); ?>

            <?php
            try {
                $db = \Typecho\Db::get();
                $query = $db->select(
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
                    ->from('table.contents')
                    ->where('table.contents.status = ?', 'publish')
                    ->where('table.contents.created < ?', $this->options->time)
                    ->where('table.contents.type = ?', 'post')
                    ->order('table.contents.created', \Typecho\Db::SORT_DESC);

                \Widget\Contents\From::allocWithAlias('clarity_archives', ['query' => $query])->to($posts);
            } catch (\Throwable $e) {
                $posts = null;
            }
            ?>

            <?php if (!$posts || !$posts->have()): ?>
                <p class="notice info"><?php _e('暂无文章可归档。'); ?></p>
            <?php else: ?>
                <div class="archives_wrap">
                    <?php
                    $currentYear = null;
                    $currentMonth = null;
                    $openedYear = false;
                    $openedMonth = false;
                    ?>

                    <?php while ($posts->next()): ?>
                        <?php
                        $year = (int) date('Y', (int) $posts->created);
                        $month = (int) date('n', (int) $posts->created);
                        $day = (int) date('j', (int) $posts->created);

                        if ($currentYear === null || $year !== $currentYear) {
                            if ($openedMonth) {
                                echo '</ul>';
                                $openedMonth = false;
                            }
                            if ($openedYear) {
                                echo '</div>';
                            }

                            $currentYear = $year;
                            $currentMonth = null;
                            $openedYear = true;

                            echo '<div class="archives_year">';
                            echo '<h2 class="mt-4 archives_year_title">' . htmlspecialchars((string) $currentYear, ENT_QUOTES, 'UTF-8') . '年 </h2>';
                        }

                        if ($currentMonth === null || $month !== $currentMonth) {
                            if ($openedMonth) {
                                echo '</ul>';
                            }

                            $currentMonth = $month;
                            $openedMonth = true;

                            echo '<h3 class="archives_month_title">' . htmlspecialchars(sprintf('%02d', $currentMonth), ENT_QUOTES, 'UTF-8') . '月 </h3>';
                            echo '<ul class="archives_list">';
                        }
                        ?>

                        <li class="archives_item">
                            <a class="archives_link" href="<?php $posts->permalink(); ?>" title="<?php $posts->title(); ?>">
                                <span class="archives_date"><?php echo htmlspecialchars(sprintf('%02d-%02d', $month, $day), ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="archives_title"><?php $posts->title(); ?></span>
                            </a>
                        </li>
                    <?php endwhile; ?>

                    <?php
                    if ($openedMonth) {
                        echo '</ul>';
                    }
                    if ($openedYear) {
                        echo '</div>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($this->allow('comment')): ?>
            <?php $this->need('comments.php'); ?>
        <?php endif; ?>
    </article>

    <?php $this->need('sidebar.php'); ?>
</div>

<?php $this->need('footer.php'); ?>

