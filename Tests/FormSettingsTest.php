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
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Yaml\Yaml;

class FormSettingsTest extends TypeTestCase
{
    /**
     * @var Registry
     */
    protected $registry;
    /**
     * @var SettingsManager
     */
    protected $settings;

    protected function setUp()
    {
        parent::setUp();

        $this->registry = new Registry();
        $this->registry->add(new FooSettings(FooSettingsForm::class));

        $parameters = [
            'foo.site_id' => 1,
            'foo.title' => 'Hello world',
        ];
        file_put_contents(__DIR__ . '/user.yaml', Yaml::dump(['parameters' => $parameters]));

        $this->settings = new SettingsManager(__DIR__ . '/user.yaml', $this->registry);
    }

    protected function tearDown()
    {
        unlink(__DIR__ . '/user.yaml');
    }

    public function testForm()
    {
        $all = [
            'foo.site_id' => 1,
            'foo.title' => 'Hello world',
        ];
        $expected = [
            'site_id' => 1,
            'title' => 'Hello world',
        ];

        $this->assertEquals($all, $this->settings->all());
        $filtered = $this->settings->all('foo');
        $this->assertEquals($expected, $filtered);

        $form = $this->factory->create(
            $this->registry->get('foo_settings')->getForm(),
            $filtered
        );
        $this->assertEquals($expected, $form->getData());
    }
}
