<?php

namespace App\Enums\Document;


enum Status : string
{
    case OPEN = 'OPEN'; //when doc created status change to open

    case WAITING = 'WAITING'; // when doc assign to user status change to WAITING

    case REGISTERED = 'REGISTERED';

    case CHECKED = 'CHECKED';
}
