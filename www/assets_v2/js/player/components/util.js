/**
工具函数模块
 */
define(['jquery', 'userApi', 'layer'], function($, userApi, layer) {
    var g = global.get(['isLogin', 'isReg', 'plan_info', 'isSign', 'resellOrgId', 'userStatus']);
    var isLogin = g.isLogin;
    var isSign = g.isSign;
    var isReg = g.isReg;
    var resellOrgId = g.resellOrgId;
    var plan_info = g.plan_info;
    var course_id = plan_info.course_id;
    var class_id = plan_info.class_id;
    var source = 'reg';
    var courseFeeType = g.userStatus.courseFeeType;
    var coursePrice = g.coursePrice;
    var _emoji2url = {
        "00": "14",
        "01": "57",
        "02": "50",
        "03": "29",
        "04": "30",
        "05": "32",
        "06": "24",
        "07": "21",
        "08": "26",
        "09": "11",
        "0a": "25",
        "0b": "7",
        "0c": "15",
        "0d": "90",
        "0e": "36",
        "0f": "114",
        "0g": "17",
        "0h": "65",
        "0i": "76",
        "0j": "74",
        "0k": "13",
        "0l": "19",
        "0m": "20",
        "0n": "72",
        "0o": "75",

        "0p": "001",
        "0q": "002",
        "0r": "003",
        "0s": "004",
        "0t": "005",
        "0u": "006",
        "0v": "008",
        "0w": "009",
        "0x": "010",
        "0y": "012",
        "0z": "016",
        "10": "018",
        "11": "022",
        "12": "023",
        "13": "027",
        "14": "028",
        "15": "031",
        "16": "033",
        "17": "034",

        "18": "035",
        "19": "037",
        "1a": "040",
        "1b": "044",
        "1c": "045",
        "1d": "046",
        "1e": "048",
        "1f": "051",
        "1g": "053",
        "1h": "054",

        "1i": "055",
        "1j": "056",
        "1k": "059",
        "1l": "061",
        "1m": "063",
        "1n": "064",
        "1o": "066",
        "1p": "070",
        "1q": "071",
        "1r": "073",

        "1s": "077",
        "1t": "081",
        "1u": "083",
        "1v": "085",
        "1w": "087",
        "1x": "088",
        "1y": "089",
        "1z": "091",
        "20": "120",
        "21": "121",

        "22": "125",
        "23": "129",
        "24": "150",
        "25": "170",
        "26": "171",
        "27": "172",
        "28": "173",
        "29": "174",
        "2a": "175",
        "2b": "176",

        "2c": "177",
        "2d": "178"
    };
    var _url2emoji = {};
    for (var k in _emoji2url) {
        var v = _emoji2url[k];
        _url2emoji[v] = k;
    }
    var _emoji2urlReg = /\/:(..)/g;
    var _url2emojiReg = /<img class="emoji-item" src="\/assets\/images\/expression\/([0-9a-z]+)\.gif.*?".*?>/gi;

    function emoji2img(s, p1) {
        if (p1 in _emoji2url) {
            return '<img class="emoji-item" src="/assets/images/expression/' + _emoji2url[p1] + '.gif" width="24px" height="24px" />';
        } else {
            return s;
        }
    }

    function img2emoji(s, p1) {
        if (p1 in _url2emojiReg) {
            return "/:" + _url2emojiReg[p1];
        } else {
            return s;
        }
    }
    $.fn.extend({
        emoji2img: emoji2img,
        img2emoji: img2emoji,
        urlPath: function(url) {
            var i = url.indexOf("//");
            if (i < 0) {
                return url;
            }
            i = url.indexOf("/", i + 2);
            if (i < 0) {
                return url;
            }
            return url.substr(i);
        },
        toggleActive: function(i) {
            if (arguments.length > 0) {
                $(this).eq(i).addClass('active').siblings().removeClass('active');
            } else {
                $(this).addClass('active').siblings().removeClass('active');
            }
            return $(this);
        }
    });
    $.extend({
        reloadPage: function() {
            // fix weixin reload
            if (userApi.isWeiXin()) {
                window.location.href = window.location.href + "?" + 10000 * Math.random();
            } else {
                window.location.href = window.location.href;
            }
        },
        calLen: function(s) {
            return s.replace(/[\u0391-\uFFE5]/g, "aa").length;
        },
        login_sign: function() {
            alert('a');
            var w, h;
            if ($(window).width() > 800) {
                w = '476px';
                h = '360px';
            } else {
                w = '90%';
                h = '400px';
            }
            if (!isLogin) {
                userApi.setCookie("course.autoreg", plan_info.course_id);
                var argsStr = location.hostname + "/course.plan.play/{$plan_id}&cid=" + course_id + "&clid=" + class_id + "&source=" + source + "&resellOrgId=" + resellOrgId;
                if (userApi.isWeiXin()) {
                    location.replace("/weixin.user.login/?url=//" + argsStr);
                } else {
                    if ($(window).width() < 800) {
                        location.replace("/site.main.login/?url=//" + argsStr);
                    } else {
                        layer.open({
                            title: false,
                            type: 2,
                            area: [w, h],
                            shadeClose: true,
                            content: ['/layer.main.userLogin/' + course_id + '/' + class_id + '/0/' + source + '/0/' + resellOrgId, 'no']
                        });
                    }
                }
            } else if (!isSign) {
                if (courseFeeType == 1 && coursePrice == 0) {
                    courseFeeType = 0;
                }
                if (courseFeeType == 0 && coursePrice == 0) {
                    $.post("/course/info/AddReg", {
                        classId: class_id,
                        cid: course_id
                    }, function(r) {
                        if (r.code == 0) {
                            layer.msg('报名成功', {
                                icon: 1
                            }, function() {
                                $.reloadPage();
                            });
                        } else if (r.code == 1021) {
                            layer.open({
                                type: 2,
                                title: false,
                                area: [w, h],
                                shadeClose: true,
                                content: ['/layer.main.userLogin/' + course_id + '/' + class_id + '/0/' + source + '/0/' + resellOrgId, 'no']
                            });
                            return false;
                        } else {
                            layer.msg(r.errMsg, function() {
                                $.reloadPage();
                            });
                        }
                    }, "json");
                } else if (courseFeeType == 1 || coursePrice > 0) {
                    $.post("/course/pay/check", {
                        classId: class_id,
                        cid: course_id,
                        resellOrgId: resellOrgId
                    }, function(r) {
                        if (r.code == 0) {
                            parent.location.href = "/order.main.buy/course/" + course_id + "/" + class_id + "/" + resellOrgId;
                        } else {
                            layer.msg(r.errMsg, function() {
                                $.reloadPage();
                            });
                        }
                    }, "json");
                }
            }
        },
        preloadimages: function(arr) {
            var newimages = [],
                loadedimages = 0
            var postaction = function() {} //此处增加了一个postaction函数
            arr = (typeof arr != "object") ? [arr] : arr;

            function imageloadpost() {
                loadedimages++
                if (loadedimages == arr.length) {
                    postaction(newimages) //加载完成用我们调用postaction函数并将newimages数组做为参数传递进去
                }
            }
            for (var i = 0; i < arr.length; i++) {
                newimages[i] = new Image()
                newimages[i].src = arr[i]
                newimages[i].onload = function() {
                    imageloadpost()
                }
                newimages[i].onerror = function() {
                    imageloadpost()
                }
            }
            return { //此处返回一个空白对象的done方法
                done: function(f) {
                    postaction = f || postaction
                }
            }
        },
        add_score_point: function(){
            
        },
        htmlEncode: function(str){
            var s = "";
            if(str.length == 0) {
                return "";
            }
            s = str.replace(/&/g,"&amp;");
            s = s.replace(/</g,"&lt;");
            s = s.replace(/>/g,"&gt;");
            s = s.replace(/ /g,"&nbsp;");
            s = s.replace(/\'/g,"&#39;");
            s = s.replace(/\"/g,"&quot;");
            return s;
        }
    });
    return $;
});
