<?php

namespace App\Repositories\Document;


use App\Models\Document\Document;
use App\Repositories\Contracts\Document\DocumentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\BaseRepositoryClass;

class DocumentRepository extends BaseRepositoryClass implements DocumentRepositoryInterface
{
    /**
     * set your model
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Document::class;
    }

}


