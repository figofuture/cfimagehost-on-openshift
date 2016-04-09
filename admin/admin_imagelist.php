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
 *   Used For:     Admin Image List
 *   Last edited:  11/01/2012
 *
 *************************************************************************************************************/

// number of items on page
	$item_on_page = (isset($_GET['list']) ? cl($_GET['list']):20);
	$list_url = ($item_on_page==20?'':'&list='.$item_on_page);
// order image list by
	$orderBy = (isset($_GET['orderBy']) ? cl($_GET['orderBy']):'added');
	$orderby_url = ($orderBy=='added'?'':'&orderBy='.$orderBy);
// sort order
	$order = (isset($_GET['order']) ? 'ASC':'DESC');
	$order_url = ($order=='DESC'?'':'&order='.$order);
// what info to show from last reset or all time
	$allTime = isset($_GET['v'])?true:false;
	$allTime_url = ($allTime?'&v=allTime':'');
// ip search
	$ipSearch = isset($_GET['ip'])?true:false;
	$ipSearch_url = ($ipSearch?'&ip='.$_GET['ip']:'');
//make image list
	$list_item	= '';
// hold pagination html
	$pagination = '';
 
// Check for images in database
	if($db_img = imageList(0,'all')){

	// workout the last reset date
		if ($settings['SET_BANDWIDTH_RESET'] == 'm') $resetdate = strtotime('01 '.date('M Y'));
		else $resetdate = strtotime('last monday', strtotime('tomorrow'));

		// add bandwidth & counter to image DB
		foreach ($db_img as $k => $v){
		
		// ip search
			if($ipSearch && $_GET['ip'] != $v['ip']){
				unset($db_img[$k]);
				$DBCOUNT--;
				continue;
			}
		
			if(!$hc = db_imageCounterList(null,$v['id'])){
			// image view counter db
				$hc = array('image'=>0,'thumb_mid'=>0,'thumb'=>0,'gallery'=>0, 'bandwidth' => 0,'date'=>0,
									'lr_image'=>0,'lr_thumb_mid'=>0,'lr_thumb'=>0,'lr_gallery'=>0, 'lr_bandwidth' => 0,'lr_date'=>0);
			}

			$db_img[$k]['lastviewed'] = ($hc['date'] == (int)0 ? round(((time() - $v['added']) / 86400),2):round(((time() - $hc['date']) / 86400),2));
			if($allTime){
				$db_img[$k]['bandwidth']	= $hc['bandwidth'];
				$db_img[$k]['hotlink']		= 0+$hc['image']+$hc['thumb_mid']+$hc['thumb'];
				$db_img[$k]['gallery']		= (!isset($hc['gallery']) ? 0:$hc['gallery']);
			}elseif($hc['lr_date']>=$resetdate){
				$db_img[$k]['bandwidth']	= $hc['lr_bandwidth'];
				$db_img[$k]['hotlink']		= 0+$hc['lr_image']+$hc['lr_thumb_mid']+$hc['lr_thumb'];
				$db_img[$k]['gallery']		= (!isset($hc['lr_gallery']) ? 0:$hc['lr_gallery']);
			}else{
				$db_img[$k]['bandwidth']	= 0;
				$db_img[$k]['hotlink']		= 0;
				$db_img[$k]['gallery']		= 0;
			}

		}
	// empty memory
		unset($hc);

	// what page are we on
		$page_number = (isset($_GET['p']) ? cl($_GET['p'])-1:0);

	// setup pagination address
		$pagination_address = $settings['SET_SITEURL'].'admin.php?p=%1$s&act=images'.$list_url.$orderby_url.$allTime_url.$ipSearch_url; // %1 page number
	// page pagination
		$pagination = pagination($page_number, $item_on_page, $DBCOUNT,$pagination_address);

	// order DB
		order_by($db_img,$orderBy,$order);
		$imageList = array_slice($db_img, ($page_number*$item_on_page), $item_on_page);
		
		foreach($imageList as $k=>$image){
			$bandwidthStyle = '';//($settings['SET_MAX_BANDWIDTH'] !=0 && ($settings['SET_MAX_BANDWIDTH']*1048576) < $image['bandwidth']?' style="background-color:red;color:#000"':'');

			$list_item .='
				<tr class="'.($odd = empty($odd) ? 'odd' : '').'">
					<td>
						<a href="'.imageAddress(2,$image,"pm").'" class="tip" title="'._T("admin_ilp_thumb_page_link").'"><img src="img/Image-Info.png" height="16" width="16" border="0" alt="'._T("admin_ilp_thumb_page_link").'" /></a>
						<a href="admin.php?act=edit&id='.$image['id'].'" class="tip" title="'._T("admin_ilp_edit_alt").'"><img src="img/Image-Edit.png" height="16" width="16" border="0" alt="'._T("admin_ilp_edit_alt").'" /></a>
						<a href="#" id="'.$image['deleteid'].'" class="tip delete" title="'._T("admin_ilp_report_alt_delete").'" ret="'.sprintf(_T("admin_ilp_report_delete"),$image['id']).'" ><img src="img/Image-Del.png" height="16" width="16" border="0" alt="'._T("admin_ilp_report_alt_delete").'" /></a>
						<a href="admin.php?act=ban&id='.$image['id'].'" class="tip" title="'._T("admin_ilp_report_alt_ban").'"><img src="img/User-Block.png" height="16" width="16" border="0" alt="'._T("admin_ilp_report_alt_ban").'" /></a>
						<a href="admin.php?act=images&ip='.$image['ip'].'" class="tip" title="'.sprintf(_T("admin_ilp_ipsearch_alt"),$image['ip']).'"><img src="img/user-search.png" height="16" width="16" border="0" alt="'.sprintf(_T("admin_ilp_ipsearch_alt"),$image['ip']).'" /></a>
					</td>
					<td>'.date('d M y',$image['added']).'</td>
					<td><a href="'.imageAddress(1,$image).'" target="_blank" title="'.$image['name'].'" img_src="<img src=\''.imageAddress(3,$image).'\'/>" class="imglink img_tooltip lightbox">'.$image['alt'].'</a></td>
					<td>'.$image['lastviewed'].'</td>
					<td>'.$image['gallery'].'</td>
					<td>'.$image['hotlink'].'</td>
					<td'.$bandwidthStyle.'>'.format_size($image['bandwidth']).'</td>
					<td>'.(array_key_exists('private',$image) && $image['private']?'Yes':'No').'</td>
				</tr>';
		}
	}else{
	//make image list
		$list_item	= '<tr class="odd"><td colspan="10"><h1>No Images Found</h1></td></tr>';
		$pagination	= '';
	}

// set any header hooks
	add_code_line('admin-footer','<script type= "text/javascript">
		$(document).ready(function() {
			removeImageFromList(this);
		});
	</script>');

// page settings
	$page['id']				= 'images';
	$page['title']			= _T("admin_menu_image_list");
	$page['description']	= '';
	$page['tipsy'] 			= true;
	$page['lightbox']		= true;

	require CFADMINPATH.'admin_page_header.php';
?>
<!-- admin image list -->
			<div class="ibox full">
				<h2><?php echo _T("admin_menu_image_list");?></h2>
				<div class="table_top">
					<div class="col">
						<select name="toShow" size="1" class="text_input" onChange="if(value) window.location.href = this.value;">
							<option selected value="<?php link_address('v');?>"><?php echo _T("admin_ilp_st_reset");?></option>
							<option value="<?php link_address('v','alltime');?>"><?php echo _T("admin_ilp_st_all");?></option>
						</select>
					</div>
					<div class="col">
						<select name="onPage" size="1" class="text_input" onChange="if(value) window.location.href = this.value;">
							<option selected value="<?php link_address('list');?>"><?php echo _T("admin_ilp_number_to_list");?></option>
							<option value="<?php link_address('list','20');?>">20</option>
							<option value="<?php link_address('list','40');?>">40</option>
							<option value="<?php link_address('list','80');?>">80</option>
							<option value="<?php link_address('list','100');?>">100</option>
							<option value="<?php link_address('list','999999');?>"><?php echo _T("admin_ilp_number_to_list_all");?></option>
						</select>
					</div>
					<div class="col">
						<select name="orderBy" size="1" class="text_input" onChange="if(value) window.location.href = this.value;">
							<option selected value="<?php link_address('orderBy');?>"><?php echo _T("admin_ilp_order_list");?></option>
							<option value="<?php link_address('orderBy','added');?>"><?php echo _T("admin_ilp_order_list_date_added");?></option>
							<option <?php echo ($orderBy=="lastviewed"? 'selected="selected"':'');?> value="<?php link_address('orderBy','lastviewed');?>"><?php echo _T("admin_ilp_order_list_last_viewed");?></option>
							<option <?php echo ($orderBy=="hotlink"?'selected="selected"':'');?> value="<?php link_address('orderBy','hotlink');?>"><?php echo _T("admin_ilp_order_list_hotlink_views");?></option>
							<option <?php echo ($orderBy=="bandwidth"?'selected="selected"':'');?> value="<?php link_address('orderBy','bandwidth');?>"><?php echo _T("admin_ilp_order_list_bandwidth_used");?></option>
							<option <?php echo ($orderBy=="gallery"?'selected="selected"':'');?> value="<?php link_address('orderBy','gallery');?>"><?php echo _T("admin_ilp_order_list_gallery_clicked");?></option>
						</select>
					</div>
					<div class="clear"></div>
				</div>
				<table>
					<thead>
					<tr class="odd">
						<th>&nbsp;</th>
						<th scope="col" title="<?php echo _T("admin_ilp_imglist_tt_image_added");?>"><a href="<?php link_address('orderBy','added');?>" class="<?php echo ($orderBy=='added'?'on':'');?>"><?php echo _T("admin_ilp_imglist_image_added");?></a><?php order_img('added');?></th>
						<th scope="col" title="<?php echo _T("admin_ilp_imglist_tt_image_name");?>"><?php echo _T("admin_ilp_imglist_image_name");?></th>
						<th scope="col" title="<?php echo _T("admin_ilp_imglist_tt_last_viewed");?>"><a href="<?php link_address('orderBy','lastviewed');?>" class="<?php echo ($orderBy=='lastviewed'?'on':'');?>"><?php echo _T("admin_ilp_imglist_last_viewed");?></a><?php order_img('lastviewed');?></th>
						<th scope="col" title="<?php echo _T("admin_ilp_imglist_tt_gallery_clicks");?>"><a href="<?php link_address('orderBy','gallery');?>" class="<?php echo ($orderBy=='gallery'?'on':'');?>"><?php echo _T("admin_ilp_imglist_gallery_clicks");?></a><?php order_img('gallery');?></th>

						<th scope="col" title="<?php echo _T("admin_ilp_imglist_tt_hotlink_views");?>"><a href="<?php link_address('orderBy','hotlink');?>" class="<?php echo ($orderBy=='hotlink'?'on':'');?>"><?php echo _T("admin_ilp_imglist_hotlink_views");?></a><?php order_img('hotlink');?></th>
						<th scope="col" title="<?php echo _T("admin_ilp_imglist_tt_bandwidth_used");?>"><a href="<?php link_address('orderBy','bandwidth');?>" class="<?php echo ($orderBy=='bandwidth'?'on':'');?>"><?php echo _T("admin_ilp_imglist_bandwidth_used");?></a><?php order_img('bandwidth');?></th>
						<th scope="col" title="<?php echo _T("admin_ilp_imglist_tt_private");?>"><?php echo _T("admin_ilp_imglist_private");?></th>
					</tr>
					</thead>
					<tbody>
						<?php echo $list_item;?>
					</tbody>
				</table>
				<?php echo $pagination;?>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>

<?php

	require CFADMINPATH.'admin_page_footer.php';
	die();
	exit;
	
	function order_img($check,$return = false){
		global $settings, $orderBy,$order;
		$html = '';
		if($orderBy == $check) $html = '<a href="'.link_address('orderBy',$check,true,true).(!isset($order) || $order == 'DESC'?'&order=1':'').'"><img src="img/'.(isset($order) && $order == 'DESC'?'up':'down').'ArrowSmall.gif"></a>';
		if($return) return $html;
		echo $html;
	}

	function link_address($k=null,$v=null,$hide_order = false ,$return = false){
		global $item_on_page ,$list_url,$orderBy,$orderby_url,$order,$order_url,$allTime,$allTime_url,	$ipSearch,$ipSearch_url;
		$url_get = 'admin.php?act=images';
		If(!is_null($k) && !is_null($v)) $url_get .= '&'.$k.'='.$v;
		if($k != 'list' && $item_on_page != 20) $url_get .= $list_url;
		if($k != 'orderBy' && $orderBy != 'added') $url_get .= $orderby_url;
		if(!$hide_order && $k != 'order' && $order != 'DESC') $url_get .= $order_url;
		if($k != 'v' && $allTime) $url_get .= $allTime_url;
		if($k != 'ip' && $ipSearch) $url_get .= $ipSearch_url;
		if($return)	 return $url_get;
		echo $url_get;
	}