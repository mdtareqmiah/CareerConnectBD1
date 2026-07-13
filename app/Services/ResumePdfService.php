<?php

namespace App\Services;

use App\Models\ResumeBuilder;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Str;

class ResumePdfService
{
    public const TEMPLATES = ['modern', 'professional', 'minimal', 'creative'];

    public function renderHtml(ResumeBuilder $builder, string $template): string
    {
        $template = $this->guardTemplate($template);

        $builder->loadMissing('jobSeekerProfile');

        return view('job-seeker.resume-builder.templates.' . $template, [
            'builder' => $builder,
            'profile' => $builder->jobSeekerProfile,
            'personal' => $builder->personal_information ?? [],
            'education' => $builder->education ?? [],
            'experience' => $builder->experience ?? [],
            'skills' => $builder->skills ?? [],
            'projects' => $builder->projects ?? [],
            'certifications' => $builder->certifications ?? [],
            'languages' => $builder->languages ?? [],
            'references' => $builder->references ?? [],
            'social' => $builder->social_links ?? [],
        ])->render();
    }

    public function download(ResumeBuilder $builder, string $template)
    {
        $pdf = $this->renderPdf($this->renderHtml($builder, $template));

        $filename = Str::slug($builder->title ?: 'resume') . '-resume.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    protected function renderPdf(string $html): string
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->output();
    }

    protected function guardTemplate(string $template): string
    {
        if (! in_array($template, self::TEMPLATES, true)) {
            abort(404);
        }

        return $template;
    }
}
