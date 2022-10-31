<?php

namespace App\Enums\Document;


enum Priority : int
{
    case NECESSARY = 3;

    case IMPORTANT = 2;

    case NORMAL = 1;
}
