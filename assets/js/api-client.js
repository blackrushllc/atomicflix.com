/**
 * AtomicFlix API Client Library
 * 
 * Provides functions to call the AtomicFlix API endpoints.
 * All functions return a Promise that resolves with the API response JSON.
 */

const BASE_URL = '/api/v1';

/**
 * Generic API caller
 * @param {string} endpoint - The API endpoint path (e.g. '/site')
 * @param {string} method - HTTP method (GET, POST, PUT, DELETE, PATCH)
 * @param {Object} data - Optional data for request body
 */
async function callApi(endpoint, method = 'GET', data = null) {
    const url = `${BASE_URL}${endpoint}`;
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        }
    };
    
    if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
        options.body = JSON.stringify(data);
    }
    
    try {
        const response = await fetch(url, options);
        if (!response.ok) {
            throw new Error(`API Error: ${response.status} ${response.statusText}`);
        }
        return await response.json();
    } catch (error) {
        console.error(`Error calling API endpoint ${endpoint}:`, error);
        throw error;
    }
}

// --- PHASE 1: SITE & CONTENT ---

export const Site = {
    getMeta: () => callApi('/site'),
    getHero: () => callApi('/hero'),
    getSeries: () => callApi('/series'),
    getSeriesById: (id) => callApi(`/series/${id}`),
    getBlitz: () => callApi('/blitz'),
    getContributors: () => callApi('/contributors')
};

// --- PHASE 2: AUTH & USER ---

export const Auth = {
    register: (userData) => callApi('/auth/register', 'POST', userData),
    login: (credentials) => callApi('/auth/login', 'POST', credentials),
    logout: () => callApi('/auth/logout', 'POST'),
    forgotPassword: (email) => callApi('/auth/forgot-password', 'POST', { email }),
    resetPassword: (token, password) => callApi('/auth/reset-password', 'POST', { token, password })
};

export const User = {
    getProfile: () => callApi('/user/profile'),
    updateProfile: (profileData) => callApi('/user/profile', 'PUT', profileData),
    updateAvatar: (avatarData) => callApi('/user/avatar', 'POST', avatarData),
    deleteAccount: () => callApi('/user/account', 'DELETE'),
    getNotificationSettings: () => callApi('/user/notifications/settings'),
    updateNotificationSettings: (settings) => callApi('/user/notifications/settings', 'PUT', settings),
    getFavorites: () => callApi('/user/favorites')
};

// --- PHASE 3: SOCIAL & ENGAGEMENT ---

export const Videos = {
    like: (id) => callApi(`/videos/${id}/like`, 'POST'),
    unlike: (id) => callApi(`/videos/${id}/like`, 'DELETE'),
    getComments: (id) => callApi(`/videos/${id}/comments`),
    postComment: (id, commentData) => callApi(`/videos/${id}/comments`, 'POST', commentData)
};

export const Comments = {
    update: (id, commentData) => callApi(`/comments/${id}`, 'PUT', commentData),
    delete: (id) => callApi(`/comments/${id}`, 'DELETE'),
    like: (id) => callApi(`/comments/${id}/like`, 'POST'),
    unlike: (id) => callApi(`/comments/${id}/like`, 'DELETE')
};

export const Series = {
    favorite: (id) => callApi(`/series/${id}/favorite`, 'POST'),
    unfavorite: (id) => callApi(`/series/${id}/favorite`, 'DELETE'),
    recommend: (id, email) => callApi(`/series/${id}/recommend`, 'POST', { email }),
    getReviews: (id) => callApi(`/series/${id}/reviews`),
    postReview: (id, reviewData) => callApi(`/series/${id}/reviews`, 'POST', reviewData),
    getRating: (id) => callApi(`/series/${id}/rating`)
};

export const Reviews = {
    update: (id, reviewData) => callApi(`/reviews/${id}`, 'PUT', reviewData),
    delete: (id) => callApi(`/reviews/${id}`, 'DELETE'),
    like: (id) => callApi(`/reviews/${id}/like`, 'POST'),
    unlike: (id) => callApi(`/reviews/${id}/like`, 'DELETE')
};

// --- PHASE 4: ADMINISTRATION ---

export const Admin = {
    updateFeatured: (featuredData) => callApi('/admin/featured', 'PUT', featuredData),
    setSeriesStatus: (id, status) => callApi(`/admin/series/${id}/status`, 'PATCH', { status }),
    setEpisodeStatus: (id, status) => callApi(`/admin/episodes/${id}/status`, 'PATCH', { status }),
    updateSeriesAssets: (id, assets) => callApi(`/admin/series/${id}/assets`, 'PUT', assets),
    updateEpisodeAssets: (id, assets) => callApi(`/admin/episodes/${id}/assets`, 'PUT', assets),
    updateSeries: (id, seriesData) => callApi(`/admin/series/${id}`, 'PUT', seriesData),
    updateEpisode: (id, episodeData) => callApi(`/admin/episodes/${id}`, 'PUT', episodeData),
    createSeries: (seriesData) => callApi('/admin/series', 'POST', seriesData),
    createEpisode: (episodeData) => callApi('/admin/episodes', 'POST', episodeData),
    createBlitz: (blitzData) => callApi('/admin/blitz', 'POST', blitzData),
    updateSiteInfo: (siteInfo) => callApi('/admin/site-info', 'PUT', siteInfo),
    updateContributors: (contributors) => callApi('/admin/contributors', 'PUT', contributors),
    updateContact: (contact) => callApi('/admin/contact', 'PUT', contact),
    getSeries: () => callApi('/admin/series'),
    getEpisodes: (seriesId) => callApi(`/admin/episodes?seriesId=${seriesId}`),
    getUsers: () => callApi('/admin/users'),
    getUserDetails: (id) => callApi(`/admin/users/${id}`),
    updateUser: (id, userData) => callApi(`/admin/users/${id}`, 'PUT', userData),
    banUser: (id) => callApi(`/admin/users/${id}/ban`, 'POST'),
    unbanUser: (id) => callApi(`/admin/users/${id}/ban`, 'DELETE'),
    deleteComment: (id) => callApi(`/admin/comments/${id}`, 'DELETE'),
    deleteReview: (id) => callApi(`/admin/reviews/${id}`, 'DELETE'),
    getAnalytics: () => callApi('/admin/analytics')
};
