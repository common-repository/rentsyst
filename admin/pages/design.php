<?php
$service->add_my_setting();
?>

<noscript>You need to enable JavaScript to run this app.</noscript>
<div id="rentsyst_frame"></div>
<script>
	RentsystFrame.init(
		{
			container: document.getElementById("rentsyst_frame"),
			settings: JSON.parse('<?= $settings ?>'),
			onResize: function (width, height) {},
			onAutoScroll: function (scrollVal) {}
		}
    );
</script>
