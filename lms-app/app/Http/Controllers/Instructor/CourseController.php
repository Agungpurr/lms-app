<?php
namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $course = Course::where('user_id', auth()->id())->latest()->paginate(10);
        return view('insructor.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'category'      => 'nullable|string',
            'level'         => 'required|in:beginner, intermediate, advanced',
            'price'         => 'required|numeric|min:0',
            'thumbnail'     => 'nullable|image\max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'draft';

        Course::create($validated);

        return redirect()->route('instructor.course.index')
                         ->with('success', 'Course berhasil dibuat!');
    }

    public function edit(Course $course)
    {
        abort_if($course->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:draft,published',
            'level'         => 'required|in:beginner, intermediate, advanced',
            'price'         => 'required|numeric|min:0',
        ]);

        $course->update($validated);

        return redirect()->route('instructor.courses.index')
                         ->with('success', 'Course diperbarui');
    }

    public function destroy(Course $course)
    {
        abort_if($course->user_id !== auth()->id(), 403);
        $course->delete();
        return back()->with('success', 'Course dihapus.');
    }    
}