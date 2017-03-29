function DisplayOptimize(dom, number, step, moveCallback){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    this._dom = dom;
    this._number = number;
    this._step = step;
    //显示的元素为[start, end)
    var a = dom.children();
    if(a.length <= number){
        a.show();
        this._start = 0;
        this._end = a.length;
    }else{
        a.hide();
        this._start = 0;
        this._end = number;
        for(var i=0;i<number;i++){
            a.eq(i).show();
        }
    }
    var _this = this;
    $(this._dom).scroll(function(e){
        if(moveCallback){
            moveCallback(e);
        }else{
            _this.move();
        }
    });
}
DisplayOptimize.prototype.append = function(dom){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._end - this._start < this._number){
        dom.show();
        this._end++;
    }else{
        dom.hide();
    }
    this._dom.append(dom);
}
DisplayOptimize.prototype.appends = function(dom){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._end - this._start + dom.length <= this._number){
        dom.show();
        this._end += dom.length;
    }else if(this._end - this._start < this._number){
        var num = this._number - (this._end - this._start);
        for(var i=0;i<dom.length;i++){
            if(i <= num){
                dom.eq(i).show();
            }else{
                dom.eq(i).hide();
            }
        }
        this._end = this._number;
    }else{
        dom.hide();
    }
    this._dom.append(dom);
}
DisplayOptimize.prototype.prepend = function(dom, notMove){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._end - this._start < this._number){
        dom.show();
        this._end++;
    }else{
        dom.hide();
        this._start++;
        this._end++;
    }
    this._dom.prepend(dom);
    if(!notMove && 1 == this._start){
        this.moveStart();
    }
}
DisplayOptimize.prototype.prepends = function(dom){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    for(var i=dom.length-1;i>=0;i--){
        this.prepend($(dom[i]), true);
    }
}
DisplayOptimize.prototype.insert = function(dom, index){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(0 == index){
        this.prepend(dom);
    }else if(index >= this._dom.children().length){
        this.append(dom);
    }else{
        if(this._end - this._start < this._number){
            dom.show();
            this._end++;
        }else if(index <= this._start){
            dom.hide();
            this._start++;
            this._end++;
        }else if(index >= this._end){
            dom.hide();
        }else{
            dom.show();
            this._dom.children().eq(this._end-1).hide();
        }
        dom.insertBefore(this._dom.children().eq(index));
    }
}
DisplayOptimize.prototype.inserts = function(dom, index){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    for(var i=0;i<dom.length;i++){
        this.insert($(dom[i]), index+i);
    }
}
DisplayOptimize.prototype._delete = function(dom){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    var index = dom.index();
    if(index >= 0){
        if(index < this._start){
            this._start--;
            this._end--;
        }else if(index >= this._end){
            ;
        }else{
            var children = this._dom.children();
            if(this._end < children.length){
                children.eq(this._end).show();
            }else if(this._start > 0){
                this._start--;
                this._end--;
                children.eq(this._start).show();
            }else{
                this._end--;
            }
        }
        dom.remove();
    }
    return index;
}
DisplayOptimize.prototype.deleteMore = function(dom){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    var index = dom.index();
    if(index >= 0){
        var h = 0;
        if(index < this._start){
            this._start--;
            this._end--;
        }else if(index >= this._end){
            ;
        }else{
            var children = this._dom.children();
            if(this._end < children.length){
                children.eq(this._end).show();
            }else if(this._start > 0){
                this._start--;
                this._end--;
                children.eq(this._start).show();
            }else{
                this._end--;
            }
            h = dom.outerHeight();
        }
        dom.remove();
        if(h){
            var move = this._dom.scrollTop() - h;
            this._dom.scrollTop(move);
        }
    }
    return index;
}
DisplayOptimize.prototype.move= function(notMove){
    var a = this._dom.children();
    var h = this._dom[0].scrollHeight;
    var b = this._dom.scrollTop() + this._dom.height() / 2;
    var move = this._dom.scrollTop();
    var i = 0;
    if(b < h * 0.15){
        if(0 == this._start){
            return;
        }
        var num = this._step;
        if(num > this._start){
            num = this._start;
        }
        for(i=0;i<num;i++){
            var d = a.eq(this._start-i-1);
            d.show();
            move += d.outerHeight();
            a.eq(this._end-i-1).hide();
        }
        this._start -= i;
        this._end -= i;
        if(move > h / 2){
            move = h / 2;
        }
        if(!notMove){
            this._dom.scrollTop(move);
        }
    }else if(b > h * 0.85){
        if(this._end == a.length){
            return;
        }
        var num = this._step;
        if(num > a.length - this._end){
            num = a.length - this._end;
        }
        for(i=0;i<num;i++){
            var d = a.eq(this._start+i);
            move -= d.outerHeight();
            d.hide();
            a.eq(this._end+i).show();
        }
        this._start += i;
        this._end += i;
        if(move < h / 2){
            move = h / 2;
        }
        if(!notMove){
            this._dom.scrollTop(move);
        }
    }
}
DisplayOptimize.prototype.moveStart = function(){
    var a = this._dom.children();
    if(a.length <= this._number){
        this._dom.scrollTop(0);
        return;
    }
    var end = this._number;
    if(end <= this._start){
        for(var i=0;i<end;i++){
            a.eq(i).show();
            a.eq(this._start+i).hide();
        }
    }else{
        for(var i=0;i<this._start;i++){
            a.eq(i).show();
            a.eq(end+i).hide();
        }
    }
    this._start = 0;
    this._end = end;
    this._dom.scrollTop(0);
}
DisplayOptimize.prototype.moveEnd = function(){
    var a = this._dom.children();
    if(a.length <= this._number){
        this._dom.scrollTop(99999999);
        return;
    }
    var h = this._dom[0].scrollHeight;
    var b = this._dom.scrollTop() + this._dom.height();
    var start = a.length - this._number;
    if(this._end <= start){
        for(var i=0;i<this._number;i++){
            a.eq(this._start+i).hide();
            a.eq(start+i).show();
        }
    }else{
        for(var i=0;i<start-this._start;i++){
            a.eq(this._start+i).hide();
            a.eq(this._end+i).show();
        }
    }
    this._start = start;
    this._end = a.length;
    this._dom.scrollTop(99999999);
}
DisplayOptimize.prototype.showNumber = function(){
    var num = 0;
    this._dom.children().each(function(i, elem){
        if("none" != $(elem).css("display")){
            num++;
        }
    });
    return num;
}
