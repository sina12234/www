wx.ready(function(){
	var meta = $("meta[name=weixin]");
	var title=meta.attr("title"); if(!title){title=document.title;}
	var link=meta.attr("link"); if(!link){link=location.href;}
	var imgurl=meta.attr("imgurl"); if(!imgurl){imgurl="";}else{var p= document.createElement('a');p.href=imgurl;imgurl=p.href;}
	var desc=meta.attr("desc"); if(!desc){desc="";}

	wx.onMenuShareTimeline({
		title: title, // 分享标题
		link: link, // 分享链接
		imgUrl: imgurl, // 分享图标
		success: function () { 
			// 用户确认分享后执行的回调函数
		},
		cancel: function () { 
			// 用户取消分享后执行的回调函数
		}
	});
	wx.onMenuShareAppMessage({
		title: title, // 分享标题
		desc: desc, // 分享描述
		link: link, // 分享链接
		imgUrl: imgurl, // 分享图标
		type: 'link', // 分享类型,music、video或link，不填默认为link
		dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
		success: function () { 
			// 用户确认分享后执行的回调函数
		},
		cancel: function () { 
			// 用户取消分享后执行的回调函数
		}
	});
	wx.onMenuShareQQ({
		title: title, // 分享标题
		desc: desc, // 分享描述
		link: link, // 分享链接
		imgUrl: imgurl, // 分享图标
		success: function () { 
			// 用户确认分享后执行的回调函数
		},
		cancel: function () { 
			// 用户取消分享后执行的回调函数
		}
	});
});
