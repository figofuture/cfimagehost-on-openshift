<?php
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
 *   Used For:     Frequently Asked Questions Page
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

 	define('cfih', 'faq');
	require './inc/cfih.php';

// check to see if this page has been hidden (set in the admin panel), if so then send user to the index/home page
	if(!$settings['SET_HIDE_FAQ']){
		header('Location: index.php');
		exit();
	}

// cache faq page once every 32 days
	cache_cheack('faq',(60*60*24*32));

// header page var
	$pageSet['id'] = 'faq_page';
	$pageSet['title'] = ' - '._T("site_faq_title");
	$pageSet['description'] = _T("site_faq_title").' for '.$settings['SET_TITLE'].', Image Hosting website';
	$pageSet['keywords'] = $settings['SET_TITLE'].', images, photos, photo hosting,  image hosting';

 // load header
	require CFROOTPATH.'header.php';

?>
		<div class="contentBox">
			<div id="faq">
				<h2><?php echo _T("site_faq_title");?></h2>
					<div class="faq_box">
						<div class="title"><?php echo _T("site_faq_q1",$settings['SET_TITLE']);?></div>
						<div class="answer" id="faq-1"><?php echo _T("site_faq_a1");?></div>
					</div>

					<div class="faq_box">
						<div class="title"><?php echo _T("site_faq_q2");?></div>
						<div class="answer" id="faq-2"><?php echo _T("site_faq_a2",$settings['SET_TITLE']);?></div>
					</div>

					<div class="faq_box">
						<div class="title"><?php echo _T("site_faq_q3");?></div>
						<div class="answer" id="faq-3"><?php echo strtoupper(implode(', ',$imgFormats));?> <?php echo _T("site_faq_a3");?></div>
					</div>

					<div class="faq_box">
						<div class="title"><?php echo _T("site_faq_q4");?></div>
						<div class="answer" id="faq-4"><?php echo _T("site_faq_a4");?></div>
					</div>

					<div class="faq_box">
						<div class="title"><?php echo _T("site_faq_q5");?></div>
						<div class="answer" id="faq-5"><?php echo _T("site_faq_a5");?></div>
					</div>

					<div class="faq_box">
						<div class="title"><?php echo _T("site_faq_q6");?></div>
						<div class="answer" id="faq-6"><?php echo _T("site_faq_a6");?> <?php echo (format_size(1048576*$settings['SET_MAX_BANDWIDTH'])).' '._T("site_index_max_bandwidth_per").($settings['SET_AUTO_DELETED_JUMP'] == 'm' ? _T("site_index_max_bandwidth_per_month"):_T("site_index_max_bandwidth_per_week"));?>.</div>
					</div>

					<div class="faq_box">
						<div class="title"><?php echo _T("site_faq_q7");?></div>
						<div class="answer" id="faq-7"><?php echo _T("site_faq_a7_1",_T("site_menu_tos"));?><?php if($settings['SET_AUTO_DELETED']){?><?php echo _T("site_faq_a7_2",$settings['SET_AUTO_DELETED_TIME']);?><?php } ?></div>
					</div>

					<div class="faq_box">
						<div class="title"><?php echo _T("site_faq_q8");?></div>
						<div class="answer" id="faq-8"><?php echo _T("site_faq_a8");?> <?php echo format_size($settings['SET_MAXSIZE']);?>.</div>
					</div>

					<div class="faq_box">
						<div class="title"><?php echo _T("site_faq_q9");?></div>
						<div class="answer" id="faq-9"><?php echo _T("site_faq_a9");?></div>
					</div>

					<div class="faq_box">
						<div class="title"><?php echo _T("site_faq_q10");?></div>
						<div class="answer" id="faq-10"><?php echo _T("site_faq_a10");?></div>
					</div>
			</div>
		</div>
<?php
	require CFROOTPATH.'footer.php';
	cache_end();