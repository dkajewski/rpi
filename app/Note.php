<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Note
 * @package App
 * @property $id
 * @property $description
 * @property $start_at
 * @property $end_at
 * @property $created_at
 * @property $updated_at
 */
class Note extends Model
{
    protected $table = 'notes';
}
