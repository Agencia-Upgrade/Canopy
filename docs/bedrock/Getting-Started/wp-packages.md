Package naming
Type	Convention	Example
Plugin	wp-plugin/plugin-name	wp-plugin/woocommerce
Theme	wp-theme/theme-name	wp-theme/twentytwentyfive
Usage

Example composer.json for standalone WordPress projects:
composer.json

{
  "repositories": [
    {
      "name": "wp-packages",
      "type": "composer",
      "url": "https://repo.wp-packages.org"
    }
  ],
  "require": {
    "composer/installers": "^2.2",
    "wp-plugin/woocommerce": "^10.0",
    "wp-theme/twentytwentyfive": "^1.0"
  },
  "extra": {
    "installer-paths": {
      "wp-content/plugins/{$name}/": ["type:wordpress-plugin"],
      "wp-content/mu-plugins/{$name}/": ["type:wordpress-muplugin"],
      "wp-content/themes/{$name}/": ["type:wordpress-theme"]
    }
  }
}

Bedrock already comes configured for both WP Packages and the roots/wordpress package.
Roots WordPress packages

Roots also provides WordPress core as Composer packages:
roots/wordpress	Meta-package for installing WordPress core via Composer
roots/wordpress-full	Full WordPress build (core + default themes + plugins + betas)
roots/wordpress-no-content	Minimal WordPress build (core only)
roots/bedrock	WordPress boilerplate with Composer, better config, and improved structure

A typical Bedrock project uses roots/wordpress for WordPress core and WP Packages for plugins and themes.
Migrating from WPackagist

Switching from WPackagist takes one command. Use the migration script to automatically update your composer.json:
curl -sO https://raw.githubusercontent.com/roots/wp-packages/main/scripts/migrate-from-wpackagist.sh && bash migrate-from-wpackagist.sh
Manually migrate
1

Remove wpackagist packages:
composer remove wpackagist-theme/twentytwentyfive
2

Remove the wpackagist repository and add WP Packages:
composer config --unset repositories.wpackagist && composer config repositories.wp-packages composer https://repo.wp-packages.org
3

Require packages with the new naming:
composer require wp-theme/twentytwentyfive
