Absolutely — here is a **third copy-paste-ready Junie prompt** focused specifically on the **watch page UX, chapter navigation, and Netflix-style auto-next overlay behavior** for AtomicFlix.

---

## Junie Prompt: AtomicFlix Watch Page + Auto-Next UX

You are working in my JetBrains project for the **AtomicFlix** website.

The site is a static, JSON-driven retro sci-fi streaming site for serialized public-domain adaptations and one-off “Blitz” videos.

This task is specifically about implementing the **watch page experience**.

I want the watch experience to feel:

* cinematic
* intuitive
* lightweight
* polished
* a little “Netflix-y” in behavior
* but still simple and built primarily around the standard HTML5 video player

Do not build a giant custom video platform. Use the browser’s video capabilities in a tasteful wrapper.

---

# Primary Goal

Build a dedicated **watch page** that supports:

1. Watching a chapter from a serialized series
2. Watching a one-off Blitz video
3. Clear previous/next navigation for chapters
4. A polished **auto-advance to next chapter** countdown overlay after playback ends
5. A cancel option so the viewer can stop the auto-advance
6. A way to return to the chapter list or browse other content
7. A responsive, immersive dark layout with retro sci-fi styling

---

# Route / URL Behavior

The watch page should work from query parameters.

## Serialized chapter example

```text id="mfr2eu"
watch.html?series=city-at-worlds-end&chapter=1
```

or optionally:

```text id="guadn4"
watch.html?series=city-at-worlds-end&chapter=chapter-1
```

The app may support both numeric chapter lookup and chapter ID lookup.

## Blitz example

```text id="02s140"
watch.html?blitz=cosmonascence
```

The watch page controller should:

* detect whether the request is for a serialized chapter or Blitz video
* load the correct item from `library.json`
* render the correct title, metadata, video source, and navigation options

---

# Core Watch Page Layout

Please create a clean, reusable watch page layout with these sections:

## 1. Top Utility/Header Area

This can be compact and cinematic.

Include:

* AtomicFlix logo/title linked back to home
* breadcrumb or back link
* context such as:

    * Series > Chapter title
    * or Blitz > Video title

Examples:

* `← Back to Chapters`
* `← Back to Blitz`
* `← Browse Series`

This area should not dominate the page.

---

## 2. Main Video Area

This is the primary focal point.

Use an HTML5 `<video>` element with controls enabled.

Requirements:

* browser-native playback controls
* fullscreen support
* play/pause
* timeline scrub
* volume
* duration display from native controls
* poster image if available
* responsive sizing
* elegant wrapper styling

Do not add subtitle controls because subtitles are already burned into the source videos.

Style notes:

* wide player area
* dark surrounding background
* subtle glow or frame treatment
* video should feel central and uncluttered

---

## 3. Video Metadata Area

Below or beside the player, show:

For serialized chapters:

* series title
* chapter number
* chapter title
* runtime
* short description

For Blitz:

* title
* creator
* runtime
* description
* tags if useful

This text should be clean and readable, not overcrowded.

---

## 4. Chapter Navigation Area

For series content, include a chapter navigation section.

Must include:

* Previous Chapter button
* Next Chapter button
* Back to Chapter List button

Behavior:

* disable or hide Previous if on first chapter
* disable or replace Next if on final chapter
* Back to Chapter List should return to the correct series detail page

Optional:

* show “Chapter X of Y”
* show small next-chapter preview card

For Blitz videos:

* do not show chapter navigation
* instead show buttons like:

    * Back to Blitz
    * Browse Series
    * Home

---

# Auto-Next Overlay

This is one of the most important parts.

When a serialized chapter finishes playing, and there is a next published chapter, show a **countdown overlay**.

## Required Behavior

When the video ends:

* detect whether there is a next chapter
* if yes, display an overlay over the player
* countdown begins automatically, for example from 10 seconds
* when countdown reaches zero, automatically navigate to the next chapter watch page

## Overlay Content

The overlay should show:

* message like:

    * `Next Chapter starts in 10`
* next chapter title
* next chapter thumbnail if available
* buttons:

    * `Play Next Now`
    * `Cancel`
    * `Back to Chapters`

## Button Behavior

### Play Next Now

* immediately navigate to the next chapter

### Cancel

* stop the countdown
* dismiss or minimize the overlay
* leave the user on the current watch page

### Back to Chapters

* stop the countdown
* navigate back to the series detail page

---

# End-of-Series Behavior

When the video ends and there is **no next chapter**, do not show the normal auto-next overlay.

Instead show an **end-of-series overlay** or end panel.

Suggested content:

* `You’ve reached the end of this available serial`
* title of the series
* options:

    * `Back to Series`
    * `Browse More Series`
    * `Watch Blitz`

Optional:

* show recommendation cards from other series or featured titles

---

# Auto-Next Overlay Design

Please style the overlay so it feels polished and streaming-like:

* semi-transparent dark panel over video area
* subtle blur or dim behind
* readable text
* thumbnail preview
* prominent primary action
* visible countdown

Do not make it flashy or tacky.

It should feel calm, elegant, and obvious.

---

# Countdown Rules

Please implement the countdown carefully.

## Suggested default

* 10 seconds

## Requirements

* countdown visibly updates every second
* countdown stops if user presses Cancel
* countdown stops if user navigates away
* countdown should not continue running in the background after dismissal
* avoid duplicate timers
* clean up timer state properly

This logic should be reliable and simple.

---

# Optional Near-End Prompt

If it fits naturally, you may optionally add a subtle near-end cue when the video is almost complete, such as:

* a small preview card during the last 10–15 seconds

But this is optional.

The required version is the **post-playback ended overlay**.

---

# Resume / Progress Memory

Please add lightweight `localStorage` support.

## For serialized chapters

Remember at least:

* last watched chapter per series
* optional playback progress timestamp for current chapter

## Minimum requirement

Store something like:

* last watched series ID
* last watched chapter number or ID

## Optional

Also store:

* last playback time in seconds
* completed status
* started status

This can be simple and front-end only.

Use it to support future UX improvements, even if the first version only lightly exposes it.

---

# Handling Missing or Invalid Content

Please gracefully handle bad URLs or missing content.

## Invalid series/chapter

If the query string references a missing series or missing chapter:

* show a friendly error state
* provide buttons to:

    * return home
    * browse series

## Missing video file

If metadata exists but the video cannot load:

* show a friendly fallback message
* keep page metadata visible
* allow navigation to previous/next/back

Do not let the page feel broken.

---

# Suggested JavaScript Modules / Responsibilities

Please organize the logic cleanly.

For example:

* `watch-page.js`

    * page controller
    * query parsing
    * render appropriate content
    * wire navigation

* `video-controller.js`

    * playback events
    * ended event handling
    * progress saving
    * cleanup

* `auto-next.js`

    * show overlay
    * countdown logic
    * cancel/start/cleanup
    * navigate to next chapter

This does not have to be overengineered, but keep logic separated enough that it remains maintainable.

---

# Functions / Behaviors to Implement

Please implement logic equivalent to:

* `parseWatchParams()`
* `loadWatchTarget()`
* `renderSeriesChapterWatchPage()`
* `renderBlitzWatchPage()`
* `getNextChapter(seriesId, currentChapter)`
* `getPreviousChapter(seriesId, currentChapter)`
* `startAutoNextCountdown(nextUrl, seconds)`
* `cancelAutoNextCountdown()`
* `showAutoNextOverlay(nextChapter)`
* `showSeriesEndOverlay()`
* `saveWatchProgress()`
* `loadWatchProgress()`

Names can vary, but functionality should be comparable.

---

# UX Details

## For serialized watch pages

Show:

* back to series
* previous / next chapter
* chapter title clearly
* chapter number clearly
* enough description for context
* maybe “Chapter X of Y”

## For Blitz watch pages

Show:

* simpler presentation
* title
* creator
* description
* back to Blitz/home links
* perhaps a small “Explore Series” suggestion row below

---

# Styling Direction

Keep the watch page aligned with the AtomicFlix brand:

* dark background
* cinematic spacing
* subtle red/cyan retro-futurist accents
* minimal chrome
* good typography
* modern streaming feel with vintage sci-fi soul

Avoid:

* clutter
* giant walls of text
* overdesigned fake player controls
* noisy animation

---

# Accessibility / Interaction Details

Please ensure:

* keyboard-accessible buttons
* visible focus states
* semantic button usage
* overlay can be dismissed intentionally
* links/buttons are clearly labeled
* color contrast remains readable

Optional but nice:

* ESC key closes a non-destructive overlay state such as auto-next cancel/dismiss

---

# Edge Cases

Please handle these well:

1. First chapter has no previous chapter
2. Final chapter has no next chapter
3. Series contains unpublished future chapters

    * only auto-advance to the next published one
4. Blitz videos should not use chapter auto-next behavior
5. Invalid query parameters should produce a clean fallback page
6. Repeated ended events should not create multiple overlays or countdowns
7. Reloading the page should not leave stale timer state running

---

# Demo Expectations

Please wire the watch page against the AtomicFlix `library.json` structure.

It should work with sample routes like:

```text id="ugwlwu"
watch.html?series=city-at-worlds-end&chapter=1
watch.html?series=two-thousand-miles-below&chapter=2
watch.html?series=first-on-the-moon&chapter=3
watch.html?blitz=cosmonascence
```

---

# Deliverables

Please produce:

1. The watch page implementation
2. Watch-page styling integrated with the AtomicFlix theme
3. Auto-next overlay and countdown logic
4. Previous/next/back navigation
5. End-of-series state
6. Blitz-specific watch behavior
7. LocalStorage-based watch memory
8. Friendly fallback UI for bad routes or missing content
9. Clean comments explaining the important logic

---

# Acceptance Criteria

This task is successful if:

1. A user can open a chapter watch page from query parameters
2. A user can watch the chapter in a clean HTML5 player
3. Previous/next/back navigation works correctly
4. When the chapter ends, the next chapter countdown overlay appears
5. The user can:

    * let it auto-advance
    * click Play Next Now
    * click Cancel
    * click Back to Chapters
6. Final chapters show an end-of-series state instead of auto-next
7. Blitz pages work cleanly without chapter logic
8. The implementation remains lightweight and static-site friendly

---

# Final Note

The ideal feel is:

* **small streaming platform**
* **pleasant binge-watching flow**
* **retro sci-fi atmosphere**
* **simple to maintain**
* **no unnecessary complexity**

Build it like a polished niche streaming experience rather than a generic template.

---

I can also give you a **fourth Junie prompt for the visual design system only** — logo area, color palette, card styling, retro-futurist UI tokens, and component design language for AtomicFlix.
