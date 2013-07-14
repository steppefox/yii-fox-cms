<div class="facebook" style="width: <?php echo $this->networks['facebook']['width'] ?>">
    <div id="fb-root"></div>
    <iframe src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode($this->networks['facebook']['href']);?>&amp;send=false&amp;layout=button_count&amp;width=120&amp;show_faces=false&amp;action=<?php echo urlencode($this->networks['facebook']['action']);?>&amp;colorscheme=<?php echo urlencode($this->networks['facebook']['colorscheme']);?>&amp;font=arial&amp;height=21" 
            scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:<?php echo urlencode($this->networks['facebook']['width']);?>; height:21px;" allowTransparency="true">
    
    </iframe>
</div>