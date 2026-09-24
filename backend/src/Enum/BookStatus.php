<?php

namespace App\Enum;

enum BookStatus: string
{
    case Reading = 'reading';
    case Finished = 'finished';
    case WantToRead = 'want-to-read';

}
