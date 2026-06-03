@php
    $r = $size / 2;
    $cx = $r; $cy = $r;
    $scale = $size / 56;
    $le = round(20 * $scale); $re = round(36 * $scale);
    $ey = round(23 * $scale);
    $er = round(3 * $scale);
    function hex2rgba($hex, $alpha) {
        [$r,$g,$b] = array_map('hexdec', str_split(ltrim($hex,'#'),2));
        return "rgba($r,$g,$b,$alpha)";
    }
@endphp

@if($mood === 'happy')
<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 56 56" aria-hidden="true">
    <circle cx="28" cy="28" r="26" fill="#EAF3DE" stroke="#639922" stroke-width="1.5"/>
    <circle cx="20" cy="23" r="3" fill="#3B6D11"/>
    <circle cx="36" cy="23" r="3" fill="#3B6D11"/>
    <path d="M18 34 Q28 43 38 34" stroke="#3B6D11" stroke-width="2.5" fill="none" stroke-linecap="round"/>
</svg>

@elseif($mood === 'mid')
<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 56 56" aria-hidden="true">
    <circle cx="28" cy="28" r="26" fill="#FAEEDA" stroke="#BA7517" stroke-width="1.5"/>
    <circle cx="20" cy="23" r="3" fill="#633806"/>
    <circle cx="36" cy="23" r="3" fill="#633806"/>
    <path d="M19 35 Q28 31 37 35" stroke="#633806" stroke-width="2.5" fill="none" stroke-linecap="round"/>
    <path d="M42 14 Q44 10 46 14 Q46 18 42 18 Q38 18 38 14 Q40 10 42 14Z" fill="#85B7EB" opacity=".8"/>
</svg>

@elseif($mood === 'sad')
<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 56 56" aria-hidden="true">
    <circle cx="28" cy="28" r="26" fill="#FCEBEB" stroke="#E24B4A" stroke-width="1.5"/>
    <circle cx="20" cy="21" r="3.5" fill="#A32D2D"/>
    <circle cx="36" cy="21" r="3.5" fill="#A32D2D"/>
    <path d="M17 37 Q28 28 39 37" stroke="#A32D2D" stroke-width="2.5" fill="none" stroke-linecap="round"/>
    <line x1="15" y1="14" x2="21" y2="19" stroke="#A32D2D" stroke-width="2" stroke-linecap="round"/>
    <line x1="41" y1="14" x2="35" y2="19" stroke="#A32D2D" stroke-width="2" stroke-linecap="round"/>
    <path d="M12 24 Q14 20 16 24" stroke="#85B7EB" stroke-width="1.5" fill="none" stroke-linecap="round"/>
    <path d="M40 30 Q42 26 44 30" stroke="#85B7EB" stroke-width="1.5" fill="none" stroke-linecap="round"/>
</svg>

@else
<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 56 56" aria-hidden="true">
    <circle cx="28" cy="28" r="26" fill="{{ hex2rgba($color, 0.12) }}" stroke="{{ $color }}" stroke-width="1.5" stroke-dasharray="4 3"/>
    <text x="28" y="34" text-anchor="middle" font-size="18" fill="{{ $color }}" font-weight="500">?</text>
</svg>
@endif