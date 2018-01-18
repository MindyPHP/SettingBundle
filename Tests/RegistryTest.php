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
use Mindy\Bundle\SettingBundle\Settings\Registry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class RegistryTest extends TestCase
{
    public function testRegistry()
    {
        $settings = new FooSettings(new FormType());

        $r = new Registry();
        $this->assertCount(0, $r->all());
        $r->add($settings);
        $this->assertTrue($r->has('test'));
        $this->assertFalse($r->has('foo'));
        $this->assertInstanceOf(AbstractSettings::class, $r->get('test'));

        $this->assertSame(FormType::class, $settings->getForm());
    }
}
