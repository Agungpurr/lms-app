<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function download(Enrollment $enrollment)
    {
        // Pastikan ini punya enrollment sendiri
        abort_if($enrollment->user_id !== auth()->id(), 403);

        // Pastikan course sudah selesai
        abort_if($enrollment->status !== 'completed', 403, 'Selesaikan course terlebih dahulu.');

        $enrollment->load(['user', 'course.instructor']);

        $data = [
            'student_name'    => $enrollment->user->name,
            'course_title'    => $enrollment->course->title,
            'instructor_name' => $enrollment->course->instructor->name,
            'completed_at'    => $enrollment->updated_at->format('d F Y'),
            'certificate_id'  => strtoupper('CERT-' . $enrollment->id . '-' . $enrollment->user_id),
        ];

        $pdf = Pdf::loadView('student.certificate', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'Sertifikat-' . str($enrollment->course->title)->slug() . '.pdf';

        return $pdf->download($filename);
    }
}