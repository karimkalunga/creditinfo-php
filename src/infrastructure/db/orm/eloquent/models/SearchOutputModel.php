<?php

namespace Devocean\Creditinfo\infrastructure\db\orm\eloquent\models;

use Illuminate\Database\Eloquent\Model;

class SearchOutputModel extends Model
{
    protected $table = 'search_output';
    protected $guarded = ['id'];
    protected $fillable = ['record', 'applicant_id'];
}