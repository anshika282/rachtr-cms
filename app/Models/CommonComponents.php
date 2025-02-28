<?php

namespace App\Models;

use App\Models\Pages;
use Illuminate\Database\Eloquent\Model;

class CommonComponents extends Model
{
    protected $fillable = [
        'name',
        'type',
        'content',
    ];

    protected $casts = ['content' => 'array'];

    public function pagesAsHeader()
    {
        return $this->hasMany(Pages::class, 'header_id');
    }

    // One-to-Many (Inverse) relationship for Footer
    public function pagesAsFooter()
    {
        return $this->hasMany(Pages::class, 'footer_id');
    } 

}
