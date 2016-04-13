<?php if(!defined('cfih') or !cfih) exit("Direct access not permitted.");
/**************************************************************************************************************
 *
 *   CF Image Hosting Script
 *   ---------------------------------
 *
 *   Author:    codefuture.co.uk
 *   Version:   1.6.5
 *
 *   You can download the latest version from: http://codefuture.co.uk/projects/imagehost/
 *
 *   Copyright (c) 2010-2013 CodeFuture.co.uk
 *   This file is part of the CF Image Hosting Script.
 *
 *   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 *   EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *   COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 *   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF
 *   OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *
 *   You may not modify and/or remove any copyright notices or labels on the software on each
 *   page (unless full license is purchase) and in the header of each script source file.
 *
 *   You should have received a full copy of the LICENSE AGREEMENT along with
 *   Codefuture Image Hosting Script. If not, see http://codefuture.co.uk/projects/imagehost/license/.
 *
 *
 *   ABOUT THIS PAGE -----
 *   Used For:     Web site Footer
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/
 
 	if(isset($pageSet['image_widgit']) && $pageSet['image_widgit'] && isset($settings['SET_IMAGE_WIDGIT']) && $settings['SET_IMAGE_WIDGIT']){
		add_code_line('footer','<script type="text/javascript">
			$(document).ready(function() {
				$(\'#ImageWidget\').fadeOut(\'slow\', function(){
					$(\'#ImageWidget\').load("'.$settings['SET_SITEURL'].'cfajax.php",{widgit: 25}, function(){
						$(\'#ImageWidget\').fadeIn(\'slow\');
					});
				});
			});
		</script>');
		echo '<div id="ImageWidget" class="boxpanel"></div>';
	}
 
?>
		<div class="clear_both"></div>
		<?php get_ad('footer','footer_ad');?>
	</div>
	<div id="footer">
		<p><?php get_site('copyright');?></p>
		<?php if(getSettings('SET_HIDE_FEED')){?>
			<div id="feed"><a href="<?php get_url('feed.php');?>" title="<?php echo _T("footer_feed_title");?>"><span><?php echo _T("footer_feed_title");?></span></a></div>
		<?php } ?>
		<div class="sp"></div>
		<p><?php echo _T("admin_footer_powered_by");?> <a href="http://codefuture.co.uk/projects/imagehost/" title="Free PHP Image Hosting Script">CF Image Hosting script</a> | Design By <a href="<?php echo theme_setting('url','http://codefuture.co.uk');?>" title="<?php echo theme_setting('linktitle','codefuture.co.uk - online webmaster tools,code Generators');?>"><?php echo theme_setting('designby','codefuture.co.uk');?></a></p>
	</div>
</div>

<?php if(getSettings('SET_GOOGLE_ANALYTICS')){?>
<!--script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	try {
		var pageTracker = _gat._getTracker("<?php echo $settings['SET_GOOGLE_ANALYTICS'];?>");
		pageTracker._trackPageview();
	} 
	catch(err) {}
</script-->
<script type="text/javascript">
  var _gaId = "<?php echo $settings['SET_GOOGLE_ANALYTICS'];?>";
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', _gaId, 'auto');
  ga('send', 'pageview');
</script>
<?php } ?>
<?php if(getSettings('SET_BAIDU_ANALYTICS')){?>
<script type="text/javascript">
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?" + "<?php echo $settings['SET_BAIDU_ANALYTICS'];?>";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
<?php } ?>
<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if necessary -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>window.jQuery || document.write("<script src='js/jquery-1.7.1.min.js'>\x3C/script>")</script>
	<script type="text/javascript" src="<?php get_url('js/user.js');?>"></script>
	<?php exec_action('footer');?>
	<!--[if lt IE 7 ]>
		<script src="js/dd_belatedpng.js"></script>
		<script>DD_belatedPNG.fix("img, .png_bg"); // Fix any <img> or .png_bg bg-images. Also, please read goo.gl/mZiyb </script>
	<![endif]-->
</body>
</html>
