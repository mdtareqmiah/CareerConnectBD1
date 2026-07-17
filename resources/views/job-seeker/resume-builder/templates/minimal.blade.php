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
        body { font-family: 'Figtree', Arial, sans-serif; margin: 0; padding: 0; color: #111827; }
        .page { width: 210mm; min-height: 297mm; padding: 24mm; box-sizing: border-box; }
        .header { margin-bottom: 22px; }
        .name { font-size: 32px; font-weight: 700; margin: 0; }
        .details { font-size: 11px; color: #4b5563; margin-top: 8px; }
        .section { margin-bottom: 16px; }
        .section-title { font-size: 12px; font-weight: 700; margin-bottom: 8px; text-transform: uppercase; color: #111827; }
        .item-title { font-size: 13px; font-weight: 600; margin-bottom: 3px; }
        .item-meta { font-size: 11px; color: #6b7280; margin-bottom: 4px; }
        .item-text { font-size: 12px; line-height: 1.7; color: #374151; }
        .divider { height: 1px; background: #e5e7eb; margin: 16px 0; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="name">{{ $name }}</div>
            <div class="details">{{ implode(' • ', array_filter([$email, $phone, $linkedin ? 'LinkedIn: ' . $linkedin : null, $github ? 'GitHub: ' . $github : null, $portfolio ? 'Portfolio: ' . $portfolio : null, $website ? 'Website: ' . $website : null])) }}</div>
        </div>

        @if($builder->professional_summary)
            <div class="section">
                <div class="section-title">Summary</div>
                <div class="item-text">{{ $builder->professional_summary }}</div>
            </div>
            <div class="divider"></div>
        @endif

        @if(count($experience))
            <div class="section">
                <div class="section-title">Experience</div>
                @foreach($experience as $item)
                    <div class="item mb-3">
                        <div class="item-title">{{ $item['title'] ?? $item['company'] ?? '' }}</div>
                        <div class="item-meta">{{ $item['company'] ?? '' }} • {{ $item['start_date'] ?? '' }} - {{ $item['end_date'] ?? 'Present' }}</div>
                        <div class="item-text">{{ $item['description'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($education))
            <div class="section">
                <div class="section-title">Education</div>
                @foreach($education as $item)
                    <div class="item mb-3">
                        <div class="item-title">{{ $item['degree'] ?? $item['school'] ?? '' }}</div>
                        <div class="item-meta">{{ $item['school'] ?? '' }} • {{ $item['start_date'] ?? '' }} - {{ $item['end_date'] ?? 'Present' }}</div>
                        <div class="item-text">{{ $item['notes'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($skills))
            <div class="section">
                <div class="section-title">Skills</div>
                <div class="item-text">{{ implode(', ', $skills) }}</div>
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
                <div class="section-title">More</div>
                @if(count($certifications))
                    <div class="item-text mb-2">Certifications: {{ collect($certifications)->map(fn($item) => ($item['name'] ?? '') . ($item['issuer'] ? ' (' . $item['issuer'] . ')' : ''))->filter()->join(', ') }}</div>
                @endif
                @if(count($languages))
                    <div class="item-text mb-2">Languages: {{ collect($languages)->pluck('name')->filter()->join(', ') }}</div>
                @endif
                @if(count($references))
                    <div class="item-text">References: {{ collect($references)->map(fn($item) => ($item['name'] ?? '') . ($item['position'] ? ', ' . $item['position'] : ''))->filter()->join('; ') }}</div>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
