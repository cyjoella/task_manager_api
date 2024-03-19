<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    protected $table = 'tasks';

    use HasFactory;

    protected $fillable = [
         'title',
         'is_done',
         'project_id',
         'creator_id',
    ];

    //to set the value for the boolean value
    protected $casts = [
        'is_done' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function project(): BelongsTo{
        return $this->belongsTo(Project::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('member', function(Builder $builder){
            $builder
            ->where('creator_id', Auth::id())
           -> orWhereIn('project_id', Auth::user()->memberships->pluck('id'));
        });
    }
}