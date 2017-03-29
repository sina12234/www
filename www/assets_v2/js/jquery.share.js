/*
*Name:xin wei
*Date:2015-12-18
* 分享功能，目前只有 tsina-新浪微博 qzone-qq空间 tqq-qq weixin-微信
*/

;(function($){
	$.fn.share=function(options){
		var defaults={
			sTitle	 	: '',
			sDes	 	: '',
			sPic   		: '',
			shareUrl	: '',
			wxHref		: ''//微信生成地址

		};
		var options= $.extend(defaults,options);
		var shref='';
		var wxshref='';
		if($.trim(options.sDes).length==0){
			//获得当前页面description中内容
			var meta = window.document.getElementsByTagName('meta');
			var share_desc = '';
			for(i in meta){
				if(typeof meta[i].name!="undefined"&&meta[i].name.toLowerCase()=="description"){
					share_desc = meta[i].content;
				}
			}
			options.sDes=share_desc;
		}
		if($.trim(options.sTitle).length==0){
			options.sTitle=document.title;
		};
		if(options.wxHref){
			wxshref=options.wxHref;
		}else{
			wxshref='https://qrcode.yunke.com/qr?s=180&t='+encodeURIComponent(shref);
		}
		this.each(function(){
			$(this).on('click','a',function(){
				var _this=$(this);
				var dataCmd=$(this).attr('data-cmd');
				if(options.shareUrl){
					shref=options.shareUrl;
				}else{
					shref=location.href;
				}
				switch (dataCmd){
					case 'tsina'://新浪微博
						window.open('http://v.t.sina.com.cn/share/share.php?url='+encodeURIComponent(shref)+'&pic='+encodeURIComponent(options.sPic)+'&title='+encodeURIComponent(options.sTitle)+'&searchPic=false');
						break;
					case 'qzone'://qq空间
						window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+encodeURIComponent(shref)+'&pics='+encodeURIComponent(options.sPic)+'&title='+encodeURIComponent(options.sTitle)+'&desc=&summary=&site=');

						break;
					case 'tqq'://qq
						window.open('http://connect.qq.com/widget/shareqq/index.html?title='+encodeURIComponent(options.sTitle)+'&summary='+encodeURIComponent(options.sDes)+'&url='+encodeURIComponent(shref)+'&pics='+encodeURIComponent(options.sPic)+'&desc=&site=');
						break;
					case 'weixin'://微信
						var wxSrc=wxshref;
						if($(".weixin").length==0){
							var weiXinHtml = "<div class='weixin' style='z-index:10000;width:410px;height:322px;position:fixed;left:50%;top:50%;margin-left:-205px;margin-top:-161px; overflow: hidden;padding:10px;background:#fff;border:1px solid #cdcdcd;'>"
								weiXinHtml += "<i style='position:absolute;right:10px;width:20px;cursor:pointer' id='weixin_close'>×</i>"
								weiXinHtml += "<div style='float:left;margin:70px 0 0 25px;'>"
								weiXinHtml += '<img src="/assets_v2/img/weixin-share-lt.jpg" width="109" alt="">'
								weiXinHtml += "</div>"
								weiXinHtml += "<div style='float:right;width:60%;'>"
								weiXinHtml += "<sapn style='margin-bottom: 10px;display: block;'><a href='javascript:;' onclick=javascript:$('.weixin').remove(); class='weixin-closeBtn' style='float:right;color: #4c4c4c;text-decoration:none;'></a></sapn>"
								weiXinHtml += "<p style='color:#333;text-align:center;padding-top:30px;font-size:16px;text-align:left;'>分享到微信</p>"
								weiXinHtml += "<img width='154' height='154' src='"+wxSrc+"'>"
								weiXinHtml += "<p style='color:#666666;text-align:center;padding-top:5px;font-size:14px;text-align:left;'>打开微信，点击底部的“发现”</p>"
								weiXinHtml += "<p style='color:#666666;text-align:center;padding-top:5px;font-size:14px;text-align:left;'>使用“扫一扫”即将网页分享给朋友</p>"
								weiXinHtml += "</div>"
								weiXinHtml += "</div>";
							$("body").append(weiXinHtml)
						}else{
							return false;
						}
				}
			})
		});

	}
})(jQuery);
$("body").on('click','#weixin_close',function(){
	$(this).parent('.weixin').remove();
})
