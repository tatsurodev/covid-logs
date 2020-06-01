<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companion extends Model
{
    protected $guarded = ['id'];

    /**
     * Relation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // apiのlinks keyの値として使用
    public function path()
    {
        return "/companions/{$this->id}";
    }
}
