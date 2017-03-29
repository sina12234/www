$(function(){
    var courselist; //用于存放从数据库取出的所有节假日的time
    var coursetimelist; //用于存放从数据库取出的所有节假日的time
    createSelectYear();  //创建年份下拉,并给对应事件
    createMonthSelect();  //创建月份下拉，并给对应事件
    getcourseBymonth(Math.round(new Date().getTime()/1000)); //从数据库取出已经设置了的课程的数据
    createTabledate(parseInt($("#calendar-select-year").html()),parseInt($("#select-month").html()));
    leftrightclick();
    setRigth(new Date().getFullYear(),new Date().getMonth()+1,new Date().getDate());
});

function createSelectYear(){
    var yearSelect= $("#calendar-select-year");
    var Nowtime=new Date();
    var currYear=Nowtime.getFullYear();
    yearSelect.html(currYear);
}

function createMonthSelect(){
    var selectmonth=$("#select-month");
    var Nowtime=new Date();
    var currMonth=Nowtime.getMonth();
    selectmonth.html(currMonth+1);
}

function getcourseBymonth(month){
    coursetimelist=[]; //这里时间的为时间戳
    courselist=[]; //这里当前月的内容
    $.ajax({
        type:"POST",
        url:"/teacher.course.livePlanAjax",
        async:false,
        dataType: "json",
        data:{start_time:month},
        success:function(data){
            var data=data.data;
            for(var p in data){
                if(data[p] == null || data[p] == ""){
                   delete(p);
                }else{
                    coursetimelist.push(p);
                    courselist[p]=data[p];
                }
            }
        }
    });
}

//根据传入的年月，创建对应的table日期,并且在每个td中创建a标签用于事件，与样式内边框的设置
function createTabledate(year,yue){
    var rilitabledele=$("#calendar-main");
    if(rilitabledele!="" && rilitabledele!=null && rilitabledele!='undefined'){
        rilitabledele.empty();
    }
    //var rilitable=newElement('tbody');
    rilitabledele.addClass("calendar-main col-md-20 col-xs-20 pd0");
    var rili=$("#calendar-table");
    rili.append(rilitabledele);
    //先得到当前月第一天是星期几,然后根据这个星期算前面几天的上个月最后几天.
    var date=setdateinfo(year,yue,1);
    var weekday=date.getDay();
    var pervLastDay;
    if(weekday!=0){
        pervLastDay=weekday-1;
    }else{
        pervLastDay=weekday+6;
    }
    //得到上个月最后一天;
    var pervMonthlastDay=getPervMonthLastDay(year,yue);
    //上月最后几天循环
    var lastdays=pervMonthlastDay-pervLastDay+1;
    var tr=newElement('tr');
        tr.setAttribute("class","col-md-20 col-xs-20 pd0");
    //tr.style.borderBottom="1px solid #e3e4e6";
    for(var i=lastdays;i<=pervMonthlastDay;i++){
        var td=newElement("td");
        var a=getA(parseInt(yue)-1==0?parseInt(year)-1:year,parseInt(yue)-1==0?12:parseInt(yue)-1,i);
        a.style.color="#BFBFC5";
//      a.href ='javascript:pervA('+parseInt(yue)-1==0?parseInt(year)-1:year+','+parseInt(yue)-1==0?12:parseInt(yue)-1+','+i+');';
        td.appendChild(a);
        td.setAttribute("class","aboluo-pervMonthDays");
        tr.appendChild(td);
    }
    //这个月开始的循环
    var startDays=8-weekday==8?1:8-weekday;
    for(var i=1;i<=startDays;i++){
        var td=newElement("td");
        var b=getA(year,yue,i);
        td.appendChild(b);
        tr.appendChild(td);
    }
    rilitabledele.append(tr);
    //指定年月最后一天
    var currMonthLashDay=getCurrMonthLashDay(year,yue);
    //当月除开第一行的起点
    var currmonthStartDay=currMonthLashDay-(currMonthLashDay-startDays)+1;
    //当月还剩余的天数
    var syts=currMonthLashDay-startDays;
    //循环次数
    var xhcs=0;
    if(check(syts/7)){
    //是小数
    xhcs=Math.ceil(syts/7);//向上取整
    }else{
    xhcs=syts/7;
    }

    //这是下个月开始的变量;
    if (xhcs <5){
        xhcs=5
    }
    var jilvn=1;
    for(var i=0;i<xhcs;i++){
        var tr1=newElement('tr');
            tr1.setAttribute("class","col-md-20 col-xs-20 pd0");
        if(i!=xhcs-1){
           // tr1.style.borderBottom="1px solid #e3e4e6";
        }
        for(var n=1;n<=7;n++){
            var td=newElement('td');
            if(startDays==0){
                var c=getA(parseInt(yue)+1==parseInt(13)?parseInt(year)+1:year,parseInt(yue)+1==parseInt(13)?1:parseInt(yue)+1,jilvn);
                c.style.color="#BFBFC5";
                td.appendChild(c);
                td.setAttribute("class","aboluo-nextMonthDays");
                jilvn++;
                tr1.appendChild(td);
                continue;
            }else{
            startDays++;
            var d=getA(year,yue,startDays);
            td.appendChild(d);
                if(startDays==currMonthLashDay){
                    startDays=0;
                }
            tr1.appendChild(td);
            }

        }
        rilitabledele.append(tr1);
    }
    setHolidayred();
    setA();
}



//给上一个月最后几天点击跳转月份
function pervA(year,yue,day){
    getcourseBymonth(Math.round(Date.parse(year+'/'+yue+'/'+day)/1000));
    live_list_today(year,yue,day);
    createTabledate(year,yue);
    setRigth(year,yue,day);
    updateSelect(year,yue);
}

//给上一个月最后几天点击跳转月份
function nextA(year,yue,day){
    getcourseBymonth(Math.round(Date.parse(year+'/'+yue+'/'+day)/1000));
    live_list_today(year,yue,day);
    createTabledate(year,yue);
    setRigth(year,yue,day);
    updateSelect(year,yue);
}

function updateSelect(year,yue){
    var selectmonth=$("#select-month");
    var selectyear=$("#calendar-select-year");
    selectmonth.html(yue);
    selectyear.html(year);
}

//遍历table将date事件等
function setHolidayred(){
    var rows=$(".calendar-main").find("tr");
    for(var i=0;i<rows.length;i++){
        for(var j=0;j<rows[i].cells.length;j++){
            var cell=rows[i].cells[j];
            var a=rows[i].cells[j].childNodes[0];
            var adate=a.getAttribute("date");
            var arr=adate.split("-");
            var date=new Date();
            var year=date.getFullYear();
            var month=date.getMonth();
            var day=date.getDate();
            if(arr[0]==year && arr[1]==month+1 && arr[2]==day){
                //cell.setAttribute("class","calendar-this");
                a.setAttribute("class","calendar-this");
                a.innerHTML = "<i>今天</i>";
            }
            /*if(j>=rows[i].cells.length-2 ){
                if(cell.getAttribute("class")!="aboluo-nextMonthDays" && cell.getAttribute("class")!="aboluo-pervMonthDays"){
                    a.style.color="red";
                }
            }*/
        }
    }
}
//给rightdiv创建元素并赋值，根据传入的年月日给内部的元素赋值 ,月份是 1-12
function setRigth(year,yue,day){
    year=year.toString();
    yue=yue.toString();
    day=day.toString();
    var date=new Date();
    var currYear=date.getFullYear();
    var currMonth=date.getMonth()+1;
    var currDay=date.getDate();
    //设置rigthdiv的marginleft;
    var rigthdiv=$("#calendar-show");
    //给p中添加a显示值
    var span='<a href="javascript:today()" class="c-fl toToday" id="toToday">返回今天</a>';
    var date=setdateinfo(year,yue,day);
    var span1='<a href="#"" class="c-fr calendar-yellow" id="course_num">0节课</a>';
    var calendarbase=$(".calendar-show-base");
    calendarbase.empty();
    if(currYear==year && currMonth==yue && currDay==day) {
    } else {
        calendarbase.append(span);
    }
    calendarbase.append(span1);
    var currday=$(".calendar-show-panel");
    currday.html(day);
    setaclass(year,yue,day);
    live_list_today(year,yue,day);
}
function formatByDate(date){
    var days;
    date=date.substring(0,10);
    if (date.indexOf("-") >= 0 ){
        return date;
    }else{
        date.length>7 ? days=date.substring(0,4)+"-"+date.substring(4,6)+"-"+date.substring(8,6) : days=date.substring(0,4)+"-"+date.substring(4,6)+"-"+date.substring(6,7);
        return days;
    }
}

function formatByYearyueday(year,yue,day){
    year=year.toString();
    yue=yue.toString();
    day=day.toString();
    return year+"-"+(yue.length<2?'0'+yue:yue)+"-"+(day.length<2?'0'+day:day);
}

function setA(){
    var tbody=$(".calendar-main");
    var arr=tbody.find("a");
    for(var i=0;i<arr.length;i++){
        var date=arr[i].getAttribute("date");
        var datearr=date.split("-");
            if(arr[i].parentNode.className=="aboluo-pervMonthDays"){
                arr[i].setAttribute("onclick","javascript:pervA("+datearr[0]+","+datearr[1]+","+datearr[2]+",this);")
            }else if(arr[i].parentNode.className=="aboluo-nextMonthDays"){
                arr[i].setAttribute("onclick","javascript:nextA("+datearr[0]+","+datearr[1]+","+datearr[2]+",this);")
            }else{
            arr[i].setAttribute("onclick","javascript:setRigth("+datearr[0]+","+datearr[1]+","+datearr[2]+");");
            }
        for(var n=0;n<coursetimelist.length;n++){
            var span=newElement('span');
            if(formatByDate(coursetimelist[n]) == formatByDate(date)){
                span.setAttribute("class","calendar-dott");
                arr[i].appendChild(span);
            }
        }
    }
}

function setaclass(year,yue,day){
    var a=$(".calendar-select");
        var date=new Date();
        var year1=date.getFullYear();
        var month1=date.getMonth();
        var day1=date.getDate();
        if(year1==year && yue==month1+1 && day1==day){
        }else{
            var tbody=$(".calendar-main");
            var arr=tbody.find("a");
            arr.removeClass("calendar-select");
            for(var i=0;i<arr.length;i++){
                var date=arr[i].getAttribute("date");
                var datearr=date.split("-");
                if(datearr[0]==year && datearr[1]==yue && datearr[2]==day){
                    arr[i].setAttribute("class","calendar-select");
                }
            }
        }

}


//获取当前选取的日期
function getAclickDomDate(){
    var aclick=$(".calendar-select");
    if(aclick==""){
        var date=new Date();
        return date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
    }else{
        return aclick.getAttribute("date");
    }
}

//获取当前选中的a元素
function getAclickDom(){
    var aclick=$(".calendar-select");
    if(aclick==""){
        return $(".calendar-this");
    }else{
        return aclick;
    }
}


//创建元素
function newElement(val){
    return document.createElement(val);
}

//创建date对象并赋值
function setdateinfo(year,yue,day){
    var date=new Date();
    date.setFullYear(parseInt(year));
    date.setMonth(parseInt(yue)-1);
    date.setDate(parseInt(day));
    return date;
}

//根据年月日判断是不是星期六星期天 //yue 按12算
function isweekend(year,yue,day){
    var date=new Date();
    date.setFullYear(year);
    date.setMonth(yue-1);
    date.setDate(day);
    var week=date.getDay();
    if(week==0 || week==6){
        return true;
    }
    return false;
}

//根据getDay()返回对应的星期字符串
function getWeek(val){
    var weekxq=new Array();
    weekxq[0]="星期日";
    weekxq[1]="星期一";
    weekxq[2]="星期二";
    weekxq[3]="星期三";
    weekxq[4]="星期四";
    weekxq[5]="星期五";
    weekxq[6]="星期六";
    return weekxq[val];
}

//判断c是否是小数
function check(c){
    var r=/^[+-]?[1-9]?[0-9]*\.[0-9]*$/;
    return r.test(c);
}

//得到指定月的上个月最后一天传进来按 12月算
function getPervMonthLastDay(year,yue){
    return parseInt(new Date(year,yue-1,0).getDate());
}

//得到指定月最后一天 传进来按 12月算
function getCurrMonthLashDay(year,yue){
    if(yue>=12){
        year=year+1;
        yue=yue-12;
    }
    return parseInt(new Date(year,yue,0).getDate());
}


//创建a标签并设置公用属性
function getA(year,yue,day){
    var a=newElement("a");
    a.href="javascript:;";
    a.innerHTML='<i>'+day+'</i>';
    if( parseInt(yue) <= 9){
        yue='0'+yue;
    }
    a.setAttribute("date",year+"-"+yue+"-"+day);
    return a;
}


//给左右的a添加事件
function leftrightclick(){
    var lefta=$("#month_perv");
    var righta=$("#month_next");
    righta.click(function(){
        var monthselect=$("#select-month");
        var monthvalue=parseInt(monthselect.html());
        var yearselect=$("#calendar-select-year");
        var yearvalue=parseInt(yearselect.html());
        if(monthvalue==12){
            yearvalue+=1;
            monthvalue=1;
        }else{
            monthvalue+=1;
        }
        getcourseBymonth(Math.round(Date.parse(yearvalue+'/'+monthvalue+'/02')/1000));
        monthselect.html(monthvalue);
        yearselect.html(yearvalue);
        var aclick=withClass("calendar-select");
        createTabledate(yearselect.html(),monthselect.html());

        if(aclick==""){
            var pervdays1=getCurrMonthLashDay(yearselect.html(),monthselect.html()+1);
            if(new Date().getDate()>pervdays1){
                setRigth(yearselect.html(),monthselect.html(),pervdays1);
                live_list_today(yearselect.html(),monthselect.html(),pervdays1);
            }else{
                setRigth(yearselect.html(),monthselect.html(),new Date().getDate());
                live_list_today(yearselect.html(),monthselect.html(),new Date().getDate());
            }
        }else{
            var adate=aclick.getAttribute("date");
            var aarr=adate.split("-");
            aarr[0]=parseInt(aarr[0]);
            aarr[1]=parseInt(aarr[1]);
            aarr[2]=parseInt(aarr[2]);
            var pervdays=getCurrMonthLashDay(aarr[0],aarr[1]+1);
            if(aarr[2]>pervdays){
                aarr[2]=pervdays;
            }
            setRigth(aarr[1]+1==13?aarr[0]+1:aarr[0],aarr[1]+1==13?1:aarr[1]+1,aarr[2]);
            live_list_today(aarr[1]+1==13?aarr[0]+1:aarr[0],aarr[1]+1==13?1:aarr[1]+1,aarr[2]);
        }
    });
    lefta.click(function(){
        var monthselect=$("#select-month");
        var monthvalue=parseInt(monthselect.html());
        var yearselect=$("#calendar-select-year");
        var yearvalue=parseInt(yearselect.html())
        if(monthvalue==1){
            yearvalue-=1;
            monthvalue=12;
        }else{
            monthvalue-=1;
        }
        monthselect.html(monthvalue);
        getcourseBymonth(Math.round(Date.parse(yearvalue+'/'+monthvalue+'/02')/1000));
        yearselect.html(yearvalue);
        var aclick=withClass(".calendar-select");
        createTabledate(yearselect.html(),monthselect.html());
        if(aclick==""){
        var pervdays1=getPervMonthLastDay(yearselect.html(),monthselect.html());
            if(new Date().getDate()>pervdays1){
                setRigth(yearselect.html(),monthselect.html(),pervdays1);
            }else{
                setRigth(yearselect.html(),monthselect.html(),new Date().getDate());
            }
        }else{
        var adate=aclick.getAttribute("date");
        var aarr=adate.split("-");
        aarr[0]=parseInt(aarr[0]);
        aarr[1]=parseInt(aarr[1]);
        aarr[2]=parseInt(aarr[2]);
        var pervdays=getPervMonthLastDay(aarr[0],aarr[1]);
            if(aarr[2]>pervdays){
                aarr[2]=pervdays;
            }
        setRigth(aarr[1]-1==0?aarr[0]-1:aarr[0],aarr[1]-1==0?12:aarr[1]-1,aarr[2]);
        }
    });

}
function today(){
    var monthselect=$("#select-month");
    var yearselect=$("#calendar-select-year");
    var date=new Date();
    monthselect.html(date.getMonth()+1);
    yearselect.html(date.getFullYear());
    getcourseBymonth(Math.round(Date.parse(date.getFullYear()+'/'+(date.getMonth()+1)+'/01')/1000));
    live_list_today(date.getFullYear(),date.getMonth()+1,date.getDate());
    createTabledate(yearselect.html(),monthselect.html());
    setRigth(date.getFullYear(),date.getMonth()+1,date.getDate());
}
function live_list_today(year,month,day){
    var liveList=$('#live_list_today');
    month.length < 2 ?month='0'+month:month;
    var currday=year+'-'+month+'-'+day;
    if(month.length < 2){
        month='0'+month;
    }
    liveList.empty();
    var star_time,course_pic;
    var s=courselist;
    var currdays=year.toString()+month.toString()+day.toString();
    var course_info;
    if (!Array.prototype.indexOf){
        Array.prototype.indexOf = function(elt /*, from*/){
            var len = this.length >>> 0;
            var from = Number(arguments[1]) || 0;
                from = (from < 0) ? Math.ceil(from) : Math.floor(from);
            if (from < 0) from += len;
            for (; from < len; from++){
                if (from in this && this[from] === elt)
                return from;
            }
            return -1;
        };
    }
    if (coursetimelist.indexOf(currdays) >=0 ){
        for(d in s){
            if(formatByDate(d) === formatByDate(currday)){
                $('#course_num').html(s[d].length+'节课');
                for(var c=0;c<s[d].length;c++){
                course_info='<li>';
                course_info += '<div class="live-class-time col-lg-2 hidden-xs">'+s[d][c].start_time+'</div>'
                course_status=s[d][c].status;
                if (course_status=="2"){
                    course_info +='<div class="live-class-pic col-lg-4 col-xs-10 col-md-5 col-sm-5"><a href="'+s[d][c].url+'" target="_blank"></a><img src="'+s[d][c].thumb_med+'"><span class="live-course-time visible-xs">'+s[d][c].start_time+'</span><span class="liveing-icon">正在上课</span></div>';
                }else{
                    course_info += '<div class="live-class-pic col-lg-4 col-xs-10 col-md-5 col-sm-5"><a href="'+s[d][c].url+'" target="_blank"></a><img src="'+s[d][c].thumb_med+'"><span class="live-course-time visible-xs">'+s[d][c].start_time+'</span><span class="g-icon3 lessons-icon c-fl hidden-lg hidden-xs hidden-sm hiddne-md">录播课</span>';
					if(s[d][c].type_id=='3'){
					course_info+='<span class="taped-icon">线下课</span>';
					}
					course_info+='</div>';
                }

                course_info +='<div class="live-class-info col-lg-11 col-xs-8"><a href="'+s[d][c].url+'" target="_blank">';
                if (s[d][c].live_public_type=="0"){
                    course_info +='<p class="title fs14">'+s[d][c].title+'</p></a>';
                } else {
                    course_info +='<p class="title fs14">'+s[d][c].title+'<i class="try-icon"></i></p></a>';
                }
                course_info +='<p class="cGray fs12">'+s[d][c].section_name;
				course_info +='&nbsp;&nbsp;'+s[d][c].section_title+'</p>';
                course_info +='<p class="cGray fs12">'+s[d][c].class_name+'</p><p class="cGray fs12">学生：（'+s[d][c].user_total_class+'人）</p>';
                course_info +='</div>';
				if(s[d][c].status=="1"&&s[d][c].type_id !='3'&&s[d][c].t_status=='1'){
                    course_info +='<div class="live-class-btn col-lg-3 hidden-xs col-md-3 c-fr col-sm-3"><a href="javascript:void(0);" class="entry-btn">进入课堂</a></div>';
					course_info +='<p class="ter mt10 c-fl ml20 live-bkym"><a href='+s[d][c].prepare_course_url+' title="" target="_blank">备课</a> | <a href='+s[d][c].upload_video_url+' title="" target="_blank">上传视频</a></p></li>';

                }else if(s[d][c].status=="3"&&s[d][c].t_status=="1"){
                    course_info +='<div class="live-class-btn col-lg-3 hidden-xs col-md-3 c-fr col-sm-3"><a href='+s[d][c].video_t_url+' class="sign-btn" target="_blank">视频管理</a></div>';
					course_info +='<p class="ter mt10 c-fl ml15 live-jxsk"><a href='+s[d][c].continue_course_url+' title="" target="_blank">继续上课</a>';
                }else if(s[d][c].type_id=="3") {
                    course_info +='<div class="live-class-btn col-lg-3 hidden-xs col-md-3 c-fr col-sm-3"><a href="'+s[d][c].url+'"  target="_blank" class="no-btn">查看详情</a></div>';
                }else{
                    course_info +='<div class="live-class-btn col-lg-3 hidden-xs col-md-3 c-fr col-sm-3"><a href="javascript:void(0);" class="no-btn">未开课</a></div>';
					course_info +='<p class="tec mt10 c-fl ml30 live-bkym"><a href='+s[d][c].prepare_course_url+' title="" target="_blank">备课</a> | <a href='+s[d][c].upload_video_url+' title="" target="_blank">上传视频</a></p></li>';
                }
                liveList.append(course_info);
            }
        }
    }
    }else{
        liveList.append('<div class="col-xs-20 fs14 tac" style="padding-top:60px;display:block;"><img src="/assets_v2/img/platform/pet3.png"><p>没有课程，休息一下吧~</p></div>');
    }
}
//得到传入参数为class的对象(同名返回第一个)
function withClass(id){
    var targets= targets ||  document.getElementsByTagName("*");
    var list=[];
    for(var k in targets){
        var target=targets[k];
        if(target.className==id){
            return target;
        }
    }
    return "";
}
