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
        body { font-family: 'Figtree', Arial, sans-serif; margin: 0; padding: 0; color: #111827; background: #faf5ff; }
        .page { width: 210mm; min-height: 297mm; padding: 24mm; box-sizing: border-box; }
        .banner { background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); color: #ffffff; padding: 24px; border-radius: 16px; margin-bottom: 22px; }
        .banner-title { font-size: 34px; margin: 0; }
        .banner-subtitle { font-size: 14px; color: rgba(255,255,255,.85); margin-top: 6px; }
        .contact { margin-top: 18px; font-size: 11px; line-height: 1.8; color: rgba(255,255,255,.9); }
        .section { background: #ffffff; border-radius: 14px; padding: 18px; margin-bottom: 16px; }
        .section-title { font-size: 12px; text-transform: uppercase; color: #6d28d9; letter-spacing: .12em; margin-bottom: 10px; }
        .item-title { font-weight: 700; font-size: 14px; margin-bottom: 4px; }
        .item-meta { font-size: 11px; color: #475569; margin-bottom: 6px; }
        .item-text { font-size: 12px; line-height: 1.7; color: #334155; }
        .tag { display: inline-block; background: #ede9fe; color: #6d28d9; border-radius: 999px; padding: 5px 10px; font-size: 11px; margin: 2px 4px 2px 0; }
        .footer { text-align: center; font-size: 10px; color: #7c3aed; margin-top: 18px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="banner">
            <div class="banner-title">{{ $name }}</div>
            <div class="banner-subtitle">{{ $builder->title ?? 'Resume' }}</div>
            <div class="contact">
                {{ implode(' • ', array_filter([$email, $phone, $linkedin ? 'LinkedIn: ' . $linkedin : null, $github ? 'GitHub: ' . $github : null, $portfolio ? 'Portfolio: ' . $portfolio : null, $website ? 'Website: ' . $website : null])) }}
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
                        <div class="item-title">{{ $item['degree'] ?? $item['school'] ?? '' }}</div>
                        <div class="item-meta">{{ $item['school'] ?? '' }} · {{ $item['start_date'] ?? '' }} - {{ $item['end_date'] ?? 'Present' }}</div>
                        <div class="item-text">{{ $item['notes'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($skills))
            <div class="section">
                <div class="section-title">Skills</div>
                <div>
                    @foreach($skills as $skill)
                        <span class="tag">{{ $skill }}</span>
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
