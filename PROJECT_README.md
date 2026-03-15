# AtomicFlix

AtomicFlix is a retro-inspired, lightweight, streaming-style website for serialized public-domain sci-fi stories and selected one-off videos.

## Project Structure

```text
/
├── index.html            # Home / Browse Page
├── series.html           # Series Detail Page (Reusable)
├── watch.html            # Video Watch Page (Reusable)
├── contributors.html     # Contributors Page
├── suggest.html          # Suggest a Book Page
├── data/
│   └── library.json      # Main Content Database
└── assets/
    ├── css/
    │   └── main.css      # Core Styles & Theme
    ├── js/
    │   └── data-loader.js# JavaScript Data Module
    ├── posters/          # Series Posters
    ├── banners/          # Series Banners
    ├── thumbs/           # Chapter Thumbnails
    └── video/            # MP4 Video Files
```

## How to Run Locally

Since the project uses ES Modules and `fetch()` to load JSON data, it must be served via a web server (it will not work via `file://` protocol).

1. Open the project folder in your favorite IDE (IntelliJ, WebStorm, etc.).
2. Use a local server extension (like "Live Server") or run a simple server:
   ```bash
   # Python 3
   python -m http.server 8000
   ```
3. Open `http://localhost:8000` in your browser.

## Content Maintenance

Everything is driven by `data/library.json`.

### Adding a New Series

1. Add a new object to the `"series"` array in `library.json`.
2. Required fields: `id` (URL-safe), `title`, `author`, `description`, `poster`, `banner`, `chapters`.
3. Update `chapterCount` to match the number of chapters.
4. Add assets to `/assets/posters/ID/` and `/assets/banners/ID/`.

### Adding a New Chapter

1. Inside a series, add a new object to the `"chapters"` array.
2. Fields: `number`, `title`, `description`, `runtime`, `thumbnail`, `video`.
3. Ensure `isPublished` is set to `true`.
4. Update the parent series `"chapterCount"`.

### Adding a Blitz Video

1. Add a new object to the `"blitz"` array.
2. Required fields: `id`, `title`, `creator`, `description`, `thumbnail`, `video`, `runtime`.

## Routing & Query Parameters

The site uses simple query parameters for dynamic rendering:

- **Series Detail:** `series.html?id=city-at-worlds-end`
- **Watch Chapter:** `watch.html?series=city-at-worlds-end&chapter=1`
- **Watch Blitz:** `watch.html?blitz=cosmonascence`

## Assets Replacement

Placeholders are used currently. Replace the following in `assets/` with real files:
- Posters: `300x450` px (approx)
- Banners: `1920x1080` px (approx)
- Thumbnails: `1280x720` px (16:9)
- Videos: `.mp4` format recommended.
