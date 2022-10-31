<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Jobs\Document\AssignDocumentToUserJob;
use App\Models\Document\Document;
use App\Services\Contracts\DocumentServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (
            app(DocumentServiceInterface::class)->count() == 0
        ) {
            Document::factory()->count(50)->create();
        }

        //assign documents to user
        dispatch(
            (new AssignDocumentToUserJob())
                ->delay(Carbon::now()->addSecond())
        );
    }
}
