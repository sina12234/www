<?php
/*
 * �Ƿ���ǰ����ʾ��Ĭ��Ϊtrue
 */
SError::$CONSOLE = true;
/*
 * �Ƿ��log��Ĭ�ϼ���error_log�ǵĵط�
 */
SError::$LOG = false;
/*
 * ָ��log�����ļ��ֻ����SError::$LOG=trueʱ�������������
 */
SError::$LOGFILE="/tmp/tmp_serror.log";
/*
 * �����ǲ��Դ���
 */





echo $DDJFK;
function test($B){
	test2($B);
}
function test2($a){
	echo "$B.$a";
	//throw new Exception("D2D");
	//throw new Exception("DD");
}

echo "D";
test("FJKE","E");
	throw new Exception("DD");
echo "D";
//test("B","c");

?>
