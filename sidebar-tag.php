<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Twodays
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside id="secondary" class="widget-area sidebar-1-area mt-3r card">
	<section id="tag-1" class="widget border-bottom widget_tag">		
		<div class="tag_list">
		<h5 class="widget-title h6">线上服务</h5>
        <label><a href="" class="tag" target="_blank">聊天</a></label>
        <label><a href="" class="tag" target="_blank">语音</a></label>
        <label><a href="" class="tag" target="_blank">视频</a></label>
        <label><a href="" class="tag" target="_blank">线上观影</a></label>
        <label><a href="" class="tag" target="_blank">唱歌</a></label>
        <label><a href="" class="tag" target="_blank">游戏</a></label>
        </div>
		<div class="tag_list">
		<h5 class="widget-title h6">线下服务</h5>
        <label><a href="" class="tag" target="_blank">逛街</a></label>
        <label><a href="" class="tag" target="_blank">看电影</a></label>
        <label><a href="" class="tag" target="_blank">户外运动</a></label>
        <label><a href="" class="tag" target="_blank">专业补课</a></label>
		<label><a href="" class="tag" target="_blank">遛狗</a></label>
        <label><a href="" class="tag" target="_blank">洗车</a></label>
        <label><a href="" class="tag" target="_blank">派对礼仪</a></label>
        <label><a href="" class="tag" target="_blank">家政</a></label>
        </div>
	</section>
	<?php //dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
