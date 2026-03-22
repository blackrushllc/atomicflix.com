<?php
/**
 * AtomicFlix Dynamic Meta Tags
 * Generates Open Graph and Twitter meta tags based on the current content.
 */

function getOgTags() {
    $libraryFile = __DIR__ . '/../../data/library.json';
    $defaultTitle = "AtomicFlix | Retro Sci-Fi Streaming";
    $defaultDesc = "Classic futures, restored and re-serialized for modern screens.";
    $defaultImage = "assets/banners/city-at-worlds-end/cover.jpg";

    if (!file_exists($libraryFile)) {
        return generateMetaHtml($defaultTitle, $defaultDesc, $defaultImage, "website");
    }

    $library = json_decode(file_get_contents($libraryFile), true);
    if (!$library) {
        return generateMetaHtml($defaultTitle, $defaultDesc, $defaultImage, "website");
    }

    // Determine context from URL parameters
    $seriesId = $_GET['id'] ?? $_GET['series'] ?? null;
    $chapterNum = $_GET['chapter'] ?? null;
    $blitzId = $_GET['blitz'] ?? null;

    $title = $library['site']['title'] ?? $defaultTitle;
    $description = $library['site']['tagline'] ?? $defaultDesc;
    $image = $library['hero']['backgroundImage'] ?? $defaultImage;
    $type = "website";

    if ($blitzId) {
        foreach ($library['blitz'] as $blitz) {
            if ($blitz['id'] === $blitzId) {
                $title = ($blitz['title'] ?? 'Blitz') . " | AtomicFlix";
                $description = $blitz['description'] ?? $description;
                $image = $blitz['thumbnail'] ?? $image;
                $type = "video.other";
                break;
            }
        }
    } else if ($seriesId) {
        foreach ($library['series'] as $series) {
            if ($series['id'] === $seriesId) {
                if ($chapterNum) {
                    foreach ($series['chapters'] as $chapter) {
                        if ($chapter['number'] == $chapterNum) {
                            $title = ($series['title'] ?? 'Series') . " - Ch " . $chapter['number'] . ": " . ($chapter['title'] ?? 'Untitled') . " | AtomicFlix";
                            $description = $chapter['description'] ?? ($series['description'] ?? $description);
                            $image = $chapter['thumbnail'] ?? ($series['poster'] ?? $image);
                            $type = "video.episode";
                            break 2;
                        }
                    }
                } else {
                    $title = ($series['title'] ?? 'Series') . " | AtomicFlix";
                    $description = $series['description'] ?? $description;
                    $image = $series['poster'] ?? $image;
                    $type = "video.tv_show";
                }
                break;
            }
        }
    }

    return generateMetaHtml($title, $description, $image, $type);
}

function generateMetaHtml($title, $description, $image, $type) {
    // Detect protocol and host
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || 
                (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'atomicflix.com';
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $fullUrl = "$protocol://$host$uri";
    
    // Ensure image is absolute
    $absoluteImage = $image;
    if ($image && strpos($image, 'http') !== 0) {
        // Strip leading slash if present
        $imagePath = ltrim($image, '/');
        $absoluteImage = "$protocol://$host/$imagePath";
    }

    $title_attr = htmlspecialchars($title);
    $desc_attr = htmlspecialchars($description);
    $url_attr = htmlspecialchars($fullUrl);
    $img_attr = htmlspecialchars($absoluteImage);

    $html = "\n    <!-- Favicon -->\n";
    $html .= "    <link rel=\"icon\" type=\"image/png\" href=\"favicon.png\">\n";

    $html .= "\n    <!-- Open Graph / Facebook -->\n";
    $html .= "    <meta property=\"og:type\" content=\"$type\">\n";
    $html .= "    <meta property=\"og:url\" id=\"og-url\" content=\"$url_attr\">\n";
    $html .= "    <meta property=\"og:title\" id=\"og-title\" content=\"$title_attr\">\n";
    $html .= "    <meta property=\"og:description\" id=\"og-description\" content=\"$desc_attr\">\n";
    $html .= "    <meta property=\"og:image\" id=\"og-image\" content=\"$img_attr\">\n";

    $html .= "\n    <!-- Twitter -->\n";
    $html .= "    <meta name=\"twitter:card\" content=\"summary_large_image\">\n";
    $html .= "    <meta name=\"twitter:url\" id=\"twitter-url\" content=\"$url_attr\">\n";
    $html .= "    <meta name=\"twitter:title\" id=\"twitter-title\" content=\"$title_attr\">\n";
    $html .= "    <meta name=\"twitter:description\" id=\"twitter-description\" content=\"$desc_attr\">\n";
    $html .= "    <meta name=\"twitter:image\" id=\"twitter-image\" content=\"$img_attr\">\n";

    return $html;
}

// Only echo if included (prevent direct access from echoing twice if we were to add more logic)
if (basename($_SERVER['PHP_SELF']) !== 'meta_tags.php') {
    echo getOgTags();
}
