Perfect — here is a **second copy-paste-ready Junie prompt** focused specifically on the **JSON schema, starter content file, and content-loading expectations** for AtomicFlix.

---

## Junie Prompt: AtomicFlix JSON Schema + Starter Content

You are working in my JetBrains project for the **AtomicFlix** website.

I already have the main website build prompt. This task is specifically to define and implement the **content model** for the site so that the UI can be driven by a single JSON library file.

The goal is that I can add new series, chapters, and one-off videos later **without creating new HTML pages**.

Please create:

1. A well-structured JSON file at something like:

    * `data/library.json`

2. A small content-loading utility in JavaScript that reads this file and exposes helper functions for:

    * getting all series
    * getting featured content
    * getting one series by ID
    * getting one chapter by series ID + chapter ID or chapter number
    * getting Blitz videos
    * getting social links
    * getting contributors page content
    * getting suggest-a-book page content

3. A short `README` section or developer note that explains how to add new content later.

---

# Requirements

## Overall Content Philosophy

AtomicFlix is:

* a static site for now
* JSON-driven
* easy to maintain manually
* future-friendly for later migration to Basil MVC/database architecture

The JSON should be:

* clean
* human-editable
* logically grouped
* obvious to extend
* stable enough that the UI can rely on it consistently

---

# File to Create

Please create:

`data/library.json`

This file should contain all starter content for the first version of AtomicFlix.

---

# Top-Level JSON Structure

Please organize the JSON with these top-level keys:

```json
{
  "site": {},
  "hero": {},
  "featured": [],
  "series": [],
  "blitz": [],
  "social": [],
  "contributors": {},
  "suggestBook": {}
}
```

You may add small supporting keys if useful, but keep it simple and readable.

---

# Detailed Schema

## 1. `site`

This should contain general site metadata.

Suggested fields:

* `title`
* `tagline`
* `description`
* `logoText`
* `theme`
* `version`

Example intent:

* title = AtomicFlix
* tagline = Retro Sci-Fi, Re-Serialized
* theme = retro-futurist-streaming

---

## 2. `hero`

This should define the home-page spotlight area.

Suggested fields:

* `featuredSeriesId`
* `headline`
* `subheadline`
* `backgroundImage`
* `primaryCtaLabel`
* `secondaryCtaLabel`

This should point initially to **The City at World’s End**.

---

## 3. `featured`

This should be an array of featured shelf items for the home page.

Each entry should support:

* `type` (`series` or `blitz`)
* `id`
* `label`
* `reason`

Example uses:

* “Featured Tonight”
* “Classic Serial”
* “Retro Pick”
* “Cosmic Essay”

---

## 4. `series`

This is the main serialized content array.

Each series object should support at least:

* `id`
* `title`
* `author`
* `sourceEra`
* `description`
* `longDescription`
* `poster`
* `banner`
* `tags`
* `genre`
* `status`
* `chapterCount`
* `featured`
* `sortOrder`
* `chapters`

### Notes

* `id` must be URL-friendly
* `chapterCount` should match actual chapter array length
* `featured` can help home-page filtering
* `sortOrder` helps predictable rendering

---

## 5. `chapters`

Inside each series, `chapters` should be an array of chapter objects.

Each chapter should support:

* `id`
* `number`
* `title`
* `description`
* `runtime`
* `thumbnail`
* `video`
* `posterFrame`
* `airLabel`
* `isPublished`

Optional:

* `nextId`
* `prevId`

However, if the app derives next/previous from chapter order, that is preferred.

### Chapter Behavior Expectations

The UI should be able to:

* sort chapters by number
* find the current chapter
* find previous and next chapters
* ignore unpublished chapters if needed later

---

## 6. `blitz`

This is the array for one-off videos.

Each Blitz item should support:

* `id`
* `title`
* `creator`
* `description`
* `thumbnail`
* `video`
* `runtime`
* `tags`
* `featured`
* `sortOrder`
* `isPublished`

These are not tied to chapters.

---

## 7. `social`

This should be an array of link objects.

Each entry:

* `platform`
* `label`
* `url`

Use placeholder URLs for now.

Platforms to seed:

* YouTube
* X
* Facebook
* Instagram

Optional:

* TikTok

---

## 8. `contributors`

This should hold page content for the Contributors page.

Suggested fields:

* `title`
* `intro`
* `areas`
* `contact`
* `notes`

### `areas`

Array of contribution areas like:

* narration
* editing
* AI video generation
* public-domain research
* poster art / thumbnails
* voice acting
* sound design
* restoration help

### `contact`

Can include:

* `email`
* `socialDm`
* `message`

Use placeholders.

---

## 9. `suggestBook`

This should hold page content for the Suggest a Book page.

Suggested fields:

* `title`
* `intro`
* `guidelines`
* `contact`
* `notes`

### `guidelines`

Array of bullet-style suggestion criteria like:

* title and author
* public-domain status if known
* why it would make a good adaptation
* source text link if available
* visual inspiration if any

---

# Seed the Initial Library Data

Please populate the JSON with realistic starter content for these 3 serialized series:

## 1. The City at World’s End

* `id`: `city-at-worlds-end`
* author: Edmond Hamilton

Create at least 3 starter chapters:

1. Chapter 1
2. Chapter 2
3. Chapter 3

Use placeholder chapter titles/descriptions if needed, but make them sound polished and consistent with a retro sci-fi serial.

---

## 2. Two Thousand Miles Below

* `id`: `two-thousand-miles-below`
* author: Charles Willard Diffin

Create at least 3 starter chapters.

---

## 3. First on the Moon

* `id`: `first-on-the-moon`
* author: Jeff Sutton

Create at least 3 starter chapters.

---

# Seed the Blitz Library

Create starter entries for:

1. House on the Borderland
2. Cosmonascence
3. Speed of Light

These should live in `blitz`.

---

# Asset Path Conventions

Please use clean placeholder paths that make it obvious where assets belong.

Examples:

* posters:

    * `/assets/posters/city-at-worlds-end/poster.jpg`
* banners:

    * `/assets/banners/city-at-worlds-end/cover.jpg`
* chapter thumbnails:

    * `/assets/thumbnails/city-at-worlds-end/ch01.jpg`
* videos:

    * `/assets/video/city-at-worlds-end/ch01.mp4`

For Blitz:

* `/assets/posters/blitz/house-on-the-borderland.jpg`
* `/assets/video/blitz/house-on-the-borderland.mp4`

Be consistent.

---

# Data Integrity Rules

Please ensure:

1. All IDs are unique
2. All series IDs are URL-safe
3. All chapter IDs are URL-safe
4. `chapterCount` matches actual number of chapters
5. `sortOrder` values are sensible
6. Placeholder URLs and asset paths are consistent
7. JSON is valid and nicely formatted
8. Text feels polished and intentional, not random filler

---

# JavaScript Helper Layer

Please create a small JS module for content access, such as:

* `loadLibrary()`
* `getSiteMeta()`
* `getHero()`
* `getFeaturedItems()`
* `getAllSeries()`
* `getSeriesById(id)`
* `getChapterBySeriesAndId(seriesId, chapterId)`
* `getChapterBySeriesAndNumber(seriesId, number)`
* `getNextChapter(seriesId, currentNumber)`
* `getPreviousChapter(seriesId, currentNumber)`
* `getBlitzItems()`
* `getBlitzById(id)`
* `getSocialLinks()`
* `getContributorsContent()`
* `getSuggestBookContent()`

Keep it lightweight and framework-agnostic if possible.

---

# Developer Note / README

Please add a short documentation file or README section that explains:

## How to add a new series

Show what fields are required.

## How to add a new chapter

Explain:

* add chapter object
* increment `chapterCount`
* add thumbnail/video assets

## How to add a new Blitz video

Explain required fields.

## How routing will work

For example:

* `series.html?id=city-at-worlds-end`
* `watch.html?series=city-at-worlds-end&chapter=1`
* `watch.html?blitz=cosmonascence`

---

# Output Style

Please make the content feel like a real curated streaming catalog:

* concise
* atmospheric
* retro sci-fi
* polished
* readable

Avoid lorem ipsum or generic filler.

---

# Final Deliverables

Please create:

1. `data/library.json`
2. a small JS content/data helper module
3. a short README or content-maintenance note

---

# Bonus

If useful, also create:

* TypeScript type definitions or JSDoc typedefs for the JSON shape
* fallback helpers for missing assets or unpublished content

But keep it lightweight.

---

## Optional starter JSON example

You can also hand Junie this **starter example** so she has something concrete to model from.

```json
{
  "site": {
    "title": "AtomicFlix",
    "tagline": "Retro Sci-Fi, Re-Serialized",
    "description": "A niche streaming home for classic science-fiction serials, one-off adaptations, and cosmic curiosities.",
    "logoText": "AtomicFlix",
    "theme": "retro-futurist-streaming",
    "version": "0.1.0"
  },
  "hero": {
    "featuredSeriesId": "city-at-worlds-end",
    "headline": "Binge the Golden Age",
    "subheadline": "Classic futures, restored and re-serialized for modern screens.",
    "backgroundImage": "/assets/banners/city-at-worlds-end/cover.jpg",
    "primaryCtaLabel": "Watch Now",
    "secondaryCtaLabel": "Browse Chapters"
  },
  "featured": [
    {
      "type": "series",
      "id": "city-at-worlds-end",
      "label": "Featured Tonight",
      "reason": "Our flagship retro serial"
    },
    {
      "type": "series",
      "id": "two-thousand-miles-below",
      "label": "Classic Serial",
      "reason": "A deep-earth pulp adventure"
    },
    {
      "type": "blitz",
      "id": "cosmonascence",
      "label": "Cosmic Essay",
      "reason": "A metaphysical one-off"
    }
  ],
  "series": [
    {
      "id": "city-at-worlds-end",
      "title": "The City at World’s End",
      "author": "Edmond Hamilton",
      "sourceEra": "Golden Age Sci-Fi",
      "description": "A quiet American town is hurled into the unimaginable far future, where the last ruins of civilization stand against cosmic desolation.",
      "longDescription": "AtomicFlix presents a serialized adaptation of Edmond Hamilton’s sweeping end-of-time adventure, blending mystery, wonder, and stark cosmic scale.",
      "poster": "/assets/posters/city-at-worlds-end/poster.jpg",
      "banner": "/assets/banners/city-at-worlds-end/cover.jpg",
      "tags": ["Time Displacement", "Far Future", "Cosmic Ruins"],
      "genre": "Science Fiction",
      "status": "ongoing",
      "chapterCount": 3,
      "featured": true,
      "sortOrder": 1,
      "chapters": [
        {
          "id": "chapter-1",
          "number": 1,
          "title": "The Vanishing Horizon",
          "description": "A familiar town wakes into an alien stillness and the first signs emerge that the world itself is no longer the one it knew.",
          "runtime": "08:40",
          "thumbnail": "/assets/thumbnails/city-at-worlds-end/ch01.jpg",
          "video": "/assets/video/city-at-worlds-end/ch01.mp4",
          "posterFrame": "/assets/thumbnails/city-at-worlds-end/ch01.jpg",
          "airLabel": "Chapter 1",
          "isPublished": true
        },
        {
          "id": "chapter-2",
          "number": 2,
          "title": "Ruins Beneath Strange Suns",
          "description": "The truth widens as the town’s survivors discover they have not merely been moved, but cast into an age beyond imagination.",
          "runtime": "09:12",
          "thumbnail": "/assets/thumbnails/city-at-worlds-end/ch02.jpg",
          "video": "/assets/video/city-at-worlds-end/ch02.mp4",
          "posterFrame": "/assets/thumbnails/city-at-worlds-end/ch02.jpg",
          "airLabel": "Chapter 2",
          "isPublished": true
        },
        {
          "id": "chapter-3",
          "number": 3,
          "title": "The Last City Calls",
          "description": "A dying future reveals its final refuge, and the visitors from the past begin to understand why they were brought here.",
          "runtime": "10:01",
          "thumbnail": "/assets/thumbnails/city-at-worlds-end/ch03.jpg",
          "video": "/assets/video/city-at-worlds-end/ch03.mp4",
          "posterFrame": "/assets/thumbnails/city-at-worlds-end/ch03.jpg",
          "airLabel": "Chapter 3",
          "isPublished": true
        }
      ]
    },
    {
      "id": "two-thousand-miles-below",
      "title": "Two Thousand Miles Below",
      "author": "Charles Willard Diffin",
      "sourceEra": "Pulp Adventure",
      "description": "An expedition into the unimaginable depths uncovers a hidden world beneath the earth.",
      "longDescription": "A subterranean serial of peril, discovery, and classic magazine-era wonder.",
      "poster": "/assets/posters/two-thousand-miles-below/poster.jpg",
      "banner": "/assets/banners/two-thousand-miles-below/cover.jpg",
      "tags": ["Underground World", "Adventure", "Pulp"],
      "genre": "Science Fiction Adventure",
      "status": "ongoing",
      "chapterCount": 3,
      "featured": true,
      "sortOrder": 2,
      "chapters": [
        {
          "id": "chapter-1",
          "number": 1,
          "title": "Descent",
          "description": "A dangerous mission begins beneath the surface of the known world.",
          "runtime": "07:48",
          "thumbnail": "/assets/thumbnails/two-thousand-miles-below/ch01.jpg",
          "video": "/assets/video/two-thousand-miles-below/ch01.mp4",
          "posterFrame": "/assets/thumbnails/two-thousand-miles-below/ch01.jpg",
          "airLabel": "Chapter 1",
          "isPublished": true
        },
        {
          "id": "chapter-2",
          "number": 2,
          "title": "The Hidden Vaults",
          "description": "The explorers encounter signs that the deep earth is neither empty nor dead.",
          "runtime": "08:36",
          "thumbnail": "/assets/thumbnails/two-thousand-miles-below/ch02.jpg",
          "video": "/assets/video/two-thousand-miles-below/ch02.mp4",
          "posterFrame": "/assets/thumbnails/two-thousand-miles-below/ch02.jpg",
          "airLabel": "Chapter 2",
          "isPublished": true
        },
        {
          "id": "chapter-3",
          "number": 3,
          "title": "Kingdom Under Stone",
          "description": "What seemed like geology becomes a living realm with its own power and peril.",
          "runtime": "09:05",
          "thumbnail": "/assets/thumbnails/two-thousand-miles-below/ch03.jpg",
          "video": "/assets/video/two-thousand-miles-below/ch03.mp4",
          "posterFrame": "/assets/thumbnails/two-thousand-miles-below/ch03.jpg",
          "airLabel": "Chapter 3",
          "isPublished": true
        }
      ]
    },
    {
      "id": "first-on-the-moon",
      "title": "First on the Moon",
      "author": "Jeff Sutton",
      "sourceEra": "Mid-Century Sci-Fi",
      "description": "A pioneering lunar mission becomes a tense race between courage, mystery, and the unknown.",
      "longDescription": "A sleek atomic-age journey to the moon, serialized for modern binge watching.",
      "poster": "/assets/posters/first-on-the-moon/poster.jpg",
      "banner": "/assets/banners/first-on-the-moon/cover.jpg",
      "tags": ["Moon Voyage", "Space Race", "Exploration"],
      "genre": "Space Adventure",
      "status": "ongoing",
      "chapterCount": 3,
      "featured": false,
      "sortOrder": 3,
      "chapters": [
        {
          "id": "chapter-1",
          "number": 1,
          "title": "Countdown",
          "description": "A daring mission leaves Earth behind and enters the silence beyond the atmosphere.",
          "runtime": "08:14",
          "thumbnail": "/assets/thumbnails/first-on-the-moon/ch01.jpg",
          "video": "/assets/video/first-on-the-moon/ch01.mp4",
          "posterFrame": "/assets/thumbnails/first-on-the-moon/ch01.jpg",
          "airLabel": "Chapter 1",
          "isPublished": true
        },
        {
          "id": "chapter-2",
          "number": 2,
          "title": "Transit",
          "description": "The crew faces mechanical strain, psychological pressure, and the growing presence of lunar mystery.",
          "runtime": "09:02",
          "thumbnail": "/assets/thumbnails/first-on-the-moon/ch02.jpg",
          "video": "/assets/video/first-on-the-moon/ch02.mp4",
          "posterFrame": "/assets/thumbnails/first-on-the-moon/ch02.jpg",
          "airLabel": "Chapter 2",
          "isPublished": true
        },
        {
          "id": "chapter-3",
          "number": 3,
          "title": "The Silent Surface",
          "description": "The moon is reached, but the first footsteps reveal more questions than triumph.",
          "runtime": "10:11",
          "thumbnail": "/assets/thumbnails/first-on-the-moon/ch03.jpg",
          "video": "/assets/video/first-on-the-moon/ch03.mp4",
          "posterFrame": "/assets/thumbnails/first-on-the-moon/ch03.jpg",
          "airLabel": "Chapter 3",
          "isPublished": true
        }
      ]
    }
  ],
  "blitz": [
    {
      "id": "house-on-the-borderland",
      "title": "House on the Borderland",
      "creator": "Guest Feature",
      "description": "A striking one-off adaptation of cosmic dread and visionary horror.",
      "thumbnail": "/assets/posters/blitz/house-on-the-borderland.jpg",
      "video": "/assets/video/blitz/house-on-the-borderland.mp4",
      "runtime": "18:30",
      "tags": ["Cosmic Horror", "Blitz", "Guest Feature"],
      "featured": true,
      "sortOrder": 1,
      "isPublished": true
    },
    {
      "id": "cosmonascence",
      "title": "Cosmonascence",
      "creator": "AtomicFlix",
      "description": "A cosmic meditation on emergence, generative reality, and the birthward movement of existence.",
      "thumbnail": "/assets/posters/blitz/cosmonascence.jpg",
      "video": "/assets/video/blitz/cosmonascence.mp4",
      "runtime": "07:12",
      "tags": ["Cosmic Essay", "Philosophy", "Blitz"],
      "featured": true,
      "sortOrder": 2,
      "isPublished": true
    },
    {
      "id": "speed-of-light",
      "title": "Speed of Light",
      "creator": "AtomicFlix",
      "description": "A visual explainer exploring c, relativity, and the strange discipline of cosmic speed.",
      "thumbnail": "/assets/posters/blitz/speed-of-light.jpg",
      "video": "/assets/video/blitz/speed-of-light.mp4",
      "runtime": "06:45",
      "tags": ["Science Essay", "Physics", "Blitz"],
      "featured": false,
      "sortOrder": 3,
      "isPublished": true
    }
  ],
  "social": [
    {
      "platform": "YouTube",
      "label": "YouTube",
      "url": "https://example.com/youtube"
    },
    {
      "platform": "X",
      "label": "X / Twitter",
      "url": "https://example.com/x"
    },
    {
      "platform": "Facebook",
      "label": "Facebook",
      "url": "https://example.com/facebook"
    },
    {
      "platform": "Instagram",
      "label": "Instagram",
      "url": "https://example.com/instagram"
    }
  ],
  "contributors": {
    "title": "Contributors",
    "intro": "AtomicFlix welcomes collaborators who love classic science fiction and want to help bring overlooked stories to modern screens.",
    "areas": [
      "Narration",
      "Editing",
      "AI video generation",
      "Public-domain research",
      "Poster art and thumbnails",
      "Voice acting",
      "Sound design",
      "Restoration assistance"
    ],
    "contact": {
      "email": "contributors@atomicflix.com",
      "socialDm": "@atomicflix",
      "message": "Send us a brief note about what you’d like to help with, along with examples of your work if available."
    },
    "notes": "A formal contributor workflow may be added later as the site grows."
  },
  "suggestBook": {
    "title": "Suggest a Book",
    "intro": "Know a forgotten sci-fi novel or public-domain gem that deserves the AtomicFlix treatment? We’d love to hear about it.",
    "guidelines": [
      "Include the title and author",
      "Mention public-domain status if known",
      "Explain why it would make a strong adaptation",
      "Provide a source-text link if available",
      "Share any visual or tonal inspiration"
    ],
    "contact": {
      "email": "suggest@atomicflix.com",
      "socialDm": "@atomicflix",
      "message": "We’re especially interested in classic science fiction, weird fiction, speculative adventure, and visually striking forgotten works."
    },
    "notes": "Please include as much sourcing information as you can."
  }
}
```

python scripts/gen.py --source source-art/city.webp --target city-at-worlds-end/ch03.jpg
