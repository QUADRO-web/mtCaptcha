<?php
/**
 * mtCaptcha Classfile
 *
 * Copyright 2020 by Jan Dähne <jan.daehne@quadro-system.de>
 *
 * @package easycart
 * @subpackage classfile
 */

 /**
  * class EasyCart
  */
class MtCaptcha {

    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'mtcaptcha';

    /**
     * The class options
     * @var array $options
     */
    public $options = array();


    /**
     * The item informations
     * @var array $options
     */
    public $item = array();


    /**
     * EasyCart constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    function __construct(modX &$modx, $options = array())
    {
        $this->modx = &$modx;
        $this->namespace = $this->getOption('namespace', $options, $this->namespace);

        //todo
        $this->api_key = $this->modx->getOption('easycart.api_key');
        $this->shop_id = $this->modx->getOption('easycart.shop_id');

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path') . 'components/' . $this->namespace . '/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path') . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url') . 'components/' . $this->namespace . '/');

        // Load some default paths for easier management
        $this->options = array_merge(array(
            'namespace' => $this->namespace,
            'version' => $this->version,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'vendorPath' => $corePath . 'vendor/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'controllersPath' => $corePath . 'controllers/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ), $options);

        $this->modx->addPackage($this->namespace, $this->getOption('modelPath'));
        $lexicon = $this->modx->getService('lexicon', 'modLexicon');
        $lexicon->load($this->namespace . ':default');
    }

    public function getOption($key, $options = array(), $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }
        return $option;
    }

    public function initialize($ctx = 'web')
    {
        switch ($ctx) {
            case 'mgr':
                $this->modx->lexicon->load('cronmanager:default');

                if (!$this->modx->loadClass('cronmanagerControllerRequest', $this->config['modelPath'] . 'cronmanager/request/', true, true)) {
                    return 'Could not load controller request handler.';
                }

                $this->request = new cronmanagerControllerRequest($this);

                return $this->request->handleRequest();
                break;
        }
        return true;
    }


    // check Token via API-Request
    public function checkToken($token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey=MTPrivat-0UrXDXNzn-iq1k7KiQjbb3fZzx0s9mPUFbDqkB8cTnqqKcDzQ8O0iQK6Scu6&token=' . $token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    // get error message
    public function getErrorMessage($codes)
    {
        return $codes[0];
    }

}
