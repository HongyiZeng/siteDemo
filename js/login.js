window.onload=function(){
	code();
	//登录验证
	var fm=document.getElementsByTagName('form')[0];
	fm.onsubmit=function(){
		//用户名验证
		if (fm.username.value.length<2||fm.username.value.length>20) {
			alert('用户名不得小于2位或者大于20位');
			fm.username.value='';//清空
			fm.username.focus();
			return false;
		}
		if (/[<>\'\"\ \ ]/.test(fm.username.value)) {
			alert('用户名不得包含非法字符');
			fm.username.value='';//清空
			fm.username.focus();
			return false;
		}
		//验证密码
		if (fm.password.value.length<6) {
			alert('密码不得小于6位');
			fm.password.value='';//清空
			fm.password.focus();
			return false;
		}
		//验证码
		if (fm.code.value.length != 4) {
			alert('验证码不正确');
			fm.code.value='';//清空
			fm.code.focus();
			return false;
		}
	}
}