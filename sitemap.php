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
 *   Used For:     Web Site Map
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

 	define('cfih', 'sitemap');
	require './inc/cfih.php';

	if(!$settings['SET_HIDE_SITEMAP']){
		header('Location: index.php');
		exit();
	}

	header ("Content-type: text/xml");
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
// cache sitemap once a day
	cache_cheack('sitemap',(60*60*24));

// list pages
	echo '<url><loc>'.$settings['SET_SITEURL'].'</loc></url>';// Home page
	if($settings['SET_HIDE_GALLERY'])	echo '<url><loc>'.$settings['SET_SITEURL'].'/gallery.php</loc></url>'; // Gallery
	if($settings['SET_HIDE_TOS'])		echo '<url><loc>'.$settings['SET_SITEURL'].'/tos.php</loc></url>'; // TOS
	if($settings['SET_HIDE_FAQ'])		echo '<url><loc>'.$settings['SET_SITEURL'].'/faq.php</loc></url>'; // FAQ
	if($settings['SET_HIDE_CONTACT'])	echo '<url><loc>'.$settings['SET_SITEURL'].'/contact.php</loc></url>';	// Contact


	if($settings['SET_HIDE_GALLERY']){
	// get images & set $DBCOUNT
		$imageList = imageList(0,'all'); // get image list from db

	// list gallery pages
		$link_mod	= (!$settings['SET_MOD_REWRITE'] ? '/gallery.php?p=':'/gallery/page');
		$ex_var		= (!$settings['SET_MOD_REWRITE'] ? '':'.html');
		$number_of_pages = ceil($DBCOUNT/$settings['SET_IMG_ON_PAGE']);
		if (($number_of_pages*$settings['SET_IMG_ON_PAGE']) < $DBCOUNT) $number_of_pages++;
		for ($i = 1; $i <= $number_of_pages; $i++) {
			echo "<url>";
			echo "<loc>".$settings['SET_SITEURL'].$link_mod.$i.$ex_var."</loc>";
			echo "</url>";
		}

	// list image pages
		foreach($imageList as $k=>$image){
			echo "<url>";
			echo "<loc>".imageAddress(2,$image,"pm")."</loc>";
			echo "</url>";
			echo "<url>";
			echo "<loc>". imageAddress(3,$image,"pt")."</loc>";
			echo "</url>";
		}
	}
	echo "</urlset>";
	cache_end();