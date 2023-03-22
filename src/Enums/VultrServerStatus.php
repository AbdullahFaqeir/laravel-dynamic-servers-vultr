<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Enums;

enum VultrServerStatus: string
{
    case NONE = 'none';

    case OK = 'ok';

    case PENDING = 'pending';

    case RUNNING = 'running';

    case ACTIVE = 'active';

    case INSTALLINGBOOTING = 'installingbooting';
}
