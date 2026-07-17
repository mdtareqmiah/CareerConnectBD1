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

        body { font-family: 'Figtree', Arial, sans-serif; background: #f8fafc; color: #111827; margin: 0; padding: 0; }
        .page { width: 210mm; min-height: 297mm; padding: 24mm; box-sizing: border-box; background: #ffffff; margin: auto; }
        .header { display: grid; grid-template-columns: 1fr auto; gap: 12px; margin-bottom: 28px; }
        .header-left { }
        .name { font-size: 34px; font-weight: 700; margin: 0; }
        .role { color: #4b5563; margin-top: 4px; }
        .contact { text-align: right; font-size: 11px; color: #6b7280; line-height: 1.8; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 13px; letter-spacing: .12em; text-transform: uppercase; color: #0f172a; margin-bottom: 10px; }
        .item-title { font-weight: 700; font-size: 14px; margin-bottom: 3px; }
        .item-meta { font-size: 11px; color: #6b7280; margin-bottom: 5px; }
        .item-text { font-size: 12px; color: #374151; line-height: 1.65; }
        .skills { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 6px; }
        .skill-pill { background: #e2e8f0; color: #111827; padding: 6px 8px; border-radius: 4px; font-size: 11px; }
        .footer { font-size: 10px; color: #9ca3af; margin-top: 24px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="header-left">
                <div class="name">{{ $name }}</div>
                <div class="role">{{ $builder->title ?? 'Resume' }}</div>
            </div>
            <div class="contact">
                {{ $email }}<br>
                {{ $phone }}<br>
                @if($linkedin) LinkedIn: {{ $linkedin }}<br>@endif
                @if($github) GitHub: {{ $github }}<br>@endif
                @if($portfolio) Portfolio: {{ $portfolio }}<br>@endif
                @if($website) Website: {{ $website }}<br>@endif
            </div>
        </div>

        @if($builder->professional_summary)
            <div class="section">
                <div class="section-title">Summary</div>
                <div class="item-text">{{ $builder->professional_summary }}</div>
            </div>
        @endif

        @if(count($experience))
            <div class="section">
                <div class="section-title">Experience</div>
                @foreach($experience as $item)
                    <div class="mb-3">
                        <div class="item-title">{{ $item['title'] ?? $item['company'] ?? '' }}</div>
                        <div class="item-meta">{{ $item['company'] ?? '' }} · {{ $item['start_date'] ?? '' }} - {{ $item['end_date'] ?? 'Present' }}</div>
                        <div class="item-text">{{ $item['description'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($education))
            <div class="section">
                <div class="section-title">Education</div>
                @foreach($education as $item)
                    <div class="mb-3">
                        <div class="item-title">{{ $item['degree'] ?? '' }}</div>
                        <div class="item-meta">{{ $item['school'] ?? '' }} · {{ $item['start_date'] ?? '' }} - {{ $item['end_date'] ?? 'Present' }}</div>
                        <div class="item-text">{{ $item['notes'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($skills))
            <div class="section">
                <div class="section-title">Skills</div>
                <div class="skills">
                    @foreach($skills as $skill)
                        <div class="skill-pill">{{ $skill }}</div>
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
                        <div class="item-text">{{ $item['description'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="footer">CareerConnectBD · {{ now()->format('F j, Y') }}</div>
    </div>
</body>
</html>
