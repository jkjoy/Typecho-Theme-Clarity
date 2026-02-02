<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

</main>

<?php $this->need('assets/symbols.svg'); ?>

<footer class="footer">
    <div class="footer_inner wrap pale">
        <img src="<?php $this->options->themeUrl('assets/icons/apple-touch-icon.png'); ?>" class="icon icon_2 transparent" alt="<?php $this->options->title(); ?>">
        <p>Copyright <span class="year"></span> <?php $this->options->title(); ?>. All rights reserved.</p>
        <?php if (!empty($this->options->customFooterHtml)):echo (string) $this->options->customFooterHtml;endif; ?>
        <a class="to_top" href="#documentTop">
            <?php clarity_sprite('to-top'); ?>
        </a>
    </div>
</footer>

<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/highlight.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/variables.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/functions.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/code.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('assets/js/index.js'); ?>"></script>
<?php $this->footer(); ?>
</body>
</html>
