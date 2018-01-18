<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SettingBundle\Settings;

class Registry
{
    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @param SettingsInterface|FormAwareSettingsInterface $settings
     */
    public function add(SettingsInterface $settings)
    {
        $slug = (new \ReflectionClass($settings))->getShortName();

        $this->settings[$this->toSnakeCase($slug)] = $settings;
    }

    /**
     * @param string $slug
     *
     * @return string
     */
    protected function toSnakeCase(string $slug): string
    {
        $value = preg_replace('/(.)(?=[A-Z])/', '$1_', $slug);

        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * @param string $slug
     *
     * @return bool
     */
    public function has(string $slug): bool
    {
        return isset($this->settings[$slug]);
    }

    /**
     * @param string $slug
     *
     * @return SettingsInterface|FormAwareSettingsInterface
     */
    public function get(string $slug): SettingsInterface
    {
        if (false === $this->has($slug)) {
            throw new \RuntimeException(sprintf(
                'Unknown settings: %s',
                $slug
            ));
        }

        return $this->settings[$slug];
    }

    /**
     * @return SettingsInterface[]|FormAwareSettingsInterface[]|array
     */
    public function all()
    {
        return $this->settings;
    }
}
