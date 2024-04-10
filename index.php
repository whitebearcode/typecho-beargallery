<?php
/**
 * 一款适用于摄影记录的相册主题
 *
 * @package Beargallery
 * @author BearNotion
 * @version v1.0.0
 * @link https://www.bearnotion.ru
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
            <div class="bgallery_fl_main_content_wrap">
				<div class="container">
				    <?php if($this->have()):?>
						<div class="bgallery_fl_gallery_list">
							<?php while ($this->next()): ?>
							<div class="bgallery_fl_gallery_list_in">
								<div class="title_holder">
									<h2><a href="<?php $this->permalink() ?>"><span class="bgallery_post_title"><?php $this->title() ?></span></a></h2>
									<span class="category"><?php $this->date('Y年m月d日'); ?> - <?php $this->category(' / ',false); ?></span>
								</div>
								<ul class="img_list">
								    <?php echo General::getArticleImage($this,'nosingle');?>
									
								</ul>
								<span class="see_more"><a href="<?php $this->permalink() ?>">查看更多</a></span>
							</div>
						<?php endwhile; ?>



							<span class="prev_g">
<?php $this->pageLink('上一页'); ?></span>  <span class="next_g"><?php $this->pageLink('下一页','next'); ?></span>
						</div>

				</div>
			</div>
			<?php else:?>
			<span style="text-align:center">
			 <h2 class="post-title"><?php _e('暂无内容'); ?></h2>
        <p><?php _e('当前页面暂时没有相关内容哦~'); ?></p>
			<svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg"><mask id="a" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="61" y="55" width="222" height="205"><path d="M101.506 259.816h140.7c22.1 0 40-17.9 40-40v-124c0-22.1-17.9-40-40-40h-140.7c-22.1 0-40 17.9-40 40v124c0 22.1 17.9 40 40 40Z" fill="#fff"/></mask><g mask="url(#a)"><path fill-rule="evenodd" clip-rule="evenodd" d="M101.506 259.816h140.7c22.1 0 40-17.9 40-40v-124c0-22.1-17.9-40-40-40h-140.7c-22.1 0-40 17.9-40 40v124c0 22.1 17.9 40 40 40Z" fill="#EFEFEF"/></g><mask id="b" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="61" y="55" width="222" height="205"><path d="M101.506 259.816h140.7c22.1 0 40-17.9 40-40v-124c0-22.1-17.9-40-40-40h-140.7c-22.1 0-40 17.9-40 40v124c0 22.1 17.9 40 40 40Z" fill="#fff"/></mask><g mask="url(#b)"><path fill-rule="evenodd" clip-rule="evenodd" d="M43.506 84.516h246v-29.1h-246v29.1Z" fill="#DCDDDD"/></g><path fill-rule="evenodd" clip-rule="evenodd" d="M103.105 118.816c0-5-4-9-9-9s-9 4-9 9 4 9 9 9 9-4 9-9ZM194.006 157.816l-29.3-48-19.2 31s-18.6-18.3-18.6-20.7c0-2.4-41.9 37.7-41.9 37.7h109Z" fill="#DCDDDD"/><path fill-rule="evenodd" clip-rule="evenodd" d="M102.006 72.016c0-3.5-2.8-6.3-6.3-6.3s-6.3 2.8-6.3 6.3 2.8 6.3 6.3 6.3c3.5.1 6.3-2.8 6.3-6.3ZM122.006 72.016c0-3.5-2.8-6.3-6.3-6.3s-6.3 2.8-6.3 6.3 2.8 6.3 6.3 6.3c3.5.1 6.3-2.8 6.3-6.3ZM142.006 72.016c0-3.5-2.8-6.3-6.3-6.3s-6.3 2.8-6.3 6.3 2.8 6.3 6.3 6.3c3.5.1 6.3-2.8 6.3-6.3Z" fill="#B5B5B6"/><path fill-rule="evenodd" clip-rule="evenodd" d="M87.406 187.316h152v-11.8h-152v11.8ZM87.406 216.716h129v-12h-129v12ZM87.406 237.116h60v-12h-60v12Z" fill="#DCDDDD"/><path fill-rule="evenodd" clip-rule="evenodd" d="m123.105 126.761 10.4-.7 4-18-12 7.6-2.4 11.1Z" fill="#fff"/><path d="m123.105 126.761 10.4-.7 4-18-12 7.6-2.4 11.1Z" stroke="#000" stroke-width="1.5"/><path fill-rule="evenodd" clip-rule="evenodd" d="m120.506 122.96 26.4-8.4 2.4-17.3-31.9-.1 3.1 25.8Z" fill="#CDCBCB"/><path d="m120.506 122.96 26.4-8.4 2.4-17.3-31.9-.1 3.1 25.8Z" stroke="#030303" stroke-width="1.5"/><path fill-rule="evenodd" clip-rule="evenodd" d="M181.706 141.16v26.6l25.6-2.8c-1.1-9.8-4.9-19.3-5.2-28.5l-20.4 4.7Z" fill="#fff"/><path d="M181.706 141.16v26.6l25.6-2.8c-1.1-9.8-4.9-19.3-5.2-28.5l-20.4 4.7Z" stroke="#000" stroke-width="1.5"/><path fill-rule="evenodd" clip-rule="evenodd" d="M131.706 110.06c0-7.1-5.8-12.9-12.9-12.9-7.1 0-12.9 5.8-12.9 12.9 0 7.1 5.8 12.9 12.9 12.9 7.1 0 12.9-5.8 12.9-12.9Z" fill="#D8D8D8"/><path d="M131.706 110.06c0-7.1-5.8-12.9-12.9-12.9-7.1 0-12.9 5.8-12.9 12.9 0 7.1 5.8 12.9 12.9 12.9 7.1 0 12.9-5.8 12.9-12.9Z" stroke="#030303" stroke-width="1.5"/><path fill-rule="evenodd" clip-rule="evenodd" d="M154.906 111.56h8s-3 14.4 2.2 24.5c5.2 10 22.9 15.8 27.8 13.1 4.9-2.7 31.1-22.5 23.4-53.9-7.7-31.4-40.8-36.6-40.8-36.6l-20.6 52.9Z" fill="#fff"/><path d="M154.906 111.56h8s-3 14.4 2.2 24.5c5.2 10 22.9 15.8 27.8 13.1 4.9-2.7 31.1-22.5 23.4-53.9-7.7-31.4-40.8-36.6-40.8-36.6l-20.6 52.9Z" stroke="#000" stroke-width="1.5"/><path fill-rule="evenodd" clip-rule="evenodd" d="m156.406 120.96 36.7-14.6c.3-.1.6-.4.8-.7.6-1.1 2.1-4 2.1-7.3 0-4.2-3.6-7.1-3.6-7.1l-37.6 3.9 1.6 25.8Z" fill="#CDCBCB"/><path d="m156.406 120.96 36.7-14.6c.3-.1.6-.4.8-.7.6-1.1 2.1-4 2.1-7.3 0-4.2-3.6-7.1-3.6-7.1l-37.6 3.9 1.6 25.8Z" stroke="#000" stroke-width="1.5"/><path fill-rule="evenodd" clip-rule="evenodd" d="M167.706 108.06c0-7.1-5.8-12.9-12.9-12.9-7.1 0-12.9 5.8-12.9 12.9 0 7.1 5.8 12.9 12.9 12.9 7.2 0 12.9-5.8 12.9-12.9Z" fill="#D8D8D8"/><path d="M167.706 108.06c0-7.1-5.8-12.9-12.9-12.9-7.1 0-12.9 5.8-12.9 12.9 0 7.1 5.8 12.9 12.9 12.9 7.2 0 12.9-5.8 12.9-12.9Z" stroke="#000" stroke-width="1.5"/><path fill-rule="evenodd" clip-rule="evenodd" d="M126.806 110.061c0-4.4-3.6-8-8-8s-8 3.6-8 8 3.6 8 8 8 8-3.6 8-8ZM162.906 108.061c0-4.4-3.6-8-8-8s-8 3.6-8 8 3.6 8 8 8 8-3.6 8-8Z" fill="#B5B5B6"/><path fill-rule="evenodd" clip-rule="evenodd" d="M167.706 69.76s-4.5.2-8 6.1c-3.6 5.9 3.2 10.7 3.2 10.7s32.2-14.7 42.4-2.2c5.5 6.6-4.9 16.2-4.9 16.2s6.3-4.4 9.8 1.1c4.3 6.9-4.8 13.1-4.8 13.1s0 8.2 9.1 8.2 2.5-9.6 9.6-9.6c7 0 2-9.2 14.8-18.2 12.7-9-6.4-10.8-6.4-10.8s2.7-7-1.2-14.6c-3.9-7.7-15.7-7.9-15.7-7.9s.6-8.8-8.7-12.2c-5.7-2.1-15.9-2.1-17.9 5-2.1 7-10.7-3.8-17 1.1-6.4 5-4.3 14-4.3 14Z" fill="#090909"/><path d="M167.706 69.76s-4.5.2-8 6.1c-3.6 5.9 3.2 10.7 3.2 10.7s32.2-14.7 42.4-2.2c5.5 6.6-4.9 16.2-4.9 16.2s6.3-4.4 9.8 1.1c4.3 6.9-4.8 13.1-4.8 13.1s0 8.2 9.1 8.2 2.5-9.6 9.6-9.6c7 0 2-9.2 14.8-18.2 12.7-9-6.4-10.8-6.4-10.8s2.7-7-1.2-14.6c-3.9-7.7-15.7-7.9-15.7-7.9s.6-8.8-8.7-12.2c-5.7-2.1-15.9-2.1-17.9 5-2.1 7-10.7-3.8-17 1.1-6.4 5-4.3 14-4.3 14Z" stroke="#000"/><path d="M162.906 127.16s5.2 1.4 8.9 1.4 7.7-1.4 7.7-1.4" stroke="#000" stroke-width="1.5"/><path d="M140.606 260.061c1.5-21.5.2-31.3-3.9-29.4-24.1 11.5-38.1 11.3-42-.8-5.2-15.9 2.4-50.2 22.7-103.1l19-2.2c1.3-.2 2.6.3 3.4 1.3.9 1 1.2 2.3.9 3.6l-15.3 66s14.8-20 26.6-27.7c11.8-7.8 16.8-7.8 40.2-10.6 23.4-2.9 50.2.5 56.1 18.2 5.1 15.3 1.9 43.1-9.8 83.5" fill="#fff"/><path d="M140.606 260.061c1.5-21.5.2-31.3-3.9-29.4-24.1 11.5-38.1 11.3-42-.8-5.2-15.9 2.4-50.2 22.7-103.1l19-2.2c1.3-.2 2.6.3 3.4 1.3.9 1 1.2 2.3.9 3.6l-15.3 66s14.8-20 26.6-27.7c11.8-7.8 16.8-7.8 40.2-10.6 23.4-2.9 50.2.5 56.1 18.2 5.1 15.3 1.9 43.1-9.8 83.5" stroke="#000" stroke-width="1.5"/><path fill-rule="evenodd" clip-rule="evenodd" d="M186.006 87.76c-6-1.8-8.5 5-8.5 5s3.2 3 3.2 8.6-5.2 12.2-5.2 12.2l2.1 13.7 9.6-2.5-2.5-13.6s5.8-3 8.1-8.2c1.9-4.3 1-9-1.7-12.8-2.7-3.8-4.4-2.2-5.1-2.4Z" fill="#fff"/><path d="M186.006 87.76c-6-1.8-8.5 5-8.5 5s3.2 3 3.2 8.6-5.2 12.2-5.2 12.2l2.1 13.7 9.6-2.5-2.5-13.6s5.8-3 8.1-8.2c1.9-4.3 1-9-1.7-12.8-2.7-3.8-4.4-2.2-5.1-2.4Z" stroke="#000" stroke-width="1.5"/><path d="m172.606 260.06 2.6-8.5-11.5-14.2 18.3-.4 10-15.3 6 17.2 17.6 4.8-14.5 11.1.2 5.3" fill="#EFEFEF"/><path d="m172.606 260.06 2.6-8.5-11.5-14.2 18.3-.4 10-15.3 6 17.2 17.6 4.8-14.5 11.1.2 5.3" stroke="#000" stroke-width="1.5" stroke-dasharray="4 4"/><path d="M249.406 185.361c-7 19.9-13.8 25.1-19.5 30.7-24.8 24.1-57.9 18.5-63.6 14-17-13.3-8.2-39.3 4.9-106l17.9-6.3c1-.3 2.1-.2 2.9.5.8.6 1.2 1.7 1.1 2.7l-8.5 77.7c10-1.1 17.6-5.3 22.5-13.2" fill="#fff"/><path d="M249.406 185.361c-7 19.9-13.8 25.1-19.5 30.7-24.8 24.1-57.9 18.5-63.6 14-17-13.3-8.2-39.3 4.9-106l17.9-6.3c1-.3 2.1-.2 2.9.5.8.6 1.2 1.7 1.1 2.7l-8.5 77.7c10-1.1 17.6-5.3 22.5-13.2" stroke="#000" stroke-width="1.5"/><path d="M199.206 55.36s7.5 3.2 5.4 7.9c-2 4.6-3.5 4.6-3.5 4.6M223.105 111.06s.9.3 2.3-.5c1.5-.9 1.2-1.9 1.2-1.9M230.206 83.86l-1.4 3.6s7-1.3 8.4 0M171.106 62.66s-.8 4.1 0 6c.8 1.9-3.6 2.1-4.9 3.4" stroke="#fff"/><path d="m114.006 135.96 25.6-1.6-25.6 1.6Z" fill="#fff"/><path d="m114.006 135.96 25.6-1.6" stroke="#000" stroke-width="1.5"/><path d="m168.706 135.16 23.4-4.2-23.4 4.2Z" fill="#fff"/><path d="m168.706 135.16 23.4-4.2" stroke="#000" stroke-width="1.5"/><path fill-rule="evenodd" clip-rule="evenodd" d="M137.406 130.661c0-1.1-.9-2.1-2-2.1s-2.1.9-2.1 2.1c0 1.1.9 2 2.1 2 1.1.1 2-.9 2-2ZM188.606 125.96c0-1.1-.9-2.1-2-2.1s-2.1.9-2.1 2.1c0 1.1.9 2 2.1 2 1.1.1 2-.9 2-2Z" fill="#D8D8D8"/><path fill-rule="evenodd" clip-rule="evenodd" d="M278.897 49.326c20.84-3.3 38.84 1.037 40.232 9.827.344 2.173-.413 4.42-1.977 6.692l-18.84 34.471 30.387 32.041-.198.032c1.633 1.361 2.606 3.029 2.888 4.807 1.377 8.692-14.387 18.478-35.227 21.779-20.741 3.285-38.757-1.151-40.134-9.843-.281-1.778.128-3.665 1.261-5.464l-.395.062 17.813-39.675L246.1 75.58l.099-.016c-1.42-1.294-2.279-2.879-2.529-4.46-1.377-8.69 14.386-18.477 35.227-21.778Z" fill="#5578EC"/><path d="M278.897 49.326c20.84-3.3 38.84 1.037 40.232 9.827.344 2.173-.413 4.42-1.977 6.692l-18.84 34.471 30.387 32.041-.198.032c1.633 1.361 2.606 3.029 2.888 4.807 1.377 8.692-14.387 18.478-35.227 21.779-20.741 3.285-38.757-1.151-40.134-9.843-.281-1.778.128-3.665 1.261-5.464l-.395.062 17.813-39.675L246.1 75.58l.099-.016c-1.42-1.294-2.279-2.879-2.529-4.46-1.377-8.69 14.386-18.477 35.227-21.778Z" stroke="#3351E5" stroke-width="9"/><path fill-rule="evenodd" clip-rule="evenodd" d="M288.688 74.707c-.579-3.654-4.027-6.247-7.78-5.653-3.655.58-6.247 4.027-5.653 7.78.579 3.655 4.027 6.247 7.78 5.653 3.753-.594 6.232-4.126 5.653-7.78ZM293.208 103.251c-.579-3.655-4.027-6.247-7.78-5.653-3.654.58-6.247 4.027-5.652 7.78.578 3.655 4.026 6.247 7.78 5.653 3.753-.595 6.231-4.126 5.652-7.78ZM297.072 127.647c-.578-3.655-4.026-6.247-7.78-5.653-3.654.579-6.247 4.027-5.652 7.78.579 3.655 4.027 6.247 7.78 5.653 3.753-.595 6.247-4.027 5.652-7.78Z" fill="#fff"/><path d="M201.206 102.361s5.9-3.1 7.4.7c.5 1.2.4 2.6-.1 4" stroke="#000" stroke-width="1.5"/><path d="M255.205 104.56c.9-.2 1.7-.7 2.3-1.4.6-.7 1-1.6 1.1-2.5.1-.9 0-1.9-.4-2.7-.4-.8-1.1-1.5-1.9-2-.8-.5-1.7-.7-2.7-.6-.9.1-1.8.4-2.6 1-.7.6-1.3 1.4-1.6 2.2-.3.9-.3 1.8-.1 2.7.3 1.2 1.1 2.2 2.2 2.9 1.1.7 2.5.8 3.7.4ZM303.606 181.86l-6.3 5.4M311.806 179.061l-6.3 5.4" stroke="#5578EC" stroke-width="1.944"/><path d="M180.506 89.36c3.3 3.2 4.5 7.3 4.1 12.2M184.506 87.46c3.3 3.2 4.5 7.2 4.1 12" stroke="#111" stroke-width="1.5"/></svg></span>
			<?php endif;?>
        </div>
    </div>
<?php $this->need('footer.php'); ?>