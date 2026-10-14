<?php

/**
 * Theme hook class. Twig `{{ site }}` is a different Timber\Site from context.
 *
 * @package Canopy
 * @author  Agência Upgrade <contato@agenciaupgrade.com.br>
 * @author  Gustavo Muniz (creator of Canopy, founder of Agência Upgrade) https://www.linkedin.com/in/gustavomunix/
 * @link    https://agenciaupgrade.com.br
 * @link    https://www.linkedin.com/company/agenciaupgrade
 */

namespace Canopy;

use Timber\Site as TimberSite;
use Timber\Timber;

class Site extends TimberSite
{
    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'themeSupports']);
        add_action('init', [$this, 'registerPostTypes']);
        add_action('init', [$this, 'registerTaxonomies']);
        add_action('after_setup_theme', [$this, 'cleanFrontend']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueAssets']);
        add_action('wp_enqueue_scripts', [$this, 'dequeueBlockStyles'], 100);
        add_action('customize_register', [$this, 'customizeRegister']);
        add_action('wp_head', [$this, 'addMetaDescription'], 1);
        add_action('wp_head', [$this, 'addNoindexTags'], 2);
        add_action('wp_head', [$this, 'printSchema'], 5);

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

    public function registerPostTypes(): void
    {
    }

    public function registerTaxonomies(): void
    {
    }

    /** Source CSS/JS — filemtime() cache-busts; there is no build. */
    public function enqueueAssets(): void
    {
        $dir = get_template_directory();
        $uri = get_template_directory_uri();

        $cssPath = $dir . '/assets/styles/main.css';
        $jsPath = $dir . '/assets/scripts/site.js';

        wp_enqueue_style(
            'canopy-main',
            $uri . '/assets/styles/main.css',
            [],
            file_exists($cssPath) ? (string) filemtime($cssPath) : null
        );

        wp_enqueue_script_module(
            'canopy-site',
            $uri . '/assets/scripts/site.js',
            [],
            file_exists($jsPath) ? (string) filemtime($jsPath) : null
        );

        if (is_singular()) {
            wp_enqueue_style('wp-block-library');

            if (function_exists('wp_enqueue_stored_styles')) {
                wp_enqueue_stored_styles();
            }
        }
    }

    /** Gutenberg, emoji, generator, and discovery links this theme does not use. */
    public function cleanFrontend(): void
    {
        remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
        remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
        remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');
        remove_action('wp_enqueue_scripts', 'wp_enqueue_stored_styles');
        remove_action('wp_footer', 'wp_enqueue_stored_styles', 1);
        remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
        remove_action('wp_enqueue_scripts', 'wp_enqueue_img_auto_sizes_contain_css_fix');

        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        add_filter('tiny_mce_plugins', [$this, 'disableEmojiTinyMce']);
        add_filter('wp_img_tag_add_auto_sizes', '__return_false');

        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    }

    /**
     * @param array<int, string> $plugins
     * @return array<int, string>
     */
    public function disableEmojiTinyMce(array $plugins): array
    {
        return array_values(array_diff($plugins, ['wpemoji']));
    }

    public function dequeueBlockStyles(): void
    {
        if (!is_singular()) {
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');
            wp_dequeue_style('core-block-supports');
        }

        wp_dequeue_style('global-styles');
        wp_dequeue_style('classic-theme-styles');
        wp_dequeue_style('wp-emoji-styles');
        wp_dequeue_style('wp-img-auto-sizes-contain');
    }

    public function customizeRegister(\WP_Customize_Manager $customize): void
    {
    }

    public function addNoindexTags(): void
    {
        if (is_search() || is_404() || (is_paged() && !is_singular())) {
            echo '<meta name="robots" content="noindex,follow">' . "\n";
        }
    }

    public function addMetaDescription(): void
    {
        if (is_search() || is_404()) {
            return;
        }

        $description = '';

        if (is_singular()) {
            $description = (string) get_the_excerpt();
        }

        if ($description === '') {
            $description = (string) get_bloginfo('description', 'display');
        }

        $description = wp_strip_all_tags($description);

        if ($description === '') {
            return;
        }

        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }

    public function printSchema(): void
    {
        $home = home_url('/');
        $name = (string) get_bloginfo('name');
        $orgId = $home . '#organization';
        $graph = [
            [
                '@type' => 'Organization',
                '@id'   => $orgId,
                'name'  => $name,
                'url'   => $home,
                'logo'  => get_template_directory_uri() . '/assets/images/logo.svg',
            ],
            [
                '@type'           => 'WebSite',
                '@id'             => $home . '#website',
                'url'             => $home,
                'name'            => $name,
                'description'     => (string) get_bloginfo('description', 'display'),
                'publisher'       => ['@id' => $orgId],
                'potentialAction' => [
                    '@type'       => 'SearchAction',
                    'target'      => [
                        '@type'       => 'EntryPoint',
                        'urlTemplate' => $home . '?s={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ];

        $crumbs = $this->breadcrumbs();

        if ($crumbs !== []) {
            $items = [];

            foreach ($crumbs as $index => $crumb) {
                $item = [
                    '@type'    => 'ListItem',
                    'position' => $index + 1,
                    'name'     => $crumb['title'],
                ];

                if (is_string($crumb['url']) && $crumb['url'] !== '') {
                    $item['item'] = $crumb['url'];
                }

                $items[] = $item;
            }

            $graph[] = [
                '@type'           => 'BreadcrumbList',
                'itemListElement' => $items,
            ];
        }

        if (is_singular('post')) {
            $post = get_queried_object();

            if ($post instanceof \WP_Post) {
                $article = [
                    '@type'            => 'BlogPosting',
                    'headline'         => get_the_title($post),
                    'datePublished'    => get_the_date('c', $post),
                    'dateModified'     => get_the_modified_date('c', $post),
                    'mainEntityOfPage' => get_permalink($post),
                    'author'           => [
                        '@type' => 'Person',
                        'name'  => get_the_author_meta('display_name', (int) $post->post_author),
                    ],
                    'publisher'        => ['@id' => $orgId],
                ];

                $image = get_the_post_thumbnail_url($post, 'full');

                if (is_string($image) && $image !== '') {
                    $article['image'] = $image;
                }

                $excerpt = wp_strip_all_tags((string) get_the_excerpt($post));

                if ($excerpt !== '') {
                    $article['description'] = $excerpt;
                }

                $graph[] = $article;
            }
        }

        echo '<script type="application/ld+json">' . wp_json_encode(
            [
                '@context' => 'https://schema.org',
                '@graph'   => $graph,
            ],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        ) . '</script>' . "\n";
    }

    /**
     * @return list<array{title: string, url: string|null}>
     */
    private function breadcrumbs(): array
    {
        $home = [
            'title' => __('Home', 'canopy'),
            'url'   => home_url('/'),
        ];

        if (is_front_page()) {
            return [];
        }

        $crumbs = [$home];

        if (is_search()) {
            $crumbs[] = [
                'title' => __('Search', 'canopy'),
                'url'   => null,
            ];

            return $crumbs;
        }

        if (is_404()) {
            $crumbs[] = [
                'title' => __('Page not found', 'canopy'),
                'url'   => null,
            ];

            return $crumbs;
        }

        if (is_singular()) {
            $post = get_queried_object();

            if ($post instanceof \WP_Post && $post->post_type === 'post') {
                $postsPageId = (int) get_option('page_for_posts');

                if ($postsPageId > 0) {
                    $crumbs[] = [
                        'title' => (string) get_the_title($postsPageId),
                        'url'   => (string) get_permalink($postsPageId),
                    ];
                } else {
                    $archive = get_post_type_archive_link('post');
                    $crumbs[] = [
                        'title' => __('Blog', 'canopy'),
                        'url'   => is_string($archive) ? $archive : home_url('/'),
                    ];
                }
            }

            $crumbs[] = [
                'title' => (string) get_the_title(),
                'url'   => null,
            ];

            return $crumbs;
        }

        if (is_home()) {
            $postsPageId = (int) get_option('page_for_posts');
            $crumbs[] = [
                'title' => $postsPageId > 0 ? (string) get_the_title($postsPageId) : __('Blog', 'canopy'),
                'url'   => null,
            ];

            return $crumbs;
        }

        if (is_archive()) {
            $crumbs[] = [
                'title' => wp_strip_all_tags(get_the_archive_title()),
                'url'   => null,
            ];
        }

        return $crumbs;
    }

    /**
     * @param array<string, mixed> $context
     * @return array<string, mixed>
     */
    public function addToContext(array $context): array
    {
        $context['menu'] = Timber::get_menu('primary_navigation');
        $context['footer_menu'] = Timber::get_menu('footer_navigation');

        $context['body_class'] = implode(' ', get_body_class());
        $context['archive_title'] = get_the_archive_title();
        $context['search_query'] = get_search_query(false);
        $context['crumbs'] = $this->breadcrumbs();

        return $context;
    }

    /**
     * @param array<string, mixed> $filters
     * @return array<string, mixed>
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
     * Registers gettext (`__`, `_n`, …) and `get_theme_mod` on Twig.
     * Text domain is `canopy`. Catalog: `wp i18n make-pot`.
     *
     * @param array<string, mixed> $functions
     * @return array<string, mixed>
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
     * WP_DEBUG: auto_reload. Otherwise compile cache under cache/twig.
     *
     * @param array<string, mixed> $options
     * @return array<string, mixed>
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
     * Appends Sitemap on live hosts only (skips localhost, .test, .local).
     *
     * @param string $output
     * @param mixed $public
     */
    public function updateRobotsTxt(string $output, mixed $public): string
    {
        $home = parse_url(home_url());
        $host = is_array($home) && isset($home['host']) ? (string) $home['host'] : '';
        $path = !empty($home['path']) ? $home['path'] : '';

        if ('localhost' !== $host && strpos($host, '.test') === false && strpos($host, '.local') === false) {
            $output .= "\n\nSitemap: {$home['scheme']}://{$host}{$path}/sitemap.xml\n";
        }

        return $output;
    }

    public function mailFrom(): string
    {
        $host = parse_url(home_url(), PHP_URL_HOST);

        return getenv('SMTP_FROM') ?: "noreply@{$host}";
    }

    public function mailFromName(): string
    {
        return getenv('SMTP_FROM_NAME') ?: get_bloginfo('name');
    }

    /**
     * getenv() — works with .env and host-injected secrets (panel, Docker, systemd).
     *
     * @param \PHPMailer\PHPMailer\PHPMailer $phpmailer
     */
    public function configureSMTP(\PHPMailer\PHPMailer\PHPMailer $phpmailer): void
    {
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
