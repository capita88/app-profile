<?php

namespace Tests\Feature;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTopic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiQuestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_submit_new_question(): void
    {
        $topic = QuestionTopic::create([
            'name' => 'Layanan KTP',
            'slug' => 'layanan-ktp',
        ]);

        $payload = [
            'topic_id' => $topic->id,
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@example.com',
            'phone' => '08123456789',
            'subject' => 'Bagaimana syarat ganti foto KTP?',
            'body' => 'Saya ingin menanyakan persyaratan dan jadwal pelayanan untuk pergantian foto KTP.',
        ];

        $response = $this->postJson('/api/v1/questions', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.subject', 'Bagaimana syarat ganti foto KTP?')
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('questions', [
            'email' => 'ahmad@example.com',
            'status' => 'pending',
        ]);
    }

    public function test_can_list_only_answered_questions(): void
    {
        $user = User::factory()->create();

        $answeredQ = Question::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'subject' => 'Pertanyaan Terjawab',
            'body' => 'Ini isi pertanyaan yang sudah dijawab.',
            'status' => QuestionStatus::Answered,
        ]);

        QuestionAnswer::create([
            'question_id' => $answeredQ->id,
            'user_id' => $user->id,
            'body' => '<p>Jawaban resmi petugas.</p>',
            'status' => AnswerStatus::Published,
            'is_published' => true,
            'answered_at' => now(),
        ]);

        Question::create([
            'name' => 'Doni',
            'email' => 'doni@example.com',
            'subject' => 'Pertanyaan Menunggu',
            'body' => 'Ini isi pertanyaan yang masih pending.',
            'status' => QuestionStatus::Pending,
        ]);

        $response = $this->getJson('/api/v1/questions');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.subject', 'Pertanyaan Terjawab')
            ->assertJsonPath('data.0.answer.body', '<p>Jawaban resmi petugas.</p>');
    }
}
