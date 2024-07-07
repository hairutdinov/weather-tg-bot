<?php

namespace app\services\telegram\commands\interfaces;

interface CustomCommandInterfaces
{
    /**
     * @return mixed
     */
    public function execute();

    /**
     * @return bool
     */
    public function isExecutable(): bool;
}