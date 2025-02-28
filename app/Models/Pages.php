<?php

namespace App\Models;

use App\Models\Pages;
use App\Models\CommonComponents;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'header_id',
        'footer_id',
        'status'
    ];

    public function header()
    {
        return $this->belongsTo(CommonComponents::class, 'header_id');
    }

    // BelongsTo relationship for Footer
    public function footer()
    {
        return $this->belongsTo(CommonComponents::class, 'footer_id');
    }

    public function parent()
    {
        return $this->belongsTo(Pages::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Pages::class, 'parent_id');
    }

    public function getFullSlugAttribute()
    {
        $slug = $this->slug;
        $parent = $this->parent;
        while ($parent) {
            $slug = $parent->slug . '/' . $slug;
            $parent = $parent->parent;
        }
        return $slug;
    }

}
