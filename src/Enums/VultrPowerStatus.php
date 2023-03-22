<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Enums;

enum VultrPowerStatus: string
{
    case RUNNING = 'running';

    case STOPPED = 'stopped';
}
