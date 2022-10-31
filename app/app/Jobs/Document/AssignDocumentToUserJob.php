<?php

namespace App\Jobs\Document;


use App\Enums\Document\Status;
use App\Enums\User\Roles;
use App\Models\Document\Document;
use App\Models\User\User;
use App\Services\Contracts\DocumentServiceInterface;
use App\Services\Contracts\DocumentUserServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\User\UserService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class AssignDocumentToUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private DocumentServiceInterface $documentService;

    private UserServiceInterface $userService;

    private DocumentUserServiceInterface $documentUserService;

    private ?Document $document;

    private ?User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->documentService = app(DocumentServiceInterface::class);
        $this->userService = app(UserService::class);
        $this->documentUserService =  app(DocumentUserServiceInterface::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->checkRequirement()) {
            return;
        }

        $this->updateModels();

        $this->documentService->checkDocumentAndChangeStatus();


        //create new job for run job again
        dispatch((new AssignDocumentToUserJob())
                     ->delay(Carbon::now()->addSeconds(10)));

    }


    /**
     * @return void
     */
    private function updateModels() : void
    {
        DB::beginTransaction();
        try {
            //create new doc for user
            $this->documentUserService->store([
              'user_id' => $this->user->id,
              'document_id' => $this->document->id,
              'appointment_end_time' => Carbon::now()->addMinute(),
            ]);

            $this->documentService
                ->update(
                    $this->document->id,
                    ['status' => Status::WAITING->value ]
                );

            DB::commit();
        } Catch (Exception) {
            DB::rollBack();
            dispatch((new AssignDocumentToUserJob())
                         ->delay(Carbon::now()->addSeconds(10)));
        }

    }

    /**
     * @return bool
     */
    private function checkRequirement() : bool
    {
        $this->user = $this->userService->getRandomUser();
        $this->document = $this->documentService->getDocOrderByPriorityAndTime(1);
        if (!$this->document || !$this->user) {
            return false;
        }
        return true;
    }
}
