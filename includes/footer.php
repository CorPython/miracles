<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
    </div><!-- 结束 pjax-container -->
	<!-- Footer Information -->
    <footer>
      <div class="footer-info footer-dark">
		<?php $this->options->footerEcho(); ?>
		<p class="copyright">Powered by <a href="http://typecho.org">Typecho</a> | Theme <a href="https://guhub.cn/p/miracles.html">Miracles</a> by <a href="https://guhub.cn">Eltrac</a><br>
		&copy; <?php echo date('Y'); ?> <a href="<?php $this->options->SiteUrl(); ?>"><?php $this->options->title(); ?></a>  All rights reserved.<br>
        <?php 
		echo $this->options->build_time?"记录已延续了 <span id=\"build-time\"></span>":"";
		if($faces=$this->options->anime_face){
		$faces = explode("&&",$faces);
		$faces = "<span class=\"anime-face\">".$faces[mt_rand(0,count($faces))]."</span>";
		}
		if($faces && $this->options->build_time)echo $faces;
		?></p>
	  </div>
    </footer>
	<!-- Raised Buttons -->
	<div class="fixed-tools">
	  <button title="返回顶部" class="fixed-button gotop-button" id="gotop" style="color:white;"><i class="iconfont icon-chevron-up"></i></button>
	</div>
	<!-- JavaScript -->
	<?php
	$js_files=array("jquery","pjax.jquery","jquery.fancybox","jquery.lazyload.min","nprogress","OwO.min","highlight","highlight-line-number","pangu");
	generate_require($js_files,"js",$this->options->CDN?"https://cdn.jsdelivr.net/gh/BigCoke233/miracles@":"");
	?>
	<!-- Varribles and Functions -->
	<script>
	var allowNavAero = <?php if($this->options->navAero):?>false<?php else:?>true<?php endif;?>;
	var siteurl = '<?php $this->options->SiteUrl() ;?>';
	var owoJson = '<?php Utils::indexTheme('assets/OwO.json'); ?>';
	<?php if($this->options->pjax && $this->options->pjax!=0) :?>var loadPjax = true;
    beforePjax = function() {NProgress.start();};
	afterPjax = function() {owoLoad();<?php $this->options->pjax_complete(); ?>};<?php endif; ?></script>
	<!-- Script that must be after-->
	<script src="<?php Utils::indexTheme('assets/js/miracles.min.js'); ?>"></script>
	<!-- BuildTime -->
	<script>
	<?php if($this->options->build_time)echo "startTime(\"".$this->options->build_time."\");" ?>
	</script>
	<!-- Send News and Loaders -->
	<script><?php if($this->is('index')): ?><?php if($this->options->news==!''): ?>alertSend('公告：<?php echo $this->options->news(); ?>');<?php endif; ?><?php endif; ?><?php if($this->is('post') || $this->is('page')): ?>owoLoad();<?php endif; ?><?php $this->options->jsEcho(); ?></script>
    <!-- Others -->
	<?php $this->footer(); ?>
  </body>
</html>
