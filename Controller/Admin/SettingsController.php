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
use Mindy\Bundle\SettingBundle\Settings\FormAwareSettingsInterface;
use Mindy\Bundle\SettingBundle\Settings\Registry;
use Mindy\Bundle\SettingBundle\Settings\SettingsManager;
use Symfony\Component\HttpFoundation\Request;

class SettingsController extends Controller
{
    protected function getFormAwareSettings(): array
    {
        $registry = $this->get(Registry::class);

        $settings = [];
        foreach ($registry->all() as $slug => $setting) {
            if ($setting instanceof FormAwareSettingsInterface) {
                $settings[$slug] = $setting;
            }
        }

        return $settings;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list()
    {
        return $this->render('admin/settings/settings/list.html', [
            'settings' => $this->getFormAwareSettings(),
        ]);
    }

    /**
     * @param Request $request
     * @param string  $slug
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function settings(Request $request, string $slug)
    {
        $registry = $this->get(Registry::class);
        $settingsManager = $this->get(SettingsManager::class);

        if (false === $registry->has($slug)) {
            $this->createNotFoundException();
        }

        /** @var AbstractSettings $settingsManager */
        $abstractSettings = $registry->get($slug);
        if (false === ($abstractSettings instanceof FormAwareSettingsInterface)) {
            $this->createNotFoundException();
        }

        $form = $this->createForm($abstractSettings->getForm(), $settingsManager->all(), [
            'method' => 'POST',
            'action' => $this->generateUrl('settings_form', ['slug' => $slug]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $settingsManager->set($form->getData());

            $this->addFlash('success', 'Настройки сохранены');

            return $this->redirect($request->getRequestUri());
        }

        return $this->render('admin/settings/settings/form.html', [
            'settings' => $abstractSettings,
            'form' => $form->createView(),
        ]);
    }
}
