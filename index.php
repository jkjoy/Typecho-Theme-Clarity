<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; 
/** 
* Clarity主题 for Typecho
* 移植自Hugo主题 Clarity
* @package Clarity
* @author 老孙
* @version 1.0.0
* @link https://www.imsun.org
*/
?>
<?php $this->need('header.php'); ?>

	<div class="grid-inverse wrap content">
	    <div>
	        <ul class="posts" id="posts">
	            <?php
	            $stickyCidSet = [];
	            $rendered = false;

	            if ($this->is('index')) {
	                $stickyCids = clarity_get_sticky_post_cids();
	                if (!empty($stickyCids)) {
	                    $stickyCidSet = array_fill_keys($stickyCids, true);

	                    $pageSize = (int) ($this->parameter->pageSize ?? 0);
	                    if ($pageSize <= 0) {
	                        $pageSize = 10;
	                    }

	                    $currentPage = (int) $this->getCurrentPage();
	                    if ($currentPage <= 0) {
	                        $currentPage = 1;
	                    }

	                    $start = ($currentPage - 1) * $pageSize;
	                    $stickyCount = count($stickyCids);

	                    $stickyLimit = 0;
	                    $stickyOffset = 0;
	                    if ($start < $stickyCount) {
	                        $stickyOffset = $start;
	                        $stickyLimit = min($pageSize, $stickyCount - $start);
	                    }

	                    $nonStickyLimit = $pageSize - $stickyLimit;
	                    $nonStickyOffset = max(0, $start - $stickyCount);

	                    try {
	                        $db = \Typecho\Db::get();

	                        if ($stickyLimit > 0) {
	                            $stickyQuery = $db->select(
	                                'table.contents.cid',
	                                'table.contents.title',
	                                'table.contents.slug',
	                                'table.contents.created',
	                                'table.contents.modified',
	                                'table.contents.text',
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
	                                ->join('table.fields', 'table.fields.cid = table.contents.cid')
	                                ->where('table.contents.status = ?', 'publish')
	                                ->where('table.contents.created < ?', $this->options->time)
	                                ->where('table.contents.type = ?', 'post')
	                                ->where('table.fields.name = ?', 'sticky')
	                                ->where('table.fields.str_value = ? OR table.fields.int_value = ?', 'true', 1)
	                                ->order('table.contents.created', \Typecho\Db::SORT_DESC)
	                                ->limit($stickyLimit)
	                                ->offset($stickyOffset);

	                            \Widget\Contents\From::allocWithAlias('clarity_sticky', ['query' => $stickyQuery])->to($stickyPosts);
	                            while ($stickyPosts->next()) {
	                                clarity_render_excerpt($stickyPosts, true);
	                            }
	                        }

	                        if ($nonStickyLimit > 0) {
	                            $normalQuery = $db->select(
	                                'table.contents.cid',
	                                'table.contents.title',
	                                'table.contents.slug',
	                                'table.contents.created',
	                                'table.contents.modified',
	                                'table.contents.text',
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
	                                ->join(
	                                    'table.fields AS sticky_field',
	                                    "sticky_field.cid = table.contents.cid AND sticky_field.name = 'sticky'",
	                                    \Typecho\Db::LEFT_JOIN
	                                )
	                                ->where('table.contents.status = ?', 'publish')
	                                ->where('table.contents.created < ?', $this->options->time)
	                                ->where('table.contents.type = ?', 'post')
	                                ->where("(sticky_field.str_value IS NULL OR sticky_field.str_value != 'true')")
	                                ->where('(sticky_field.int_value IS NULL OR sticky_field.int_value != ?)', 1)
	                                ->order('table.contents.created', \Typecho\Db::SORT_DESC)
	                                ->limit($nonStickyLimit)
	                                ->offset($nonStickyOffset);

	                            \Widget\Contents\From::allocWithAlias('clarity_index', ['query' => $normalQuery])->to($normalPosts);
	                            while ($normalPosts->next()) {
	                                clarity_render_excerpt($normalPosts);
	                            }
	                        }

	                        $rendered = true;
	                    } catch (\Throwable $e) {
	                        $rendered = false;
	                    }
	                }
	            }

	            if (!$rendered) {
	                while ($this->next()) {
	                    $isSticky = !empty($stickyCidSet) && isset($stickyCidSet[(int) $this->cid]);
	                    clarity_render_excerpt($this, $isSticky);
	                }
	            }
	            ?>
	        </ul>
            <?php if ($this->have() && $this->getTotal() <= $this->parameter->pageSize): ?>
                <ul class="pagination">
                    <li class="page-item active">
                        <a href="<?php echo htmlspecialchars((string) $this->archiveUrl, ENT_QUOTES, 'UTF-8'); ?>">1</a>
                    </li>
                </ul>
            <?php else: ?>
                <?php $this->pageNav('&laquo;', '&raquo;', 3, '...', [
                    'wrapTag' => 'ul',
                    'wrapClass' => 'pagination',
                    'itemTag' => 'li',
                    'currentClass' => 'page-item active',
                    'prevClass' => 'page-item prev',
                    'nextClass' => 'page-item next',
                    'textTag' => 'span',
                ]); ?>
            <?php endif; ?>
	    </div>
	    <?php $this->need('sidebar.php'); ?>
	</div>

<?php $this->need('footer.php'); ?>
