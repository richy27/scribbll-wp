if (document.URL.match(/(gif|png|jpg|jpeg)$/i) && (navigator.appVersion.indexOf('Chrome/') != -1 || navigator.appVersion.indexOf('Safari/') != -1)) {
	alert('For direct jpg/gif/png url, please fetch image at Add > Pin > From Web');
}

(function(){
	var v = '1.7';

	if (window.jQuery === undefined || window.jQuery.fn.jquery < v) {
		var done = false;
		var script = document.createElement('script');
		script.src = '//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js';
		script.onload = script.onreadystatechange = function(){
			if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
				done = true;
				pincloneit();
			}
		};
		document.getElementsByTagName('head')[0].appendChild(script);
	} else {
		pincloneit();
	}

	function pincloneit() {
		(window.pincloneit = function() {
			if (jQuery('#pincloneframe').length == 0) {
				jQuery('body').css('overflow', 'hidden')
				.append("\
				<div id='pincloneframe'>\
					<div id='pincloneframebg'><p>Loading...</p></div>\
					<div id='pincloneheader'><p id='pincloneclose'>X</p><p id='pinclonelogo'>" + pinclonesite + "</p></div>\
					<div id='pincloneimages'></div>\
					<style type='text/css'>\
						#pincloneframe {color: #333;}\
						#pincloneframebg {background: #f2f2f2; display: none; position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 2147483646;}\
						#pincloneframebg p {background: #999; border-radius: 8px; color: white; font: normal normal bold 16px\/22px Helvetica, Arial, sans-serif; margin: -2em auto 0 -9.5em; padding: 12px; position: absolute; top: 50%; left: 50%; text-align: center; width: 15em;}\
						#pincloneframe #pincloneheader {background: white; border-bottom: 1px solid #d4d4d4; border-top: 3px solid #2f2f2f; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); color: white; height: 40px; margin: 0; overflow: hidden; padding: 0; position: fixed; top: 0; left: 0; text-align: center; width: 100%; z-index: 2147483647;}\
						#pincloneframe #pincloneheader #pinclonelogo {color: black; font: normal normal bold 20px\/20px Helvetica, Arial, sans-serif; margin: 0; padding: 12px 15px 13px 20px;}\
						#pincloneframe #pincloneheader #pincloneclose {background: #f33; color: white; cursor: pointer; float: right; font: normal normal bold 16px\/16px Helvetica, Arial, sans-serif; margin: 0; padding: 12px 15px 13px 20px;}\
						#pincloneimages {position: fixed; top: 60px; left: 0; width: 100%; height: 94%; overflow-x: auto; overflow-y: scroll; text-align: center; z-index: 2147483647;}\
						#pincloneimages .pincloneimgwrapper {border: 1px solid #aaa; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2); cursor: pointer; display: inline-block; height: 200px; margin: 15px; overflow: hidden; position: relative; width: 200px;}\
						#pincloneimages .pinclonebutton {background: rgba(0, 0, 0, 0.5); border-radius: 8px; color: white; font: normal normal bold 36px/36px Helvetica, Arial, sans-serif; padding: 8px 16px; display: none; margin-left: -24px; margin-top: -24px; position: absolute; top: 50%; left:50%;}\
						#pincloneimages .pinclonedimension {background: white; font: normal normal normal 12px/12px Helvetica, Arial, sans-serif; padding: 3px 0; position: absolute; right: 0; bottom: 0; left: 0;}\
						#pincloneimages img {width: 100%; height: auto;}\
					</style>\
				</div>");
				
				jQuery('#pincloneframebg').fadeIn(200);
				
				var imgarr = [];
				var videoflag = '0';
				var documentURL = document.URL;

				-1==documentURL.indexOf("youtube.com/watch")||$('[id*="oneframeb"]').length?documentURL.match(/vimeo.com\/(\d+)($|\/)/)&&!$('[id*="oneframeb"]').length?(video_id=documentURL.split("/")[3],jQuery.getJSON("http://www.vimeo.com/api/v2/video/"+video_id+".json?callback=?",{format:"json"},function(a){imgsrc=a[0].thumbnail_large,imgarr.unshift([imgsrc,640,360]),videoflag="1",display_thumbnails(imgarr,videoflag)})):-1==documentURL.indexOf("xvideos.com/video")||$('[id*="oneframeb"]').length?documentURL.match(/redtube.com\/(\d+)($|\/)/)&&!$('[id*="oneframeb"]').length?(imgsrc=jQuery('meta[property="og:image"]').attr("content").replace("m.jpg","i.jpg"),imgarr.unshift([imgsrc,582,388]),videoflag="1",display_thumbnails(imgarr,videoflag)):-1==documentURL.indexOf("hardsextube.com/video/")||$('[id*="oneframeb"]').length?-1==documentURL.indexOf("youporn.com/watch/")||$('[id*="oneframeb"]').length?(jQuery("img").each(function(){var a=jQuery(this).prop("src"),b=this.naturalWidth;b||(b=jQuery(this).width());var c=this.naturalHeight;c||(c=jQuery(this).height()),a&&b>=125&&imgarr.unshift([a,b,c])}),display_thumbnails(imgarr,videoflag)):(imgsrc=jQuery("#galleria img:eq(7)").attr("src"),imgarr.unshift([imgsrc,720,576]),videoflag="1",display_thumbnails(imgarr,videoflag)):(imgsrc=jQuery('link[rel="image_src"]').attr("href"),imgarr.unshift([imgsrc,1920,1080]),videoflag="1",display_thumbnails(imgarr,videoflag)):(imgsrc=jQuery("#tabVote > img").attr("src"),imgarr.unshift([imgsrc,180,135]),videoflag="1",display_thumbnails(imgarr,videoflag)):(video_id=document.URL.match("[\\?&]v=([^&#]*)"),imgsrc="http://img.youtube.com/vi/"+video_id[1]+"/0.jpg",imgarr.unshift([imgsrc,480,360]),videoflag="1",display_thumbnails(imgarr,videoflag),jQuery("#movie_player").css("visibility","hidden"));
			}

			jQuery('#pincloneheader').on('click', '#pincloneclose', function() {
				if (documentURL.indexOf('youtube.com/watch') != -1) {
					jQuery('#movie_player').css('visibility','visible');
				}
				jQuery('body').css('overflow', 'visible');
				jQuery('#pincloneframe').fadeOut(200, function() {
					jQuery(this).remove();
				});
			});
			
			jQuery('#pincloneimages').on('click', '.pincloneimgwrapper', function() {
				window.open(jQuery(this).data('href'), 'pinclonewindow', 'width=400,height=760,left=0,top=0,resizable=1,scrollbars=1');
				if (documentURL.indexOf('youtube.com/watch') != -1) {
					jQuery('#movie_player').css('visibility','visible');
				}
				jQuery('body').css('overflow', 'visible');
				jQuery('#pincloneframe').remove();
			});
			
			jQuery('#pincloneimages').on('mouseover', '.pincloneimgwrapper', function() {
				jQuery(this).find('.pinclonebutton').show();
			}).on('mouseout', '.pincloneimgwrapper', function() {
				jQuery(this).find('.pinclonebutton').hide();
			});
			
			jQuery(document).keyup(function(e) {
				if (e.keyCode == 27) { 
				if (documentURL.indexOf('youtube.com/watch') != -1) {
					jQuery('#movie_player').css('visibility','visible');
				}
				jQuery('body').css('overflow', 'visible');
				jQuery('#pincloneframe').fadeOut(200, function() {
					jQuery(this).remove();
				});
				}
			});
		})();
	}
	
	function display_thumbnails(imgarr, videoflag) {
		if (!imgarr.length) {
			jQuery('#pincloneframebg').html('<p>Sorry, unable to find anything to save on this page.</p>');
		} else if (document.URL.match(/(gif|png|jpg|jpeg)$/i)) {
			jQuery('#pincloneimages').hide();
			jQuery('#pincloneframebg').html('<p>For direct jpg/gif/png url,<br />please fetch image at<br /><a href="' + pinclonesiteurl + '/pins-settings/">Add > Pin > From Web</a></p>');
		} else {
			imgarr.sort(function(a,b)
			{
				if (a[1] == b[1]) return 0;
				return a[1] > b[1] ? -1 : 1;
			});
			
			var imgstr = '';
			for (var i = 0; i < imgarr.length; i++) {
				if (videoflag == '0') {
					imgstr += '<div class="pincloneimgwrapper" data-href="' + pinclonesiteurl + 'pins-settings/?m=bm&imgsrc=' + encodeURIComponent(imgarr[i][0].replace('http','')) + '&source=' + encodeURIComponent(document.URL.replace('http','')) + '&title=' + encodeURIComponent(jQuery.trim(document.getElementsByTagName('title')[0].innerHTML)) + '&video=' + videoflag + '"><div class="pinclonebutton">+</div><div class="pinclonedimension">' + parseInt(imgarr[i][1],10) + ' x ' + parseInt(imgarr[i][2],10) + '</div><img src="' + imgarr[i][0] + '" /></div>';
				} else {
					imgstr += '<div class="pincloneimgwrapper" data-href="' + pinclonesiteurl + 'pins-settings/?m=bm&imgsrc=' + encodeURIComponent(imgarr[i][0].replace('http','')) + '&source=' + encodeURIComponent(document.URL.replace('http','')) + '&title=' + encodeURIComponent(jQuery.trim(document.getElementsByTagName('title')[0].innerHTML)) + '&video=' + videoflag + '"><div class="pinclonebutton">+</div><div class="pinclonedimension"> Video </div><img src="' + imgarr[i][0] + '" /></div>';
				}
			}
			jQuery('#pincloneframebg p').fadeOut(200);
			jQuery('#pincloneimages').css('height',jQuery(window).height()-jQuery('#pincloneheader').height()-20)
								.html(imgstr + '<div style="height:40px;clear:both;"><br /></div>');
			if ((navigator.appVersion.indexOf('Chrome/') != -1 || navigator.appVersion.indexOf('Safari/')) && videoflag != '1') {
				jQuery('#pincloneimages .pincloneimgwrapper').css('float','left');
			}
		}	
	}
})();