<?php

/**
 * Site class — central theme setup and configuration
 *
 * Handles all theme initialization: hooks, menus, CPTs, context, filters.
 *
 * @package    Canopy
 * @author     Agência Upgrade <contato@agenciaupgrade.com.br>
 * @link       https://agenciaupgrade.com.br
 */

namespace Canopy;

use Timber\Site as TimberSite;
use Timber\Timber;

/**
 * Site class
 */
class Site extends TimberSite
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'themeSupports']);
        add_action('init', [$this, 'registerPostTypes']);
        add_action('init', [$this, 'registerTaxonomies']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueAssets']);
        add_action('wp_enqueue_scripts', [$this, 'dequeueBlockStyles'], 20);
        add_action('customize_register', [$this, 'customizeRegister']);
        add_action('wp_head', [$this, 'addNoindexTags'], 2);

        add_filter('timber/context', [$this, 'addToContext']);
        add_filter('timber/twig/filters', [$this, 'addFiltersToTwig']);
        add_filter('timber/twig/functions', [$this, 'addFunctionsToTwig']);
        add_filter('timber/twig/environment/options', [$this, 'updateTwigEnvironmentOptions']);
        add_filter('robots_txt', [$this, 'updateRobotsTxt'], 10, 2);
        add_filter('wp_sitemaps_enabled', '__return_false');
        add_filter('wp_mail_from', [$this, 'mailFrom']);
        add_filter('wp_mail_from_name', [$this, 'mailFromName']);
        add_action('phpmailer_init', [$this, 'configureSMTP']);

        parent::__construct();
    }

    /**
     * Theme supports
     */
    public function themeSupports(): void
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);

        load_theme_textdomain('canopy', get_template_directory() . '/languages');

        register_nav_menus([
            'primary_navigation' => __('Primary Navigation', 'canopy'),
            'footer_navigation'  => __('Footer Navigation', 'canopy'),
        ]);
    }

    /**
     * Register custom post types
     *
     * Add custom post type definitions here.
     * Reference: https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/
     */
    public function registerPostTypes(): void
    {
        // Example: register_post_type( 'portfolio', [ ... ] );
    }

    /**
     * Register custom taxonomies
     *
     * Add custom taxonomy definitions here.
     * Reference: https://developer.wordpress.org/plugins/taxonomies/registering-custom-taxonomies/
     */
    public function registerTaxonomies(): void
    {
        // Example: register_taxonomy( 'portfolio_category', 'portfolio', [ ... ] );
    }

    /**
     * Enqueue theme styles and scripts
     *
     * Assets are served as source files. The web server / LiteSpeed Cache
     * handles compression and browser caching in production. filemtime()
     * provides cache-busting on every change.
     */
    public function enqueueAssets(): void
    {
        $dir = get_template_directory();
        $uri = get_template_directory_uri();

        // Main stylesheet (includes @font-face for local fonts)
        wp_enqueue_style(
            'canopy-main',
            $uri . '/assets/styles/main.css',
            [],
            filemtime($dir . '/assets/styles/main.css')
        );

        // Theme JS is an ES module so it can import Motion (motion.dev) directly.
        // Registered as a script module (WordPress 6.5+) — loaded with type="module".
        wp_enqueue_script_module(
            'canopy-site',
            $uri . '/assets/scripts/site.js',
            [],
            filemtime($dir . '/assets/scripts/site.js')
        );
    }

    /**
     * Dequeue WordPress block editor styles on the frontend.
     * This theme does not render Gutenberg blocks — these stylesheets are unused.
     */
    public function dequeueBlockStyles(): void
    {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('global-styles');
        wp_dequeue_style('core-block-supports');
    }

    /**
     * Customize register — add theme customizer options
     */
    public function customizeRegister(\WP_Customize_Manager $customize): void
    {
        // Add customizer sections, settings, and controls here
    }

    /**
     * Add noindex tags for non-public pages
     */
    public function addNoindexTags(): void
    {
        if (is_search() || is_404() || (is_paged() && !is_singular())) {
            echo '<meta name="robots" content="noindex,follow">' . "\n";
        }
    }

    /**
     * Add data to Twig context
     *
     * @param array<string, mixed> $context Current Twig context
     * @return array<string, mixed> Modified context
     */
    public function addToContext(array $context): array
    {
        $context['menu'] = Timber::get_menu('primary_navigation');
        $context['footer_menu'] = Timber::get_menu('footer_navigation');

        // Values WordPress exposes through functions, made available to Twig.
        $context['body_class'] = implode(' ', get_body_class());
        $context['archive_title'] = get_the_archive_title();
        $context['search_query'] = get_search_query();

        return $context;
    }

    /**
     * Add custom filters to Twig
     *
     * @param array<string, mixed> $filters Current filters
     * @return array<string, mixed> Modified filters
     */
    public function addFiltersToTwig(array $filters): array
    {
        $filters['excerpt'] = [
            'callable' => function ($text, $length = 55) {
                return wp_trim_words($text, $length);
            },
        ];

        return $filters;
    }

    /**
     * Add custom functions to Twig
     *
     * Exposes WordPress' gettext functions to Twig so template strings are
     * translatable with the 'canopy' text domain, e.g.
     * {{ __('Read more', 'canopy') }} or {{ esc_html__('Search', 'canopy') }}.
     * Generate a catalog from your templates with `wp i18n make-pot`.
     *
     * @param array<string, mixed> $functions Current functions
     * @return array<string, mixed> Modified functions
     */
    public function addFunctionsToTwig(array $functions): array
    {
        $functions['get_theme_mod'] = [
            'callable' => 'get_theme_mod',
        ];

        foreach (['__', '_e', '_x', '_n', 'esc_html__', 'esc_html_e', 'esc_attr__', 'esc_attr_e'] as $fn) {
            $functions[$fn] = ['callable' => $fn];
        }

        return $functions;
    }

    /**
     * Update Twig environment options
     *
     * @param array<string, mixed> $options Current options
     * @return array<string, mixed> Modified options
     */
    public function updateTwigEnvironmentOptions(array $options): array
    {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $options['auto_reload'] = true;
        } else {
            $cacheDir = get_template_directory() . '/cache/twig';
            wp_mkdir_p($cacheDir);
            $options['cache'] = $cacheDir;
        }

        return $options;
    }

    /**
     * Update robots.txt
     *
     * @param string $output Current robots.txt output
     * @param string $host   Current host
     * @return string Modified robots.txt
     */
    public function updateRobotsTxt(string $output, string $host): string
    {
        $home = parse_url(home_url());
        $path = !empty($home['path']) ? $home['path'] : '';

        if ('localhost' !== $host && strpos($host, '.test') === false && strpos($host, '.local') === false) {
            $output .= "\n\nSitemap: {$home['scheme']}://{$host}{$path}/sitemap.xml\n";
        }

        return $output;
    }

    /**
     * Mail from
     */
    public function mailFrom(): string
    {
        $host = parse_url(home_url(), PHP_URL_HOST);

        // getenv() reads the live process environment, so SMTP credentials work
        // whether they come from .env or are injected by the host (panel, Docker, systemd).
        return getenv('SMTP_FROM') ?: "noreply@{$host}";
    }

    /**
     * Mail from name
     */
    public function mailFromName(): string
    {
        return getenv('SMTP_FROM_NAME') ?: get_bloginfo('name');
    }

    /**
     * Configure SMTP via PHPMailer
     *
     * @param \PHPMailer\PHPMailer\PHPMailer $phpmailer PHPMailer instance
     */
    public function configureSMTP(\PHPMailer\PHPMailer\PHPMailer $phpmailer): void
    {
        // getenv() so credentials injected outside .env (host panel, Docker, systemd) are honored.
        if (!getenv('SMTP_HOST')) {
            return;
        }

        $user = getenv('SMTP_USER');
        $pass = getenv('SMTP_PASS');

        $phpmailer->isSMTP();
        $phpmailer->Host       = (string) getenv('SMTP_HOST');
        $phpmailer->Port       = (int) (getenv('SMTP_PORT') ?: 587);
        $phpmailer->SMTPSecure = getenv('SMTP_ENCRYPTION') ?: 'tls';

        // Authenticate only when both credentials are present; some relays
        // authenticate by IP and need no username/password.
        if ($user && $pass) {
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = (string) $user;
            $phpmailer->Password = (string) $pass;
        }
    }
}
