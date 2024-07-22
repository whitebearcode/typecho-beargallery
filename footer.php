<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<footer class="bgallery_fl_footer">
     
                <div class="bgallery_fl_copyright">
                	<span class="cright">&copy; <?php echo date("Y");?> <span class="autor"><a class="autor" href="<?php $this->options->siteUrl();?>"><?php $this->options->title();?></a></span> All right Reserved.  <br><?php _e('Powered by <span class="autor"><a href="http://www.typecho.org">Typecho</a> & <a href="https://github.com/whitebearcode/typecho-beargallery"> BearGallery</a></span>  '); ?><br>
     <?php if (General::Options('PoliceBa')): ?><img src="<?php $this->options->themeUrl('assets/images/beian.png');?>"> <span class="autor"><a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=<?php echo General::parseNumber(General::Options('PoliceBa')); ?>"><?php echo General::Options('PoliceBa'); ?></a></span><?php endif; ?> <?php if (General::Options('IcpBa')): ?><img src="<?php $this->options->themeUrl('assets/images/icp.png');?>"> <span class="autor"><a href="https://beian.miit.gov.cn/"><?php echo General::Options('IcpBa'); ?></a></span><?php endif; ?></span>
     <?php if(!empty(General::Options('CustomizationFooterCode'))): ?>
     <br><?php echo General::Options('CustomizationFooterCode');?>
     <?php endif;?>
                </div>
    </footer>
</div>

<a class="totop" href="#"><i class="ri-arrow-up-double-fill"></i></a>
</div>

<script>
    $(function(){
          Fancybox.bind('[data-fancybox]');
         $('img.lazyload')
  .visibility({
    type       : 'image',
    transition : 'fade in',
    duration   : 1000,
  });

})
</script>
<script src="<?php $this->options->themeUrl('assets/plugins/lazyload/transition.min.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/plugins/lazyload/visibility.min.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/plugins/pjax/jquery.pjax.min.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/plugins/nprogress/nprogress.min.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/flexslider.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/resizesensor.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/sticky-sidebar.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/scroll.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/js/init.js?v=2'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/plugins/fancybox/fancybox.umd.min.js'); ?>"></script>


</body>
</html>