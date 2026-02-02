<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<div class="post_comments">
    <?php if ($this->allow('comment')): ?>
        <?php $this->comments()->to($comments); ?>

        <?php if ($comments->have()): ?>
            <h2 class="mt-4"><?php $this->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('%d 条评论')); ?></h2>
            <?php $comments->listComments([
                'before' => '<ol class="comment-thread">',
                'after' => '</ol>',
                'avatarSize' => 48,
                'replyWord' => _t('回复'),
            ]); ?>
            <?php $comments->pageNav('&laquo;', '&raquo;'); ?>
        <?php endif; ?>

        <div id="<?php $this->respondId(); ?>" class="comment_respond mt-4">
            <div class="comment_card comment_form_card">
                <div class="comment_form_header">
                    <h3 class="comment_form_title" id="response"><?php _e('添加新评论'); ?></h3>
                    <div class="comment_form_cancel">
                        <?php $comments->cancelReply(); ?>
                    </div>
                </div>

                <form method="post" action="<?php $this->commentUrl(); ?>" id="comment-form" class="comment_form" role="form">
                    <?php if ($this->user->hasLogin()): ?>
                        <div class="comment_form_login">
                            <?php _e('登录身份'); ?>:
                            <a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>
                            ·
                            <a href="<?php $this->options->logoutUrl(); ?>" title="<?php _e('退出'); ?>"><?php _e('退出'); ?></a>
                        </div>
                    <?php else: ?>
                        <div class="comment_form_grid">
                            <div class="comment_field">
                                <label for="author" class="required"><?php _e('称呼'); ?></label>
                                <input type="text" name="author" id="author" class="comment_input" value="<?php $this->remember('author'); ?>" required />
                            </div>
                            <div class="comment_field">
                                <label for="mail"<?php if ($this->options->commentsRequireMail): ?> class="required"<?php endif; ?>><?php _e('Email'); ?></label>
                                <input type="email" name="mail" id="mail" class="comment_input" value="<?php $this->remember('mail'); ?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?> />
                            </div>
                            <div class="comment_field">
                                <label for="url"<?php if ($this->options->commentsRequireUrl): ?> class="required"<?php endif; ?>><?php _e('网站'); ?></label>
                                <input type="url" name="url" id="url" class="comment_input" placeholder="<?php _e('https://'); ?>" value="<?php $this->remember('url'); ?>"<?php if ($this->options->commentsRequireUrl): ?> required<?php endif; ?> />
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="comment_field">
                        <label for="textarea" class="required"><?php _e('内容'); ?></label>
                        <textarea rows="6" cols="50" name="text" id="textarea" class="comment_textarea" required><?php $this->remember('text'); ?></textarea>
                    </div>

                    <div class="comment_form_submit">
                        <button type="submit" class="button"><?php _e('提交评论'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
