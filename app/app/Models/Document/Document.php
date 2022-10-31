<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    use HasFactory;


    protected $table   = 'documents';

    protected $fillable = [
        //| id          | bigint unsigned                               | NO   | PRI | NULL    | auto_increment |
        'title'      ,//| varchar(150)                                  | NO   |     | NULL    |                |
        'priority'   ,//| enum('3','2','1')                             | NO   | MUL | 1       |                |
        'status'     ,//| enum('OPEN','WAITING','REGISTERED','CHECKED') | NO   | MUL | IS_OPEN |                |
        //| created_at  | timestamp                                     | YES  |     | NULL    |                |
        //| updated_at  | timestamp                                     | YES  |     | NULL    |                |
    ];


    /**
     * @return HasOne
     */
    public function documentUser() : HasOne
    {
        return $this->hasOne(DocumentUser::class);
    }
}
