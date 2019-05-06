<?php
	header('Content-Type:text/html charset:utf-8');
	date_default_timezone_set('PRC');
	$rootDir = 'listFile';	//站点根目录，装载本程序所有文件
	error_reporting(0); // Disable error reporting
	//站点base_url设置方法：
	//考虑到通用性，现默认使用方法二，修改方法时注意同时修改.htaccess文件
	
	//方法一：设置站点目录为根目录
	//对应.htaccess:
	//#RewriteBase   /
	// $base_url = 'http://www.listfile.com/';
	
	//方法二：设置站点子目录为根目录
	//对应.htaccess: 	
	//RewriteBase   /listFile/
	$base_url = 'http://localhost/'. $rootDir .'/';
	

	//解析文件夹路径
	if(empty($_GET['return'])){
		$dir = '.';
	}else {
		$dir = trim(array_pop(explode($rootDir,$_GET['return'])),'/');
		if(empty($dir)) $dir = '.';
		//else $dir = "./" . $dir;
	}
	//echo $rootDir . "/" . $dir;	//当前文件夹
	
	//遍历当前文件夹
	$pattern = '*';		// '*'搜索全部文件，可以智能匹配，如*.jpg 搜索jpg文件，*.{jpg,png}搜索jpg和png文件，区分大小写！！
	//$skip	 = '*.skip';	//排除.skip类型文件（对应了“被跳过输出文件.skip”），如*.php排除所有php文件
	$files = scandir_through($dir,$pattern,$skip,false);
    // 把PHP数组转成JSON字符串
    $json_string = json_encode($files);
    
    $list = scandir('./'); 
    for($i=0;$i<count($list);$i++){
       $list[$i] = iconv('gb2312','utf-8',$list[$i]); 
    }
    
    echo '<pre>';
    //print_r(json_encode(my_scandir($dir), JSON_UNESCAPED_UNICODE));
    
    //$fileTree = my_scandir($dir);
    //print_r($fileTree);
    echo '</pre>';
    
    $aa = arr_transform($fileTree);
    function arr_transform($fileTree){
        $data = [];       
        foreach($fileTree as $key => $value){
            if(is_array($value)){
                $data[] = (object)["name"=>$key, "children"=>arr_transform($value)];
            }else{
                $data[]['name'] = $value;
            }
        } 
        return $data;
    }
    
    echo '<pre>';
    //print_r(json_encode($aa, JSON_UNESCAPED_UNICODE));
    echo '</pre>';
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>List Files</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<script type="text/javascript" src="<?php echo $base_url . 'jquery-1.6.2.min.js' ?>"></script>
	<script type="text/javascript" src="<?php echo $base_url . 'main.js' ?>"></script>
	<script type="text/javascript" src="ztree.js"></script>
    
    <link rel="stylesheet" href="bootstrapStyle.css" type="text/css">
    <link rel="stylesheet" rev="stylesheet" href="<?php echo $base_url . 'base.css' ?>" type="text/css" />
    <style>
        *{
            padding: 0;
            margin: 0;
        }
        .zTree {
            width:200px;
            overflow:auto;
            position: absolute;
            left: 2px;
            top: 2px;
            bottom: 2px;
        }
        #showInfo {
            position: absolute;
            left: 212px;
            padding: 15px;
        }
    </style>
</head>
<body>
<script type="text/javascript">
var base_url = '<?php echo $base_url ?>';
//链接携带return标志，若携带，则autoClickUrl自添加一层下级文件夹用于跳转,跳转后获得美化后的URL。
var autoClickUrl = '<?php echo (strpos($_SERVER['REQUEST_URI'],'?return') !== false)?array_shift(explode('?',$_SERVER['REQUEST_URI']))."baddir/":'';?>';
/*var treeNodes = 
<?php 
    print_r(json_encode($aa));
?>;*/

//console.log(treeNodes);

        function fCilck(event, treeId, treeNode){
            //文件名显示
            var index = treeNode.name.lastIndexOf('.');
            if( index !== -1 ){ 
                var suffix = treeNode.name.substring(index+1) //后缀名;
                if( suffix === 'mp4' || suffix === 'ogg' ){
                    
                }
            }else {
                //点击文件夹
                console.log("This is a dir");
            }
            
        }
        
        var setting = {
            view: {
                //addHoverDom: addHoverDom,
                removeHoverDom: removeHoverDom,
                selectedMulti: false
            },
            check: {
                enable: true
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            //edit: {
            //    enable: true
            //}
            callback: {
                onClick: fCilck
            }
        };


        //var test_arr = [{"name":".htaccess"},{"name":"base.css"},{"name":"dengnixiake.mp3"},{"name":"dir1","children":[{"name":"1.TXT"},{"name":"dir2","children":[{"name":"2 - \u526f\u672c (2).TXT"},{"name":"2 - \u526f\u672c (3).TXT"},{"name":"2 - \u526f\u672c.TXT"},{"name":"2.TXT"},{"name":"dir3","children":[{"name":"3 - \u526f\u672c (2).TXT"},{"name":"3 - \u526f\u672c.TXT"},{"name":"3.TXT"},{"name":"dir4","children":[{"name":"4 - \u526f\u672c (2).TXT"},{"name":"4 - \u526f\u672c (3).TXT"},{"name":"4 - \u526f\u672c (4).TXT"},{"name":"4 - \u526f\u672c.TXT"},{"name":"4.TXT"}]}]}]}]},{"name":"images","children":[{"name":"css.jpg"},{"name":"dir.jpg"},{"name":"doc.jpg"},{"name":"exe.jpg"},{"name":"gif.jpg"},{"name":"htm.jpg"},{"name":"html.jpg"},{"name":"jpg.jpg"},{"name":"js.jpg"},{"name":"mp3.jpg"},{"name":"php.jpg"},{"name":"png.jpg"},{"name":"ppt.jpg"},{"name":"rar.jpg"},{"name":"txt.jpg"},{"name":"undefined.jpg"},{"name":"video.jpg"},{"name":"xls.jpg"},{"name":"zip.jpg"}]},{"name":"index - \u526f\u672c.php"},{"name":"index.html"},{"name":"index.php"},{"name":"jquery-1.6.2.min.js"},{"name":"login2.png"},{"name":"login_bg.jpg"},{"name":"main.js"},{"name":"movie.ogg"},{"name":"test.html"},{"name":"test.json"},{"name":"test4.avi"},{"name":"testcss.css"},{"name":"testdoc.doc"},{"name":"testexcel.xls"},{"name":"testgif.gif"},{"name":"testhtml.html"},{"name":"testjpg.jpg"},{"name":"testjs.js"},{"name":"testmp3.mp3"},{"name":"testphp.php"},{"name":"testpng.png"},{"name":"testrar.rar"},{"name":"testtxt.txt"},{"name":"testzip.zip"},{"name":"undefinedfile"},{"name":"\u88ab\u8df3\u8fc7\u8f93\u51fa\u6587\u4ef6.skip"},{"name":"\u811a\u672c\u4e4b\u5bb6.url"},{"name":"\u8bf4\u660e.txt"}]

        /*$(document).ready(function(){
            $.fn.zTree.init($("#treeDemo"), setting, treeNodes);
        });*/

        var newCount = 1;
        function addHoverDom(treeId, treeNode) {
            var sObj = $("#" + treeNode.tId + "_span");
            if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
            var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
                + "' title='add node' onfocus='this.blur();'></span>";
            sObj.after(addStr);
            var btn = $("#addBtn_"+treeNode.tId);
            if (btn) btn.bind("click", function(){
                var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, name:"new node" + (newCount++)});
                return false;
            });
        };
        function removeHoverDom(treeId, treeNode) {
            $("#addBtn_"+treeNode.tId).unbind().remove();
        };

</script>

    <ul id="treeDemo" class="ztree"></ul>
    <div id="showInfo">
        <h4><i class='fileName'></i></h4>
        <hr>
    </div>
<?php
	//文件类型数组
	$filetypes = array(
		'txt'	=>	'txt文本文件',
		'dir'	=>	'文件夹',
		'php'	=>	'php文件',
		'css'	=>	'css文件',
		'js'	=>	'js文件',
		'doc'	=>	'Word文档',
		'xls'	=>	'Excel工作表',
		'jpg'	=>	'jpg图片文件',
		'gif'	=>	'gif图片文件',
		'png'	=>	'png图片文件',
		'mp3'	=>	'mp3文件',
        'avi'   =>  'avi格式视频文件',
        'ogg'   =>  'ogg格式视频文件',
		'zip'	=>	'zip压缩包',
		'rar'	=>	'rar压缩包',
		'htm'	=>	'htm网页文件',
		'html'	=>	'html网页文件',
		'undefined'=>'文件类型未知',
	);
	//自定义屏蔽输出文件
	$skipfiles = array(
		'index.php',
		'index.html',
		'jquery-1.6.2.min.js',
		'main.js',
		'base.css',
	);

	//按规律输出当前文件夹所有文件
	echo "<div id='back'><a href='' title = '返回上一级'><img src='{$base_url}images/dir.jpg'/>&lt;-</a></div>";
	echo "<div id='container'>";
	echo "<div class='file text-center'><div class='filename border-right'>名称</div><div class='filesize border-right'>大小</div>";
	echo "<div class='filetype border-right'>类型</div><div class='filemtime'>修改日期</div></div>";
	foreach($files['filename'] as $index => $file){
		if(in_array($file,$skipfiles)) continue;
		if(is_null($filetypes[$files['ext'][$index]])) $filetype = '文件类型未知';
		else $filetype = $filetypes[$files['ext'][$index]];
        if(preg_match('/ogg|avi/',"{$files['ext'][$index]}")){
           echo "<div class='file'><div class='filename'><img src='{$base_url}images/video.jpg'/><a href='{$base_url}{$files['widthDir'][$index]}'>{$file}</a></div>"; 
        }else {
           echo "<div class='file'><div class='filename'><img src='{$base_url}images/{$files['ext'][$index]}.jpg'/><a href='{$base_url}{$files['widthDir'][$index]}'>{$file}</a></div>"; 
        }
		echo "<div class='filesize text-right'>{$files['filesize'][$index]}&nbsp;</div><div class='filetype text-right'>{$filetype}</div>";
		echo "<div class='filemtime text-center'>{$files['filemtime'][$index]}</div></div>";
	}
	echo '</div>';
?>
</body>
</html>

<?php
	//文件夹遍历函数
   
	function scandir_through($dir,$pattern='*',$skip=false,$subInclude=true,$flag=GLOB_BRACE){
        
		$files = array();
		//获取当前目录下所有文件及文件夹
		$items  = glob($dir . '/*');
		//遍历所有项目，若设置$subInclude为true，则继续遍历子目录
		for ($i = 0; $i < count($items); $i++) {
            $items[$i] = iconv('gb2312','utf-8',$items[$i]);
            
			if ($subInclude && is_dir($items[$i])) {
				$add = glob($items[$i] . '/*');
				if($add === false) $add = array();
				$items = array_merge($items, $add);
			}else {
				$slash = strrpos($items[$i],'/');
				$dir = substr($items[$i],0,$slash);
				//若当前文件匹配文件查找模式$pattern，则加入$files数组中
				if(in_array($items[$i],glob($dir.'/'.$pattern,$flag)) && (($skip===false) || !in_array($items[$i],glob($dir.'/'.$skip,$flag)))) {
					$files['filemtime'][] = date('Y-m-d H:i:s',filemtime($items[$i]));	//放这里为了解决iconv后中文名文件获取时间失败问题
					$items[$i] = iconv('gb2312','utf-8',$items[$i]);
					$file = substr($items[$i],$slash+1);
					$files['widthDir'][] = $items[$i];
					$files['filename'][] = $file;
					if(is_dir($items[$i])) {
						$files['ext'][] = 'dir';
						$files['filesize'][] = '';
					}else {
						$files['filesize'][] = ceil(filesize($file)/1024).'KB';
						if(strrpos($file,'.') === false) $files['ext'][] = 'undefined';
						else $files['ext'][] = strtolower(array_pop(explode('.',$file)));
					}
				}
			}
            
		}
		return $files;
	}
   
    function my_scandir($dir){
        if(is_dir($dir)){ 
            $files = array(); 
            $child_dirs = scandir($dir); 
            foreach($child_dirs as $key => $child_dir){
                //'.'和'..'是Linux系统中的当前目录和上一级目录，必须排除掉， 
                //否则会进入死循环，报segmentation falt 错误 
                $child_dir = iconv('gb2312','utf-8',$child_dir); //解决中文文件名乱码
                if($child_dir != '.' && $child_dir != '..'){
                    
                    if(is_dir($dir.'/'.$child_dir)){ //如果是子文件夹，就进行递归
                        $files[$child_dir] = my_scandir($dir.'/'.$child_dir);
                        
                    }else{//不然就将文件的名字存入数组；
                        $files[] = $child_dir;
                    }
                }
            } 
            
            return $files;
        }else{
            return $dir; 
        } 
    }
  
    
/*
//.htaccess 文件，位于根目录下，原理：访问路径非文件，即文件夹，因此跳转至根路径下做解析。
RewriteEngine on
#一级目录法
#RewriteBase   /
#二级目录法
RewriteBase   /listFile/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule (.*) index.php?return=%{REQUEST_FILENAME} [L]
*/

 ?>