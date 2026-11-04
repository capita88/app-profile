<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\QuestionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QuestionResource;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Question::query()
            ->answered()
            ->with(['topic', 'publishedAnswer.user'])
            ->latest();

        if ($request->filled('topic')) {
            $query->whereHas('topic', function ($q) use ($request) {
                $q->where('slug', $request->query('topic'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $perPage = min((int) $request->query('per_page', 10), 50);

        return QuestionResource::collection($query->paginate($perPage));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topic_id' => ['nullable', 'exists:question_topics,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $validated['status'] = QuestionStatus::Pending;
        $validated['ip_address'] = $request->ip();

        $question = Question::create($validated);

        return response()->json([
            'message' => 'Pertanyaan Anda berhasil dikirim dan sedang dalam antrean peninjauan petugas.',
            'data' => [
                'id' => $question->id,
                'subject' => $question->subject,
                'status' => $question->status->value,
                'created_at' => $question->created_at->toIso8601String(),
            ],
        ], 201);
    }
}
