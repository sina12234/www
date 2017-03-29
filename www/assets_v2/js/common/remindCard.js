(function($,undefined){//根据cookie提示上课
    var wo={};
    window.wo=wo;
    wo.remindCard=function(uid,pre){
        var dataObj={};
        var ckData={};//cookie值
        var dataArr=[];//ID数组
        var itemId={};//单个ID
        var uid=uid;//用户ID
        var pre=pre;//排课ID
        ckData=$.cookie("plan_id");
        if(ckData){
            dataArr=JSON.parse(ckData);
        }
        $.ajax({
            type:'get',
            url:'/course.ajax.getUserWillBeginRemindAjax/',
            data:{ uid:uid },
            dataType:'json',
            contentType:'application/json;charset=utf-8',
            async:'false',
            success:function(data){
                if(data.code==0){
                    dataObj=data.data;
                    addreminder();
                    if(ckData==null){
                        $.each(dataObj,function(){
                            var thisPlan_id=this.plan_id;
                            if(!(pre&&pre==thisPlan_id)){
                                addCar(this);
                            }
                        });
                    }else{
                        $.each(dataObj,function(){
                            var flag=false;
                            var thisPlan_id=this.plan_id;
                            ckData=eval(ckData);
                            $.each(ckData,function(){
                                if((thisPlan_id==this.plan_id)||(pre&&pre==thisPlan_id)){
                                    flag=true;
                                }
                            })
                            if(!flag){
                                addCar(this);
                            }
                        })
                    }
                }
            }
        });

    function addreminder(){
        var reminder=$("<div>").addClass('class-reminder');
        $('body').append(reminder);
    }
    function addCar(m){
        var Div=$("<div>").addClass('reminder-ct reminder-ct-one fs14').attr('id',m.plan_id);
        var spanIcon=$("<span>").addClass('btn-clear clear-reminder-btn');
        var H6=$("<h6>").html('上课提醒：');
        var H5=$("<h5>").addClass('tec mt10');
        var p1=$("<p>").html(m.course_name);
        var p2=$("<p>").html('<span>'+m.section_name+'</span><span style="color: #f01400;">'+m.plan_start_time+'</span><span>'+m.plan_status_info+'</span>');
        var A=$("<a>").html('进入课堂').attr('href',m.url);
        Div.append(spanIcon).append(H6).append(p1).append(p2).append(H5.append(A));
        $("div.class-reminder").append(Div);
        spanIcon.on('click',function(){
            var thisPlan_id=$(this).parent().attr("id");
            itemId={plan_id:thisPlan_id};
            dataArr.push(itemId);
            var dataStr=JSON.stringify(dataArr);
            var domain=document.domain;
            domain=domain.split('.')[1]+'.com';
            $.cookie('plan_id',dataStr,{expires:1,path:'/',domain:domain});
            Div.remove();
        });
    }
    }
}(jQuery));

