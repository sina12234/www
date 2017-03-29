/*
* js公共类
* 创建时间2015 09 28 
* 最后一次改进时间  2015 09 28
* 备注 增加js 只能注入数字和小数点 判断
* by fanbin
* 
*/

function clearNoNum(obj){
	//先把非数字的都替换掉，除了数字和.
	obj.value = obj.value.replace(/[^\d.]/g,"");
	//必须保证第一个为数字而不是.
	obj.value = obj.value.replace(/^\./g,"");
	//保证只有出现一个.而没有多个.
	obj.value = obj.value.replace(/\.{2,}/g,".");
	//保证.只出现一次，而不能出现两次以上
	obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
}
