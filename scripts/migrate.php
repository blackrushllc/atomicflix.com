<?php
/**
 * AtomicFlix Database Migration Script
 * Migrates library.json to MySQL and sets up other required tables.
 */

require_once __DIR__ . '/../api/v1/db.php';

// Check if we can get a DB connection
try {
    $pdo = getDbConnection();
} catch (Exception $e) {
    die("Database Connection Error: " . $e->getMessage() . "\n");
}

echo "Starting migration...\n";

// Disable foreign key checks while dropping tables
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

$tablesToDrop = [
    'notification_queue',
    'access_logs',
    'avatars',
    'favorites',
    'reviews',
    'replies',
    'comments',
    'likes',
    'users',
    'suggest_book_guidelines',
    'suggest_book',
    'contributor_workflow',
    'contributor_techstack',
    'contributor_areas',
    'contributors',
    'social_links',
    'blitz_videos',
    'chapters',
    'series',
    'featured_items',
    'hero_section',
    'site_settings'
];

foreach ($tablesToDrop as $table) {
    echo "Dropping table: $table\n";
    $pdo->exec("DROP TABLE IF EXISTS `$table` CASCADE");
}

$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

echo "Creating tables...\n";

// --- Site Settings ---
$pdo->exec("CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    tagline VARCHAR(255),
    description TEXT,
    logoText VARCHAR(255),
    theme VARCHAR(100),
    version VARCHAR(20),
    defaultPlaybackSpeed DECIMAL(3, 2) DEFAULT 0
)");

// --- Hero Section ---
$pdo->exec("CREATE TABLE hero_section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    featuredSeriesId VARCHAR(255),
    headline VARCHAR(255),
    subheadline VARCHAR(255),
    backgroundImage VARCHAR(255),
    primaryCtaLabel VARCHAR(100),
    secondaryCtaLabel VARCHAR(100)
)");

// --- Featured Items ---
$pdo->exec("CREATE TABLE featured_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('series', 'blitz'),
    item_id VARCHAR(255),
    label VARCHAR(255),
    reason TEXT
)");

// --- Series ---
$pdo->exec("CREATE TABLE series (
    id VARCHAR(100) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    sourceEra VARCHAR(100),
    description TEXT,
    longDescription TEXT,
    poster VARCHAR(255),
    banner VARCHAR(255),
    genre VARCHAR(100),
    status VARCHAR(50),
    chapterCount INT DEFAULT 0,
    featured BOOLEAN DEFAULT FALSE,
    sortOrder INT DEFAULT 0
)");

// --- Chapters ---
$pdo->exec("CREATE TABLE chapters (
    id VARCHAR(100),
    series_id VARCHAR(100),
    number INT,
    title VARCHAR(255),
    description TEXT,
    runtime VARCHAR(20),
    thumbnail VARCHAR(255),
    video VARCHAR(255),
    posterFrame VARCHAR(255),
    airLabel VARCHAR(100),
    isPublished BOOLEAN DEFAULT TRUE,
    playbackSpeed DECIMAL(3, 2) DEFAULT 1.0,
    PRIMARY KEY (series_id, id),
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
)");

// --- Blitz Videos ---
$pdo->exec("CREATE TABLE blitz_videos (
    id VARCHAR(100) PRIMARY KEY,
    title VARCHAR(255),
    creator VARCHAR(255),
    description TEXT,
    thumbnail VARCHAR(255),
    video VARCHAR(255),
    videoType VARCHAR(50) DEFAULT 'direct',
    runtime VARCHAR(20),
    featured BOOLEAN DEFAULT FALSE,
    sortOrder INT DEFAULT 0,
    isPublished BOOLEAN DEFAULT TRUE,
    playbackSpeed DECIMAL(3, 2) DEFAULT 1.0
)");

// --- Social Links ---
$pdo->exec("CREATE TABLE social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform VARCHAR(100),
    label VARCHAR(100),
    url VARCHAR(255)
)");

// --- Contributors ---
$pdo->exec("CREATE TABLE contributors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    intro TEXT,
    notes TEXT,
    email VARCHAR(255),
    socialDm VARCHAR(255),
    message TEXT
)");

$pdo->exec("CREATE TABLE contributor_areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contributor_id INT,
    area_text VARCHAR(255),
    FOREIGN KEY (contributor_id) REFERENCES contributors(id) ON DELETE CASCADE
)");

$pdo->exec("CREATE TABLE contributor_techstack (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contributor_id INT,
    tech_text VARCHAR(255),
    FOREIGN KEY (contributor_id) REFERENCES contributors(id) ON DELETE CASCADE
)");

$pdo->exec("CREATE TABLE contributor_workflow (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contributor_id INT,
    step_text VARCHAR(255),
    FOREIGN KEY (contributor_id) REFERENCES contributors(id) ON DELETE CASCADE
)");

// --- Suggest Book ---
$pdo->exec("CREATE TABLE suggest_book (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    intro TEXT,
    notes TEXT,
    email VARCHAR(255),
    socialDm VARCHAR(255),
    message TEXT
)");

$pdo->exec("CREATE TABLE suggest_book_guidelines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    suggest_book_id INT,
    guideline_text VARCHAR(255),
    FOREIGN KEY (suggest_book_id) REFERENCES suggest_book(id) ON DELETE CASCADE
)");

// --- NEW TABLES REQUESTED ---

// Users table
$pdo->exec("CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    role ENUM('admin', 'user') DEFAULT 'user',
    profile_bio TEXT,
    avatar_url VARCHAR(255),
    facebook_id VARCHAR(255),
    twitter_id VARCHAR(255),
    google_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Likes
$pdo->exec("CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    content_type ENUM('chapter', 'blitz'),
    content_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// Comments
$pdo->exec("CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    content_type ENUM('series', 'chapter', 'blitz'),
    content_id VARCHAR(100),
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// Replies
$pdo->exec("CREATE TABLE replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comment_id INT,
    user_id INT,
    reply_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (comment_id) REFERENCES comments(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// Reviews
$pdo->exec("CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    series_id VARCHAR(100),
    rating INT CHECK (rating BETWEEN 1 AND 5),
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
)");

// Favorites
$pdo->exec("CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    series_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
)");

// Avatars (to store avatar history/options)
$pdo->exec("CREATE TABLE avatars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    file_path VARCHAR(255),
    is_current BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// Access Logs
$pdo->exec("CREATE TABLE access_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(255),
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)");

// Notification Queue
$pdo->exec("CREATE TABLE notification_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    subject VARCHAR(255),
    message TEXT,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sent_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

echo "Loading data from library.json...\n";

$jsonPath = __DIR__ . '/../data/library.json';
if (!file_exists($jsonPath)) {
    die("Library JSON not found at $jsonPath\n");
}

$data = json_decode(file_get_contents($jsonPath), true);
if (!$data) {
    die("Error decoding library JSON\n");
}

// --- Migrate Site Settings ---
echo "Migrating site settings...\n";
$stmt = $pdo->prepare("INSERT INTO site_settings (title, tagline, description, logoText, theme, version, defaultPlaybackSpeed) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $data['site']['title'],
    $data['site']['tagline'],
    $data['site']['description'],
    $data['site']['logoText'],
    $data['site']['theme'],
    $data['site']['version'],
    $data['site']['defaultPlaybackSpeed'] ?? 0
]);

// --- Migrate Hero Section ---
echo "Migrating hero section...\n";
$stmt = $pdo->prepare("INSERT INTO hero_section (featuredSeriesId, headline, subheadline, backgroundImage, primaryCtaLabel, secondaryCtaLabel) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $data['hero']['featuredSeriesId'],
    $data['hero']['headline'],
    $data['hero']['subheadline'],
    $data['hero']['backgroundImage'],
    $data['hero']['primaryCtaLabel'],
    $data['hero']['secondaryCtaLabel']
]);

// --- Migrate Featured Items ---
echo "Migrating featured items...\n";
$stmt = $pdo->prepare("INSERT INTO featured_items (type, item_id, label, reason) VALUES (?, ?, ?, ?)");
foreach ($data['featured'] as $item) {
    $stmt->execute([$item['type'], $item['id'], $item['label'], $item['reason']]);
}

// --- Migrate Series & Chapters ---
echo "Migrating series and chapters...\n";
$stmtSeries = $pdo->prepare("INSERT INTO series (id, title, author, sourceEra, description, longDescription, poster, banner, genre, status, chapterCount, featured, sortOrder) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmtChapter = $pdo->prepare("INSERT INTO chapters (id, series_id, number, title, description, runtime, thumbnail, video, posterFrame, airLabel, isPublished, playbackSpeed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($data['series'] as $series) {
    $stmtSeries->execute([
        $series['id'],
        $series['title'],
        $series['author'],
        $series['sourceEra'],
        $series['description'],
        $series['longDescription'],
        $series['poster'],
        $series['banner'],
        $series['genre'],
        $series['status'],
        $series['chapterCount'],
        $series['featured'] ? 1 : 0,
        $series['sortOrder']
    ]);

    foreach ($series['chapters'] as $chapter) {
        $stmtChapter->execute([
            $chapter['id'],
            $series['id'],
            $chapter['number'],
            $chapter['title'],
            $chapter['description'],
            $chapter['runtime'],
            $chapter['thumbnail'],
            $chapter['video'],
            $chapter['posterFrame'] ?? $chapter['thumbnail'],
            $chapter['airLabel'] ?? ("Episode " . $chapter['number']),
            ($chapter['isPublished'] ?? true) ? 1 : 0,
            $chapter['playbackSpeed'] ?? 1.0
        ]);
    }
}

// --- Migrate Blitz Videos ---
echo "Migrating blitz videos...\n";
$stmt = $pdo->prepare("INSERT INTO blitz_videos (id, title, creator, description, thumbnail, video, videoType, runtime, featured, sortOrder, isPublished, playbackSpeed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($data['blitz'] as $blitz) {
    $stmt->execute([
        $blitz['id'],
        $blitz['title'],
        $blitz['creator'],
        $blitz['description'],
        $blitz['thumbnail'],
        $blitz['video'],
        $blitz['videoType'] ?? 'direct',
        $blitz['runtime'],
        ($blitz['featured'] ?? false) ? 1 : 0,
        $blitz['sortOrder'] ?? 0,
        ($blitz['isPublished'] ?? true) ? 1 : 0,
        $blitz['playbackSpeed'] ?? 1.0
    ]);
}

// --- Migrate Social Links ---
echo "Migrating social links...\n";
$stmt = $pdo->prepare("INSERT INTO social_links (platform, label, url) VALUES (?, ?, ?)");
foreach ($data['social'] as $link) {
    $stmt->execute([$link['platform'], $link['label'], $link['url']]);
}

// --- Migrate Contributors ---
echo "Migrating contributors...\n";
$contributors = $data['contributors'];
$stmt = $pdo->prepare("INSERT INTO contributors (title, intro, notes, email, socialDm, message) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $contributors['title'],
    $contributors['intro'],
    $contributors['notes'],
    $contributors['contact']['email'],
    $contributors['contact']['socialDm'],
    $contributors['contact']['message']
]);
$contributorId = $pdo->lastInsertId();

$stmtArea = $pdo->prepare("INSERT INTO contributor_areas (contributor_id, area_text) VALUES (?, ?)");
foreach ($contributors['areas'] as $area) {
    $stmtArea->execute([$contributorId, $area]);
}

$stmtTech = $pdo->prepare("INSERT INTO contributor_techstack (contributor_id, tech_text) VALUES (?, ?)");
foreach ($contributors['techstack'] as $tech) {
    $stmtTech->execute([$contributorId, $tech]);
}

$stmtWorkflow = $pdo->prepare("INSERT INTO contributor_workflow (contributor_id, step_text) VALUES (?, ?)");
foreach ($contributors['workflow'] as $step) {
    $stmtWorkflow->execute([$contributorId, $step]);
}

// --- Migrate Suggest Book ---
echo "Migrating suggest book info...\n";
$suggest = $data['suggestBook'];
$stmt = $pdo->prepare("INSERT INTO suggest_book (title, intro, notes, email, socialDm, message) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $suggest['title'],
    $suggest['intro'],
    $suggest['notes'],
    $suggest['contact']['email'],
    $suggest['contact']['socialDm'],
    $suggest['contact']['message']
]);
$suggestId = $pdo->lastInsertId();

$stmtGuideline = $pdo->prepare("INSERT INTO suggest_book_guidelines (suggest_book_id, guideline_text) VALUES (?, ?)");
foreach ($suggest['guidelines'] as $guideline) {
    $stmtGuideline->execute([$suggestId, $guideline]);
}

// --- Create Admin and Demo Users ---
echo "Creating admin and demo users...\n";

$adminUsername = $_ENV['ADMIN_USER'] ?? 'admin';
$adminPassword = $_ENV['ADMIN_PASS'] ?? 'password';
$demoUsername = $_ENV['DEMO_USER'] ?? 'user';
$demoPassword = $_ENV['DEMO_PASS'] ?? 'password';

$stmtUser = $pdo->prepare("INSERT INTO users (username, password, email, role, profile_bio, avatar_url, facebook_id, twitter_id, google_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Admin user
$stmtUser->execute([
    $adminUsername,
    password_hash($adminPassword, PASSWORD_DEFAULT),
    'admin@atomicflix.com',
    'admin',
    'AtomicFlix System Administrator. Retro sci-fi enthusiast and data archivist.',
    'assets/images/avatars/admin.jpg',
    'fb_admin_123',
    'tw_admin_123',
    'gg_admin_123'
]);

// Demo user
$stmtUser->execute([
    $demoUsername,
    password_hash($demoPassword, PASSWORD_DEFAULT),
    'user@atomicflix.com',
    'user',
    'A casual traveler through time and space. I love classic pulp adventures and cosmic horror.',
    'assets/images/avatars/user.jpg',
    null,
    null,
    null
]);

echo "Migration completed successfully!\n";
?>
