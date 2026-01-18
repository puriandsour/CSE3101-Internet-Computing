<?php
/**
 * Help and Documentation View
 * Renders the root README.md file
 */

// Path to README.md (root of project)
$readmePath = __DIR__ . '/../../../README.md';
$content = file_exists($readmePath) ? file_get_contents($readmePath) : "# README.md not found.";

/**
 * Simple Markdown Parser for README display
 */
function parseBasicMarkdown($text)
{
    // Escape HTML first
    $text = htmlspecialchars($text);

    // Headers
    $text = preg_replace('/^# (.*)$/m', '<h1 style="font-size: 32px; font-weight: 800; color: #0f172a; margin: 32px 0 16px;">$1</h1>', $text);
    $text = preg_replace('/^## (.*)$/m', '<h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin: 24px 0 12px; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">$1</h2>', $text);
    $text = preg_replace('/^### (.*)$/m', '<h3 style="font-size: 20px; font-weight: 700; color: #334155; margin: 20px 0 10px;">$1</h3>', $text);

    // Bold
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);

    // Links
    $text = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2" target="_blank" style="color: #2563eb; text-decoration: none; font-weight: 600;">$1</a>', $text);

    // List items
    $text = preg_replace('/^\* (.*)$/m', '<li style="margin-bottom: 8px; line-height: 1.6; color: #475569;">$1</li>', $text);
    $text = preg_replace('/^(\d+)\. (.*)$/m', '<li style="margin-bottom: 8px; line-height: 1.6; color: #475569;">$2</li>', $text);

    // Inline Code
    $text = preg_replace('/`(.*?)`/', '<code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-family: monospace; color: #e11d48;">$1</code>', $text);

    // Wrap list items in <ul> or <ol> (its suppose to work)
    if (strpos($text, '<li') !== false) {
        $text = preg_replace('/(<li.*<\/li>)/s', '<ul style="padding-left: 24px; margin-bottom: 20px;">$1</ul>', $text);
    }

    // Paragraphs
    $lines = explode("\n", $text);
    foreach ($lines as &$line) {
        $trimmed = trim($line);
        if ($trimmed !== '' && $trimmed[0] !== '<') {
            $line = '<p style="margin-bottom: 16px; line-height: 1.7; color: #475569; font-size: 16px;">' . $line . '</p>';
        }
    }

    return implode("\n", $lines);
}

$htmlContent = parseBasicMarkdown($content);
?>

<div class="help-container"
    style="padding: 40px; max-width: 900px; margin: 0 auto; background: white; border-radius: 24px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
    <div
        style="margin-bottom: 40px; border-bottom: 1px solid #f1f5f9; padding-bottom: 24px; display: flex; align-items: center; gap: 16px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 0;">Help and Documentation</h1>
            <p style="color: #64748b; margin-top: 4px;">Project overview, setup, and design resources.</p>
        </div>
    </div>

    <article class="markdown-body">
        <?php echo $htmlContent; ?>
    </article>

    <div style="margin-top: 64px; padding: 32px; background: #f8fafc; border-radius: 16px; border: 1px solid #f1f5f9;">
        <h4
            style="font-weight: 700; color: #1e293b; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" style="color: #6366f1;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            System Information
        </h4>
        <p style="color: #64748b; font-size: 14px; line-height: 1.6;">
            This application is built with vanilla PHP, following the MVC architectural pattern acording to project
            requirements.
            It uses CSS for styling. Tailwind would have been better for the team to use, but we stuck to the
            requirements.
        </p>
    </div>
</div>

<style>
    /* Additional refinements for the rendered markdown */
    .markdown-body ul {
        list-style-type: disc;
    }

    .markdown-body li::marker {
        color: #cbd5e1;
    }
</style>