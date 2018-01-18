# Setting Bundle

[![Build Status](https://travis-ci.org/MindyPHP/SettingBundle.svg?branch=master)](https://travis-ci.org/MindyPHP/SettingBundle)
[![codecov](https://codecov.io/gh/MindyPHP/SettingBundle/branch/master/graph/badge.svg)](https://codecov.io/gh/MindyPHP/SettingBundle)
[![Latest Stable Version](https://poser.pugx.org/mindy/setting-bundle/v/stable.svg)](https://packagist.org/packages/mindy/setting-bundle)
[![Total Downloads](https://poser.pugx.org/mindy/setting-bundle/downloads.svg)](https://packagist.org/packages/mindy/setting-bundle)

Resources
---------

  * [Documentation](https://mindy-cms.com/doc/current/bundles/user/index.html)
  * [Contributing](https://mindy-cms.com/doc/current/contributing/index.html)
  * [Report issues](https://github.com/MindyPHP/mindy/issues) and
    [send Pull Requests](https://github.com/MindyPHP/mindy/pulls)
    in the [main Mindy repository](https://github.com/MindyPHP/mindy)

## Установка

```bash
composer require mindy/setting-bundle --prefer-dist
```

## Настройка

В директории с параметрами приложения необходимо 
создать `parameters_user.yaml` с правами на запись для пользователя от которого
работает ваш сайт

```bash
⟩ ls -la config/ | grep user.yaml
-rw-rw-rw-   1 max  staff   249 Jan 18 22:27 parameters_user.yaml
```

Пример `config.yaml`:

```yaml
imports:
    - { resource: parameters.yaml }
    - { resource: parameters_user.yaml }
    
# ...
```

## Использование

Получение всех настроек

```php
$settingsManager->all();
```

Получение отфильтрованных настроек

```php
$settingsManager->all($myPrefix);
```

Использование с формой:

```php
class OrderSettings implements FormAwareSettingsInterface
{
    // ...
    
    public function getForm(): string
    {
        return OrderSettingsForm::class;
    }
}
```

```php
$settings = $this->get(OrderSettings::class);
$form = $this->createForm($settings->getForm(), $settingsManager->all($settings->getPrefix()), [
    'method' => 'POST',
]);
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) {
    $valid = [];
    foreach ($form->getData() as $key => $value) {
        $valid[sprintf("%s.%s", $settings->getPrefix(), $key)] = $value;
    }
    
    $settingsManager->set($valid);
    
    // ...
}
```
