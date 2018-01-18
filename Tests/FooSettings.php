<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SettingBundle\Tests;

use Mindy\Bundle\SettingBundle\Settings\AbstractSettings;

class FooSettings extends AbstractSettings
{
    public function __construct($form)
    {
        $this->setForm($form);
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return 'foo';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Simple foo settings';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Simple foo description';
    }
}
