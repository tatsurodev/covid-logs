<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
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
        return "/communities/{$this->id}";
    }
}
