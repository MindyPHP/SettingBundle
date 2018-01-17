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

abstract class AbstractSettings implements SettingsInterface
{
    /**
     * @var string
     */
    protected $form;

    /**
     * Settings constructor.
     *
     * @param FormTypeInterface $form
     */
    public function __construct(FormTypeInterface $form)
    {
        $this->form = get_class($form);
    }

    /**
     * @return string
     */
    public function getForm(): string
    {
        return $this->form;
    }
}
