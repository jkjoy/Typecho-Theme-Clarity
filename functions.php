<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function themeConfig($form)
{
    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        null,
        _t('LOGO 图片地址'),
        _t('用于顶部导航栏的 LOGO；留空则显示站点标题。')
    );
    $form->addInput($logoUrl->addRule('url', _t('请填写一个合法的URL地址')));

    $navItems = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'navItems',
        null,
        '',
        _t('导航菜单（可选）'),
        _t("每行一个：标题|链接\n留空则自动列出「首页 + 所有独立页面」。")
    );
    $form->addInput($navItems);

    $socialLinks = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'socialLinks',
        null,
        '',
        _t('社交链接（可选）'),
        _t("每行一个：icon|url\nicon 必须是图标名（例如：github / twitter / mail / rss / mastodon / discord / bluesky / ko-fi）。")
    );
    $form->addInput($socialLinks);

    $introTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'introTitle',
        null,
        '',
        _t('侧边栏作者标题（可选）'),
        _t('例如你的名字或站点名。')
    );
    $form->addInput($introTitle);

    $introDescription = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'introDescription',
        null,
        '',
        _t('侧边栏简介（可选）'),
        _t('支持 HTML（建议仅使用安全标签）。')
    );
    $form->addInput($introDescription);

    $introUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'introUrl',
        null,
        '',
        _t('侧边栏按钮链接（可选）'),
        _t('例如：/about.html 或 https://example.com/about')
    );
    $form->addInput($introUrl);

    $enforceLightMode = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'enforceLightMode',
        ['on' => _t('强制浅色（禁用暗色）')],
        [],
        _t('颜色模式')
    );
    $form->addInput($enforceLightMode);

    $enforceDarkMode = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'enforceDarkMode',
        ['on' => _t('强制深色（禁用浅色）')],
        [],
        _t(' ')
    );
    $form->addInput($enforceDarkMode);

    $maxCodeLines = new \Typecho\Widget\Helper\Form\Element\Text(
        'maxCodeLines',
        null,
        '120',
        _t('代码块折叠行数（JS）'),
        _t('超过该行数会显示展开控制。')
    );
    $form->addInput($maxCodeLines);

    $showCodeLines = new \Typecho\Widget\Helper\Form\Element\Radio(
        'showCodeLines',
        ['true' => _t('显示'), 'false' => _t('隐藏')],
        'true',
        _t('代码块行号（JS）')
    );
    $form->addInput($showCodeLines);

    $popularPostsLimit = new \Typecho\Widget\Helper\Form\Element\Text(
        'popularPostsLimit',
        null,
        '5',
        _t('侧边栏热门文章数量'),
        _t('按评论数降序排列。默认 5。')
    );
    $form->addInput($popularPostsLimit);

    $tagsLimit = new \Typecho\Widget\Helper\Form\Element\Text(
        'tagsLimit',
        null,
        '20',
        _t('侧边栏 Tags 数量'),
        _t('标签云显示数量。默认 20。')
    );
    $form->addInput($tagsLimit);

    $sidebarWidgets = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'sidebarWidgets',
        [
            'search' => _t('搜索'),
            'intro' => _t('侧边栏简介'),
            'toc' => _t('目录（文章页）'),
            'recent' => _t('最近文章'),
            'popular' => _t('热门文章'),
            'categories' => _t('分类'),
            'tags' => _t('标签'),
        ],
        ['search', 'intro', 'toc', 'recent', 'popular', 'categories', 'tags'],
        _t('侧边栏组件'),
        _t('勾选需要显示的模块；不勾选则不显示。')
    );
    $form->addInput($sidebarWidgets);

    $customHeadHtml = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'customHeadHtml',
        null,
        '',
        _t('自定义 Head HTML'),
        _t('插入到页面 <head> 内（不做转义，请自行确保安全）。')
    );
    $form->addInput($customHeadHtml);

    $customFooterHtml = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'customFooterHtml',
        null,
        '',
        _t('自定义 Footer 上方 HTML'),
        _t('插入到页面 footer 区块之前（不做转义，请自行确保安全）。')
    );
    $form->addInput($customFooterHtml);
}

function themeFields($layout)
{
    $sticky = new \Typecho\Widget\Helper\Form\Element\Radio(
        'sticky',
        ['false' => _t('否'), 'true' => _t('是')],
        'false',
        _t('置顶文章'),
        _t('在首页文章列表中置顶显示，并影响分页顺序。')
    );
    $layout->addItem($sticky);
}

function clarity_parse_lines($raw)
{
    $raw = (string) $raw;
    $lines = preg_split("/\\r\\n|\\r|\\n/", $raw);
    $result = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        $result[] = $line;
    }

    return $result;
}

function clarity_sprite($icon, $title = null)
{
    $icon = trim((string) $icon);
    if ($icon === '') {
        return;
    }
    ?>
    <svg class="icon">
        <?php if ($title !== null && $title !== ''): ?>
            <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
        <?php else: ?>
            <title><?php echo htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?></title>
        <?php endif; ?>
        <use xlink:href="#<?php echo htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?>"></use>
    </svg>
    <?php
}

function clarity_options($archive = null)
{
    if (is_object($archive) && isset($archive->options) && is_object($archive->options)) {
        return $archive->options;
    }

    if (class_exists('\\Widget\\Options') && method_exists('\\Widget\\Options', 'alloc')) {
        return \Widget\Options::alloc();
    }

    if (class_exists('Typecho_Widget')) {
        return \Typecho_Widget::widget('Widget_Options');
    }

    return null;
}

function clarity_nav($archive)
{
    $options = clarity_options($archive);
    $custom = ($options && isset($options->navItems)) ? (string) $options->navItems : '';
    $items = clarity_parse_lines($custom);

    if (!empty($items)) {
        foreach ($items as $line) {
            $parts = array_map('trim', explode('|', $line, 2));
            $name = $parts[0] ?? '';
            $url = $parts[1] ?? '';
            if ($name === '' || $url === '') {
                continue;
            }
            ?>
            <div class="nav_parent">
                <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" class="nav_item" title="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </div>
            <?php
        }
        return;
    }

    ?>
    <div class="nav_parent<?php if ($archive->is('index')): ?> nav_active<?php endif; ?>">
        <a href="<?php if ($options) { $options->siteUrl(); } else { echo '/'; } ?>" class="nav_item" title="<?php _e('首页'); ?>"><?php _e('首页'); ?></a>
    </div>
    <?php \Widget\Contents\Page\Rows::alloc()->to($pages); ?>
    <?php while ($pages->next()): ?>
        <div class="nav_parent<?php if ($archive->is('page', $pages->slug)): ?> nav_active<?php endif; ?>">
            <a href="<?php $pages->permalink(); ?>" class="nav_item" title="<?php $pages->title(); ?>"><?php $pages->title(); ?></a>
        </div>
    <?php endwhile;
}

function clarity_follow($archive)
{
    $options = clarity_options($archive);
    $raw = ($options && isset($options->socialLinks)) ? (string) $options->socialLinks : '';
    $items = clarity_parse_lines($raw);

    if (empty($items)) {
        ?>
        <div class="follow">
            <div class="color_mode">
                <input type="checkbox" class="color_choice" id="mode">
            </div>
        </div>
        <?php
        return;
    }

    ?>
    <div class="follow">
        <?php foreach ($items as $line):
            $parts = array_map('trim', explode('|', $line, 2));
            $icon = $parts[0] ?? '';
            $url = $parts[1] ?? '';
            if ($icon === '' || $url === '') {
                continue;
            }
            ?>
            <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>">
                <?php clarity_sprite($icon); ?>
            </a>
        <?php endforeach; ?>
        <div class="color_mode">
            <input type="checkbox" class="color_choice" id="mode">
        </div>
    </div>
    <?php
}

function clarity_post_meta(\Widget\Base\Contents $archive, $withShare = false)
{
    $readingMinutes = clarity_get_reading_time_minutes($archive);
    ?>
    <div class="post_meta">
        <span><?php clarity_sprite('calendar'); ?></span>
        <span class="post_date">
            <time datetime="<?php $archive->date('c'); ?>"><?php $archive->date('M j, Y'); ?></time>
        </span>
        <span class="post_meta_sep">&nbsp;·</span>
        <span><?php //clarity_sprite('clock'); ?>&nbsp;</span>
        <span class="post_reading_time">
            <?php echo htmlspecialchars(clarity_format_reading_time($readingMinutes), ENT_QUOTES, 'UTF-8'); ?>
        </span>
        <?php if (!empty($archive->tags)): ?>
            <span class="post_meta_sep">&nbsp;·</span>
            <span class="post_tags">
                <?php foreach ($archive->tags as $tag): ?>
                    <a href="<?php echo htmlspecialchars($tag['permalink'], ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars($tag['name'], ENT_QUOTES, 'UTF-8'); ?>" class="post_tag button button_translucent">
                        <?php echo htmlspecialchars($tag['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                <?php endforeach; ?>
            </span>
        <?php endif; ?>

        <?php if ($withShare): ?>
            <span class="page_only">&nbsp;·
                <div class="post_share">
                    <?php _e('Share on'); ?>:
                    <a href="https://twitter.com/intent/tweet?text=<?php echo rawurlencode($archive->title); ?>&url=<?php echo rawurlencode($archive->permalink); ?>&tw_p=tweetbutton" class="twitter" title="Share on Twitter" target="_blank" rel="nofollow noopener">
                        <?php clarity_sprite('twitter'); ?>
                    </a>
                    <a href="https://www.facebook.com/sharer.php?u=<?php echo rawurlencode($archive->permalink); ?>&t=<?php echo rawurlencode($archive->title); ?>" class="facebook" title="Share on Facebook" target="_blank" rel="nofollow noopener">
                        <?php clarity_sprite('facebook'); ?>
                    </a>
                    <a href="#linkedinshare" id="linkedinshare" class="linkedin" title="Share on LinkedIn" rel="nofollow">
                        <?php clarity_sprite('linkedin'); ?>
                    </a>
                    <a href="<?php $archive->permalink(); ?>" title="Copy Link" class="link link_yank">
                        <?php clarity_sprite('copy'); ?>
                    </a>
                </div>
            </span>
        <?php endif; ?>
    </div>
    <?php
}

function clarity_get_reading_time_minutes(\Widget\Base\Contents $archive): int
{
    static $cache = [];

    $cid = 0;
    if (isset($archive->cid)) {
        $cid = (int) $archive->cid;
    }

    if ($cid > 0 && isset($cache[$cid])) {
        return $cache[$cid];
    }

    $raw = '';
    if (isset($archive->text)) {
        $raw = (string) $archive->text;
    }

    $minutes = clarity_estimate_reading_time_minutes_from_text($raw);

    if ($cid > 0) {
        $cache[$cid] = $minutes;
    }

    return $minutes;
}

function clarity_estimate_reading_time_minutes_from_text(string $raw): int
{
    $raw = html_entity_decode($raw, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $raw = preg_replace('#<pre\\b[^>]*>.*?</pre>#si', ' ', $raw);
    $text = strip_tags((string) $raw);
    $text = trim(preg_replace('/\\s+/u', ' ', $text));

    if ($text === '') {
        return 1;
    }

    $cjkCount = 0;
    $wordCount = 0;

    $cjkPattern = '/[\\p{Han}\\p{Hiragana}\\p{Katakana}\\p{Hangul}]/u';
    $wordPattern = '/[A-Za-z0-9]+(?:[\\\'’\\-][A-Za-z0-9]+)*/u';

    $cjkMatchCount = @preg_match_all($cjkPattern, $text, $m1);
    if (is_int($cjkMatchCount) && $cjkMatchCount > 0) {
        $cjkCount = $cjkMatchCount;
    }

    $nonCjk = @preg_replace($cjkPattern, ' ', $text);
    if (!is_string($nonCjk)) {
        $nonCjk = $text;
    }

    $wordMatchCount = @preg_match_all($wordPattern, $nonCjk, $m2);
    if (is_int($wordMatchCount) && $wordMatchCount > 0) {
        $wordCount = $wordMatchCount;
    }

    $minutesByCjk = $cjkCount / 500;
    $minutesByWords = $wordCount / 200;
    $minutes = (int) ceil(max($minutesByCjk, $minutesByWords));

    return max(1, $minutes);
}

function clarity_format_reading_time(int $minutes): string
{
    $minutes = max(1, (int) $minutes);
    if ($minutes <= 1) {
        return _t('约 1 分钟阅读');
    }

    return _t('约 %d 分钟阅读', $minutes);
}

function clarity_render_excerpt(\Widget\Base\Contents $archive, bool $isSticky = false): void
{
    ?>
    <li class="post_item">
        <div class="excerpt">
            <div class="excerpt_header">
                <h3 class="post_link">
                    <a href="<?php $archive->permalink(); ?>" title="<?php $archive->title(); ?>">
                        <?php $archive->title(); ?>
                        <?php if ($isSticky): ?>
                            <span class="post_sticky_badge"><?php _e('[置顶]'); ?></span>
                        <?php endif; ?>
                    </a>
                </h3>
                <?php clarity_post_meta($archive); ?>
            </div>
            <div class="excerpt_footer">
                <div class="pale">
                    <p><?php $archive->excerpt(200, '...'); ?></p>
                    <br>
                    <a href="<?php $archive->permalink(); ?>" title="<?php _e('阅读全文'); ?>" class="excerpt_more button"><?php _e('阅读全文'); ?></a>
                </div>
            </div>
        </div>
    </li>
    <?php
}

function clarity_is_truthy($value): bool
{
    if (is_bool($value)) {
        return $value;
    }

    if (is_int($value) || is_float($value)) {
        return (int) $value !== 0;
    }

    $value = strtolower(trim((string) $value));
    return in_array($value, ['1', 'true', 'yes', 'on'], true);
}

function clarity_get_sticky_post_cids(): array
{
    static $cache = null;
    if (is_array($cache)) {
        return $cache;
    }

    try {
        $db = \Typecho\Db::get();
    } catch (\Throwable $e) {
        $cache = [];
        return $cache;
    }

    $rows = $db->fetchAll(
        $db->select('table.contents.cid')
            ->from('table.contents')
            ->join('table.fields', 'table.fields.cid = table.contents.cid')
            ->where('table.contents.type = ?', 'post')
            ->where('table.contents.status = ?', 'publish')
            ->where('table.fields.name = ?', 'sticky')
            ->where('table.fields.str_value = ? OR table.fields.int_value = ?', 'true', 1)
            ->order('table.contents.created', \Typecho\Db::SORT_DESC)
    );

    $cids = [];
    foreach ($rows as $row) {
        if (isset($row['cid'])) {
            $cids[] = (int) $row['cid'];
        }
    }

    $cache = array_values(array_unique($cids));
    return $cache;
}

function clarity_db()
{
    if (class_exists('\\Typecho\\Db') && method_exists('\\Typecho\\Db', 'get')) {
        return \Typecho\Db::get();
    }
    if (class_exists('Typecho_Db') && method_exists('Typecho_Db', 'get')) {
        return \Typecho_Db::get();
    }
    return null;
}

function clarity_widget_instance(string $alias, $params = null, $request = null)
{
    if (class_exists('\\Typecho\\Widget')) {
        return \Typecho\Widget::widget($alias, $params, $request);
    }
    if (class_exists('Typecho_Widget')) {
        return \Typecho_Widget::widget($alias, $params, $request);
    }
    return null;
}

function clarity_contents_from($alias, $query)
{
    if (class_exists('\\Widget\\Contents\\From')) {
        if ($alias !== null && $alias !== '') {
            return \Widget\Contents\From::allocWithAlias($alias, ['query' => $query]);
        }
        return \Widget\Contents\From::alloc(['query' => $query]);
    }

    if (class_exists('Widget_Contents_From')) {
        if ($alias !== null && $alias !== '') {
            return \Widget_Contents_From::allocWithAlias($alias, ['query' => $query]);
        }
        return \Widget_Contents_From::alloc(['query' => $query]);
    }

    return clarity_query_iterator_from_query($query, $alias);
}

function clarity_query_iterator_from_query($query, $aliasPrefix = null)
{
    $db = clarity_db();
    if (!$db) {
        return null;
    }

    try {
        $rows = $db->fetchAll($query);
    } catch (\Throwable $e) {
        return null;
    }

    $prefix = $aliasPrefix ? (string) $aliasPrefix : 'clarity_query';
    return new Clarity_Query_Iterator($rows, $prefix);
}

function clarity_widget_from_row($row, string $alias)
{
    $cid = 0;
    if (is_array($row) && isset($row['cid'])) {
        $cid = (int) $row['cid'];
    } elseif (is_object($row) && isset($row->cid)) {
        $cid = (int) $row->cid;
    }

    if ($cid > 0) {
        $widget = clarity_widget_instance('Widget_Archive@' . $alias, 'type=post', 'cid=' . $cid);
        if ($widget && method_exists($widget, 'have') && $widget->have()) {
            $widget->next();
            return $widget;
        }
    }

    return is_array($row) ? (object) $row : $row;
}

if (!class_exists('Clarity_Query_Iterator')) {
    class Clarity_Query_Iterator
    {
        private $rows = [];
        private $index = 0;
        private $current = null;
        private $aliasPrefix = '';

        public function __construct(array $rows, $aliasPrefix)
        {
            $this->rows = array_values($rows);
            $this->aliasPrefix = (string) $aliasPrefix;
        }

        public function have()
        {
            return !empty($this->rows);
        }

        public function next()
        {
            if ($this->index >= count($this->rows)) {
                $this->current = null;
                $this->index = 0;
                return false;
            }

            $row = $this->rows[$this->index++];
            $this->current = clarity_widget_from_row($row, $this->aliasPrefix . '_' . $this->index);
            return $this->current;
        }

        public function __get($name)
        {
            if (is_object($this->current)) {
                return $this->current->{$name} ?? null;
            }
            if (is_array($this->current)) {
                return $this->current[$name] ?? null;
            }
            return null;
        }

        public function __call($name, $args)
        {
            if (is_object($this->current) && method_exists($this->current, $name)) {
                return $this->current->{$name}(...$args);
            }

            $value = null;
            if (is_object($this->current) && isset($this->current->{$name})) {
                $value = $this->current->{$name};
            } elseif (is_array($this->current) && isset($this->current[$name])) {
                $value = $this->current[$name];
            }

            if ($value !== null) {
                echo $value;
            }

            return null;
        }

        public function __isset($name)
        {
            if (is_object($this->current)) {
                return isset($this->current->{$name});
            }
            if (is_array($this->current)) {
                return isset($this->current[$name]);
            }
            return false;
        }
    }
}

if (!function_exists('threadedComments')) {
    function threadedComments($comments, $options)
    {
        if (!is_object($comments)) {
            return;
        }

        $commentClass = 'comment_item';

        if (!empty($comments->authorId)) {
            if ($comments->authorId == $comments->ownerId) {
                $commentClass .= ' comment-by-author';
            } else {
                $commentClass .= ' comment-by-user';
            }
        }

        if ($comments->levels > 0) {
            $commentClass .= ' comment-child';
        } else {
            $commentClass .= ' comment-parent';
        }
        ?>
        <li id="<?php $comments->theId(); ?>" class="<?php echo $commentClass; ?>">
            <div class="comment_card">
                <div class="comment_header">
                    <div class="comment_avatar">
                        <?php
                        $shouldShowAvatar = true;
                        if (isset($comments->options) && is_object($comments->options) && isset($comments->options->commentsAvatar)) {
                            $shouldShowAvatar = (bool) $comments->options->commentsAvatar;
                        }
                        ?>
                        <?php if ($shouldShowAvatar && 'comment' == $comments->type): ?>
                            <?php
                            $size = isset($options->avatarSize) ? (int) $options->avatarSize : 48;
                            if ($size <= 0) {
                                $size = 48;
                            }
                            $rating = (string) ($comments->options->commentsAvatarRating ?? 'X');
                            $defaultAvatar = $options->defaultAvatar ?? null;
                            $secure = false;
                            if (isset($comments->request) && is_object($comments->request) && method_exists($comments->request, 'isSecure')) {
                                $secure = (bool) $comments->request->isSecure();
                            }

                            $url = \Typecho\Common::gravatarUrl($comments->mail, $size, $rating, $defaultAvatar, $secure);
                            $srcset = '';
                            if (!empty($options->avatarHighRes)) {
                                $url2x = \Typecho\Common::gravatarUrl($comments->mail, $size * 2, $rating, $defaultAvatar, $secure);
                                $url3x = \Typecho\Common::gravatarUrl($comments->mail, $size * 3, $rating, $defaultAvatar, $secure);
                                $srcset = ' srcset="' . htmlspecialchars($url2x, ENT_QUOTES, 'UTF-8') . ' 2x, ' . htmlspecialchars($url3x, ENT_QUOTES, 'UTF-8') . ' 3x"';
                            }
                            ?>
                            <img class="avatar" loading="lazy" src="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $srcset; ?> alt="" width="<?php echo $size; ?>" height="<?php echo $size; ?>" />
                        <?php endif; ?>
                    </div>
                    <div class="comment_meta">
                        <div class="comment_meta_line">
                            <span class="comment_author"><?php $comments->author(); ?></span>
                            <?php if (!empty($comments->authorId) && $comments->authorId == $comments->ownerId): ?>
                                <span class="comment_badge">OP</span>
                            <?php endif; ?>
                            <span class="comment_sep">·</span>
                            <span class="comment_time">
                                <time datetime="<?php $comments->date('c'); ?>">
                                    <?php $comments->date($options->dateFormat); ?>
                                </time>
                            </span>
                            <?php if ('approved' !== $comments->status): ?>
                                <em class="comment_awaiting"><?php $options->commentStatus(); ?></em>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="comment_body">
                    <?php $comments->content(); ?>
                </div>

                <div class="comment_actions">
                    <?php $comments->reply($options->replyWord); ?>
                </div>

                <?php if ($comments->children): ?>
                    <div class="comment_children">
                        <?php $comments->threadedComments(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </li>
        <?php
    }
}
