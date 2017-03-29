/*显示学生列表
 *一个main，多个分组
 * */
function DisplayStudent(mainDom, number, step){
    this._mainDom = mainDom;
    this._number = number;
    this._step = step;
    this.indexFlag = true;
    this.onlineNum = 0;
    this.raiseHandNum = 0;	//显示的举手数（不是实际的举手数）
    this.speechNum = 0;	//显示的发言中的人的个数（不是实际发言的人的个数，只能为0或1）
    this.speechUser = 0;
    this.speechToken = "";
    this.selectId = 1;
    this.queryStudent = "";
    this._groups = {};
    this._mainDisplay = new DisplayOptimize(mainDom, number, step);
}
DisplayStudent.prototype.addGroup = function(gid, dom){
    if(gid in this._groups){
        return;
    }
    var a = {};
    a["dom"] = dom;
    a["display"] = new DisplayOptimize(dom, this._number, this._step);
    a.indexFlag = true;
    a.onlineNum = 0;
    a.raiseHandNum = 0;
    a.speechNum = 0;
    this._groups[gid] = a;
}
DisplayStudent.prototype.deleteDom = function(dom, dom2, gid){
    this._mainDisplay._delete(dom);
    if(dom2){
        this._groups[gid]["display"]._delete(dom2);
    }
}
//在学生列表里显示在线学生（不包括发言和举手的）
DisplayStudent.prototype.setOnlineDisplay = function (dom, onlineNum, speechNum, raiseHandNum, display, container){
    dom.attr("data-display", "1");
    if(0 == onlineNum){		//没有在线的被显示
        if(0 == speechNum+raiseHandNum){
            //dom.prependTo(_students);
            display.prepend(dom);
        }else{
            display.insert(dom, speechNum+raiseHandNum);
        }
    }else{
        var a = container.children();
        var n = parseInt(dom.attr("data-good"));
        var uid = dom.attr("data-id");
        var flag = false;
        for(var i=0;i<onlineNum;i++){
            var b = a.eq(i+speechNum+raiseHandNum);
            if(b.attr("data-id") == uid){
                display.insert(dom, i+speechNum+raiseHandNum+1);
                flag = true;
                break;
            }
            if(parseInt(b.attr("data-good")) < n){
                display.insert(dom, i+speechNum+raiseHandNum);
                flag = true;
                break;
            }
        }
        if(!flag){
            display.insert(dom, speechNum+raiseHandNum+onlineNum);
        }
    }
}
//在学生列表里显示离线学生（直接加到最后）
DisplayStudent.prototype.setOfflineDisplay = function(dom, display){
    dom.attr("data-display", "1");
    display.append(dom);
}
/*DisplayStudent.prototype.setOfflineDisplay = function(dom, dom2, gid){
    function f(dom, display){
		dom.attr("data-display", "1");
        display.append(dom);
    }
    f(dom, this._mainDisplay);
    if(dom2){
        f(dom2, this._groups[gid]["display"]);
    }
}*/
//在学生列表里显示举手学生
DisplayStudent.prototype.setRaiseHandDisplay = function(dom, display, container, speechNum, raiseHandNum){
    dom.attr("data-display", "1");
    if(0 == raiseHandNum){		//没有举手的被显示
        if(0 == speechNum){
            display.prepend(dom);
        }else{
            display.insert(dom, 1);
        }
    }else{
        var a = container.children();
        var t = parseInt(dom.attr("data-hand"));
        var flag = false;
        for(var i=0;i<raiseHandNum;i++){
            var b = a.eq(i+speechNum);
            if(parseInt(b.attr("data-hand")) > t){
                display.insert(dom, i+speechNum);
                flag = true;
                break;
            }
        }
        if(!flag){
            display.insert(dom, speechNum+raiseHandNum);
        }
    }
}
DisplayStudent.prototype.initIndex = function(){
    this.indexFlag = false;
    for(var gid in this._groups){
        this._groups[gid].indexFlag = false;
    }
}
//设置学生列表序号
DisplayStudent.prototype.setStudentsIndex = function(gid){
    function f(that, container){
        if(that.indexFlag){
            return;
        }
        if(container.children().length > 100){
            that.indexFlag = true;
            setTimeout(function(){
                container.children().each(function(i, elem){
                    $(this).find("b[data-index]").text(i+1);
                });
                that.indexFlag = false;
            }, 2000);
        }else{
            container.children().each(function(i, elem){
                $(this).find("b[data-index]").text(i+1);
            });
        }
    }
    if(this._mainDom){
        f(this, this._mainDom);
    }
    if(gid){
        var a = this._groups[gid];
        f(a, a.dom);
    }else{
        for(var i in this._groups){
            var a = this._groups[i];
            f(a, a.dom);
        }
    }
}
//显示一个报名学生（输入dom，是否在线，是否重新设置序号）
DisplayStudent.prototype.setDisplay = function(dom, dom2, gid, online, setIndex){
    function f(dom, online, selectId, queryStudent, display, container, speechUser, speechToken, that, that2){
        var oldDisplay = parseInt(dom.attr("data-display"));
        var newDisplay = 0;
        if(1 == selectId){	//全部学员
            newDisplay = 1;
        }else if(4 == selectId){
            if(dom.attr("data-name").indexOf(queryStudent) >= 0){
                newDisplay = 1;
            }
        }else{
            if(online){
                if(2 == selectId){	//未点名学员
                    if(dom.attr("data-call") == 0){
                        newDisplay = 1;
                    }
                }else if(3 == selectId){	//已点名学员
                    if(dom.attr("data-call") != 0){
                        newDisplay = 1;
                    }
                }
            }
        }
        if(oldDisplay == newDisplay){
            return 0;
        }
        if(newDisplay){
            if(dom.attr("data-id")==speechUser && dom.attr("data-token")==speechToken){
                dom.attr("data-display", "1");
                display.prepend(dom);
                that2.speechNum = 1;
            }else if(parseInt(dom.attr("data-hand")) > 0){
                that.setRaiseHandDisplay(dom, display, container, that2.speechNum, that2.raiseHandNum);
                that2.raiseHandNum++;
            }else{
                if(online){
                    that.setOnlineDisplay(dom, that2.onlineNum, that2.speechNum, that2.raiseHandNum, display, container);
                    that2.onlineNum++;
                }else{
                    that.setOfflineDisplay(dom, display);
                }
            }
        }else{
            if(dom.attr("data-id")==speechUser && dom.attr("data-token")==speechToken){
                that2.speechNum = 0;
            }else if(parseInt(dom.attr("data-hand")) > 0){
                that2.raiseHandNum--;
            }else{
                if(online){
                    that2.onlineNum--;
                }
            }
            display._delete(dom);
            dom.attr("data-display", "0");
        }
    }
    if(dom){
        f(dom, online, this.selectId, this.queryStudent, this._mainDisplay, this._mainDom, this.speechUser, this.speechToken, this, this);
    }
    if(dom2){
        f(dom2, online, this.selectId, this.queryStudent, this._groups[gid].display, this._groups[gid].dom, this.speechUser, this.speechToken, this, this._groups[gid]);
    }
    if(setIndex){
        this.setStudentsIndex(gid);
    }
}
//响应点赞消息（修改点赞数，重新排序）
DisplayStudent.prototype.goodResponseToken= function(u, token, num, online, dom, dom2, gid){
    dom.attr("data-good", num);
    dom.find("span[data-good]").text(num);
    if(dom2){
        dom2.attr("data-good", num);
        dom2.find("span[data-good]").text(num);
    }
    if(online){
        if(u == this.speechUser && token == this.speechToken){
            ;
        }else if(dom.attr("data-hand") > 0){
            ;
        }else{
            if(1 == dom.attr("data-display")){
                dom.attr("data-display", "0");
                this.onlineNum--;
                if(dom2){
                    dom2.attr("data-display", "0");
                    this._groups[gid].onlineNum--;
                }
                this.deleteDom(dom, dom2, gid);
                this.setDisplay(dom, dom2, gid, true, true);
            }
        }
    }
}
//停止举手后的显示
DisplayStudent.prototype.cancelRaiseHand = function(dom, dom2, gid, online){
    dom.find("span[data-hand]").hide();
    dom.attr("data-hand", "0");
    if(dom2){
        dom2.find("span[data-hand]").hide();
        dom2.attr("data-hand", "0");
    }
    if(1 == dom.attr("data-display")){
        this.raiseHandNum--;
        dom.attr("data-display", "0");
        if(dom2){
            this._groups[gid].raiseHandNum--;
            dom2.attr("data-display", "0");
        }
        this.deleteDom(dom, dom2, gid);
    }
    this.setDisplay(dom, dom2, gid, online, true);
}
//把一个dom显示成发言状态（包括去除之前的状态）
DisplayStudent.prototype.displaySpeech = function(dom, dom2, gid){
    if(1 == dom.attr("data-display")){
        if(dom.attr("data-hand") > 0){
            dom.find("span[data-hand]").hide();
            dom.attr("data-hand", "0");
            this.raiseHandNum--;
            if(dom2){
                dom2.find("span[data-hand]").hide();
                dom2.attr("data-hand", "0");
                this._groups[gid].raiseHandNum--;
            }
        }else{
            this.onlineNum--;
            if(dom2){
                this._groups[gid].onlineNum--;
            }
        }
        this.deleteDom(dom, dom2, gid);
        dom.attr("data-display", "0");
        if(dom2){
            dom2.attr("data-display", "0");
        }
        this.setStudentsIndex(gid);
    }else{
        if(dom.attr("data-hand") > 0){
            dom.find("span[data-hand]").hide();
            dom.attr("data-hand", "0");
            if(dom2){
                dom2.find("span[data-hand]").hide();
                dom2.attr("data-hand", "0");
            }
        }
    }
    this.speechUser = dom.attr("data-id");
    this.speechToken = dom.attr("data-token");
    dom.find("span[data-speech]").show();
    if(dom2){
        dom2.find("span[data-speech]").show();
    }
    this.setDisplay(dom, dom2, gid, true, true);
}
//停止发言后的显示
DisplayStudent.prototype.cancelSpeech = function(dom, dom2, gid, online){
    this.speechUser = 0;
    this.speechToken = "";
    dom.find("span[data-speech]").hide();
    if(dom2){
        dom2.find("span[data-speech]").hide();
    }
    this.deleteDom(dom, dom2, gid);
    this.speechNum = 0;
    dom.attr("data-display", "0");
    if(dom2){
        this._groups[gid].speechNum = 0;
        dom2.attr("data-display", "0");
    }
    this.setDisplay(dom, dom2, gid, online, true);
}
DisplayStudent.prototype.offlineMore = function(dom, dom2, gid){
    this.deleteDom(dom, dom2, gid);
    this.onlineNum--;
    if(dom2){
        this._groups[gid].onlineNum--;
    }
    this.setStudentsIndex(gid);
}
DisplayStudent.prototype.offlineOne = function(dom, dom2, gid){
    dom.attr("data-display", "0");
    this.onlineNum--;
    dom.attr("token", "");
    dom.find("div > span").hide();
    if(dom2){
        dom2.attr("data-display", "0");
        this._groups[gid].onlineNum--;
        dom2.attr("token", "");
        dom2.find("div > span").hide();
    }
    this.deleteDom(dom, dom2, gid);
    this.setDisplay(dom, dom2, gid, false, true);
}
DisplayStudent.prototype.raiseHand = function(dom, dom2, gid){
    var t = (new Date).valueOf();
    dom.attr("data-hand", t);
    dom.find("span[data-hand]").show();
    if(dom2){
        dom.attr("data-hand", t);
        dom.find("span[data-hand]").show();
    }
    if(1 == dom.attr("data-display")){
        dom.attr("data-display", "0");
        this.onlineNum--;
        if(dom2){
            dom2.attr("data-display", "0");
            this._groups[gid].onlineNum--;
        }
        this.deleteDom(dom, dom2, gid);
        this.setDisplay(dom, dom2, gid, true, true);
    }
}
