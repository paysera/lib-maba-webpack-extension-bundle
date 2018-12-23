<?php

namespace Paysera\Bundle\MabaWebpackExtensionBundle\Service;

use Maba\Bundle\WebpackBundle\Config\WebpackConfig;
use Maba\Bundle\WebpackBundle\Config\WebpackConfigDumper;

class ExtendedWebpackConfigDumper extends WebpackConfigDumper
{
    const CONFIG_ITEM_WEBPACK_CONFIG_PATH = 'webpack_config_path';
    const CONFIG_ITEM_ALIAS = 'alias';
    const CONFIG_ITEM_MANIFEST_PATH = 'manifest_path';
    const CONFIG_ITEM_ENTRY = 'entry';

    /**
     * @var array
     */
    private $replacePaths;

    /**
     * @var array
     */
    private $replaceItems;

    /**
     * @var string
     */
    private $webpackConfigPath;

    /**
     * @var string
     */
    private $includeConfigPath;

    /**
     * @var string
     */
    private $manifestPath;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var array
     */
    private $parameters;

    public function __construct(
        $path,
        $includeConfigPath,
        $manifestPath,
        $environment,
        array $parameters
    ) {
        parent::__construct($path, $includeConfigPath, $manifestPath, $environment, $parameters);

        $this->includeConfigPath = $includeConfigPath;
        $this->manifestPath = $manifestPath;
        $this->environment = $environment;
        $this->parameters = $parameters;
    }

    public function setReplacePaths(array $replacePaths)
    {
        $this->replacePaths = $replacePaths;
    }

    public function setReplaceItems(array $replaceItems)
    {
        $this->replaceItems = $replaceItems;
    }

    public function setWebpackConfigPath($path)
    {
        $this->webpackConfigPath = $path;
    }

    public function dump(WebpackConfig $config)
    {
        $configTemplate = 'module.exports = require(%s)(%s);';
        $configContents = sprintf(
            $configTemplate,
            json_encode($this->replaceStringIfEnabled(
                $this->includeConfigPath,
                self::CONFIG_ITEM_WEBPACK_CONFIG_PATH
            )),
            json_encode(
                [
                    'entry' => (object)$this->replaceArrayIfEnabled(
                        $config->getEntryPoints(),
                        self::CONFIG_ITEM_ENTRY
                    ),
                    'alias' => (object)$this->replaceArrayIfEnabled(
                        $config->getAliases(),
                        self::CONFIG_ITEM_ALIAS
                    ),
                    'manifest_path' => $this->replaceStringIfEnabled(
                        $this->manifestPath,
                        self::CONFIG_ITEM_MANIFEST_PATH
                    ),
                    'groups' => (object)$config->getAssetGroups(),
                    'environment' => $this->environment,
                    'parameters' => (object)$this->parameters,
                ],
                JSON_PRETTY_PRINT
            )
        );

        file_put_contents($this->webpackConfigPath, $configContents);

        return parent::dump($config);
    }

    /**
     * @param string $string
     * @param string $itemName
     * @return string
     */
    private function replaceStringIfEnabled($string, $itemName)
    {
        if (isset($this->replaceItems[$itemName]) && $this->replaceItems[$itemName] === true) {
            return strtr($string, $this->replacePaths);
        }

        return $string;
    }

    /**
     * @param array $list
     * @param string $itemName
     * @return array
     */
    private function replaceArrayIfEnabled(array $list, $itemName)
    {
        $array = [];
        foreach ($list as $key => $item) {
            $array[$key] = $this->replaceStringIfEnabled($item, $itemName);
        }

        return $array;
    }
}
