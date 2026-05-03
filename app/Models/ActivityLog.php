<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
   protected $fillable = ['admin_name', 'action', 'target'];
}
