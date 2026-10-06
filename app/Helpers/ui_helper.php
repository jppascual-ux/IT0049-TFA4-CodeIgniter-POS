<?php

/**
 * UI helper — small, dependency-free building blocks for the views:
 * inline SVG icons, the brand mark, and initials avatars.
 *
 * Loaded automatically through BaseController::$helpers.
 */

if (! function_exists('icon')) {
    /**
     * Returns an inline SVG icon (stroke style, inherits the text colour).
     */
    function icon(string $name, string $class = ''): string
    {
        $paths = [
            'database' => '<ellipse cx="12" cy="5.5" rx="7.5" ry="2.8"/><path d="M4.5 5.5v6.5c0 1.6 3.4 2.8 7.5 2.8s7.5-1.2 7.5-2.8V5.5"/><path d="M4.5 12v6.5c0 1.6 3.4 2.8 7.5 2.8s7.5-1.2 7.5-2.8V12"/>',
            'list'     => '<path d="M8.5 6.5h11M8.5 12h11M8.5 17.5h11"/><circle cx="4.5" cy="6.5" r="1"/><circle cx="4.5" cy="12" r="1"/><circle cx="4.5" cy="17.5" r="1"/>',
            'plus'     => '<path d="M12 5v14M5 12h14"/>',
            'pencil'   => '<path d="M15.5 4.5l4 4L9 19H5v-4z"/><path d="M13.5 6.5l4 4"/>',
            'check'    => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
            'check-circle' => '<circle cx="12" cy="12" r="9"/><path d="M8 12.3l2.8 2.8L16.2 9.5"/>',
            'alert'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5v5.5"/><circle cx="12" cy="16.3" r=".6" fill="currentColor"/>',
            'lock'     => '<rect x="5" y="10.5" width="14" height="10" rx="2.5"/><path d="M8 10.5V8a4 4 0 0 1 8 0v2.5"/>',
            'unlock'   => '<rect x="5" y="10.5" width="14" height="10" rx="2.5"/><path d="M8 10.5V8a4 4 0 0 1 7.6-1.7"/>',
            'upload'   => '<path d="M12 15.5V4.5M7.5 9L12 4.5 16.5 9"/><path d="M4.5 15v2.5a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V15"/>',
            'search'   => '<circle cx="10.5" cy="10.5" r="6"/><path d="M15 15l4.5 4.5"/>',
            'chevron'  => '<path d="M1.5 1.5l6 6-6 6"/>',
            'user'     => '<circle cx="12" cy="8.5" r="3.8"/><path d="M4.8 20c1.2-3.6 4-5.3 7.2-5.3s6 1.7 7.2 5.3"/>',
            'key'      => '<circle cx="8" cy="15" r="4"/><path d="M11 12l8.5-8.5M16 7l2.5 2.5M14 9l2 2"/>',
            'form'     => '<rect x="4.5" y="3.5" width="15" height="17" rx="2.5"/><path d="M8 8.5h8M8 12.5h8M8 16.5h4.5"/>',
            'image'    => '<rect x="3.5" y="4.5" width="17" height="15" rx="2.5"/><circle cx="9" cy="10" r="1.8"/><path d="M4 17.5l5-4.5 4 3.5 3-2.5 4 3.5"/>',
            'shield'   => '<path d="M12 3.5l7 2.8v5.4c0 4.3-2.9 7.6-7 8.8-4.1-1.2-7-4.5-7-8.8V6.3z"/><path d="M9 12l2.2 2.2L15.3 10"/>',
            'logout'   => '<path d="M14 4.5H7a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h7"/><path d="M11 12h9M17 8.5l3.5 3.5-3.5 3.5"/>',
            'session'  => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
        ];

        if (! isset($paths[$name])) {
            return '';
        }

        $viewBox = $name === 'chevron' ? '0 0 9 15' : '0 0 24 24';
        $width   = $name === 'chevron' ? '2' : '1.7';

        return '<svg class="' . esc($class, 'attr') . '" viewBox="' . $viewBox . '" fill="none" stroke="currentColor" stroke-width="' . $width
            . '" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[$name] . '</svg>';
    }
}

if (! function_exists('brand_mark')) {
    /**
     * The POS mark: a receipt inside a rounded square.
     */
    function brand_mark(string $class = 'brand__mark'): string
    {
        return '<svg class="' . esc($class, 'attr') . '" viewBox="0 0 26 26" aria-hidden="true" focusable="false">'
            . '<defs><linearGradient id="bm" x1="0" y1="0" x2="1" y2="1">'
            . '<stop offset="0" stop-color="#2563eb"/><stop offset=".55" stop-color="#6366f1"/><stop offset="1" stop-color="#e8467c"/>'
            . '</linearGradient></defs>'
            . '<rect width="26" height="26" rx="7.5" fill="url(#bm)"/>'
            . '<path d="M8.5 6.5h9v13l-1.5-1-1.5 1-1.5-1-1.5 1-1.5-1-1.5 1z" fill="none" stroke="#fff" stroke-width="1.5" stroke-linejoin="round"/>'
            . '<path d="M10.8 10h4.4M10.8 13h4.4" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/>'
            . '</svg>';
    }
}

if (! function_exists('initials')) {
    /**
     * "Maria Santos" → "MS" (used for customer avatars).
     */
    function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $first = mb_substr($parts[0] ?? '', 0, 1);
        $last  = count($parts) > 1 ? mb_substr((string) end($parts), 0, 1) : '';

        return mb_strtoupper($first . $last);
    }
}

if (! function_exists('name_hue')) {
    /**
     * A stable colour (0–359) for a name, so each customer keeps the same avatar colour.
     */
    function name_hue(string $name): int
    {
        return (int) (hexdec(substr(md5($name), 0, 6)) % 360);
    }
}

if (! function_exists('partial')) {
    /**
     * Renders a reusable view fragment WITHOUT keeping its variables afterwards.
     * (CodeIgniter's view() keeps data between calls by default, so an option
     * like 'required' => true on one field would leak into the next field.)
     */
    function partial(string $name, array $data = []): string
    {
        return view($name, $data, ['saveData' => false]);
    }
}
