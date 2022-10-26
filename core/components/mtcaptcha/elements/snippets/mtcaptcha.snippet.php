<?php
/*
 * mtCaptcha
 *
 * Formit hook to validate captcha
 *
 * Usage examples:
 * [[FormIt? &hooks=`mtCaptcha`]]
 *
 * @author Jan Dähne <jan.daehne@quadro-system.de>
 */

$corePath = $modx->getOption('mtcaptcha.core_path', null, $modx->getOption('core_path') . 'components/mtcaptcha/');
$mtcaptcha = $modx->getService('mtcaptcha', 'mtcaptcha', $corePath . 'model/mtcaptcha/', array(
    'core_path' => $corePath
));

// init mtCaptcha
$mt = new MtCaptcha($modx);

// check token via api
$res = $mt->checkToken($hook->getValue('mtcaptcha-verifiedtoken'));

// validate input
if ($res->success) return true;

// get error message
$error = $mt->getErrorMessage($res->fail_codes);

// add error
$hook->addError('mtcaptcha', $error);

return false;
