<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormModelRepository extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
}