<?php
namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('user_id', auth()->id())->latest()->paginate(10);
        return view('instructor.courses.index', compact('courses')); // ✅ fix typo + view yang benar
    }

    public function create()
    {
        return view('instructor.courses.create'); // ✅ method create yang hilang
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'category'      => 'nullable|string',
            'level'         => 'required|in:beginner,intermediate,advanced', // ✅ hapus spasi
            'price'         => 'nullable|numeric|min:0',
            'thumbnail'     => 'nullable|image|max:2048', // ✅ ganti \ jadi |
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['user_id'] = auth()->id();
          $validated['status']  = $request->has('is_published') ? 'published' : 'draft';

        Course::create($validated);

        return redirect()->route('instructor.courses.index') // ✅ fix nama route
                         ->with('success', 'Course berhasil dibuat!');
    }

    public function edit(Course $course)
    {
        abort_if($course->user_id !== auth()->id(), 403);
        return view('instructor.courses.edit', compact('course')); // ✅ return view, bukan validasi
    }

    public function update(Request $request, Course $course) // ✅ method update yang hilang
    {
        abort_if($course->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:draft,published',
            'level'         => 'required|in:beginner,intermediate,advanced', // ✅ hapus spasi
            'price'         => 'required|numeric|min:0',
        ]);


        $validated['status'] = $request->has('is_published') ? 'published' : 'draft';
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