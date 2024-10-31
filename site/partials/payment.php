<?php
?>
	<noscript>You need to enable JavaScript to run this app.</noscript>
	<div id="rentsyst_payment"></div>


<?php
$script = <<<JS
RentsystPayment.init({
container: document.getElementById("rentsyst_payment"), 
settings: JSON.parse('$settings'),
onResize: function (width, height) {},
onAutoScroll: function (scrollVal) {}
});
JS;

wp_add_inline_script( 'rentsyst_bundle_payments', $script);
