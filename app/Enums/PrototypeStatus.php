<?php

namespace App\Enums;

enum PrototypeStatus: string
{
    case New = 'new';
    case Planned = 'planned';
    case Implemented = 'implemented';
    case Published = 'published';
}
