! function(a) {  
    function b() {  
        //为window对象添加了rem属性，其值为document.documentElement的宽度的1/16!  
        //同时把他设置为document.documentElement的fontSize属性，这里其实不用把window对象设置一个rem属性，因为a.rem只是设置了一次  
        a.rem = f.getBoundingClientRect().width / 36, f.style.fontSize = a.rem + "px"  
    }  
    var c, d = a.navigator.appVersion.match(/iphone/gi) ? a.devicePixelRatio : 1,  
    //iphone手机要计算devicePixelRatio的值  
        e = 1 / d,  
        //dpr表示一个CSS像素对应于多少个物理像素。那么网页的缩放比例很显然就是其倒数，这样才能在不同设备上进行自适应  
        f = document.documentElement,  
        g = document.createElement("meta");  
        //为window对象设置了dpr属性，同时为window对象设置了resize方法  
    if (a.dpr = d, a.addEventListener("resize", function() {  
        //在resize方法中如果两次resize的时间间隔小于300ms，这时候我们会清除上一次的resize事件  
            clearTimeout(c), c = setTimeout(b, 300)  
        }, !1), a.addEventListener("pageshow", function(a) {  
            //pageshow事件在页面显示时候触发，不论该页面是否来自bfcache,在重新加载页面中，pageshow会在load 事件触发后触发，而对于bfcache中的页面，pageshow会在页面状态完全恢复的那一刻触发。注意；虽然这个事件的目标是document，但是必须将其事件处理程序添加到window上。pageshow的event对象的persisted属性是布尔值，如果页面保存在bfcache中这个属性为true。  
            a.persisted && (clearTimeout(c), c = setTimeout(b, 300))  
        }, !1), f.setAttribute("data-dpr", d), g.setAttribute("name", "viewport"), g.setAttribute("content", "initial-scale=" + e + ", maximum-scale=" + e + ", minimum-scale=" + e + ", user-scalable=no"), f.firstElementChild) f.firstElementChild.appendChild(g);  
        //firstElementChild表示的就是head元素，为他指定一个meta标签就可以了  
    else {  
        var h = document.createElement("div");  
        h.appendChild(g), document.write(h.innerHTML)  
    }  
    b()  
}(window);