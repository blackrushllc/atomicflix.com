Absolutely — here is a copy-paste-ready **Junie prompt** for AtomicFlix.

---

## Junie Prompt: Build the AtomicFlix Static Website

You are working inside my JetBrains project to create the first version of a website called **AtomicFlix**.

AtomicFlix is a retro-inspired, lightweight, streaming-style website for serialized public-domain sci-fi stories and selected one-off videos. It should **feel a little bit like Netflix in spirit**, but only in a broad UX sense: dark cinematic layout, horizontal browsing rows, clean focus on posters/series/chapters, autoplay to next chapter, immersive video watching. Do **not** clone Netflix branding, logos, or exact UI.

This first version must be:

* **static-site friendly**
* **JSON-driven**
* **easy to extend**
* **front-end only**
* no real backend yet
* no auth yet
* no payments yet

Later, this may become a Basil-powered MVC app with a database, but for now it should be implemented as a static front-end site with clean architecture so we can evolve it later.

---

# Primary Goal

Build a polished static website that allows visitors to:

1. Browse serialized sci-fi video series
2. Open a series detail page
3. Manually choose a chapter to watch
4. Watch a chapter in a pleasant HTML5 video player experience
5. Automatically advance to the next chapter with a countdown overlay
6. Cancel auto-advance and return to the chapter list if desired
7. Browse one-off “Blitz” videos
8. View a Contributors page
9. View a Suggest a Book page
10. Use social media links in the header/footer

Everything should be driven primarily from a JSON content file so I can add more series and episodes later without creating new HTML pages.

---

# Site Name and Theme

## Brand

* Site title: **AtomicFlix**
* Tagline ideas you can use if needed:

    * “Retro Sci-Fi, Re-Serialized”
    * “Classic Futures, Streamed”
    * “Binge the Golden Age”

## Visual Tone

Create a **retro-futurist / atomic-age / classic sci-fi** feel:

* dark cinematic background
* glowing accent colors
* subtle neon/CRT/atomic motifs
* tasteful retro styling, not cheesy
* slightly mid-century sci-fi pulp influence
* elegant, readable, modern UX

Color direction:

* deep charcoal / near-black background
* muted red or crimson accent for primary actions
* cyan / teal / electric blue secondary glow accents
* warm off-white text
* optional amber/orange accent for timers or “Blitz”

Typography:

* modern readable sans-serif for body
* optional stylized retro display font for headings/logo
* do not let theme overpower usability

Motion:

* subtle hover effects
* smooth card focus animation
* tasteful transitions
* no heavy/parallax gimmicks

---

# Technical Direction

Please implement this as a **static front-end site** using a clean structure.

Preferred stack:

* HTML
* CSS
* JavaScript
* JSON data source

You may use a lightweight front-end library if it helps, but keep it simple and static-hostable. My preference is:

* **Vanilla JS or a very light framework**
* minimal dependencies
* no backend required
* no build complexity unless clearly justified

If you think Vite is the best choice for organization, that is fine, but keep the output static and simple.

---

# Core Architecture Requirements

Use a content-driven architecture.

## Content Source

Create a JSON file such as:

`/data/library.json`

This JSON should contain:

* site metadata
* featured items
* serialized series
* chapters/episodes
* Blitz videos
* social links
* contributor contact info
* suggest-a-book contact info

The goal is that later I can add a new series or new chapter by editing JSON only.

## Suggested App Structure

Something along these lines is fine:

```text
/public or root
  index.html
  series.html
  watch.html
  blitz.html
  contributors.html
  suggest.html

/assets/
  /css
  /js
  /images
  /posters
  /thumbnails

/data/
  library.json
```

Or if you prefer a small SPA, that is also acceptable, as long as:

* routing is simple
* content remains JSON-driven
* pages remain easy to maintain
* static hosting works cleanly

---

# Content Model

Please define and use a clean JSON schema.

## Top-Level Sections

The JSON should support at least:

* `site`
* `hero`
* `featured`
* `series`
* `blitz`
* `social`
* `contributors`
* `suggestBook`

## Series Data

Each serialized series should support fields like:

* `id`
* `title`
* `author`
* `year` or `sourceEra`
* `description`
* `longDescription`
* `poster`
* `banner`
* `tags`
* `status` (ongoing / complete / coming-soon)
* `genre`
* `chapterCount`
* `chapters`

## Chapter Data

Each chapter should support:

* `id`
* `number`
* `title`
* `description`
* `runtime`
* `thumbnail`
* `video`
* `posterFrame` or fallback thumbnail
* `airLabel` or optional release text
* `nextId` optional, though it can also be derived by order

## Blitz Video Data

For one-off videos:

* `id`
* `title`
* `creator`
* `description`
* `thumbnail`
* `video`
* `runtime`
* `tags`

---

# Initial Content to Seed

Please create placeholder/sample content in the JSON for the following:

## Serialized Books

1. **The City at World’s End** — Edmond Hamilton
2. **Two Thousand Miles Below** — Charles Willard Diffin
3. **First on the Moon** — Jeff Sutton

Each of these should have:

* poster image placeholders
* banner placeholders
* a short description
* at least 3 sample chapter entries for demo purposes
* placeholder MP4 paths
* thumbnail paths

## Blitz Videos

Create a “Blitz” category for one-off videos and seed it with placeholder entries for:

* House on the Borderland
* Cosmonascence
* Speed of Light

These can be represented as one-off streaming cards using placeholder video sources for now.

---

# Pages to Build

## 1. Home / Browse Page

This is the main landing page.

It should include:

* branded header with AtomicFlix logo/title
* hero area with featured content
* row for “Featured Series”
* row for “Serialized Stories”
* row for “Blitz”
* footer with social links
* elegant CTA buttons like:

    * Watch Now
    * Browse Chapters
    * View Blitz

UX goals:

* horizontal poster/card rows
* keyboard-friendly where practical
* responsive layout
* focus on visual browsing

## 2. Series Detail Page

A reusable detail page for any series.

It should show:

* banner / hero background
* poster
* title
* author
* description
* metadata/tags
* chapter list

The chapter list should be easy to browse and manually select. Each chapter row/card should show:

* chapter number
* title
* thumbnail
* runtime
* short description
* play/watch button

This page must be driven by query string or route parameter, for example:

* `series.html?id=city-at-worlds-end`

No separate HTML page should be needed for each series.

## 3. Watch Page

A dedicated video watch page driven by query parameters, for example:

* `watch.html?series=city-at-worlds-end&chapter=1`

This page should include:

* large HTML5 video player
* chapter title and series title
* chapter description
* chapter navigation
* next / previous chapter buttons
* link back to chapter list
* fullscreen-friendly experience
* immersive dark layout

### Important Watch Behavior

After the video ends:

* if there is a next chapter, show an overlay countdown
* example text:

    * “Next Chapter starts in 10…”
* include buttons:

    * Play Next Now
    * Cancel
    * Back to Chapters

If cancelled:

* stop countdown
* remain on current page or return user to the chapter list depending on the button chosen

Also include an option to trigger the next chapter overlay near the end if desired, but at minimum it must work when video ends.

## 4. Blitz Page

A page or section dedicated to one-off videos.

This should display one-off titles distinctly from serialized books, but in the same overall visual language.

## 5. Contributors Page

This page should explain:

* AtomicFlix welcomes contributors
* people may wish to help with:

    * narration
    * editing
    * AI video generation
    * restoration
    * public-domain research
    * subtitles burned into source videos before upload
    * artwork/posters/thumbnails
    * voice acting
    * music/sound design where appropriate

Include clear placeholder contact instructions, for example:

* email placeholder
* social media DM placeholder
* future contribution form placeholder

Keep the page warm and inviting.

## 6. Suggest a Book Page

This page should explain how users can suggest:

* public-domain sci-fi books
* serialized story ideas
* one-off adaptations
* obscure classic works worth reviving

Include placeholder contact details and a simple list of what makes a strong submission:

* public domain status if known
* title/author
* why it would make a good series
* links to source text if available
* optional visual inspiration

---

# Header / Navigation

Include a clean top navigation with links to:

* Home
* Series
* Blitz
* Contributors
* Suggest a Book

Optional:

* About

Also include social media icons or text links in header and/or footer.

Use placeholder links in JSON for:

* YouTube
* X / Twitter
* Facebook
* Instagram
* TikTok or optional future platform

---

# Watch Experience Details

This part is important.

## HTML5 Video Player

Use the browser’s HTML5 video player in a polished way. We do not need a custom full player from scratch unless you want to add tasteful wrapper controls. Built-in subtitles are already burned into the videos, so:

* no subtitle toggle needed
* no caption settings needed

## Needed Features

* play/pause
* scrub
* fullscreen
* volume
* visible title/context outside player
* previous/next chapter links
* back to series/chapter list
* auto-next overlay countdown when finished

## Auto-Next Overlay

Design a stylish overlay/modal that appears after playback completes if another chapter exists.

Example behavior:

* display semi-transparent overlay over player area
* show thumbnail/title of next chapter
* countdown from 10 seconds
* buttons:

    * Play Next Now
    * Cancel
    * Back to Chapters

If there is no next chapter:

* instead show an end-of-series message with options like:

    * Back to Series
    * Browse More Series
    * Watch Blitz

---

# UX / Design Notes

Please make the experience:

* intuitive
* elegant
* responsive
* cinematic
* uncluttered

Important:

* do not make it look like a generic corporate template
* do not overdo the retro theme
* make it feel like a real niche streaming site
* posters and episode browsing should feel satisfying

Card behavior:

* hover lift
* glow accent
* visible play affordance
* clear labeling

Responsive behavior:

* desktop first, but mobile-friendly
* rows should collapse nicely on smaller screens
* watch page should remain pleasant on tablets and phones

Accessibility:

* semantic HTML
* keyboard usable nav/buttons
* color contrast should remain readable
* visible focus states

---

# Content Editing Convenience

Please optimize for future manual content maintenance.

I want it to be easy to:

* add a new series
* add a new chapter
* update poster paths
* update video paths
* update descriptions
* add new Blitz videos
* update social/contact info

So:

* keep JSON well organized
* add comments in documentation if JSON comments are not possible
* create a small README explaining how to add content

---

# Deliverables

Please produce:

## 1. The working website structure

With all pages, styles, scripts, and demo data.

## 2. JSON-driven rendering

Series, chapters, and Blitz items should load from JSON.

## 3. Reusable JavaScript modules

Keep logic organized, such as:

* data loader
* renderer for cards
* renderer for series detail
* watch page controller
* auto-next countdown logic
* URL/query parameter parsing

## 4. Placeholder assets structure

Use obvious placeholder asset paths and fallback behavior when files are missing.

## 5. A README

Explain:

* project structure
* how to run locally
* how to add a new series
* how to add a new chapter
* how to add a Blitz video
* how routing/query parameters work
* what to replace when real assets are ready

---

# Nice-to-Haves

If these fit naturally, include them:

* a “Continue Exploring” row on end-of-series
* a “Featured Tonight” hero area
* a small retro loading shimmer/skeleton
* graceful fallback if video file is missing
* a lightweight “coming soon” state for unfinished series
* tiny “Atomic Age” decorative accents in UI
* chapter progress memory using localStorage
* “last watched chapter” memory per series using localStorage

Do not let nice-to-haves bloat the project.

---

# Avoid

Please avoid:

* backend code
* auth systems
* payment systems
* overcomplicated framework setup
* giant dependency chains
* hardcoding series directly into HTML
* making separate HTML pages for each series or episode

---

# Acceptance Criteria

The build is successful if:

1. I can open the home page and browse series/blitz content
2. I can click a series and see a detail page
3. I can click a chapter and open the watch page
4. The watch page can auto-forward to the next chapter with a cancel option
5. Adding a new series/chapter only requires editing JSON and adding assets
6. Contributors and Suggest a Book pages exist and look integrated into the site
7. The visual style feels like a retro sci-fi streaming platform
8. The site works as a static front-end without a backend

---

# Optional Creative Copy

Feel free to write tasteful placeholder microcopy for:

* hero banners
* CTA buttons
* empty states
* end-of-series messages
* contributor invitation text
* suggest-a-book instructions

Keep it fun, retro, and polished.

---

# Final Note

AtomicFlix is meant to feel like a **small but compelling niche streaming home for classic sci-fi adaptations**. Think:

* curated
* atmospheric
* bingeable
* retro-futurist
* easy to grow

Please implement it cleanly and make it enjoyable to expand later.

---

If you want, I can also give you a **second Junie prompt just for the JSON schema and starter content file**, so she has an even more structured handoff.
