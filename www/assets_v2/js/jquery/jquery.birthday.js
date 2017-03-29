/**
 *
 * @authors Your Name (you@example.org)
 * @date    2015-07-30 14:07:03
 * @version $Id$
 */
(function($){
  $.fn.birthday = function(options){
  var opts = $.extend({}, $.fn.birthday.defaults, options);//整合参数
  var $year = $(this).find("input[name="+ opts.year +"] ").parent();
  var $month = $(this).find("input[name="+ opts.month +"] ").parent();
  var $day = $(this).find("input[name="+ opts.day +"]> ").parent();
  var $year_dl=$year.find('dl');
  var $month_dl=$month.find('dl');
  var $day_dl=$day.find('dl');
  MonHead = [31,28,31,30,31,30,31,31,30,31,30,31];
  return this.each(function(){
    var y = new Date().getFullYear();
    var str = '';
    var con = "";
    //添加年份
    for(var i = y; i >= (y-80); i--){
        if(i==y){
            con += "<dd selected='selected'><a href='javascript:;' selectid="+i+">"+i+"年"+"</a></dd>";
        }else{
            con += "<dd><a href='javascript:;' selectid="+i+">"+i+"年"+"</a></dd>";
        }
    }
    $year_dl.append(con);
    con = "";
    //添加月份
    for(var i = 1;i <= 12; i++){
        if(i<10){
            str = '0' + i;
        }else{
            str =  i;
        }
        if(i==1){
            con += "<dd selected='selected'><a href='javascript:;' selectid="+i+">"+str+"月"+"</a></dd>";
        }else{
            con += "<dd><a href='javascript:;' selectid="+i+">"+str+"月"+"</a></dd>";
        }
    }
    $month_dl.append(con);
    con = "";
    //添加日期
    var n = MonHead[0];//默认显示第一月
    for(var i = 1; i <= n; i++){
        if(i<10){
            str = '0' + i;
        }else{
            str =  i;
        }
        if(i==1){
            con += "<dd selected='selected'><a href='javascript:;' selectid="+i+">"+str+"日"+"</a></dd>";
        }else{
            con += "<dd><a href='javascript:;' selectid="+i+">"+str+"日"+"</a></dd>";
        }
    }
    $day_dl.append(con);
    $.fn.birthday.change($(this));
  });
  };
  $.fn.birthday.change = function(obj){
    var y=obj.find("input[name="+ $.fn.birthday.defaults.year +"]");
    var m=obj.find("input[name="+ $.fn.birthday.defaults.month +"]");
    var d=obj.find("input[name="+ $.fn.birthday.defaults.day +"]");
    var $day2 = obj.find("input[name="+ $.fn.birthday.defaults.day +"]").parent();
    var selectedYear,selectedMonth,selectedDay,inputDay;
    obj.find('.divselect .cite-text').on('valuechange',function (event) {
        $day2.find('dl').html('');
        selectedYear = y.val();
        selectedMonth = m.val();
        selectedDay = d.val();
        if(selectedMonth == 2 && $.fn.birthday.IsRunYear(selectedYear)){//如果是闰年
            var c ="";
            for(var i = 1; i <= 29; i++){
                if(i<10){
                    str = '0' + i;
                }else{
                    str =  i;
                }
                if(i==1){
                    c += "<dd selected='selected'><a href='javascript:;' selectid="+i+">"+str+"日"+"</a></dd>";
                }else{
                    c += "<dd><a href='javascript:;' selectid="+i+">"+str+"日"+"</a></dd>";
                }
            }
            $day2.find('dl').append(c);
        }else {//如果不是闰年也没选2月份
            var c = "";
            for(var i = 1; i <= MonHead[selectedMonth-1]; i++){
                if(i<10){
                    str = '0' + i;
                }else{
                    str =  i;
                }
                if(i==1){
                    c += "<dd selected='selected'><a href='javascript:;' selectid="+i+">"+str+"日"+"</a></dd>";
                }else{
                    c += "<dd><a href='javascript:;' selectid="+i+">"+str+"日"+"</a></dd>";
                }
            }
            $day2.find('dl').append(c);
        }
        if(selectedDay == ''){
            selectedDay = d.parent().find('dl dd').eq(0).find('a').attr('selectid');
        }
        if(parseInt(selectedDay)<10){
            inputDay = '0' + selectedDay+'日';
        }else{
            inputDay = selectedDay+'日';
        }
        $day2.find('.cite-text').text(inputDay);
    });
  };
  $.fn.birthday.IsRunYear = function(selectedYear){
    return (0 == selectedYear % 4 && (selectedYear%100 != 0 || selectedYear % 400 == 0));
  };
  $.fn.birthday.defaults = {
      year:"year",
      month:"month",
      day:"day"
  };
})(jQuery);