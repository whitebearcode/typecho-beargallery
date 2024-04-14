<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<footer class="bgallery_fl_footer">
    </footer>
</div>

<a class="totop" href="#"><i class="ri-arrow-up-double-fill"></i></a>
</div>

<script>
    $(function(){
          Fancybox.bind('[data-fancybox]');
          const lazyLoad = new LazyLoad({
		elements_selector: "img.lazy",
	});
})
</script>
<script src="<?php $this->options->themeUrl('assets/plugins/lazyload/lazyload.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/plugins/pjax/jquery.pjax.min.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/plugins/nprogress/nprogress.min.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/flexslider.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/resizesensor.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/sticky-sidebar.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/scroll.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/init.js?v=2'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/plugins/fancybox/fancybox.umd.min.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/plugins/instant.page/instantpage.min.js'); ?>"></script>


</body>
</html>