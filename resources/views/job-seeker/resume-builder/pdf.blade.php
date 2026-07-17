@php
    $styles = match ($template) {
        'professional' => 'professional',
        'minimal' => 'minimal',
        'creative' => 'creative',
        default => 'modern',
    };

    $name = trim(($personal['first_name'] ?? $profile->first_name ?? '') . ' ' . ($personal['last_name'] ?? $profile->last_name ?? '')) ?: 'Your Name';
    $email = $personal['email'] ?? $profile->user->email ?? '';
    $phone = $personal['phone'] ?? $profile->phone ?? '';
    $linkedin = $personal['linkedin_url'] ?? $profile->linkedin_url ?? '';
    $github = $personal['github_url'] ?? $profile->github_url ?? '';
    $portfolio = $personal['portfolio_url'] ?? $profile->portfolio_url ?? '';
    $website = $personal['website_url'] ?? $profile->website_url ?? '';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $builder->title ?? 'Resume' }}</title>
    <style>
        @import url('https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap');

        body {
            font-family: 'Figtree', Arial, sans-serif;
            color: #202020;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 24mm;
            margin: 0 auto;
            box-sizing: border-box;
        }

        .page-break {
            page-break-after: always;
        }

        .header {
            margin-bottom: 16px;
            border-bottom: 1px solid #dcdcdc;
            padding-bottom: 12px;
        }

        .header h1 {
            font-size: 28px;
            margin: 0 0 4px;
        }

        .header .contact {
            font-size: 12px;
            color: #505050;
        }

        .section {
            margin-bottom: 16px;
        }

        .section-title {
            display: inline-block;
            font-size: 14px;
            font-weight: 700;
            color: #1f4e79;
            border-bottom: 2px solid #1f4e79;
            margin-bottom: 10px;
            padding-bottom: 4px;
        }

        .text-muted {
            color: #505050;
        }

        .item-title {
            font-weight: 700;
            margin-bottom: 2px;
        }

        .item-meta {
            font-size: 12px;
            color: #606060;
            margin-bottom: 6px;
        }

        .item-description {
            font-size: 12px;
            line-height: 1.5;
        }

        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }

        .skills span {
            display: inline-block;
            background: #e7f1fb;
            color: #1f4e79;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
        }

        .footer {
            position: absolute;
            bottom: 24mm;
            left: 24mm;
            right: 24mm;
            font-size: 10px;
            color: #909090;
            border-top: 1px solid #e1e1e1;
            padding-top: 8px;
        }

        .modern .header { color: #193f6d; }
        .minimal .header { color: #202020; }
        .professional { background: #fafafa; }
        .creative .header { color: #8a3ffc; }
        .creative .skills span { background: #f5e3ff; color: #6c2bb7; }

        .print-hide {
            display: none;
        }
    </style>
</head>
<body class="{{ $styles }}">
    <div class="page">
        <div class="header">
            <h1>{{ $name }}</h1>
            <div class="contact">
                {{ $email }}@if($email && ($phone || $linkedin || $github || $portfolio || $website)), @endif
                {{ $phone }}
                @if($linkedin) • LinkedIn: {{ $linkedin }}@endif
                @if($github) • GitHub: {{ $github }}@endif
                @if($portfolio) • Portfolio: {{ $portfolio }}@endif
                @if($website) • Website: {{ $website }}@endif
            </div>
        </div>

        @if($builder->professional_summary)
            <div class="section">
                <div class="section-title">Professional Summary</div>
                <p class="item-description">{{ $builder->professional_summary }}</p>
            </div>
        @endif

        @if(count($education))
            <div class="section">
                <div class="section-title">Education</div>
                @foreach($education as $item)
                    <div class="mb-3">
                        <div class="item-title">{{ $item['degree'] ?? $item['school'] ?? '' }}</div>
                        <div class="item-meta">{{ $item['school'] ?? '' }} · {{ $item['start_date'] ?? '' }} - {{ $item['end_date'] ?? 'Present' }}</div>
                        <div class="item-description">{{ $item['notes'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($experience))
            <div class="section">
                <div class="section-title">Experience</div>
                @foreach($experience as $item)
                    <div class="mb-3">
                        <div class="item-title">{{ $item['title'] ?? $item['company'] ?? '' }}</div>
                        <div class="item-meta">{{ $item['company'] ?? '' }} · {{ $item['start_date'] ?? '' }} - {{ $item['end_date'] ?? 'Present' }}</div>
                        <div class="item-description">{{ $item['description'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($skills))
            <div class="section">
                <div class="section-title">Skills</div>
                <div class="skills">
                    @foreach($skills as $skill)
                        <span>{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        @if(count($projects))
            <div class="section">
                <div class="section-title">Projects</div>
                @foreach($projects as $item)
                    <div class="mb-3">
                        <div class="item-title">{{ $item['name'] ?? '' }}</div>
                        <div class="item-meta">{{ $item['link'] ?? '' }}</div>
                        <div class="item-description">{{ $item['description'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($certifications) || count($languages) || count($references))
            <div class="section">
                <div class="section-title">Additional Information</div>
                @foreach($certifications as $item)
                    <div class="mb-2">
                        <strong>{{ $item['name'] ?? '' }}</strong> · {{ $item['issuer'] ?? '' }} {{ $item['date'] ?? '' }}
                    </div>
                @endforeach
                @if(count($languages))
                    <div class="mb-2">
                        <strong>Languages:</strong> {{ collect($languages)->pluck('name')->filter()->join(', ') }}
                    </div>
                @endif
                @foreach($references as $item)
                    <div class="mb-2">
                        <strong>{{ $item['name'] ?? '' }}</strong> · {{ $item['position'] ?? '' }}<br>
                        <span class="text-muted">{{ $item['contact'] ?? '' }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="footer">Generated by CareerConnectBD · {{ now()->format('F j, Y') }}</div>
    </div>
</body>
</html>
