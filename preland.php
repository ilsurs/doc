<?php

$redirectUrl = '{offer}';
$push_link = '';

require('lib/app.php');

if (isset($_GET['fb_pixel'])) {
    $redirectUrl = add_fb_pixel($redirectUrl, $_GET['fb_pixel']);
}

$prelandInjector = new PrelandInjector();
$prelandInjector->redirectUrl = $redirectUrl;

$renderCallback = new BeforeRenderCallback([], getcwd());
$renderCallback->addCallback($prelandInjector);

ob_start($renderCallback);

register_shutdown_function(function() use($renderCallback) {
    $renderCallback->prepare();
    $content = $renderCallback(ob_get_clean(), 0);
    echo $content;
});
