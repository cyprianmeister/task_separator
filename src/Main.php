<?php

namespace App;

use Symfony\Component\Console\Application;

class Main
{
    private const SINGLE_COMMAND = true;
    private const COMPOSER_CONFIG = __DIR__ . '/../composer.json';
    private const DEFAULT = [
        'name' => 'App',
        'version' => '0.0.1'
    ];

    public static function create(): Application
    {
        $app = new Application();
        $command = new MainCommand();
        $appData = self::getAppData();
        $app->add($command);
        $app->setName($appData['appName']);
        $app->setVersion($appData['version']);
        $app->setDefaultCommand($command->getName(), self::SINGLE_COMMAND);

        return $app;
    }

    private static function getAppData(): array
    {
        $configData = self::getConfigData();
        return [
            'appName' => self::getAppName($configData),
            'version' => $configData['version'] ?? self::DEFAULT['version'],
        ];
    }

    private static function getAppName(array $configData): ?string
    {
        if (isset($configData['name'])) {
            return ucwords(str_replace('_', ' ', explode('/', $configData['name'])[1]));
        }
        return self::DEFAULT['name'];
    }

    private static function getConfigData(): array
    {
        try {
            $composerConfig = file_get_contents(self::COMPOSER_CONFIG);
            return json_decode($composerConfig, true, 512, JSON_THROW_ON_ERROR);
        } catch(\Exception $e) {
            return [];
        }
    }
}
