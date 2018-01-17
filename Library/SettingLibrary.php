<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SettingBundle\Library;

use Mindy\Bundle\SettingBundle\Settings\SettingsManager;
use Mindy\Template\Library\AbstractLibrary;

class SettingLibrary extends AbstractLibrary
{
    /**
     * @var SettingsManager
     */
    protected $settings;

    /**
     * SettingLibrary constructor.
     * @param SettingsManager $settings
     */
    public function __construct(SettingsManager $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function getHelpers()
    {
        return [
            'get_parameter_value' => function ($key) {
                return $this->settings->getParameterBag()->get($key);
            },
        ];
    }
}
