<?php
//phpinfo();
    error_reporting(0); // Disable error reporting
$fileTree = array();
    function my_scandir($dir){
     //   if(is_dir($dir)){
            $files = array();
            $child_dirs = scandir($dir);
            foreach($child_dirs as $key => $child_dir){
                $child_dir = iconv('gb2312','utf-8',$child_dir); //解决中文文件名乱码
                //'.'和'..'是Linux系统中的当前目录和上一级目录，必须排除掉，
                //否则会进入死循环，报segmentation falt 错误
                if($child_dir != '.' && $child_dir != '..'){
                    $suffix = 'images/'.substr(strrchr($child_dir, '.'), 1).'.jpg';
                    if(is_dir($dir.'/'.$child_dir)){
                        //如果是子文件夹，就进行递归
                        $files[] = ['name'=>$child_dir, 'children'=>my_scandir($dir.'/'.$child_dir)];
                    }else{
                        if(substr(strrchr($child_dir, '.'), 1) == ''){
                            $suffix = 'images/undefined.jpg';
                        }
                        $files[] = ['name'=>$child_dir, 'icon'=>$suffix];
                    }
                }
            }
            return $files;
//        }
//        else{
//            return $dir;
//        }
    }

$dir =  dirname(__FILE__);
define('DS', DIRECTORY_SEPARATOR);                                        //根据系统 识别文件分割符号
function read_all_dir($dir)
{
    $arr = array();
    $hander = scandir($dir);                                             //列出文件下的目录
    foreach ($hander as $v) {                                           //遍历文件夹下所有文文件
        if (is_dir($dir . DS . $v) && $v != "." && $v != "..") {        //判断文件夹下是否还有文件夹需要遍历             //递归调用
            $arr[] = ['name'=>$v, 'children'=>read_all_dir($dir . DS . $v)];
        }else{
            $suffix = 'images/'.substr(strrchr($v, '.'), 1).'.jpg';
            if($v != "." && $v != ".."){                                //排除  "." " .."
                $arr[] = ['name'=>$v, 'icon'=>$suffix];                                          //文件存入
            }

        }
    }
    return $arr;
}
$arr = read_all_dir($dir);                                            //函数调用
$fileTree = $arr;
echo "<pre>";
//print_r($arr);



////扫描文件夹
//$file = scandir($dir);
////显示
//
//print_r($file);


// $fileTree = my_scandir(dirname(__FILE__));
//    print_r($fileTree);
  //  print_r(json_encode($fileTree, JSON_UNESCAPED_UNICODE));
echo "<pre>";
echo "aaa";
    
?>
<!DOCTYPE html>
<html>
<head>
	<title>List Files</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<script type="text/javascript" src="jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="zTree.js"></script>
    
    <link rel="stylesheet" href="bootstrapStyle.css" type="text/css">
    <style>
        *{
            padding: 0;
            margin: 0;
        }
        hr{
            border-top: 1px solid #eee;
        }
        h3,hr{
            margin: 8px 0;
        }
        .zTree {
            width: 300px;
            overflow: auto;
            position: absolute;
            left: 2px;
            top: 2px;
            bottom: 2px;
        }
        #showInfo {
            position: absolute;
            left: 312px;
            top: 0;
            bottom: 0;
            right: 0;
            padding: 15px;
        }
        #showInfo .video {
            width: 100%;
            height: 90%;
            object-fit: fill;
        }
        
        .ztree li span.center_docu, .ztree li span.ico_docu {
            cursor: default;
        }
        .ztree li span.ico_docu {
            -moz-background-size:15px 13px; /* 老版本的 Firefox */
            background-size:15px 13px;
        }
        .ztree li span.button.switch {
            height: 22px;
        }
    </style>
</head>
<body>
<script type="text/javascript">
    
    var treeNodes = <?php print_r(json_encode($fileTree)); ?>;
    console.log(treeNodes);

    //TODO
    //修改文件图片大小
    $('.ztree li span.ico_docu').each(function(index, item){
        $(item).css('backgroundSize','15px 13px');
    });
    
    //点击左树后的回调
    function fCilck(event, treeId, treeNode){
        //文件名显示
        var e = event || window.event;
        var target = e.target || e.srcElement;
        var index = treeNode.name.lastIndexOf('.');
        //var innerHtml = target.innerHTML; 
        if( index !== -1 ){ 
            var suffix = treeNode.name.substring(index+1) //后缀名;
            if( suffix === 'mp4' || suffix === 'ogg' || suffix === 'mp3' ){
                $('#showInfo .fileName').html(treeNode.name);
                $('#showinfo .video').attr('src',treeNode.name);
            }else{
                if( target.className === 'node_name' ){
                    var href = target.parentNode.getAttribute('title');
                    target.parentNode.setAttribute('href',href);
                }
            }
        }else {
            //TODO
            //点击文件夹(ps:这里不一定是文件夹，也有可能是没后缀名的文件，可根据center_docu判断)
            //center_docu标识不带＋或者－，即没有后缀名的文件
            //console.log("This is a dir");
        }
        
    }
    
    var setting = {
        callback: {
            onClick: fCilck
        }
    };

    $(document).ready(function(){
        $.fn.zTree.init($("#treeDemo"), setting, treeNodes);
    });

</script>

    <ul id="treeDemo" class="ztree"></ul>
    <div id="showInfo">
        <h3 class='fileName'>&nbsp;</h3>
        <hr>
        <video class='video' controls="controls" autoplay>
          您的浏览器不支持 video 标签。
        </video>
    </div>

</body>
</html>

