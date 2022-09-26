<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    protected $guarded = [];

    //relationsheep----------------------------------
    public function images()
    {
        return $this->hasMany(Gallery::class);

    }//end if hasMany imaged
    
}//end of model
