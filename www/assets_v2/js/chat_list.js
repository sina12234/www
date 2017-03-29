/**
 * app/templates/course/plan.play.v2.html 播放页已经不再引用此文件
 * 引用的此文件被合并到了 /assets_v2/js/player/module.js
 * 如有修改 请同时修改 /assets_v2/js/player/module.js 的部分
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
function ChatList(mainDom, number, step, moveCallback){
    if(mainDom && !mainDom.length){
        mainDom = null;
    }
    this._mainDom = mainDom;
    this._number = number;
    this._step = step;
    this._moveCallback = moveCallback;
    this._groups = {};
    this._div = null;
    this._news = null;
    this.newsNum = 0;
    if(mainDom){
        this._mainDisplay = new DisplayOptimize(mainDom, number, step, moveCallback);
    }
}
ChatList.prototype.addGroup = function(gid, dom, uids){
    if(gid in this._groups){
        return;
    }
    var a = {};
    a["dom"] = dom;
    a["uids"] = uids;
    a["display"] = new DisplayOptimize(dom, this._number, this._step, this._moveCallback);
    a["newsNum"] = 0;
    this._groups[gid] = a;
}
ChatList.prototype.setNews = function(dom){
    this._news = dom;
}
ChatList.prototype.modifyNewsNum = function(uid, num){
    uid = parseInt(uid);
    this.newsNum += num;
    if(uid){
        for(var gid in this._groups){
            var a = this._groups[gid];
            if(uid in a["uids"]){
                a["newsNum"] += num;
            }
        }
    }
}
ChatList.prototype.showNews = function(gid){
    var num = 0;
    if(gid){
        num = this._groups[gid].newsNum;
    }else{
        num = this.newsNum;
    }
    if(num > 0){
        var spanValue = "" + num + "条新消息";
        if(num > 99){
            spanValue = "99+条新消息";
        }
        this._news.find("div[class=chat-c]").text(spanValue);
        this._news.show();
    }
}
ChatList.prototype.switchNews = function(gid){
    this._news.hide();
    this.showNews(gid);
}
ChatList.prototype.isToEnd = function(gid){
    var dom = this._mainDom;
    if(gid){
        dom = this._groups[gid]["dom"];
    }
    if(dom.scrollTop() + 120 >= dom[0].scrollHeight - dom.height()){
        return true;
    }else{
        return false;
    }
}
ChatList.prototype.addDiv = function(){
    var dom = $("<div></div>");
    this._div = dom;
    if(this._mainDom){
        this._mainDisplay.prepend(dom);
    }
    for(var gid in this._groups){
        var a = this._groups[gid];
        a["div"] = dom.clone();
        a["display"].prepend(a["div"]);
    }
}
ChatList.prototype.addMore = function(){
    if(this._mainDom){
        if(this._div){
            this._mainDisplay.insert("<li data-more><p style='cursor:pointer'>更多</p></li>", this._div.index());
        }else{
            this._mainDisplay.prepend("<li data-more><p style='cursor:pointer'>更多</p></li>");
        }
    }
    for(var gid in this._groups){
        var a = this._groups[gid];
        if("div" in a){
            a["display"].insert("<li data-more><p style='cursor:pointer'>更多</p></li>", a["div"].index());
        }else{
            a["display"].prepend("<li data-more><p style='cursor:pointer'>更多</p></li>");
        }
    }
}
ChatList.prototype.deleteMore = function(){
    if(this._mainDom){
        var a = this._mainDom.children("li[data-more]");
        if(a.length){
            this._mainDisplay._delete(a);
        }
    }
    for(var gid in this._groups){
        var a = this._groups[gid];
        var b = a["dom"].children("li[data-more]");
        if(b.length){
            a["display"]._delete(b);
        }
    }
}
ChatList.prototype.append = function(dom, groupFlag){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._mainDom){
        this._mainDisplay.append(dom);
    }
    if(groupFlag){
        for(var gid in this._groups){
            var a = this._groups[gid];
            a["display"].append(dom.clone());
        }
    }else{
        var u = parseInt(dom.attr("data-uid"));
        if(u){
            for(var gid in this._groups){
                //过滤，加内容
                var a = this._groups[gid];
                if(u in a["uids"]){
                    a["display"].append(dom.clone());
                }
            }
        }
    }
}
ChatList.prototype.appends = function(dom, groupFlag){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._mainDom){
        this._mainDisplay.appends(dom);
    }
    if(groupFlag){
        for(var gid in this._groups){
            var a = this._groups[gid];
            a["display"].appends(dom.clone());
        }
    }else{
        for(var i=0;i<dom.length;i++){
            var u = parseInt($(dom[i]).attr("data-uid"));
            if(u){
                for(var gid in this._groups){
                    var a = this._groups[gid];
                    if(u in a["uids"]){
                        a["display"].append($(dom[i]).clone());
                    }
                }
            }
        }
    }
}
ChatList.prototype.prepend = function(dom, groupFlag){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._mainDom){
        this._mainDisplay.prepend(dom);
    }
    if(groupFlag){
        for(var gid in this._groups){
            var a = this._groups[gid];
            a["display"].prepend(dom.clone());
        }
    }else{
        var u = parseInt(dom.attr("data-uid"));
        if(u){
            for(var gid in this._groups){
                var a = this._groups[gid];
                if(u in a["uids"]){
                    a["display"].prepend(dom.clone());
                }
            }
        }
    }
}
ChatList.prototype.prepends = function(dom, groupFlag){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._mainDom){
        this._mainDisplay.prepends(dom);
    }
    if(groupFlag){
        for(var gid in this._groups){
            var a = this._groups[gid];
            a["display"].prepends(dom.clone());
        }
    }else{
        for(var i=dom.length-1;i>=0;i--){
            var u = parseInt($(dom[i]).attr("data-uid"));
            if(u){
                for(var gid in this._groups){
                    var a = this._groups[gid];
                    if(u in a["uids"]){
                        a["display"].prepend($(dom[i]).clone());
                    }
                }
            }
        }
    }
}
ChatList.prototype.beforeDiv = function(dom, groupFlag){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._mainDom){
        this._mainDisplay.insert(dom, this._div.index());
    }
    if(groupFlag){
        for(var gid in this._groups){
            var a = this._groups[gid];
            a["display"].insert(dom.clone(), a["div"].index());
        }
    }else{
        var u = parseInt(dom.attr("data-uid"));
        if(u){
            for(var gid in this._groups){
                var a = this._groups[gid];
                if(u in a["uids"]){
                    a["display"].insert(dom.clone(), a["div"].index());
                }
            }
        }
    }
}
ChatList.prototype.beforeDivs = function(dom, groupFlag){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._mainDom){
        this._mainDisplay.inserts(dom, this._div.index());
    }
    if(groupFlag){
        for(var gid in this._groups){
            var a = this._groups[gid];
            a["display"].inserts(dom.clone(), a["div"].index());
        }
    }else{
        for(var i=0;i<dom.length;i++){
            var u = parseInt($(dom[i]).attr("data-uid"));
            if(u){
                for(var gid in this._groups){
                    var a = this._groups[gid];
                    if(u in a["uids"]){
                        a["display"].insert($(dom[i]).clone(), a["div"].index());
                    }
                }
            }
        }
    }
}
ChatList.prototype.deleteMsg = function(msg_id){
    if(this._mainDom){
        this._mainDisplay._delete(this._mainDom.find("li[data-msg="+msg_id+"]"));
    }
    for(var gid in this._groups){
        var a = this._groups[gid];
        var b = a["dom"].find("li[data-msg="+msg_id+"]");
        if(b.length){
            a["display"]._delete(b);
        }
    }
}
ChatList.prototype.move = function(gid){
    if(gid){
        if(gid in this._groups){
            this._groups[gid]["display"].move();
        }
    }else{
        this._mainDisplay.move();
    }
}
ChatList.prototype.moveStart = function(gid){
    if(gid){
        if(gid in this._groups){
            this._groups[gid]["display"].moveStart();
        }
    }else{
        this._mainDisplay.moveStart();
    }
}
ChatList.prototype.moveEnd = function(gid){
    if(gid){
        if(gid in this._groups){
            this._groups[gid]["display"].moveEnd();
            this._groups[gid]["newsNum"] = 0;
            if(this._news){
                this._news.hide();
            }
        }
    }else{
        this._mainDisplay.moveEnd();
        this.newsNum = 0;
        if(this._news){
            this._news.hide();
        }
    }
}
ChatList.prototype.showNumber = function(){
    if(this._mainDom){
        console.log("main number=["+this._mainDisplay.showNumber()+"]\n");
    }
    for(var gid in this._groups){
        console.log(gid+" number=["+this._groups[gid]["display"].showNumber()+"]");
    }
}
