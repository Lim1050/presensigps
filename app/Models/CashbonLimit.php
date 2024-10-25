<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashbonLimit extends Model
{
    use HasFactory;

    protected $table = "cashbon_limit";
    protected $primaryKey = "id";
    public $incrementing = true;
    protected $guarded = [];
}
