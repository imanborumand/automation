<?php

namespace App\Services\Contracts;



use App\Services\Contracts\Basic\CRUDServiceInterface;
use Illuminate\Database\Eloquent\Collection;

interface DocumentServiceInterface extends CRUDServiceInterface
{

    /**
     * get the latest document by sort create time and priority
     *
     * @param int $userId
     * @return mixed
     */
    public function getDocOrderByPriorityAndTime(int $userId) : mixed;


    /**
     * This method checks and restores the documents whose appointment time
     * has expired but whose status is not registered and reviewed.
     *
     * @return void
     */
    public function checkDocumentAndChangeStatus() : void;


    public function count() : int;


    public function assignDocumentToUser(array $params);

    public function assignList() : Collection;
}
