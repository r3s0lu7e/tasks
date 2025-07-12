<?php

namespace App\Helpers;

class DescriptionHelper
{
    /**
     * Parse task description and convert markdown-style images to HTML
     *
     * @param string $description
     * @return string
     */
    public static function parseDescription($description)
    {
        if (empty($description)) {
            return '';
        }

        // Escape HTML to prevent XSS
        $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

        // Convert markdown-style image links to HTML images
        // Pattern: ![alt text](image_url)
        $pattern = '/!\[([^\]]*)\]\(([^)]+)\)/';
        $replacement = '<div class="my-4"><img src="$2" alt="$1" class="max-w-full h-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700" style="max-height: 500px;" loading="lazy" /></div>';

        $description = preg_replace($pattern, $replacement, $description);

        // Convert line breaks to HTML
        $description = nl2br($description);

        return $description;
    }

    /**
     * Extract image URLs from description
     *
     * @param string $description
     * @return array
     */
    public static function extractImageUrls($description)
    {
        if (empty($description)) {
            return [];
        }

        $pattern = '/!\[([^\]]*)\]\(([^)]+)\)/';
        preg_match_all($pattern, $description, $matches);

        return $matches[2] ?? [];
    }

    /**
     * Check if description contains images
     *
     * @param string $description
     * @return bool
     */
    public static function hasImages($description)
    {
        if (empty($description)) {
            return false;
        }

        return preg_match('/!\[([^\]]*)\]\(([^)]+)\)/', $description) > 0;
    }

    /**
     * Remove images from description (for plain text preview)
     *
     * @param string $description
     * @return string
     */
    public static function removeImages($description)
    {
        if (empty($description)) {
            return '';
        }

        $pattern = '/!\[([^\]]*)\]\(([^)]+)\)/';
        return preg_replace($pattern, '[Image: $1]', $description);
    }
}
