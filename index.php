<?php
/**
 * Main entry point for Mahati Interior Design Website
 * Handles routing and page rendering with security measures
 */

// Define secure access constant
define('SECURE_ACCESS', true);

// Include configuration
require_once 'mahati/includes/config.php';

// Get and validate the requested page
$page = get_get_data('page', DEFAULT_PAGE);
$page = validate_page($page);

// Handle form submissions - redirect to dedicated form handler
if (is_post_request()) {
    $form_type = get_post_data('form_type');
    
    if ($form_type === 'contact' || $form_type === 'newsletter') {
        // Redirect to dedicated form handler
        header('Location: ' . SITE_URL . '/mahati/includes/form-handler.php');
        exit;
    }
}

// Include header
include INCLUDES_PATH . 'header.php';

// Include the requested page content
global $valid_pages;
$page_file = PAGES_PATH . $valid_pages[$page];

if (file_exists($page_file)) {
    include $page_file;
} else {
    // 404 error - page not found
    http_response_code(404);
    echo '<section class="error-page">';
    echo '<div class="container">';
    echo '<div style="text-align: center; padding: 4rem 0;">';
    echo '<h1 style="font-size: 3rem; color: #ff6b35; margin-bottom: 1rem;">404</h1>';
    echo '<h2 style="font-size: 2rem; margin-bottom: 1rem;">Page Not Found</h2>';
    echo '<p style="font-size: 1.2rem; color: #666; margin-bottom: 2rem;">The page you are looking for does not exist.</p>';
    echo '<a href="' . page_url() . '" class="cta-button">Go Home</a>';
    echo '</div>';
    echo '</section>';
    log_error("404 - Page not found: {$page}");
}

// Include footer
include INCLUDES_PATH . 'footer.php';
?>
