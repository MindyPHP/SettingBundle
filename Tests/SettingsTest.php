<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SettingBundle\Tests;

use Mindy\Bundle\SettingBundle\Settings\Registry;
use Mindy\Bundle\SettingBundle\Settings\SettingsManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Yaml\Yaml;

class SettingsTest extends TestCase
{
    /**
     * @var Registry
     */
    protected $registry;

    protected function setUp()
    {
        $settingsForm = $this
            ->getMockBuilder(FormTypeInterface::class)
            ->getMock();

        $this->registry = new Registry();
        $this->registry->add(new FooSettings($settingsForm));
        $this->registry->add(new BarSettings($settingsForm));

        $parameters = [
            'unknown' => 'foobar',
            'foo.site_id' => 1,
            'foo.title' => 'Hello world',
            'bar.secret' => '123',
        ];
        file_put_contents(__DIR__.'/user.yaml', Yaml::dump(['parameters' => $parameters]));
    }

    protected function tearDown()
    {
        unlink(__DIR__.'/user.yaml');
    }

    public function testSettings()
    {
        $settings = new SettingsManager(__DIR__.'/unknown.yaml', $this->registry);
        $this->assertSame([], $settings->all());

        $settings = new SettingsManager(__DIR__.'/user.yaml', $this->registry);
        $this->assertSame([
            'foo.site_id' => 1,
            'foo.title' => 'Hello world',
            'bar.secret' => '123',
        ], $settings->all());

        $settings->set([
            'foo.title' => 'World hello',
        ]);
        $this->assertSame([
            'foo.site_id' => 1,
            'foo.title' => 'World hello',
            'bar.secret' => '123',
        ], $settings->all());

        $this->assertSame([
            'secret' => '123',
        ], $settings->all('bar'));

        $this->assertInstanceOf(ParameterBag::class, $settings->getParameterBag());
    }
}
