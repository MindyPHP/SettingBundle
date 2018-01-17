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
     * @param string           $slug
     * @param AbstractSettings $settings
     */
    public function add(string $slug, AbstractSettings $settings)
    {
        $this->settings[$slug] = $settings;
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
     * @return AbstractSettings
     */
    public function get(string $slug): AbstractSettings
    {
        return $this->settings[$slug];
    }

    /**
     * @return AbstractSettings[]|array
     */
    public function all()
    {
        return $this->settings;
    }
}
