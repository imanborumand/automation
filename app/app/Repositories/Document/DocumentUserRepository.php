<?php

namespace App\Repositories\Document;



use App\Models\Document\DocumentUser;
use App\Repositories\Contracts\Document\DocumentUserRepositoryInterface;
use App\Repositories\BaseRepositoryClass;

class DocumentUserRepository extends BaseRepositoryClass implements DocumentUserRepositoryInterface
{
    /**
     * set your model
     * @inheritDoc
     */
    public function getModel(): string
    {
        return DocumentUser::class;
    }

}


