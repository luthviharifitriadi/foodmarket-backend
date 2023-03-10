<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'name', 'description', 'ingredients', 'rate', 'price','types',
        'picturePath'
    ];

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->timestamp;
    }

    public function getUpdatedAttribute($value){
        return Carbon::parse($value)->timestamp;
    }

    //picturePath bisa dipanggil
    public function toArray(){
        $toArray = parent::toArray();
        $toArray['picturePath'] = $this->picturePath;
        return $toArray;
    }

    public function getPicturePathAttribute(){
        return url(''). Storage::url($this->attributes['picturePath']);
    }


}
  