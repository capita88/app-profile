<?php

namespace App\Models;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'body',
        'status',
        'is_published',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => AnswerStatus::class,
            'is_published' => 'boolean',
            'answered_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function (QuestionAnswer $answer) {
            if ($answer->is_published) {
                $answer->question->update(['status' => QuestionStatus::Answered]);
            }
        });
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
