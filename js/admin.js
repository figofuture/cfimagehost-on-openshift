
/**************************************************************************************************************
 *
 *   CF Image Hosting Script
 *   ---------------------------------
 *
 *   Author:    codefuture.co.uk
 *   Version:   1.6
 *
 *   You can download the latest version from: http://codefuture.co.uk/projects/imagehost/
 *
 *   Copyright (c) 2010-2012 CodeFuture.co.uk
 *   This file is part of the CF Image Hosting Script.
 *
 *   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 *   EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *   COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 *   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF
 *   OR  IN  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
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
 *   Used For:     Admin JS Code
 *   Last edited:  30/05/2012
 *
 *************************************************************************************************************/

function doconfirm(message) {
	if (confirm(message)){
		return true;
	}else{
		return false;
	}
}

$.ajaxSetup ({  
	cache: false,
	beforeSend: function() {
		$('#loader').show();
	},
	complete: function(){
		$('#loader').fadeOut('slow');
	},
	success: function() {
		$('#loader').fadeOut('slow');
	}
});

// remove image
var removeImageFromList = function(scope) {
	$(".delete", scope).click(function() {
		var msg = $(this).attr('ret');
		var imgageLine=$(this).closest("tr");
		var id = $(this).attr("id");
		var dataString = 'act=remove&d='+ id;
		if(doconfirm(msg)){
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "admin.php",
				data: dataString,
				success: function(result){
					
					if(result.status){
						$("#msg").html(result['suc']).slideDown(400).fadeTo(400, 100);
						imgageLine.fadeTo(400, 0, function () {imgageLine.remove();});
					}else{
						$("#msg").html(result['error']);
					}
				},
				error: function(errorThrown){
					$("#msg").html(errorThrown['error']);
				}
			});
			return false;
		}
	});
}


//backup
$(function() {
	$(".backup").click(function() {
		var msg = $(this).attr('ret');
		var id = $(this).attr("id");
		if(doconfirm(msg)){
			var dataString = 'act=backup&id='+id;
			$.ajax({
				type: "POST",
				url: "cfajax.php",
				data: dataString,
				dataType: 'json',
				cache: false,
				success: function(result){
					$("#msg").html(result['suc']).slideDown(400).fadeTo(400, 100);
				},
				error: function(errorThrown){
					$("#msg").html(errorThrown['error']);
				}
			});
			return false;
		}
	});
});
//unzip backup
$(function() {
	$(".unzip").click(function() {
		var msg = $(this).attr('ret');
		var fname = $(this).attr("alt");
		if(doconfirm(msg)){
			var dataString = 'act=unzip&name='+fname;
			$.ajax({
				type: "POST",
				url: "cfajax.php",
				data: dataString,
				dataType: 'json',
				cache: false,
				success: function(result){
					$("#msg").html(result['suc']).slideDown(400).fadeTo(400, 100);
				},
				error: function(errorThrown){
					$("#msg").html(errorThrown['error']);
				}
			});
			return false;
		}
	});
});
//remove backup
$(function() {
	$(".remove").click(function() {
		var msg = $(this).attr('ret');
		var test=$(this).closest("tr");
		var fname = $(this).attr("alt");
		if(doconfirm(msg)){
			var dataString = 'act=remove&name='+fname;
			$.ajax({
				type: "POST",
				url: "cfajax.php",
				data: dataString,
				dataType: 'json',
				cache: false,
				success: function(result){
					$("#msg").html(result['suc']).slideDown(400).fadeTo(400, 100);
					test.remove();
				},
				error: function(errorThrown){
					$("#msg").html(errorThrown['error']);
				}
			});
			return false;
		}
	});
});

// Close button:
$(".close").live('click',function(){
	$(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
		$(this).slideUp(400,function() {$(this).remove();});
	});
	$("#msg").slideUp().fadeTo(400, 0);// image remove fix
	return false;
});


$(document).ready(function (){
// settings panel
	var lastOpen = '';
	$(".flip").click(function(){
		if(lastOpen != $(this)){
			$(lastOpen).next('.panel').animate({"height": "toggle", "opacity": "toggle"}, "slow");
			$(this).next('.panel').animate({"height": "toggle", "opacity": "toggle"}, "slow");
			lastOpen = $(this);
		}
	});
});


$(document).ready(function (){
	$(function () {
		var tabContainers = $('div.tabs > div');
		tabContainers.hide().filter(':first').show();
		$('div.tabs ul.tabs_list a').click(function () {
			tabContainers.hide();
			tabContainers.filter(this.hash).show();
			$('div.tabs ul.tabs_list a').removeClass('current');
			$(this).addClass('current');
			return false;
		}).filter(':first').click();
	});
});
