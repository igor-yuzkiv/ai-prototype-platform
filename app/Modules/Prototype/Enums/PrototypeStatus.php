<?php

namespace App\Modules\Prototype\Enums;

enum PrototypeStatus: string
{
    case New = 'new';
    case Planned = 'planned';
    case Implemented = 'implemented';
}
