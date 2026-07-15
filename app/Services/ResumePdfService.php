<?php

namespace App\Services;

use App\Models\ResumeBuilder;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ResumePdfService
{
    public const TEMPLATES = ['modern', 'professional', 'minimal', 'creative'];

    public function renderHtml(ResumeBuilder $builder, string $template): string
    {
        $template = $this->guardTemplate($template);

        $builder->loadMissing('jobSeekerProfile');

        $personal = $this->normalizeRecord($builder->personal_information ?? []);
        $education = $this->normalizeRecordList($builder->education ?? []);
        $experience = $this->normalizeRecordList($builder->experience ?? []);
        $skills = $this->normalizeStringList($builder->skills ?? []);
        $projects = $this->normalizeRecordList($builder->projects ?? []);
        $certifications = $this->normalizeRecordList($builder->certifications ?? []);
        $languages = $this->normalizeRecordList($builder->languages ?? []);
        $references = $this->normalizeRecordList($builder->references ?? []);
        $social = $this->normalizeRecord($builder->social_links ?? []);

        return view('job-seeker.resume-builder.templates.' . $template, [
            'builder' => $builder,
            'profile' => $builder->jobSeekerProfile,
            'personal' => $personal,
            'education' => $education,
            'experience' => $experience,
            'skills' => $skills,
            'projects' => $projects,
            'certifications' => $certifications,
            'languages' => $languages,
            'references' => $references,
            'social' => $social,
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

    protected function normalizeRecord(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $normalized = [];

        foreach ($value as $key => $item) {
            $normalized[$key] = $this->normalizeScalar($item);
        }

        return $normalized;
    }

    protected function normalizeRecordList(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $normalized = [];

        foreach ($value as $item) {
            if (is_array($item)) {
                $normalized[] = $this->normalizeRecord($item);

                continue;
            }

            $text = $this->normalizeScalar($item);

            if ($text !== '') {
                $normalized[] = ['value' => $text];
            }
        }

        return $normalized;
    }

    protected function normalizeStringList(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $normalized = [];

        foreach ($value as $item) {
            if (is_array($item)) {
                $preferred = Arr::first([
                    Arr::get($item, 'name'),
                    Arr::get($item, 'title'),
                    Arr::get($item, 'label'),
                    Arr::get($item, 'value'),
                ], fn ($candidate) => is_scalar($candidate) && trim((string) $candidate) !== '');

                $text = $preferred !== null
                    ? trim((string) $preferred)
                    : $this->normalizeScalar($item);
            } else {
                $text = $this->normalizeScalar($item);
            }

            if ($text !== '') {
                $normalized[] = $text;
            }
        }

        return $normalized;
    }

    protected function normalizeScalar(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_scalar($value)) {
            return trim((string) $value);
        }

        if (is_array($value)) {
            $flattened = [];

            array_walk_recursive($value, function ($item) use (&$flattened): void {
                if ($item === null) {
                    return;
                }

                if (is_bool($item)) {
                    $flattened[] = $item ? 'Yes' : 'No';

                    return;
                }

                if (is_scalar($item)) {
                    $text = trim((string) $item);

                    if ($text !== '') {
                        $flattened[] = $text;
                    }
                }
            });

            return implode(', ', array_unique($flattened));
        }

        return '';
    }
}
