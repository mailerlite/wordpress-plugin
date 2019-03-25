<div style='position: relative;'>
    <div style='position: absolute;top:0;left:0;width:100%;height:100%;'></div>
    <iframe style='z-index:1;' src='<?php echo $url ?>'
            onload="this.style.height = this.contentWindow.document.body.scrollHeight + 'px'"></iframe>
</div>