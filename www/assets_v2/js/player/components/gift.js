// 礼物模块
define(['jquery','global'],function($,global){
	var g = global;
	var supportGift = g.get('supportGift'),
		planId = g.get('planId'),
		isLogin = g.get('isLogin'),
		isSign = g.get('planId'),
		isLive = g.getFunc('isLive');
	window.supportGift = true;
	jQuery(document).ready(function($) {
	    var planId = window.planId;
	    var isLogin = window.isLogin;
	    var isSign = !window.isSign;
	    var isLive = function(){return $("#live-status").attr('data-status') == 'living' ? true : false;}
	    var flowerImg = '<i class="icon-gift-flower"></i>';
	    var sendEffectImage = (function(){
	        var arr = [];
	        for (var i = 1; i < 11; i++) {
	            arr.push('/assets_v2/img/send-gift-effect/'+i+'.png');
	        }
	        return arr;
	    })();
	    var sendEffectImageFlag = sendEffectImage.slice();
	    function preloadimages(arr){
	        function load(i){
	            return function(){
	                var img = new Image()
	                img.src=arr[i]
	                img.onload = function(){
	                    arr[i] = 1;
	                }
	            }
	        }
	        for (var i=0; i<arr.length; i++){
	            load(i)();
	        }
	    }
	    preloadimages(sendEffectImageFlag);
	    var _config = {
	        supportGift: window.supportGift,
	        url: {
	            dataGet: '/user/gift/getStudentGift',
	            guide: '/user/gift/getGuide',
	            ifReceive: '/user/gift/addPlanFlower',
	            receive: '/user/gift/getFlowers'
	        },
	        effect:['#ffe11b','#ffcc1b','#ffbc1b','#ff961b','#ff711b','#ff561b','#ff3000','#f20303','#f20303','#f20379'],
	        giftSendEffectDom: function(type,i){
	            var str1 = '<div id="giftSendEffect" class="gift-send-effect" style="display:none">'
	                     +'     <p>连送老师<i class="icon-gift-flower"></i><span class="send-count">x'+i+'</span></p>'
	                     +'</div>';
	            var str2 = '<div id="giftSendEffect" class="gift-send-effect" style="display:none">'
	                     +  '<img src="' +sendEffectImage[i - 1]+ '">'
	                     +  '</div>';
	            return type == 'img'? str2 : str1;
	        },
	        giftReceiveDom: (function(){
	            var str = '<div class="gift-receive" id="giftReceive" style="display:none">'
	                     +'      <div class="gift-receive-header">'
	                     +'          <img src="/assets_v2/img/gift-receive-title.png">'
	                     +'          <i class="gift-receive-close">╳</i>'
	                     +'      </div>'
	                     +'      <div class="gift-receive-body">'
	                     +'          <p>领取鲜花可以送给老师哦~<br />签到也可以领取哦</p>'
	                     +'          <span class="gift-btn gift-receive-btn">点击领取</span>'
	                     +'      </div>'
	                     +'</div>';
	            return str;
	        })(),
	        giftType:{
	            1:'FLOWER'
	        }
	        
	    };
	    var el = {
	        supportClassName: 'supportGift',
	        forbidClassName: 'forbid',
	        giftSendEffectId: 'giftSendEffect',
	        giftReceiveId: 'giftReceive',
	        playerContent: $('#playerContent'),
	        gift: $('#gift'),
	        giftGuide: $('#guideGift'),
	        teacherGift: $('#teacherGift'),
	        teacherGiftCount: $('#teacherGiftCount'),
	        userGiftCount: $('#userGiftCount'),
	        sendClassName: {
	            all: 'gift-send',
	            1: 'gift-send-flower'
	        }
	    };

	    // 送礼限制
	    var temp_lastTime = null;
	    var temp_thisTime = '';
	    var temp_limitTime = 2000;
	    var temp_forbidTimeout = null;
	    var temp_sendTimeout = null;

	    // 送礼连击
	    var temp_sendCount = 0;
	    var temp_sendType = null;
	    var temp_effectTimeout = null;

	    var temp_giftCount = {
	        1: 0,
	        2: 0,
	        3: 0,
	        4: 0
	    };

	    // 领取礼物定时
	    var temp_receiveTimeout = null;

	    var data_teacherGiftCount = 0;
	    var data_userGiftCount = {
	        1: 0
	    };

	    function updateTeacherGift(num){
	        data_teacherGiftCount = num;
	        el.teacherGiftCount.text(data_teacherGiftCount);
	    }

	    function dataTrans(d){
	        var data = {};
	        $.each(d,function(i, v) {
	            data[v.type] = v.giftCount;
	        });
	        return data;
	    }
	    /**
	     * [updateUserGift 更新礼物数量]
	     * @param  {[type]} d [传入礼物数据]
	     * @param  {[type]} flag   [是否禁止数据转换]
	     */
	    function updateUserGift(d,flag){
	        var data = null;
	        if(!flag){
	            data = dataTrans(d);
	        }else{
	            data = d;
	        }
	        data_userGiftCount = $.extend({}, data_userGiftCount, data);
	        temp_giftCount = $.extend({}, temp_giftCount, data_userGiftCount);
	        $.each(data_userGiftCount,function(i, v) {
	            if(v <=0 ){
	                setForBid.call($('.'+el.sendClassName[i]),true);
	            }else{
	                setForBid.call($('.'+el.sendClassName[i]),false);
	            }
	            var n = v > 99 ? '99+' : v < 0 ? 0 : v;
	            $('.'+el.sendClassName[i])
	            .attr('title',(v > 0 ? '' : '您没有鲜花送给老师啦'))
	            .find('.gift-count').text(n);
	        });
	    }

	    function setForBid(flag){
	        var f = flag;
	        if(temp_giftCount[1] < 1){
	            f = true;
	        }
	        //f || !isSign ? this.addClass(el.forbidClassName) : this.removeClass(el.forbidClassName);
	        f ? this.addClass(el.forbidClassName) : this.removeClass(el.forbidClassName);
	    }

	    var _GIFT = function(op) {
	        if (this instanceof _GIFT) {
	            this.dft = {
	            };
	            this.opt = $.extend({},this.dft,op);
	            this.init();
	        } else {
	            return new _GIFT(op);
	        }
	    };
	    _GIFT.prototype = {
	        sendValid: function(){
	            var flag = true;
	            temp_thisTime = new Date();
	            if(temp_thisTime - temp_lastTime < temp_limitTime){
	                //layer.msg('赠送太频繁');
	                flag = false;
	                
	            }
	            if(temp_giftCount[temp_sendType] < 1){
	                layer.msg('您没有鲜花送给老师啦');
	                flag = false;
	            }
	            if(!flag){
	                setForBid.call($('.'+el.sendClassName[temp_sendType]),true);
	                clearTimeout(temp_forbidTimeout)
	                temp_forbidTimeout = setTimeout(function(){
	                    setForBid.call($('.'+el.sendClassName['all']),false);
	                },temp_limitTime);
	            }
	            return flag;
	        },
	        send: function(type){
	            if($('.'+el.sendClassName[type]).hasClass(el.forbidClassName)){
	                return false;
	            }
	            if(!temp_sendType){
	                temp_sendType = type;
	            }else if(temp_sendType != type){
	                this.sendGift();
	            }
	            if(temp_sendCount>9){
	                this.sendGift();
	                return false;
	            }
	            if(!this.sendValid()){
	                return false;
	            }
	            var self = this;
	            clearTimeout(temp_sendTimeout);
	            temp_sendCount += 1;
	            temp_giftCount[type] -= 1;
	            temp_giftCount[type] = temp_giftCount[type] < 0 ? 0 : temp_giftCount[type];
	            if(temp_sendCount > 1){ this.sendEffect(); }
	            var n = temp_giftCount[type]  > 99 ? '99+' : temp_giftCount[type]  < 0 ? 0 : temp_giftCount[type] ;
	            $('.' + el.sendClassName[type]).find('.gift-count').text(n);
	            temp_sendTimeout = setTimeout(function(){
	                self.sendGift();
	            },500);
	        },
	        sendGift: function(){
	            temp_lastTime = new Date();
	            var self = this;
	            if(temp_sendCount>0 && temp_sendType){
	                this.messageSend();
	                console.log('赠送了'+temp_sendCount+'朵花');
	                temp_sendCount = 0;
	                temp_sendType = null;
	            }
	        },
	        sendEffect: function(){
	            var dom = null;
	            var newDom = null;
	            if(sendEffectImageFlag[temp_sendCount-1] == 1){
	                newDom = _config.giftSendEffectDom('img',temp_sendCount);
	                if(!$('#'+el.giftSendEffectId)[0]){
	                    dom = $(newDom);
	                    $('#chat').append(dom);
	                }else{
	                    dom = $('#'+el.giftSendEffectId);
	                    dom.html($(newDom).html());
	                }
	            }else{
	                newDom = _config.giftSendEffectDom('text',temp_sendCount);
	                if(!$('#'+el.giftSendEffectId)[0]){
	                    dom = $(newDom);
	                    $('#chat').append(dom);
	                }else{
	                    dom = $('#'+el.giftSendEffectId);
	                    dom.html($(newDom).html());
	                }
	                if(temp_sendCount >1 && temp_sendCount< 11){
	                    //dom.attr('data-effect','s'+temp_sendCount);
	                    dom
	                    .css('color', _config.effect[temp_sendCount-1])
	                    .find('.send-count').css('font-size', 18+(temp_sendCount-1)*2+'px');
	                }
	            }
	            if(dom.is(":hidden")){
	                dom.fadeIn();
	            }
	            clearTimeout(temp_effectTimeout);
	            temp_effectTimeout = setTimeout(function(){
	                dom.fadeOut();
	            },2000); 
	        },
	        sendFail: function(info){
	            if(!info.ext && !isNaN(parseInt(info.ext))){
	                updateUserGift({1:parseInt(info.ext)},true);
	            }else{
	                updateUserGift({1:data_userGiftCount[1]},true);
	            }
	        },
	        sendSuccess: function(info){
	            info.ext && !isNaN(parseInt(info.ext)) && updateUserGift({1:parseInt(info.ext)},true);
	            console.log('剩余花的数量:'+data_userGiftCount[1]);
	        },
	        messageSend: function(){
	            // 赠送
	            console.log('赠送之前花的数量：'+data_userGiftCount[temp_sendType])
	            var content = {
	                "gift": _config.giftType[temp_sendType],
	                "number": temp_sendCount
	            };
	            var self = this;
	            message.giftSend(JSON.stringify(content),function(value,info){
	                if(!temp_sendType && temp_sendCount == 0 && info){
	                    if(info.code == 0){
	                        self.sendSuccess(info);
	                    }else{
	                        self.sendFail(info);
	                    }
	                }
	            });
	        },
	        messageAddMsg: function(res){
	            var from = res.uf_n || '';
	            var to = res.ut_n || window.teacherName;
	            var c = res.c && JSON.parse(res.c) || '';
	            if(from && to && c && _displayChatList){
	                var num =  c.number;
	                var type = c.gift;
	                var str = '<li data-uid="'+userId+'"><p>\''+from+'\' 送给 \''+to+'\' ';
	                if(num <= 1){
	                    str += '一朵鲜花 '+ flowerImg;
	                }else{
	                    str += '鲜花 '+ flowerImg + ' x '+ num;
	                }
	                str += '</p></li>';
	                _displayChatList.append(str);
	                _displayChatList.moveEnd(_activeGroupId || 0);
	                _scrollFlag = true;
	                updateTeacherGift( parseInt(data_teacherGiftCount) + parseInt(num));
	                return;
	            }
	        },
	        bind: function(){
	            var self = this;
	            // 绑定dom事件
	            // 赠送礼物
	            el.gift.on('click', '.'+el.sendClassName.all, function(event) {
	                event.preventDefault();
	                var type = $(this).attr('data-type');
	                if(!type){
	                    return false;
	                }
	                self.send(type);
	            });
	        },
	        showFunction: function(){
	            $('.'+el.supportClassName).removeClass('hide');
	        },
	        receiveFail: function(){

	        },
	        receiveEnd: function(flag){
	            clearTimeout(temp_receiveTimeout);
	            $('#'+el.giftReceiveId)[0] && $('#'+el.giftReceiveId).remove();
	            flag && this.receiveSend();
	        },
	        receiveSend: function(){
	            var self = this;
	            console.log('领取成功');
	            var ajax = $.ajax({
	                url: _config.url.receive,
	                type: 'POST',
	                dataType: 'json',
	                data: {planId: planId}
	            })
	            .done(function(res){
	                if(res.code == 0){
	                    receiveSuccess();
	                }else{
	                    self.receiveFail();
	                }
	            });
	            return ajax;
	        },
	        receive: function(){
	            var self = this;
	            $.ajax({
	                url: _config.url.ifReceive,
	                type: 'POST',
	                dataType: 'json',
	                data: {
	                    planId: planId,
	                    courseId: window.courseId
	                }
	            })
	            .done(function(res) {
	                if(res.code == 0){
	                    if(res.status && res.status == "true"){
	                        if(!$('#'+el.giftReceiveId)[0]){
	                            var dom = $(_config.giftReceiveDom);
	                            el.playerContent.append(dom);
	                            dom.on('click','.gift-receive-close',function(){
	                                self.receiveEnd();
	                            });
	                            dom.on('click','.gift-receive-btn',function(){
	                                self.receiveEnd(true);
	                            });
	                            dom.show();
	                            temp_receiveTimeout = setTimeout(function(){
	                                self.receiveEnd();
	                            },300*1000);
	                        }
	                    }else{
	                        self.receiveEnd();
	                    }
	                }else{
	                    self.receiveEnd();
	                    res.msg && console.log(res.msg)
	                }
	            });
	            
	        },
	        refreshData: function(){
	            $.ajax({
	                url: _config.url.dataGet,
	                type: 'POST',
	                dataType: 'json',
	                data: {planId: planId}
	            })
	            .done(function(res) {
	                if(res.code == 0){
	                    console.log('刷新礼物数据',res.result);
	                    if(res.result.gift && res.result.gift.length > 0){
	                        updateUserGift(res.result.gift);
	                    }else{
	                        setForBid.call($('.'+el.sendClassName['all']),true);
	                    }
	                    res.result.giftSum && updateTeacherGift(res.result.giftSum);
	                }else{
	                    console.log('礼物数据获取',res.msg);
	                }
	            });
	            
	        },
	        init: function(){
	            // 初始化
	            // 礼物功能展现
	            this.showFunction(); 
	            // 事件绑定
	            this.bind();
	            // 获取数据
	            this.refreshData(); 
	            // 送礼功能展现
	            if(isLogin){
	                $('.'+el.sendClassName['all']).removeClass('hide');
	                //setForBid.call($('.'+el.sendClassName['all']),!isSign);
	            }
	            // 礼物领取
	            if(isLogin && isSign && isLive()){
	                this.receive();
	            }
	        }
	    };
	    // pravite
	    
	    function receiveSuccess(){
	        updateUserGift({1:parseInt(data_userGiftCount[1]) + 1},true);
	    }
	    window.GuideGift = {
	        endCallback: function(){
	            resetAutoCloseList();
	        },
	        bind: function(){
	            var self = this;
	            el.giftGuide.on('click','.gift-guide-btn,.gift-guide-close',function(event) {
	                event.preventDefault();
	                el.giftGuide.hide();
	                self.endCallback();
	            });
	        },
	        init: function(){
	            var self = this;
	            if(!supportGift){
	                self.endCallback();
	                return false;
	            }
	            if(!$('#content').hasClass('expend') || !$('#chat').hasClass('active')){
	                $('[ck="talk"]').trigger('click');
	            }
	            $.ajax({
	                url: _config.url.guide,
	                type: 'POST',
	                dataType: 'json',
	                data: {
	                    uid: userId,
	                    planId: planId
	                }
	            })
	            .done(function(res) {
	                if(res.code == 0 && res.result.giftGuide && res.result.giftGuide == "true"){
	                    self.bind();
	                    el.giftGuide.show();
	                }else{
	                    console.log('礼物引导： ',res);
	                    self.endCallback();
	                }
	            });
	        }
	    };
	    if(_config.supportGift && !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
	        window.GIFT = new _GIFT();
	    }
	});
});