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
 *   Used For:     Terms of Service Page
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

 	define('cfih', 'tos');
	require './inc/cfih.php';

// check to see if this page has been hidden (set in the admin panel), if so then send user to the index/home page
	if(!$settings['SET_HIDE_TOS']){
		header('Location: index.php');
		exit();
	}

// cache faq page once every 32 days
	cache_cheack('tos',(60*60*24*32));

// header page var
	$pageSet['id'] = 'tos_page';
	$pageSet['title'] =   ' - '._T("site_tos_title");
	$pageSet['description'] =  _T("site_tos_title").' page for '.$settings['SET_TITLE'];
	$pageSet['keywords'] = '';

 // load header
	require CFROOTPATH.'header.php';
?>
		<div class="contentBox">
			<div id="tos">
				<h2><?php echo _T("site_tos_title");?></h2>
				<ul>
					<li><?php echo $settings['SET_TITLE'];?> <?php echo _T("site_tos_line1");?></li>
					<li><?php echo _T("site_tos_line2");?></li>
					<li><?php echo $settings['SET_TITLE'];?> <?php echo _T("site_tos_line3");?>
						<ul>
							<li><?php echo _T("site_tos_line4");?></li>
							<li><?php echo _T("site_tos_line5");?></li>
							<li><?php echo _T("site_tos_line6");?></li>
							<li><?php echo _T("site_tos_line7");?></li>
							<li><?php echo _T("site_tos_line8");?></li>
						</ul>
					</li>
					<li><?php echo _T("site_tos_line9");?></li>
					<li><?php echo $settings['SET_TITLE'];?> <?php echo _T("site_tos_line10");?></li>
					<li><?php echo $settings['SET_TITLE'];?> <?php echo _T("site_tos_line11");?></li>
				</ul>
				<br/><br/>
				<h2><?php echo _T("site_privacy_policy_title");?></h2>
				<ul>
					<li><?php echo _T("site_privacy_policy_line1");?></li>
				</ul>
			</div>
		</div>
<?php
	require CFROOTPATH. 'footer.php';
	cache_end();
