/*
* pageid    节点id
* path  路径
* num   每页个数 
* page  页号
* total 总页数
*/

// 返回浏览器的可视区域位置 
function getClient(){ 
	var l, t, w, h; 
	l = document.documentElement.scrollLeft || document.body.scrollLeft; 
	t = document.documentElement.scrollTop || document.body.scrollTop; 
	w = document.documentElement.clientWidth; 
	h = document.documentElement.clientHeight; 
	return { left: l, top: t, width: w, height: h }; 
} 

// 返回待加载资源位置 
function getSubClient(p){ 
	var l = 0, t = 0, w, h; 
	w = p.offsetWidth; 
	h = p.offsetHeight; 
	while(p.offsetParent){ 
		l += p.offsetLeft; 
		t += p.offsetTop; 
		p = p.offsetParent; 
	} 
	return { left: l, top: t, width: w, height: h }; 
} 




// 判断两个矩形是否相交,返回一个布尔值 
function intens(rec1, rec2){ 
	var lc1, lc2, tc1, tc2, w1, h1; 
	lc1 = rec1.left + rec1.width / 2; 
	lc2 = rec2.left + rec2.width / 2; 
	tc1 = rec1.top + rec1.height / 2 ; 
	tc2 = rec2.top + rec2.height / 2 ; 
	w1 = (rec1.width + rec2.width) / 2 ; 
	h1 = (rec1.height + rec2.height) / 2; 
	return Math.abs(lc1 - lc2) < w1 && Math.abs(tc1 - tc2) < h1 ; 
} 


/*
var div1 = document.getElementById("div1"); 
window.onscroll = function(){ 
	var prec1 = getClient(); 
	var prec2 = getSubClient(div1); 
	if (intens(prec1, prec2)) { 
		alert("true"); 
	} 
}; 
*/






function callback1(){
	alert("123");
}

// 比较某个子区域是否呈现在浏览器区域 
function jiance(arr, prec1, callback){ 
	var prec2; 
	for (var i = arr.length - 1; i >= 0; i--) { 
		if (arr[i]) { 
			prec2 = getSubClient(arr[i]); 
			if (intens(prec1, prec2)) { 
				//callback(arr[i]); 
				callback();
				// 加载资源后，删除监测 
				delete arr[i]; 
			} 
		} 
	} 
} 






// 检测目标对象是否出现在客户区 
function autocheck(){ 
	var prec1 = getClient(); 

	jiance(arr,prec1,callback1);
	/*jiance(arr, prec1, function(obj){ 
		// 加载资源... 
		//alert(obj.innerHTML); 
		alert("12312"); 
	}) 
	*/
}


// 子区域一 
var d1 = document.getElementById("div1"); 
// 子区域二 
var d2 = document.getElementById("div2"); 
var d3 = document.getElementById("div3"); 
var d4 = document.getElementById("div4"); 
var d5 = document.getElementById("div5"); 
var d6 = document.getElementById("div6"); 

// 需要按需加载区域集合 
//var arr = [d1, d2, d3, d4, d5, d6]; 
var arr = [d1,]; 
/*
window.onscroll = function(){ 
	// 重新计算 
	　//　autocheck(); 
}


window.onresize = function(){ 
	// 重新计算 
	//autocheck(); 
} 
*/
