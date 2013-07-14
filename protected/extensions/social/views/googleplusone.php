<!--For details: http://developers.google.com/+/plugins/+1button/ -->
<div class="plusone" style="width: <?php echo $this->networks['googleplusone']['width'] ?>">
	<div id="plusone-div"></div>
	<script type="text/javascript">
	//<![CDATA[
	gapi.plusone.render
	(
	'plusone-div',
	{
		"size": "<?php echo urlencode($this->networks['googleplusone']['size']);?>",
		"annotation":"<?php echo urlencode($this->networks['googleplusone']['annotation']);?>",
	}
	);
	//]]>
	</script>
</div>