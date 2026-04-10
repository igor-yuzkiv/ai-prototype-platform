<?php

namespace App\Modules\Prototype\Enums;

enum PrototypeStatus: string
{
    case New = 'new';
    case Planned = 'planned';
    case Implementing = 'implementing';
    case Implemented = 'implemented';
}
