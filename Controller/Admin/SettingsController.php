<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SettingBundle\Controller\Admin;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Bundle\SettingBundle\Settings\AbstractSettings;
use Mindy\Bundle\SettingBundle\Settings\Registry;
use Mindy\Bundle\SettingBundle\Settings\SettingsManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SettingsController extends Controller
{
    /**
     * @param Registry $registry
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(Registry $registry)
    {
        return $this->render('admin/settings/settings/list.html', [
            'settings' => $registry->all(),
        ]);
    }

    /**
     * @param Request  $request
     * @param SettingsManager $settings
     * @param Registry $registry
     * @param string   $slug
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function settings(Request $request, SettingsManager $settings, Registry $registry, string $slug)
    {
        if (false === $registry->has($slug)) {
            throw new NotFoundHttpException();
        }

        /** @var AbstractSettings $settings */
        $abstractSettings = $registry->get($slug);

        $form = $this->createForm($settings->getForm(), $settings->all(), [
            'method' => 'POST',
            'action' => $this->generateUrl('settings_form', ['slug' => $slug]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $settings->set($form->getData());

            $this->addFlash('success', 'Настройки сохранены');

            return $this->redirect($request->getRequestUri());
        }

        return $this->render('admin/settings/settings/form.html', [
            'settings' => $abstractSettings,
            'form' => $form->createView(),
        ]);
    }
}
