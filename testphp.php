<?php
    $finfo = finfo_open(FILEINFO_MIME); // 返回 mime 类型
    $filename = 'testmp3.mp3';
    var_dump(finfo_file($finfo, $filename));
    finfo_close($finfo);
?>