<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SettingBundle\Settings;

use Symfony\Component\Form\FormTypeInterface;

/**
 * Class AbstractSettings
 */
abstract class AbstractSettings implements FormAwareSettingsInterface
{
    /**
     * @var FormTypeInterface
     */
    protected $form;

    /**
     * @param mixed $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getForm(): string
    {
        if (is_string($this->form)) {
            return $this->form;
        }

        return get_class($this->form);
    }
}
