<?php

namespace App\Models\Document;


use Illuminate\Database\Eloquent\Model;


class DocumentUser extends Model
{

    protected $table   = 'document_user';

    protected $fillable = [
        //| id                | bigint unsigned              | NO   | PRI | NULL    | auto_increment |
        'user_id'          ,//| bigint unsigned              | NO   | MUL | NULL    |                |
        'document_id'      ,//| bigint unsigned              | NO   | MUL | NULL    |                |
        'refuse_of_review' ,//| tinyint(1)                   | NO   | MUL | 0       |                |
        'appointment_end_time' ,//| tinyint(1)                   | NO   | MUL | 0       |                |
        //| created_at        | timestamp                    | YES  |     | NULL    |                |
        //| updated_at        | timestamp                    | YES  |     | NULL    |                |
    ];
}
