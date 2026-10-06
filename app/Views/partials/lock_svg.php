<svg class="<?= esc($class ?? '', 'attr') ?>" viewBox="0 0 120 140" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="lock-grad" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#a78bfa"/>
            <stop offset="1" stop-color="#6366f1"/>
        </linearGradient>
    </defs>
    <circle class="art-lock__glow" cx="60" cy="96" r="52" fill="#6366f1" opacity="0"/>
    <path class="art-lock__shackle" d="M32 64V44a28 28 0 0 1 56 0v20" fill="none" stroke="#c7c7cc" stroke-width="11" stroke-linecap="round"/>
    <rect class="art-lock__body" x="14" y="60" width="92" height="76" rx="20" fill="url(#lock-grad)"/>
    <circle cx="60" cy="92" r="9" fill="#fff" opacity=".92"/>
    <rect x="56.5" y="96" width="7" height="18" rx="3.5" fill="#fff" opacity=".92"/>
</svg>
