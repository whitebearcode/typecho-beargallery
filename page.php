<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
 <div class="bgallery_fl_main_content_wrap">
				
				<div class="bgallery_fl_gallery_single">
					<div class="container">
					<div>
						<div class="bgallery_fl_about">
							
							<div class="about_us sticky_sidebar">
								<div class="about_us_in">
									<h2><span style="font-weight:bold;letter-spacing:3px;"><?php $this->title() ?></span></h2>
								<?php echo Parse::ParseContent($this->content);?>
								</div>
							</div>
						</div>
					</div>
					</div>
				</div>
				
			</div>
        </div>

<?php $this->need('footer.php'); ?>