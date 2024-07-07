<?php

const DEFAULT_LOG_MAX_FILE_SIZE = 1024 * 2;
const DEFAULT_MAX_LOG_FILES = 20;

return [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error'],
            'categories' => ['api.integration.telegram*'],
            'logVars' => [],
            'logFile' => '@app/runtime/logs/integration/telegram/error.log',
            'maxFileSize' => DEFAULT_LOG_MAX_FILE_SIZE,
            'maxLogFiles' => DEFAULT_MAX_LOG_FILES,
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['info'],
            'categories' => ['api.integration.telegram*'],
            'logVars' => [],
            'logFile' => '@app/runtime/logs/integration/telegram/info.log',
            'maxFileSize' => DEFAULT_LOG_MAX_FILE_SIZE,
            'maxLogFiles' => DEFAULT_MAX_LOG_FILES,
        ],

        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning'],
            'except' => ['api.integration*', 'yii\debug\Module::checkAccess']
        ],
    ],
];