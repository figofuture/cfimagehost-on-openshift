
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
 *   Used For:     User JS Code
 *   Last edited:  30/05/2012
 *
 *************************************************************************************************************/
function initMenu() {
	var currentId = $('body').attr('id');
	$('#main-nav li#page_'+currentId+' a').addClass('current');
}

//Multiple Upload
	var uploadMultipleBox = $(".Upload_Multiple").height();
	$(document).ready(function(){
		initMenu();

		$('form.upform').submit(function() {
			$(".file").animate({"height": "toggle", "opacity": "toggle"}, "slow");
			$(".upload_op").animate({"height": "toggle", "opacity": "toggle"}, "slow");
			$(".Upload_Multiple").animate({"height": 0, "opacity": 0}, "slow");
			$("#uploadbutton").animate({"height": 0, "opacity": 0}, "slow");
			$(".loading").fadeIn("slow");
		});
	});

	var count = 0;
	$(document).ready(function(){
		$('.add_another_file_input').click(function(){
			var html = $('.Upload_Multiple').prev('.uploadbox').html();
			$('.file:last').after( '<div class="uploadbox file hide">'+html+'</div>' );
			$('.file:last').animate({"height": "toggle", "opacity": "toggle"}, "slow",function(){
				$(this).children('.closeUpload').fadeIn('slow');
				$('.pref_title:last').next('.preferences').children('input#private').attr('name', 'private[' + count + ']');
				$('.pref_title:last').next('.preferences').children('input#shorturl').attr('name', 'shorturl[' + count  + ']');
			});
			count++;
			if(count >= (js_text.t103-1)) $('.Upload_Multiple').animate({"height": 0, "opacity": 0}, "slow",function(){$(this).css("display", "none")});
			if(count === 2) copyfileName();
		return false;
		});
	});

	$(".closeUpload").live('click',function(){
		$(this).parent().fadeTo(400, 0, function () {
			$(this).slideUp(400,function() {
				js_text.t103++;
				$('.Upload_Multiple').delay(1).css("display", "block").animate({"height": uploadMultipleBox+"px", "opacity": "100"}, "slow");
				$(this).remove();
			});
		});
		return false;
	});

	$(document).ready(function(){
		$("#linkremote").click(function(){
			$('#local_panel').fadeOut(1, function(){$('#remote_panel').fadeIn('slow');});
			$('div.hide').fadeTo(400, 0, function(){$('div.hide').slideUp(400,function(){$('div.hide').remove();});});
			$('.Upload_Multiple').animate({"height": 0, "opacity": 0}, "slow",function(){$(this).css("display", "none")});
			$('#linklocal').removeClass('linklocal show').addClass('linklocal');
			$('#file').val("");
			$('#fileName').val("");
			$('#linkremote').removeClass('linkremote').addClass('linkremote show');
			count = 1;
		});
		$("#linklocal").click(function(){
			$('#remote_panel').fadeOut(1, function(){$('#local_panel').fadeIn('slow');});
			$('.Upload_Multiple').delay(1).css("display", "block").animate({"height": uploadMultipleBox+"px", "opacity": "100"}, "slow");
			$('#linklocal').removeClass('linklocal').addClass('linklocal show');
			$('#imgUrl').val("");
			$('#linkremote').removeClass('linkremote show').addClass('linkremote');
		});
	});
	$(document).ready(function(){
		$(".pref_title").live('click',function(){
			$(this).next('.preferences').animate({"height": "toggle", "opacity": "toggle"}, "slow");//slideToggle("slow");
			$(this).toggleClass("open_pref");
		});
	});
//files name
	function  copyfileName() {
		var name;
		var file = document.upload.elements["file[]"];
		var file_name = document.upload.elements["fileName[]"];
		var alt = document.upload.elements["alt[]"];
		if (file.length > 0){
			for (i = 0; (i < file.length); i++) {
				if (file_name[i].value != file[i].value){
					file_name[i].value = file[i].value;
					name = file_name[i].value.slice(file_name[i].value.lastIndexOf("\\")+1,file_name[i].value.lastIndexOf('.'));
					alt[i].value = name.replace(/_|-/g," ");
				}
			}
		}else{
			file_name.value = file.value;
			name = file_name.value.slice(file_name.value.lastIndexOf("\\")+1,file_name.value.lastIndexOf('.'));
			alt.value = name.replace(/_|-/g," ");
		}
	}
//file ext
	function fileExt(extArray) {
		//var extArray = new Array("<?=implode('","',$accepted);?>");
		var file = document.upload.elements["fileName[]"];
		var fileUrl = document.upload.elements["imgUrl"];
		var upCount = file.length;
		if (upCount == null) upCount = 1;
		for (i = 0; (i < upCount); i++) {
			var extOk = false;
			if (upCount == 1 && i == 0){
				if (!file.value && !fileUrl.value){
					return false;
				}else if(file.value) {
					fileItem = file;
				}else if(fileUrl.value) {
					fileItem = fileUrl;
				}
			}else if(!file[i].value){
				return false;
			}
				
			var filename = fileItem.value.slice(fileItem.value.indexOf("\\") + 1);
			var ext = filename.substring(filename.lastIndexOf(".")+1).toLowerCase();
			for (var i = 0; i < extArray.length; i++) {
				if (extArray[i] === ext) {
					extOk = true;
				}
			}
			if (extOk != true) {
				alert(js_text.t101);
				return false;
			}
		}
		return true;
	}

// image report
	function doconfirm(message,id) {
		if (confirm(message)) {
			$.post("cfajax.php",{report: id},function(result){if(result.status){$("#msg").html(result.suc).slideDown(400).fadeTo(400, 100);}else{$("#msg").html(result.error).slideDown(400).fadeTo(400,100);}},"json");
		}else{
			return false;
		}
	}
	$(document).ready(function(){
		if( $('.img_report').length ){
			var thisImg;
		//	var thisImgWidth;
			$(".gallery li").hover(function(e){
				thisImg  = $(this);
				var thisImgWidth =(180-$(this).children("a.thumb").children("img").width())/2-5;
				var thisImgoffset =$(this).children("a.thumb").children("img").offset();
				var thisLiOffset =$(this).offset();
				var thisimgtop = thisImgoffset.top - thisLiOffset.top;
				$(this).children(".img_report")
					.css("right",thisImgWidth+"px")
					.css("top",thisimgtop+"px")
					.fadeIn("slow");
			},
			function(){
				$(thisImg).children(".img_report").css("display", "none");
			});
		}
	});
// Close button:
	$(".close").live('click',function(){
		$(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
			$(this).slideUp(400,function() {$(this).remove();});
		});
		$("#msg").slideUp().fadeTo(400, 0);// image remove fix
		return false;
	});
// FAQ
$(document).ready(function() {
	$("a[name^='faq-']").each(function() {
		$(this).click(function() {
			if( $("#" + this.name).is(':hidden') ) {
				$("#" + this.name).slideDown(400).fadeTo(400, 100);
			} else {
				$("#" + this.name).fadeTo(400, 0).slideUp(400);
			}
			return false;
		});
	});
});
