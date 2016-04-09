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
 *   Used For:     Gallery/search Page
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

	define('cfih', 'gallery');
	require './inc/cfih.php';

// check to see if this page has been hidden
	if(!$settings['SET_HIDE_GALLERY']){
		header('Location: index.php');
		exit();
	}

// setup gallery
	require CFLIBPATH.'gallery.class.php';
	$gallery = new gallery();

	if($settings['SET_HIDE_SEARCH'] && !empty($_GET['search'])) $search = $gallery->gallerySearch($_GET['search']);

	$imgOnGalleryRow =theme_setting('gallery_row_no',$settings['SET_IMG_ROW_NO']);

	$gallery->setNumberOfImagesToShow($imgOnGalleryRow*theme_setting('gallery_rows',$settings['SET_IMG_ROWS']));
	$galleryList = $gallery->galleryList();	// make gallery

// check for search
	if(isset($search)){
		if(!$search || !$gallery->imagesFound){
			$Err['searchErr'] = $search;
			$searchHeader =  '<h4 class="search">'._T("site_search_no_results").' <span class="search_for">'.$gallery->searchFor.'</span></h4><p class="search_sug">'._T("site_search_suggestions").'</p>';
			$pageSet['image_widgit'] = true;
		}else{
			$searchHeader = '<h4 class="search">'._T("site_search_results",'<span class="search_for">'.$gallery->imagesFound.'</span>').' <span class="search_for">'.$gallery->searchFor.'</span></h4>';
		}
	}

// check gallery for images
	if(empty($galleryList) && !isset($search)){
		user_feedback('error',_T("site_gallery_err_no_image"),'gallery_search_no_image');
	}

// header page var
	$pageSet['id'] = 'gallery';
	$pageSet['title'] = ' - '._T("site_gallery_page_title").' '.($gallery->pageNmber);
	$pageSet['description'] = 'Free Image Hosting, Online image gallery of uploaded images';
	$pageSet['keywords'] = 'gallery, images, photos, image gallery, photo gallery, free image hosting';

 // load header
	require CFROOTPATH.'header.php';

// print search Header to page if needed
	echo (isset($searchHeader)?$searchHeader:'');

// check gallery has images to list
	if(!empty($galleryList)){
		foreach($galleryList as $k=>$image){
			echo !($k % $imgOnGalleryRow)?'<ul class="gallery">':'';// start new row
?>
			<li>
				<a href="<?php echo $image['thumbLink'];?>" title="<?php echo $image['imageAlt'];?>" class="thumb" >
					<img src="<?php echo $image['thumbSmallUrl'];?>" alt="<?php echo $image['imageAlt'];?>" />
				</a>
				<h2><a href="<?php echo $image['thumbLink'];?>" title="<?php echo $image['imageAlt'];?>"><?php echo $image['imageAlt'];?></a></h2>
				<?php if($settings['SET_ALLOW_REPORT']){?>
					<div class="img_report">
						<a rel="nofollow" href="#" title="<?php echo _T("site_gallery_report_title");?>" onclick="return doconfirm('<?php echo _T("site_gallery_report_this");?>','<?php echo $image['id'];?>')">
							<?php echo _T("site_gallery_report");?>
						</a>
					</div>
				<?php } ?>
			</li>
<?php
			echo !(($k+1) % $imgOnGalleryRow)?'</ul>':'';// end row
			if(($k+1) == $imgOnGalleryRow){
				get_ad('gallery','gallery_ad');
			}

		}// endfor 
	// end row if it was not a full row of images
		if((($k+1) % $imgOnGalleryRow))
			echo '</ul>';

		echo $gallery->pagination(theme_setting('pagination_link_no'));
	}

// load footer
	require CFROOTPATH.'footer.php';