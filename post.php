<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div id="dialog-content" style="display:none;max-width:500px;">
        <h2 style="color:#000;text-align:center">微信分享二维码</h2>
        <p>
            <canvas id="wechatQrcode"></canvas>
        </p>
      </div>
            <div class="bgallery_fl_main_content_wrap">
            
				<div class="bgallery_fl_gallery_single">
					<div class="container">
					<div>
						<div class="bgallery_fl_gallery_single_in">
							<div class="title_holder sticky_sidebar">
								<h2><span style="font-weight:bold;letter-spacing:3px;"><?php $this->title() ?></span></h2>
								<span class="category"><?php $this->date('Y年m月d日'); ?> - <?php $this->category(' / ',false); ?></span>
								<div class="ppp">
									<?php echo General::getArticleText($this->content);?>
								</div>
							</div>
							<div class="img_list sticky_sidebar">
                           <?php echo General::getArticleImage($this,'single');?>

								<div class="space100"></div>
								<span class="prev_next"><?php $this->thePrev('上一篇:%s', '上一篇:没有了'); ?> / <?php $this->theNext('下一篇:%s', '下一篇:没有了'); ?></span>
							</div>
						</div>
					</div>
					</div>
				</div>
          	
           	</div>
           	
        </div>
<script>
$(function(){
    var qr = new QRious({
    element:document.getElementById('wechatQrcode'),
    size:300, 	   level:'H',	   value:'<?php echo $this->permalink;?>'
            });
            
    var title=$(document.head).find("[name=title], [name=Title]").attr("content")||document.title;var url=window.location.href;var domain=window.location.origin;var description=$(document.head).find("[name=description], [name=Description]").attr("content")||"";var source=$(document.head).find("[name=site], [name=Site]").attr("content")||document.title;
    $('.share_qzone').on('click',function(){window.open('https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+url+'&title='+title+'&site='+source+'&desc='+description+'&summary='+description+'&pics='+$(this).attr('data-img'));});
    $('.share_weibo').on('click',function(){window.open('https://service.weibo.com/share/share.php?url='+url+'&title='+title+'&pic='+$(this).attr('data-img')+'&appkey=');});
})
    
</script>
<?php $this->need('footer.php'); ?>