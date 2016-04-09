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
 *   Used For:     RSS Feed page
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

 	define('cfih', 'feed');
	require './inc/cfih.php';

	if(!$settings['SET_HIDE_FEED']){
		header('Location: index.php');
		exit();
	}
	
	header('Content-type: text/xml');
	echo '<?xml version="1.0" encoding="utf-8"?>';
	

// cache feed page once a hour
	cache_cheack('feed',(60*60));
	
?>
<feed xmlns="http://www.w3.org/2005/Atom"
  xmlns:xh="http://www.w3.org/1999/xhtml">
			<title><?php echo $settings['SET_TITLE'];?></title>
			<link><?php echo $settings['SET_SITEURL'];?></link>
			<description><?php echo _T("feed_description");?></description>
			<language><?php echo _T("feed_language");?></language>
	<?php
		if($settings['SET_HIDE_GALLERY'] && $imageList = imageList(0,10)){
			foreach($imageList as $k=>$image){
				$date = date('Y-m-d\TH:i:s\Z', $image['added']);
				$url = imageAddress(2,$image,"pm");
				$thumb_url = imageAddress(3,$image,"dt");
				$img_ext = $image['ext'];
				if($img_ext=='jpg') $img_ext = 'jpeg';
				?>
				<entry>
					<title><?php echo $image['alt'];?></title>
					<link rel="alternate" type="text/html" href="<?php echo $url;?>"/>
					<published><?php echo $date;?></published>
					<updated><?php echo $date;?></updated>
					<content type="html">
					&lt;p&gt;&lt;img style="float:left;margin-right:10px;margin-bottom: 10px;" src="<?php echo $thumb_url;?>" alt="<?php echo $image['alt'];?>" /&gt;&lt;/p&gt;</content>
					<link rel="enclosure" type="image/<?php echo $img_ext;?>" href="<?php echo $thumb_url;?>" />
				</entry>
			<?php }
		}else{?>
				<entry>
					<title><?php echo _T("feed_no_images");?></title>
					<link><?php echo $settings['SET_SITEURL'];?></link>
					<pubDate><?php echo date("D, d M Y H:i:s T");?></pubDate>
					<description><![CDATA[ No Images ]]></description>
				</entry>
<?php } ?>
		</feed>
<?php
	cache_end();