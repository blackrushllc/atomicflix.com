# AtomicFlix Roadmap: Migration to Basil MVC & Enhanced Features

This document outlines the roadmap for migrating AtomicFlix from a static JSON-driven site to a dynamic application powered by the Basil MVC framework and a SQL database.

## Phase 1: Infrastructure & Data Migration

### 1. Migrate library.json to SQL
- **Goal**: Move all content data from `data/library.json` to a relational database (PostgreSQL/MySQL).
- **Tasks**:
    - Design SQL schema for Site, Hero, Series, Chapters, Blitz, Social, Contributors, and Suggestions.
    - Create a migration script to import existing JSON data into the database.
- **API Endpoints**:
    - `GET /api/v1/site`: Retrieve site metadata.
    - `GET /api/v1/hero`: Retrieve hero section data.
    - `GET /api/v1/series`: Retrieve all series.
    - `GET /api/v1/series/:id`: Retrieve specific series with its chapters.
    - `GET /api/v1/blitz`: Retrieve all Blitz videos.
    - `GET /api/v1/contributors`: Retrieve contributor page data.

## Phase 2: User Accounts & Authentication

### 2. Join/Login/Delete Account with User Profile and Avatar
- **Goal**: Implement user management.
- **API Endpoints**:
    - `POST /api/v1/auth/register`: Create a new account.
    - `POST /api/v1/auth/login`: Authenticate user and return session/token.
    - `POST /api/v1/auth/logout`: End user session.
    - `GET /api/v1/user/profile`: Retrieve current user profile.
    - `PUT /api/v1/user/profile`: Update user profile details.
    - `POST /api/v1/user/avatar`: Upload/Update user avatar image.
    - `DELETE /api/v1/user/account`: Permanently delete account.

### 3. Email Notifications
- **Goal**: Notify users of new content or account activity.
- **API Endpoints**:
    - `GET /api/v1/user/notifications/settings`: Get user notification preferences.
    - `PUT /api/v1/user/notifications/settings`: Update preferences.

### 4. Forgot Password
- **Goal**: Secure password recovery.
- **API Endpoints**:
    - `POST /api/v1/auth/forgot-password`: Trigger password reset email.
    - `POST /api/v1/auth/reset-password`: Update password using reset token.

## Phase 3: Social & Engagement Features

### 5. Video "Like" / "Unlike"
- **API Endpoints**:
    - `POST /api/v1/videos/:id/like`: Like a video (episode or blitz).
    - `DELETE /api/v1/videos/:id/like`: Remove like.

### 6. Series Recommendations
- **Goal**: Share series via email.
- **API Endpoints**:
    - `POST /api/v1/series/:id/recommend`: Send recommendation email to a specified address.

### 7. Series Bookmarking (Favorites)
- **API Endpoints**:
    - `POST /api/v1/series/:id/favorite`: Add series to user's favorites.
    - `DELETE /api/v1/series/:id/favorite`: Remove series from favorites.

### 8. Bookmarked Series Page
- **Goal**: View and manage favorites.
- **API Endpoints**:
    - `GET /api/v1/user/favorites`: List all favorited series for the current user.

### 9. Video Comments
- **API Endpoints**:
    - `GET /api/v1/videos/:id/comments`: Retrieve comments for a video.
    - `POST /api/v1/videos/:id/comments`: Post a new comment.
    - `PUT /api/v1/comments/:id`: Edit a comment.
    - `DELETE /api/v1/comments/:id`: Delete a comment.

### 10. Series Reviews
- **Goal**: Star ratings and text reviews for series.
- **API Endpoints**:
    - `GET /api/v1/series/:id/reviews`: Retrieve all reviews for a series.
    - `POST /api/v1/series/:id/reviews`: Post a review (stars + comment).
    - `PUT /api/v1/reviews/:id`: Edit a review.
    - `DELETE /api/v1/reviews/:id`: Delete a review.

### 11. Like/Unlike Comments and Reviews
- **API Endpoints**:
    - `POST /api/v1/comments/:id/like`: Like a comment.
    - `DELETE /api/v1/comments/:id/like`: Remove like from comment.
    - `POST /api/v1/reviews/:id/like`: Like a review.
    - `DELETE /api/v1/reviews/:id/like`: Remove like from review.

### 12. Average Review Score
- **Goal**: Display average star rating.
- **API Endpoints**:
    - `GET /api/v1/series/:id/rating`: Get average score and total review count.

## Phase 4: Administration & Moderation

### 13. Administration Dashboard
Centralized management for site content and users.

- **a. Set Featured Series**
    - `PUT /api/v1/admin/featured`: Update the hero and featured shelves.
- **b. Content Status Management**
    - `PATCH /api/v1/admin/series/:id/status`: Set series status (e.g., complete, ongoing, coming soon).
    - `PATCH /api/v1/admin/episodes/:id/status`: Toggle episode visibility (isPublished).
- **c. Asset Management**
    - `PUT /api/v1/admin/series/:id/assets`: Update banners, posters for series.
    - `PUT /api/v1/admin/episodes/:id/assets`: Update thumbnails, video links for episodes.
- **d. Data Editing**
    - `PUT /api/v1/admin/series/:id`: Edit series titles, descriptions, metadata.
    - `PUT /api/v1/admin/episodes/:id`: Edit episode titles, runtimes, descriptions.
- **e. Content Creation & Site Configuration**
    - `POST /api/v1/admin/series`: Add a new series.
    - `POST /api/v1/admin/episodes`: Add a new episode to a series.
    - `POST /api/v1/admin/blitz`: Add a new one-off video.
    - `PUT /api/v1/admin/site-info`: Edit site title, tagline, etc.
    - `PUT /api/v1/admin/contributors`: Edit contributors page data.
    - `PUT /api/v1/admin/contact`: Edit contact info.
- **f. User Management**
    - `GET /api/v1/admin/users`: List all users.
    - `GET /api/v1/admin/users/:id`: View user details.
    - `PUT /api/v1/admin/users/:id`: Update user roles or information.
- **g. Site Analytics**
    - `GET /api/v1/admin/analytics`: Retrieve site-wide traffic and engagement metrics.
- **h. Moderation & Banning**
    - `POST /api/v1/admin/users/:id/ban`: Ban a user from the platform.
    - `DELETE /api/v1/admin/users/:id/ban`: Unban a user.
    - `DELETE /api/v1/admin/comments/:id`: Remove inappropriate comments.
    - `DELETE /api/v1/admin/reviews/:id`: Remove inappropriate reviews.
