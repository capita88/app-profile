<?php

namespace App\Models;

use App\Enums\QuestionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'name',
        'email',
        'phone',
        'subject',
        'body',
        'status',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'status' => QuestionStatus::class,
        ];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(QuestionTopic::class, 'topic_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    public function publishedAnswer(): HasOne
    {
        return $this->hasOne(QuestionAnswer::class)->where('is_published', true)->latestOfMany();
    }

    public function scopeAnswered(Builder $query): Builder
    {
        return $query->where('status', QuestionStatus::Answered);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', QuestionStatus::Pending);
    }
}
