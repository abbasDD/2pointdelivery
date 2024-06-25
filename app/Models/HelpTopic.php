<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'content',
        'is_active',
    ];

    /**
     * Get the helpQuestions for the Help Topic.
     */
    public function helpQuestions()
    {
        return $this->hasMany(HelpQuestion::class);
    }
}
