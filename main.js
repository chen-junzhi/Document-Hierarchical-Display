$(document).ready(function(){
	//根节点删除返回链接
	if(window.location.href == base_url || window.location.href == 'http://127.0.0.1/listFile/') $("#back").hide();
	//返回处理
	$("#back a").click(function(){
		if(autoClickUrl != ''){
			//Add baddir for click back.
			var url = autoClickUrl;
		}else{
			var url=window.location.href;
		}
		if(url == base_url) return false;	//如果在根节点触发返回链接，直接返回。
		url = url.replace(location.search,'');	//如果链接携带?return，截除return后续内容（由.htaccess生成）
		url = url.substr(0,url.length-2);	// 从url后第2位开始，避免/#情况存在时跳转错误
		url = url.substr(0,url.lastIndexOf('/')+1);	//截除最后一层文件夹，后退一级
		window.location.href = url;
		return false;	//处理完毕，返回false阻止<a>标签点击后的跳转。
	});
	if(autoClickUrl != '') $("#back a").click()
});
