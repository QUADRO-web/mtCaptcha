<?php
/*
 * mtCaptchaRenderer
 *
 * Captcha Renderer
 *
 * Usage examples:
 * [[mtCaptchaRenderer? &tpl=`myCustomChunk`]]
 *
 * @author Jan Dähne <jan.daehne@quadro-system.de>
 */


// properties
$tpl = $modx->getOption('tpl', $scriptProperties, 'mtCaptcha', true);

// templating
return $modx->getChunk($tpl);