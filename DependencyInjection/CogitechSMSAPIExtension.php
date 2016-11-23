<?php

/*
 * This file is part of SMSAPIBundle
 *
 * (c) Krystian Karaś <k4rasq@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cogitech\SMSAPIBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CogitechSMSAPIExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $smsapiConfig = array(
            'default_values' => $config['default_values'],
        );

        if (isset($config['authentication']['token'])) {
            $smsapiConfig['authentication']['type'] = 'token';
            $smsapiConfig['authentication']['token'] = $config['authentication']['token'];
        } elseif (isset($config['authentication']['login']) && isset($config['authentication']['password'])) {
            $smsapiConfig['authentication']['type'] = 'login';
            $smsapiConfig['authentication']['login'] = $config['authentication']['login'];
            $smsapiConfig['authentication']['password'] = $config['authentication']['password'];
        } else {
            throw new \InvalidArgumentException('Missing `token` or `login` and `password` in `cogitech_smsapi` configuration section.');
        }

        $container->setParameter('cogitech.sms_api.config', $smsapiConfig);
    }
}
