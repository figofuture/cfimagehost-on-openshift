<?php if(!defined('cfih') or !cfih or cfih!='admin') die('This file cannot be directly accessed.');
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
 *   Used For:     Admin page footer
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/
?>
<!-- admin page footer -->
	</div></div></div>
	<div id="footer">
		<span class="copyright"><?php echo _T("admin_footer_powered_by");?> <a href="http://codefuture.co.uk/projects/imagehost/" title="Free PHP Image Hosting Script">CF Image Hosting script</a></span>
		<span class="version"><b><?php echo _T("admin_footer_version");?>:</b> <?php echo $settings['SET_VERSION'];?></span>
	</div>
<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if necessary -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>window.jQuery || document.write("<script src='js/jquery-1.7.1.min.js'>\x3C/script>")</script>
<?php if(isset($page['tipsy'])){ ?>
	<script type="text/javascript" src="js/tipsy.js"></script>
	<script type="text/javascript">
		$(function() {
			$('a.img_tooltip').tipsy({title: 'img_src',fade: true, gravity: 's',html: true,live: true,opacity: 1});
			$('#content th[title]').tipsy({fade: true, gravity: 's',live: true});
			$('#content a.tip[title]').tipsy({fade: true, gravity: 's',live: true});
		})
	</script>
<?php } ?>
<?php if(isset($page['lightbox'])){ ?>
	<script type="text/javascript" src="<?php echo $settings['SET_SITEURL'];?>js/lightbox/lightbox.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".lightbox").lightbox({
				fitToScreen: true,
				imageClickClose: true,
				displayDownloadLink: true
			});
		});
	</script>
<?php } ?>
	<script src="js/admin.js?<?php echo $settings['SET_VERSION'];?>"></script>
	<?php exec_action('admin-footer');?>

</body>
</html>