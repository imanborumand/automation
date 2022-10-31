<?php

namespace App\Observers\Document;

use App\Models\Document\Document;
use Illuminate\Support\Facades\Log;

class DocumentObserver
{


    /**
     * @param Document $document
     * @return void
     */
    public function updating( Document $document) : void
    {
    }

}
