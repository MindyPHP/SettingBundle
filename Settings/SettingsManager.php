<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SettingBundle\Settings;

use Mindy\Bundle\SettingBundle\Utils\SettingsUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Yaml\Yaml;

class SettingsManager
{
    /**
     * @var string
     */
    protected $configPath;
    /**
     * @var Registry
     */
    protected $registry;
    /**
     * @var SettingsUtils
     */
    protected $utils;
    /**
     * @var ParameterBag
     */
    protected $parameters;

    /**
     * Settings constructor.
     *
     * @param string   $configPath
     * @param Registry $registry
     */
    public function __construct(string $configPath, Registry $registry)
    {
        $this->configPath = $configPath;
        $this->registry = $registry;
        $this->parameters = $this->loadParameters($configPath);
    }

    /**
     * @return ParameterBag
     */
    private function loadParameters(): ParameterBag
    {
        if (false === is_file($this->configPath)) {
            $parameters = new ParameterBag();
        } else {
            $raw = file_get_contents($this->configPath);
            $parameters = $this->validate($this->parseParameters($raw));
        }
        $parameters->resolve();

        return $parameters;
    }

    /**
     * @param string $content
     *
     * @return ParameterBag
     */
    public function parseParameters(string $content): ParameterBag
    {
        $data = Yaml::parse($content);

        return new ParameterBag($data['parameters'] ?? []);
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    public function dumpParameters(array $parameters): string
    {
        return Yaml::dump($parameters);
    }

    /**
     * @param array $newParameters
     *
     * @return bool
     */
    public function set(array $newParameters): bool
    {
        foreach ($newParameters as $key => $value) {
            $this->parameters->set($key, $value);
        }

        $content = $this->dumpParameters([
            'parameters' => $this->validate($this->parameters)->all(),
        ]);

        return file_put_contents($this->configPath, $content) > 0;
    }

    /**
     * @param ParameterBag $parameterBag
     *
     * @return ParameterBag
     */
    public function validate(ParameterBag $parameterBag): ParameterBag
    {
        $validParameters = new ParameterBag();
        $parameters = $parameterBag->all();
        foreach ($this->registry->all() as $settings) {
            foreach ($this->filter($parameters, $settings->getPrefix()) as $key => $value) {
                $validParameters->set($key, $value);
            }
        }
        $validParameters->resolve();

        return $validParameters;
    }

    /**
     * @param array  $parameters
     * @param string $prefix
     *
     * @return array
     */
    protected function filter(array $parameters, string $prefix): array
    {
        $valid = [];
        foreach ($parameters as $key => $value) {
            if (false === strpos($key, $prefix)) {
                continue;
            }

            $valid[$key] = $value;
        }

        return $valid;
    }

    /**
     * @return ParameterBag
     */
    public function getParameterBag(): ParameterBag
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->parameters->all();
    }
}
