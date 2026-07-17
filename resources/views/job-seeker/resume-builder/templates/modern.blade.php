@php
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

        body { font-family: 'Figtree', Arial, sans-serif; color: #1f2937; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; padding: 24px; }
        .header { margin-bottom: 24px; }
        .title { font-size: 30px; font-weight: 700; margin-bottom: 4px; }
        .subtitle { font-size: 14px; color: #667085; margin-bottom: 12px; }
        .contact { font-size: 12px; color: #475569; line-height: 1.7; }
        .section { margin-bottom: 18px; }
        .section-title { text-transform: uppercase; font-size: 12px; letter-spacing: .1em; color: #0f172a; margin-bottom: 10px; }
        .item-title { font-weight: 700; font-size: 14px; margin-bottom: 2px; }
        .item-meta { font-size: 12px; color: #475569; margin-bottom: 6px; }
        .item-text { font-size: 12px; color: #334155; line-height: 1.6; }
        .chips { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
        .chip { background: #eef2ff; color: #3730a3; padding: 5px 10px; border-radius: 999px; font-size: 11px; }
        .footer { font-size: 10px; color: #94a3b8; margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">{{ $name }}</div>
            <div class="subtitle">{{ $builder->title ?? 'Resume' }}</div>
            <div class="contact">
                {!! implode(' • ', array_filter([$email, $phone, $linkedin ? 'LinkedIn: ' . $linkedin : null, $github ? 'GitHub: ' . $github : null, $portfolio ? 'Portfolio: ' . $portfolio : null, $website ? 'Website: ' . $website : null])) !!}
            </div>
        </div>

        @if($builder->professional_summary)
            <div class="section">
                <div class="section-title">Professional Summary</div>
                <div class="item-text">{{ $builder->professional_summary }}</div>
            </div>
        @endif

        @if(count($education))
            <div class="section">
                <div class="section-title">Education</div>
                @foreach($education as $item)
                    <div class="item mb-3">
                        <div class="item-title">{{ $item['degree'] ?? $item['school'] ?? '' }}</div>
                        <div class="item-meta">{{ $item['school'] ?? '' }} · {{ $item['start_date'] ?? '' }} - {{ $item['end_date'] ?? 'Present' }}</div>
                        <div class="item-text">{{ $item['notes'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($experience))
            <div class="section">
                <div class="section-title">Experience</div>
                @foreach($experience as $item)
                    <div class="item mb-3">
                        <div class="item-title">{{ $item['title'] ?? $item['company'] ?? '' }}</div>
                        <div class="item-meta">{{ $item['company'] ?? '' }} · {{ $item['start_date'] ?? '' }} - {{ $item['end_date'] ?? 'Present' }}</div>
                        <div class="item-text">{{ $item['description'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($skills))
            <div class="section">
                <div class="section-title">Skills</div>
                <div class="chips">
                    @foreach($skills as $skill)
                        <span class="chip">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        @if(count($projects))
            <div class="section">
                <div class="section-title">Projects</div>
                @foreach($projects as $item)
                    <div class="item mb-3">
                        <div class="item-title">{{ $item['name'] ?? '' }}</div>
                        <div class="item-meta">{{ $item['link'] ?? '' }}</div>
                        <div class="item-text">{{ $item['description'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($certifications) || count($languages) || count($references))
            <div class="section">
                <div class="section-title">Additional Information</div>
                @foreach($certifications as $item)
                    <div class="item-text mb-2"><strong>{{ $item['name'] ?? '' }}</strong> · {{ $item['issuer'] ?? '' }} {{ $item['date'] ?? '' }}</div>
                @endforeach
                @if(count($languages))
                    <div class="item-text mb-2"><strong>Languages:</strong> {{ collect($languages)->pluck('name')->filter()->join(', ') }}</div>
                @endif
                @foreach($references as $item)
                    <div class="item-text mb-2"><strong>{{ $item['name'] ?? '' }}</strong> · {{ $item['position'] ?? '' }}<br>{{ $item['contact'] ?? '' }}</div>
                @endforeach
            </div>
        @endif

        <div class="footer">CareerConnectBD · {{ now()->format('F j, Y') }}</div>
    </div>
</body>
</html>
