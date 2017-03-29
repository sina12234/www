
$(function(){
    $('#submit_btn').on('click',function(){
        var nameVal = $('#username').val();
        var passwordVal = $('#password').val();
        if(nameVal.length == 0 && passwordVal.length != 0){
            checkValFn(nameVal,passwordVal);
        }else if(nameVal.length == 0 && passwordVal.length == 0){
            checkInputValFn(nameVal,passwordVal);
        }else if(nameVal.length != 0 && passwordVal.length == 0){
            checkValReg(nameVal,passwordVal)
        }


    })

    function checkInputValFn(a,b) {
        if(a == '' || a == '邮箱/手机号' && b == '' || b == '密码') {
            $('#tip_error').text('请输入内容')
        }
    }

    function checkValFn(a,b){
        if(a == '' || a == '邮箱/手机号' && b != '' || b != '密码') {
            $('#tip_error').text('请输入邮箱/手机号')
        }else if(a != '' || a != '邮箱/手机号' && b == '' || b == '密码') {
            $('#tip_error').text('请输入密码')
        }
    }

    function checkValReg(a,b){
        var phoneReg = /^\d{17}([0-9]|x)$/;
        var emailReg = /^\w+\@\w+\.(com|com.cn|cn|org)$/;
        var passwordReg = /^\w{6,12}$/;
        if(!phoneReg.test(a) || !emailReg.test(a)) {
            $('#tip_error').text('请输入正确的邮箱/手机号')
        }else if((phoneReg.test(a) || emailReg.test(a)) && !passwordReg.test(b)) {
            $('#tip_error').text('请输入正确密码')
        }
    }
})
