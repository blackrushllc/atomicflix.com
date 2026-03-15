/**
 * AtomicFlix Data Loader and Content Helper
 */

let libraryData = null;

/**
 * Loads the library JSON data.
 * @returns {Promise<Object>} The library data.
 */
export async function loadLibrary() {
    if (libraryData) return libraryData;
    
    try {
        const response = await fetch('data/library.json');
        if (!response.ok) {
            throw new Error(`Failed to load library: ${response.status} ${response.statusText}`);
        }
        libraryData = await response.ok ? await response.json() : null;
        return libraryData;
    } catch (error) {
        console.error('Error loading AtomicFlix library:', error);
        return null;
    }
}

export function getSiteMeta() {
    return libraryData?.site || {};
}

export function getHero() {
    return libraryData?.hero || {};
}

export function getFeaturedItems() {
    return libraryData?.featured || [];
}

export function getAllSeries() {
    return libraryData?.series || [];
}

export function getSeriesById(id) {
    return libraryData?.series?.find(s => s.id === id);
}

export function getChapterBySeriesAndId(seriesId, chapterId) {
    const series = getSeriesById(seriesId);
    return series?.chapters?.find(c => c.id === chapterId);
}

export function getChapterBySeriesAndNumber(seriesId, number) {
    const series = getSeriesById(seriesId);
    const num = parseInt(number, 10);
    return series?.chapters?.find(c => c.number === num);
}

export function getNextChapter(seriesId, currentNumber) {
    const series = getSeriesById(seriesId);
    if (!series || !series.chapters) return null;
    const currentIdx = series.chapters.findIndex(c => c.number === parseInt(currentNumber, 10));
    if (currentIdx === -1) return null;
    
    // Find next published chapter
    for (let i = currentIdx + 1; i < series.chapters.length; i++) {
        if (series.chapters[i].isPublished) {
            return series.chapters[i];
        }
    }
    return null;
}

export function getPreviousChapter(seriesId, currentNumber) {
    const series = getSeriesById(seriesId);
    if (!series || !series.chapters) return null;
    const currentIdx = series.chapters.findIndex(c => c.number === parseInt(currentNumber, 10));
    if (currentIdx === -1) return null;

    // Find previous published chapter
    for (let i = currentIdx - 1; i >= 0; i--) {
        if (series.chapters[i].isPublished) {
            return series.chapters[i];
        }
    }
    return null;
}

export function getBlitzItems() {
    return libraryData?.blitz || [];
}

export function getBlitzById(id) {
    return libraryData?.blitz?.find(b => b.id === id);
}

export function getSocialLinks() {
    return libraryData?.social || [];
}

export function getContributorsContent() {
    return libraryData?.contributors || {};
}

export function getSuggestBookContent() {
    return libraryData?.suggestBook || {};
}
