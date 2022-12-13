<?php

namespace App\Enum;

enum TaskEnum: string
{
    case NEW = 'new';
    case WAITING = 'waiting';
    case DONE = 'done';
}
