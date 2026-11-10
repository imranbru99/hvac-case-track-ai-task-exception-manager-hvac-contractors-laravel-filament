<?php

namespace App\Services;

/**
 * Local equivalent of imrandevbd/laravel-unicode-pdf for HVAC case reports.
 */
class UnicodePdfService
{
    public function renderCaseReport(array $payload): string
    {
        $title = e($payload['reference'] ?? $payload['task_number'] ?? 'Case');
        $body = e($payload['body'] ?? $payload['description'] ?? '');
        $summary = e($payload['ai_summary'] ?? $payload['details'] ?? '');

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>{$title}</title></head>
<body>
<h1>HvacCaseTrack Case Report</h1>
<p><strong>Reference:</strong> {$title}</p>
<p><strong>Summary:</strong> {$summary}</p>
<pre>{$body}</pre>
</body>
</html>
HTML;
    }
}
