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

namespace App;

use Timber\Site as TimberSite;
use Timber\Timber;
use Twig\Environment;
use Twig\TwigFilter;
use Twig\TwigFunction;

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
     */
    public function enqueueAssets(): void
    {
        $dir = get_template_directory();
        $uri = get_template_directory_uri();

        // In production (WP_DEBUG=false), load minified assets
        $isDev    = defined('WP_DEBUG') && WP_DEBUG;
        $cssFile  = $isDev ? 'assets/styles/main.css' : 'assets/styles/main.min.css';
        $jsFile   = $isDev ? 'assets/scripts/site.js' : 'assets/scripts/site.min.js';

        // Main stylesheet (includes @font-face for local fonts)
        wp_enqueue_style(
            'canopy-main',
            $uri . '/' . $cssFile,
            [],
            filemtime($dir . '/' . $cssFile)
        );

        // GSAP 3 — CDN (jsDelivr), deferred
        $gsapVersion = '3.12.5';
        $gsapCdn     = "https://cdn.jsdelivr.net/npm/gsap@{$gsapVersion}/dist";

        wp_enqueue_script(
            'gsap',
            "{$gsapCdn}/gsap.min.js",
            [],
            $gsapVersion,
            ['strategy' => 'defer', 'in_footer' => true]
        );

        // GSAP plugins — each depends on 'gsap' so load order is guaranteed
        $gsapPlugins = [
            'gsap-scrolltrigger'   => 'ScrollTrigger.min.js',
            'gsap-scrollto'        => 'ScrollToPlugin.min.js',
            'gsap-observer'        => 'Observer.min.js',
            'gsap-draggable'       => 'Draggable.min.js',
            'gsap-flip'            => 'Flip.min.js',
            'gsap-motionpath'      => 'MotionPathPlugin.min.js',
            'gsap-easepack'        => 'EasePack.min.js',
            'gsap-customease'      => 'CustomEase.min.js',
            'gsap-textplugin'      => 'TextPlugin.min.js',
        ];

        foreach ($gsapPlugins as $handle => $file) {
            wp_enqueue_script($handle, "{$gsapCdn}/{$file}", ['gsap'], $gsapVersion, ['strategy' => 'defer', 'in_footer' => true]);
        }

        // Theme JS — depends on all GSAP plugins
        $siteDeps = array_merge(['gsap'], array_keys($gsapPlugins));

        wp_enqueue_script(
            'canopy-site',
            $uri . '/' . $jsFile,
            $siteDeps,
            filemtime($dir . '/' . $jsFile),
            ['strategy' => 'defer', 'in_footer' => true]
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
    public function customizeRegister($customize): void
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
     * @param array $context Current Twig context
     * @return array Modified context
     */
    public function addToContext($context)
    {
        $context['menu'] = Timber::get_menu('primary_navigation');
        $context['footer_menu'] = Timber::get_menu('footer_navigation');

        return $context;
    }

    /**
     * Add custom filters to Twig
     *
     * @param array $filters Current filters
     * @return array Modified filters
     */
    public function addFiltersToTwig($filters)
    {
        $filters['excerpt'] = new TwigFilter('excerpt', function ($text, $length = 55) {
            return wp_trim_words($text, $length);
        });

        return $filters;
    }

    /**
     * Add custom functions to Twig
     *
     * @param array $functions Current functions
     * @return array Modified functions
     */
    public function addFunctionsToTwig($functions)
    {
        $functions['get_theme_mod'] = new TwigFunction('get_theme_mod', 'get_theme_mod');

        return $functions;
    }

    /**
     * Update Twig environment options
     *
     * @param array $options Current options
     * @return array Modified options
     */
    public function updateTwigEnvironmentOptions($options)
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
     * @param string $host Current host
     * @return string Modified robots.txt
     */
    public function updateRobotsTxt($output, $host)
    {
        $home = parse_url(home_url());
        $path = !empty($home['path']) ? $home['path'] : '';

        if ('localhost' !== $host && !strpos($host, '.test') && !strpos($host, '.local')) {
            $output .= "\n\nSitemap: {$home['scheme']}://{$host}{$path}/sitemap.xml\n";
        }

        return $output;
    }

    /**
     * Mail from
     *
     * @return string Email address
     */
    public function mailFrom()
    {
        $host = parse_url(home_url(), PHP_URL_HOST);

        return getenv('SMTP_FROM') ?: "noreply@{$host}";
    }

    /**
     * Mail from name
     *
     * @return string Display name
     */
    public function mailFromName()
    {
        return getenv('SMTP_FROM_NAME') ?: get_bloginfo('name');
    }

    /**
     * Configure SMTP via PHPMailer
     *
     * @param \PHPMailer\PHPMailer\PHPMailer $phpmailer PHPMailer instance
     */
    public function configureSMTP($phpmailer): void
    {
        if (!getenv('SMTP_HOST')) {
            return;
        }

        $phpmailer->isSMTP();
        $phpmailer->Host       = getenv('SMTP_HOST');
        $phpmailer->Port       = getenv('SMTP_PORT') ?: 587;
        $phpmailer->SMTPAuth   = true;
        $phpmailer->Username   = getenv('SMTP_USER');
        $phpmailer->Password   = getenv('SMTP_PASS');
        $phpmailer->SMTPSecure = 'tls';
    }
}
