<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    // ─── Module Methods ───────────────────────────────────────

    public function createModule(Course $course)
    {
        $this->authorizeCourse($course);

        return view('instructor.lessons.create-module', compact('course'));
    }

    public function storeModule(Request $request, Course $course)
    {
        $this->authorizeCourse($course);

        $request->validate(['title' => 'required|string|max:255']);

        $order = $course->modules()->max('order') + 1;

        $course->modules()->create([
            'title' => $request->title,
            'order' => $order,
        ]);

        return redirect()->route('instructor.courses.show', $course)
            ->with('success', 'Modul berhasil ditambahkan.');
    }

    public function destroyModule(Course $course, Module $module)
    {
        $this->authorizeCourse($course);

        $module->delete();

        return back()->with('success', 'Modul dihapus.');
    }

    // ─── Lesson Methods ───────────────────────────────────────

    public function create(Course $course, Module $module)
    {
        $this->authorizeCourse($course);

        return view('instructor.lessons.create', compact('course', 'module'));
    }

    public function store(Request $request, Course $course, Module $module)
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'type'      => 'required|in:video,article,pdf',
            'content'   => 'nullable|string',
            'video_url' => 'nullable|url',
            'file'      => 'nullable|file|mimes:pdf,mp4,zip|max:51200',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')
                ->store('lessons', 'public');
        }

        $order = $module->lessons()->max('order') + 1;
        $validated['order'] = $order;

        $module->lessons()->create($validated);

        return redirect()->route('instructor.courses.show', $course)
            ->with('success', 'Lesson berhasil ditambahkan.');
    }

    public function edit(Course $course, Module $module, Lesson $lesson)
    {
        $this->authorizeCourse($course);

        return view('instructor.lessons.edit', compact('course', 'module', 'lesson'));
    }

    public function update(Request $request, Course $course, Module $module, Lesson $lesson)
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'type'      => 'required|in:video,article,pdf',
            'content'   => 'nullable|string',
            'video_url' => 'nullable|url',
            'file'      => 'nullable|file|mimes:pdf,mp4,zip|max:51200',
        ]);

        if ($request->hasFile('file')) {
            if ($lesson->file) {
                Storage::disk('public')->delete($lesson->file);
            }
            $validated['file'] = $request->file('file')
                ->store('lessons', 'public');
        }

        $lesson->update($validated);

        return redirect()->route('instructor.courses.show', $course)
            ->with('success', 'Lesson diperbarui.');
    }

    public function destroy(Course $course, Module $module, Lesson $lesson)
    {
        $this->authorizeCourse($course);

        if ($lesson->file) {
            Storage::disk('public')->delete($lesson->file);
        }

        $lesson->delete();

        return back()->with('success', 'Lesson dihapus.');
    }

    // ─── Reorder Lessons ──────────────────────────────────────

    public function reorder(Request $request, Course $course, Module $module)
    {
        $this->authorizeCourse($course);

        $request->validate(['lessons' => 'required|array']);

        foreach ($request->lessons as $index => $lessonId) {
            $module->lessons()->where('id', $lessonId)->update(['order' => $index + 1]);
        }

        return response()->json(['message' => 'Urutan disimpan.']);
    }

    private function authorizeCourse(Course $course): void
    {
        abort_if($course->user_id !== auth()->id(), 403, 'Akses ditolak.');
    }
}