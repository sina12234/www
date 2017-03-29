// 引导模块
define(['jquery','global'], function($,global) {
    // 礼物引导  1
    // 笔记引导  2 
    var guideList = [1, 2];
    /**
     * @type: 
     * node - 页面中的节点
     * html - 代码
     */
    var contents = {
        1: [{
            type: 'node',
            selector: '#guideNote1'
        }, {
        	type: 'node',
        	selector: '#guideNote2'
        }],
        2: [{
        	type: 'node',
        	selector: '#guideGift'
        }]  
        // 3: [{
        // 	type: 'html',
        // 	code: '<p>这是一个段落引导</p>'
        // }]
    };
    var Guide = {
    	selConfirm: '.guide-confirm',
    	selCancel: '.guide-cancel',
    	data: [],
    	_show: function(){
    		var data = this.data[this.now];
    		if(!data){
    			this._remove();
    		}else{
    			var dom = data.type == 'node' ? $(data.selector) : $(data.code);
    			this.box.html('');
    			if(this.now == this.data.length - 1){
    				box.find(this.selConfirm).text('知道啦');
    			}
				this.el.show();
    		}
    	},
    	now: 0,
    	_remove: function(){
    		this.el.hide().remove();
    		this = null;
    	},
    	transData: function(data){
    		var arr = [];
    		var v = '';
    		for (var i = 0,l = guideList.length; i < l; i++) {
    			v = guideList[v];
    			if(data[] == 'true'){
    				arr.concat(contents[v]);
    			}
    		}
    		this.data = arr;
    		if(arr.length > 0){
    			this.bind();
    			this._show();
    		}else{
    			this._remove();
    		}
    	},
    	bind: function(){
            this.el = $('<div id="guide" style="position: fixed;top: 0;left: 0;width: 100%;height: 100%;display:none"><div style="width: 100%;height: 100%;background: #000;opacity: 0.6;"></div></div>');
    		this.box = $('<div id="guideBox"></div>');
            this.el.append(this.box);
            var _this = this;
    		this.box.on('click', selConfirm, function(event) {
    			event.preventDefault();
    			if(_this.now == _this.data.length - 1){
    				_this._remove();
    			}else{
	    			_this.now++;
	    			_this.show();
    			}
    		}).on('click', selCancel, function(event) {
    			event.preventDefault();
    		});
    	},
    	init: function(){
    		var _this = this;
    		$.ajax({
    			url: '/user/guide/guide',
    			type: 'POST',
    			dataType: 'json',
    			data: {
                    guide: guideList,
                    planId: global.get('plan_id')
                }
    		})
    		.done(function(res) {
    			if(res.code == '0'){
    				_this.transData(res.data);
    			}else{
    				console.error(res.code,res.msg);
    			}
    		});
    	}
    }
    $(document).ready(function($) {
    	Guide.init();
    });
});
