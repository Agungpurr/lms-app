<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $user   = auth()->user();
        $course = $quiz->module->course;

        abort_if(! $user->isEnrolledIn($course), 403);

        $quiz->load('questions');

        $lastResult = QuizResult::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->first();

        return view('student.quiz.show', compact('quiz', 'lastResult'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $user   = auth()->user();
        $course = $quiz->module->course;

        abort_if(! $user->isEnrolledIn($course), 403);

        $quiz->load('questions');

        $request->validate(['answers' => 'required|array']);

        $score  = $this->calculateScore($quiz, $request->answers);
        $passed = $score >= $quiz->passing_score;

        $result = QuizResult::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score'   => $score,
            'passed'  => $passed,
        ]);

        return redirect()->route('student.quiz.result', $result)
            ->with('success', $passed ? '🎉 Selamat, kamu lulus!' : '😔 Belum lulus, coba lagi ya!');
    }

    public function result(QuizResult $result)
    {
        abort_if($result->user_id !== auth()->id(), 403);

        $result->load('quiz.questions');

        return view('student.quiz.result', compact('result'));
    }

    private function calculateScore(Quiz $quiz, array $answers): int
    {
        $autoGradable = $quiz->questions->whereIn('type', ['multiple_choice', 'true_false']);

        if ($autoGradable->isEmpty()) return 0;

        $correct = 0;
        foreach ($autoGradable as $question) {
            $submitted = $answers[$question->id] ?? null;
            if ($submitted && $submitted === $question->correct_answer) {
                $correct++;
            }
        }

        return (int) round(($correct / $autoGradable->count()) * 100);
    }
}