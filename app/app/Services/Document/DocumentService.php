<?php

namespace App\Services\Document;

use App\Enums\Document\Status;
use App\Exceptions\CustomValidationException;
use App\Models\Document\Document;
use App\Repositories\Contracts\Document\DocumentRepositoryInterface;
use App\Services\Contracts\DocumentServiceInterface;
use App\Services\Contracts\DocumentUserServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DocumentService implements DocumentServiceInterface
{


    public function __construct(
        private readonly DocumentRepositoryInterface $documentRepository
    )
    {
    }


    /**
     * Receiving the last document based on time and priority
     * checking that the document has not been refuse_of_review by the user
     *
     * @param int $userId
     * @return mixed
     */
    public function getDocOrderByPriorityAndTime(int $userId) : mixed
    {
        return   $this->documentRepository
            ->baseQuery()
            ->whereDoesntHave('documentUser', function(Builder $q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->where('refuse_of_review', true);
            })
            ->where('status', Status::OPEN->value)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->first();
    }


    public function store( array $data )
    {
        // TODO: Implement store() method.
    }


    public function destroy( $id )
    {
        // TODO: Implement destroy() method.
    }


    public function list( $request = null )
    {
        // TODO: Implement list() method.
    }


    public function show( $id )
    {
        // TODO: Implement show() method.
    }


    public function update( $id , array $data )
    {
        return $this->documentRepository
            ->baseQuery()
            ->where('id', $id)
            ->update($data);
    }


    public function count() : int
    {
        return $this->documentRepository
            ->baseQuery()->count();
    }

    /**
     * @return void
     */
    public function checkDocumentAndChangeStatus() : void
    {
        $documents = $this->documentRepository
            ->baseQuery()
            ->where('status', Status::WAITING->value)
            ->whereHas('documentUser', function(Builder $query) {
                $query->where('appointment_end_time', '<', Carbon::now())
                ->where('refuse_of_review', false);
            })->get();

        //todo move to observer
        foreach ($documents as $document) {
            $document->update(['status' => Status::OPEN->value]);
            $document->documentUser()->delete();
        }
    }


    /**
     * @param array $params
     * @return bool
     * @throws \Throwable
     */
    public function assignDocumentToUser( array $params ) : bool
    {
        app(DocumentUserServiceInterface::class)
            ->store([
              'user_id' => $params['user_id'],
              'document_id' => $this->getDocument($params)->id,
              'appointment_end_time' => Carbon::createFromFormat(
                  'Y/m/d',
                  $params['appointment_end_time']
              ),
        ]);

        return true;
    }



    public function assignList() : Collection
    {
       return $this->documentRepository
           ->baseQuery()
           ->whereHas('documentUser')
           ->latest()
           ->get();
    }


    /**
     * @param array $params
     * @return Document
     * @throws \Throwable
     */
    private function getDocument(array $params) : Document
    {
        $document = $this->documentRepository
            ->baseQuery()
            ->whereDoesntHave('documentUser', function(Builder $builder) use ($params) {
                $builder->where('user_id',  $params['user_id']);
            })
            ->where('status', Status::OPEN->value)
            ->where('id', $params['document_id'])
            ->first();

        throw_if(
            !$document,
            new CustomValidationException()
        );

        return $document;
    }



}
