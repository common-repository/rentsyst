<?php
?>
<noscript>You need to enable JavaScript to run this app.</noscript>
<div id="rentsyst_frame"></div>

<?php
$script = <<<JS
RentsystFrame.init({
container: document.getElementById("rentsyst_frame"), 
settings: JSON.parse('$settings'),
onResize: function (width, height) {},
onAutoScroll: function (scrollVal) {}
});
JS;

wp_add_inline_script( 'rentsyst_bundle_front', $script);
