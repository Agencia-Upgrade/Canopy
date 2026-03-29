# WordPress CLI[¶](#wordpress-cli "Permanent link"){.headerlink}

WP-CLI is the command-line interface for WordPress. It allows you to
perform many actions that would normally require visiting your WordPress
Dashboard in a browser. The following commands are provided by
LiteSpeed, so that you may manage your cache with the CLI.

::: {.admonition .tip}
Tip

All LiteSpeed CLI commands (except `litespeed-database` commands) accept
[standard WP
parameters](https://make.wordpress.org/cli/handbook/references/config/),
in addition to any specific parameters that we mention in the command
descriptions.
The syntax for LiteSpeed CLI commands looks like this:

::: highlight
    wp <command> <subcommand> [parameters]
The following commands are available:

  Command                                     Scope
  ------------------------------------------- ----------------------------
  [litespeed-option](#litespeed-option)       Options
  [litespeed-purge](#litespeed-purge)         Purge
  [litespeed-presets](#litespeed-presets)     Presets
  [litespeed-image](#litespeed-image)         Image Optimization
  [litespeed-online](#litespeed-online)       QUIC.cloud Online Services
  [litespeed-debug](#litespeed-debug)         Debug
  [litespeed-crawler](#litespeed-crawler)     Crawler
  [litespeed-database](#litespeed-database)   Database Optimization

## litespeed-option[¶](#litespeed-option "Permanent link"){.headerlink}

The `litespeed-option` command allows you to manipulate your LSCWP
settings.

Syntax:

::: highlight
    wp litespeed-option <subcommand> [parameters]
  Subcommand        Description
  ----------------- -------------------------
  `set`             Set a particular option
  `get`             Get a particular option
  `all`             Get all options
  `export`          Export options
  `import`          Import options
  `import_remote`   Import remote options
  `reset`           Reset options

### litespeed-option set[¶](#litespeed-option-set "Permanent link"){.headerlink}

::: highlight
    wp litespeed-option set <key> <value>
Set a particular option.

  Parameter   Description
  ----------- -----------------------------------
  `<key>`     The option key to update
  `<value>`   The value to assign to the option

:::: {.admonition .example}
Example

Set **Cache Logged-in Users** to `OFF`:

::: highlight
    wp litespeed-option set cache-priv false
:::: {.admonition .example}
Example

Set the first URL in **CDN Mapping** to `https://cdn.example.com`:

::: highlight
    wp litespeed-option set 'cdn-mapping[url][0]' https://cdn.example.com
:::::: {.admonition .example}
Example

What if `<value>` contains multiple lines? The CLI doesn\'t accept
multi-line input, so you would need to use a different syntax, like
this:

::: highlight
    wp litespeed-option set <key> $'<value1>\n<value2>'
To set **LQIP Excludes** to a list of three images like this:

::: highlight
    img1.jpg
    img2.jpg
    img3.jpg
Use the following command:

::: highlight
    wp litespeed-option set media-lqip_exc $'img1.jpg\nimg2.jpg\nimg3.jpg'`
### litespeed-option get[¶](#litespeed-option-get "Permanent link"){.headerlink}

::: highlight
    wp litespeed-option get <key>
Get a particular option.

  Parameter   Description
  ----------- ---------------------------------------
  `<key>`     The option key to return the value of

:::: {.admonition .example}
Example

Get the value of **Cache Logged-in Users**:

::: highlight
    wp litespeed-option get cache-priv
:::: {.admonition .example}
Example

Get the value of the first URL in **CDN Mapping**:

::: highlight
    wp litespeed-option get 'cdn-mapping[url][0]'
### litespeed-option all[¶](#litespeed-option-all "Permanent link"){.headerlink}

::: highlight
    wp litespeed-option all [--format=<format>]
Displays a list of all options and their values.

  Parameter             Description
  --------------------- --------------------------------------------------------------------------------------------
  `--format=<format>`   Format for the output. `<format>` may be `table`, `json`, `csv`, `yaml`, `ids`, or `count`

:::: {.admonition .example}
Example

Display all LSCWP options and values in CSV format:

::: highlight
    wp litespeed-option all --format=csv
### litespeed-option export[¶](#litespeed-option-export "Permanent link"){.headerlink}

::: highlight
    wp litespeed-option export `[--filename=<path>]`
All options are exported to
`CURRENTDIR/lscache_wp_options_DATE-TIME.txt` unless a new path is
specified.

  Parameter               Description
  ----------------------- -----------------------------------------------
  `[--filename=<path>]`   Different path to save export file. Optional.

:::: {.admonition .example}
Example

Export all options to `/tmp/export`:

::: highlight
    wp litespeed-option export --filename=/tmp/export
### litespeed-option import[¶](#litespeed-option-import "Permanent link"){.headerlink}

::: highlight
    wp litespeed-option import <file>
Import options from specified file. Options must be one per line and
formatted as `option_key=option_value`. A semicolon (`;`) at the
beginning of a line indicates a comment. Comment lines are ignored.

  Parameter   Description
  ----------- ------------------------
  `<file>`    Options file to import

:::: {.admonition .example}
Example

Import from `options.txt` file:

::: highlight
    wp litespeed-option import options.txt
### litespeed-option import_remote[¶](#litespeed-option-import_remote "Permanent link"){.headerlink}

::: highlight
    wp litespeed-option import_remote <URL>
Import options from a remote URL. Options must be one per line and
formatted as `option_key=option_value`. A semicolon (`;`) at the
beginning of a line indicates a comment. Comment lines are ignored.

  Parameter   Description
  ----------- -------------------------------
  `<URL>`     URL of options file to import

:::: {.admonition .example}
Example

Import from `https://example.com/options.txt`

::: highlight
    wp litespeed-option import_remote https://example.com/options.txt
### litespeed-option reset[¶](#litespeed-option-reset "Permanent link"){.headerlink}

::: highlight
    wp litespeed-option reset
Resets all options to factory default. No parameters.

## litespeed-purge[¶](#litespeed-purge "Permanent link"){.headerlink}

The `litespeed-purge` command allows you to purge all of your site's
cache content, or subsets of it.

Syntax:

::: highlight
    wp litespeed-purge <subcommand> [parameters]
  Subcommand       Description
  ---------------- -----------------------------------------------------------
  `network_list`   List all site domains and IDs on the network
  `all`            Purge all cache entries
  `url`            Purge all cache tags related to a URL
  `blog`           Purge all cache tags for a particular site in the network
  `category`       Purge all cache entries for a list of categories
  `tag`            Purge all cache entries for a list of tags
  `post_id`        Purge all cache entries for a list of post IDs

### litespeed-purge network_list[¶](#litespeed-purge-network_list "Permanent link"){.headerlink}

::: highlight
    wp litespeed-purge network_list
List all site domains and IDs on the network. No parameters.

### litespeed-purge all[¶](#litespeed-purge-all "Permanent link"){.headerlink}

::: highlight
    wp litespeed-purge all
Purge all cache entries for every site in the network. Also works for
non-networked sites, and just purges that single site. No parameters

### litespeed-purge url[¶](#litespeed-purge-url "Permanent link"){.headerlink}

::: highlight
    wp litespeed-purge url <url>
Purge all cache tags related to a URL.

  Parameter   Description
  ----------- ------------------
  `<url>`     The URL to purge

:::: {.admonition .example}
Example

Purge all cache tags related to `https://example.com`:

::: highlight
    wp litespeed-purge url https://example.com
### litespeed-purge blog[¶](#litespeed-purge-blog "Permanent link"){.headerlink}

::: highlight
    wp litespeed-purge blog <blogid>
Purge all cache entries for a particular site in the network.

  Parameter    Description
  ------------ -------------------------
  `<blogid>`   ID of the site to purge

:::: {.admonition .example}
Example

Purge all cache for site with the ID `2`:

::: highlight
    wp litespeed-purge blog 2
### litespeed-purge category[¶](#litespeed-purge-category "Permanent link"){.headerlink}

::: highlight
    wp litespeed-purge category <ids>
Purge all cache entries for a list of categories.

  Parameter   Description
  ----------- ------------------------------------------------------
  `<ids>`     IDs of the categories to purge, separated by a space

:::: {.admonition .example}
Example

Purge the categories with the IDs `1`, `3`, and `5`:

::: highlight
    wp litespeed-purge category 1 3 5
### litespeed-purge tag[¶](#litespeed-purge-tag "Permanent link"){.headerlink}

::: highlight
    wp litespeed-purge tag <ids>
Purge all cache entries for a list of tags.

  Parameter   Description
  ----------- ------------------------------------------------
  `<ids>`     IDs of the tags to purge, separated by a space

:::: {.admonition .example}
Example

Purge the tags with the IDs `1`, `3`, and `5`:

::: highlight
    wp litespeed-purge tag 1 3 5
### litespeed-purge post_id[¶](#litespeed-purge-post_id "Permanent link"){.headerlink}

::: highlight
    wp litespeed-purge post_id <ids>
Purge all cache entries for a list of post IDs.

  Parameter   Description
  ----------- -------------------------------------------------
  `<ids>`     IDs of the posts to purge, separated by a space

:::: {.admonition .example}
Example

Purge the posts with the IDs `1`, `3`, and `5`:

::: highlight
    wp litespeed-purge post_id 1 3 5
## litespeed-presets[¶](#litespeed-presets "Permanent link"){.headerlink}

Apply presets and restore backups using `litespeed-presets`.

Syntax:

::: highlight
    wp litespeed-presets <subcommand> [parameters]
  Subcommand      Description
  --------------- -----------------------------------------
  `apply`         Apply a particular preset configuration
  `get_backups`   Get a list of available backups
  `restore`       Restore a previous configuration

### litespeed-presets apply[¶](#litespeed-presets-apply "Permanent link"){.headerlink}

::: highlight
    wp litespeed-presets apply <preset>
Apply a particular preset configuration.

  Parameter    Description
  ------------ -----------------------------
  `<preset>`   Name of the preset to apply

:::: {.admonition .example}
Example

Apply the `basic` preset:

::: highlight
    wp litespeed-presets apply basic
### litespeed-presets get_backups[¶](#litespeed-presets-get_backups "Permanent link"){.headerlink}

::: highlight
    wp litespeed-presets get_backups
When you apply a preset, a backup is saved of your existing
configuration before the preset is applied. This option provides a list
of available backups. No parameters.

### litespeed-presets restore[¶](#litespeed-presets-restore "Permanent link"){.headerlink}

::: highlight
    wp litespeed-presets restore <backup_number>
Restore a previous configuration using one of the backups listed via the
`get_backups` option.

  Parameter           Description
  ------------------- ---------------------------------
  `<backup_number>`   Number of the backup to restore

:::: {.admonition .example}
Example

Restore backup number `1667485245`:

::: highlight
    wp litespeed-presets restore 1667485245
## litespeed-image[¶](#litespeed-image "Permanent link"){.headerlink}

The `litespeed-image` command allows you to control certain aspects of
the QUIC.cloud image optimization service.

::: highlight
    wp litespeed-image <subcommand> [parameters]
  Subcommand        Description
  ----------------- ----------------------------------------------
  `push`            Push images
  `pull`            Pull images
  `status` or `s`   Get status
  `clean`           Clean up
  `rm_bkup`         Remove original backups
  `batch_switch`    Switch between optimized or original backups

### litespeed-image push[¶](#litespeed-image-push "Permanent link"){.headerlink}

::: highlight
    wp litespeed-image push
Send an image optimization request to the QUIC.cloud server. No
parameters.

### litespeed-image pull[¶](#litespeed-image-pull "Permanent link"){.headerlink}

::: highlight
    wp litespeed-image pull
Pull optimized images from the QUIC.cloud server. No parameters.

### litespeed-image status[¶](#litespeed-image-status "Permanent link"){.headerlink}

::: highlight
    wp litespeed-image status
Show optimization status based on local data. No parameters.

### litespeed-image clean[¶](#litespeed-image-clean "Permanent link"){.headerlink}

::: highlight
    wp litespeed-image clean
Clean up unfinished image data on the QUIC.cloud server. No parameters.

### litespeed-image rm_bkup[¶](#litespeed-image-rm_bkup "Permanent link"){.headerlink}

::: highlight
    wp litespeed-image rm_bkup
Remove original image backups. No parameters.

### litespeed-image batch_switch[¶](#litespeed-image-batch_switch "Permanent link"){.headerlink}

::: highlight
    wp litespeed-image batch_switch <type>
Switch between serving optimized images and the original image backups.

  Parameter   Description
  ----------- -------------------------------------------------------------------------------------
  `<type>`    Type of images to serve. May be `optm` for optimized or `orig` for original images.

:::: {.admonition .example}
Example

Switch to serving optimized images:

::: highlight
    wp litespeed-image batch_switch optm
## litespeed-online[¶](#litespeed-online "Permanent link"){.headerlink}

Use the `litespeed-online` command to control certain aspects of
QUIC.cloud CDN and Online Services, and lookup information.

Syntax:

::: highlight
    wp litespeed-online <subcommand> [parameters]
  Subcommand     Description
  -------------- ----------------------------------------
  `init`         Generate domain API key
  `sync`         Synchronize usage information
  `services`     List all services
  `nodes`        List active nodes
  `ping`         Find closest active node for a service
  `cdn_status`   Return status of CDN for your account
  `cdn_init`     Activate CDN for a particular domain
  `link`         

### litespeed-online init[¶](#litespeed-online-init "Permanent link"){.headerlink}

::: highlight
    wp litespeed-online init
Generate an anonymous domain API key for secure communication with
QUIC.cloud. No parameters.

### litespeed-online sync[¶](#litespeed-online-sync "Permanent link"){.headerlink}

::: highlight
    wp litespeed-online sync [--format=<format>]
Synchronize QUIC.cloud service usage information. Returns the usage and
status for all QUIC.cloud services and other useful information.

  Parameter             Description
  --------------------- --------------------------------------------------------------------------------------------
  `--format=<format>`   Format for the output. `<format>` may be `table`, `json`, `csv`, `yaml`, `ids`, or `count`

:::: {.admonition .example}
Example

Display the usage information formatted as a table:

::: highlight
    wp litespeed-online sync --format=table
### litespeed-online services[¶](#litespeed-online-services "Permanent link"){.headerlink}

::: highlight
    wp litespeed-online services [--format=<format>]
List all QUIC.cloud services.

  Parameter             Description
  --------------------- --------------------------------------------------------------------------------------------
  `--format=<format>`   Format for the output. `<format>` may be `table`, `json`, `csv`, `yaml`, `ids`, or `count`

:::: {.admonition .example}
Example

Get a list of all QUIC.cloud services in JSON format:

::: highlight
    wp litespeed-online services --format=json
### litespeed-online nodes[¶](#litespeed-online-nodes "Permanent link"){.headerlink}

::: highlight
    wp litespeed-online nodes [--format=<format>]
List all currently active QUIC.cloud nodes.

  Parameter             Description
  --------------------- --------------------------------------------------------------------------------------------
  `--format=<format>`   Format for the output. `<format>` may be `table`, `json`, `csv`, `yaml`, `ids`, or `count`

:::: {.admonition .example}
Example

Get a list of all currently active QUIC.cloud nodes in CSV format:

::: highlight
    wp litespeed-online nodes --format=csv
### litespeed-online ping[¶](#litespeed-online-ping "Permanent link"){.headerlink}

::: highlight
    wp litespeed-online ping <service> [--force]
Detect the closest active QUIC.cloud node for a particular service.

  Parameter     Description
  ------------- ------------------------------------------------------------------------------------------------
  `<service>`   Service to find a node for. Possible values are `img_optm`, `ccss`, `ucss`, `lqip`, and `vpi`.
  `--force`     Don\'t read the node from cache, redetect it

:::: {.admonition .example}
Example

Find the closest active image optimization node, and it's ok to read it
from cache:

::: highlight
    wp litespeed-online ping img_optm
### litespeed-online cdn_status[¶](#litespeed-online-cdn_status "Permanent link"){.headerlink}

::: highlight
    wp litespeed-online cdn_status
Return information pertaining to your account status and whether the CDN
is enabled or disabled for your account. No parameters.

### litespeed-online cdn_init[¶](#litespeed-online-cdn_init "Permanent link"){.headerlink}

::: highlight
    wp litespeed-online cdn_init --method=cname|ns|cfi [--cf-token=] [--ssl-cert=] [--ssl-key=]
Activate QUIC.cloud CDN for a specified domain.

  Parameter      Description
  -------------- ---------------------------------------------------------------------------------------------------------------------
  `--ssl-cert`   SSL Certificate, optional
  `--ssl-key`    SSL Key, optional
  `--method`     Possible values for the setup method: `cname` (CNAME record), `ns` (QUIC.cloud DNS), `cfi` (CloudFlare Integration)
  `--cf-token`   Cloudflare token, optional, for use with `--method: cfi`

:::: {.admonition .example}
Example

Set up QUIC.cloud CDN using the Cloudflare Integration method and
Cloudflare token `123456789`:

::: highlight
    wp litespeed-online cdn_init --method=cfi --cf-token=123456789
:::: {.admonition .example}
Example

Activate QUIC.cloud CDN with the CNAME method and specify an SSL
certificate and key:

::: highlight
    wp litespeed-online cdn_init --method=cname --ssl-cert=xxx.pem --ssl-key=xxx
### litespeed-online link[¶](#litespeed-online-link "Permanent link"){.headerlink}

::: highlight
    wp litespeed-online link --email=<string> --api-key=<string>
Link a user account by API key

  Parameter     Description
  ------------- --------------------------
  `--email`     QUIC.cloud account email
  `--api-key`   API Key

:::: {.admonition .example}
Example

Link the `you@example.com` account with API key `123456789`:

::: highlight
    wp litespeed-online link --email=you@example.com --api-key=123456789
## litespeed-debug[¶](#litespeed-debug "Permanent link"){.headerlink}

LiteSpeed Debug commands begin with `litespeed-debug`.

Syntax:

::: highlight
    wp litespeed-debug <subcommand> [parameters]
  Subcommand   Description
  ------------ -------------------------------------------------
  `send`       Send an Environment Report to LiteSpeed support

### litespeed-debug send[¶](#litespeed-debug-send "Permanent link"){.headerlink}

::: highlight
    wp litespeed-debug send
Send an Environment Report to LiteSpeed support. No parameters.

## litespeed-crawler[¶](#litespeed-crawler "Permanent link"){.headerlink}

Use `litespeed-crawler` to control the cache crawlers.

Syntax:

::: highlight
    wp litespeed-crawler <subcommand> [parameters]
  Subcommand      Description
  --------------- -------------------------------
  `list` or `l`   List all existing crawlers
  `enable`        Enable the specified crawler
  `disable`       Disable the specified crawler
  `run` or `r`    Start running the crawlers
  `reset`         Reset the crawler position

### litespeed-crawler list[¶](#litespeed-crawler-list "Permanent link"){.headerlink}

::: highlight
    wp litespeed-crawler list
Get a list of all existing crawlers. No parameters.

### litespeed-crawler enable[¶](#litespeed-crawler-enable "Permanent link"){.headerlink}

::: highlight
    wp litespeed-crawler enable <number>
Enable the specified crawler.

  Parameter    Description
  ------------ -----------------------
  `<number>`   The crawler ID number

:::: {.admonition .example}
Example

Enable the crawler with ID `2`:

::: highlight
    wp litespeed-crawler enable 2
### litespeed-crawler disable[¶](#litespeed-crawler-disable "Permanent link"){.headerlink}

::: highlight
    wp litespeed-crawler disable <number>
Disable the specified crawler.

  Parameter    Description
  ------------ -----------------------
  `<number>`   The crawler ID number

:::: {.admonition .example}
Example

Disable the crawler with ID `2`:

::: highlight
    wp litespeed-crawler disable 2
### litespeed-crawler run[¶](#litespeed-crawler-run "Permanent link"){.headerlink}

::: highlight
    wp litespeed-crawler run
OR

::: highlight
    wp litespeed-crawler r
Start the crawler running. No parameters.

### litespeed-crawler reset[¶](#litespeed-crawler-reset "Permanent link"){.headerlink}

::: highlight
    wp litespeed-crawler reset
Reset the crawler\'s position. No parameters.

## litespeed-database[¶](#litespeed-database "Permanent link"){.headerlink}

::: {.admonition .note}
Note

Unlike all other LiteSpeed CLI command sets, this set does not accept
default WordPress CLI parameters.
Database Optimization commands correspond to the buttons found in the
plugin GUI at **LiteSpeed Cache \> Database \> Manage**. These commands
all begin with `litespeed-database`, and have no parameters.

Syntax:

::: highlight
    wp litespeed-database <subcommand> [blog <id>]
  Subcommand           Description
  -------------------- --------------------------------------------------
  `clear_posts`        Clean up post data
  `clear_comments`     Clean up comment data
  `clear_trackbacks`   Clean up trackbacks and pingbacks
  `clear_transients`   Clean up transients
  `optimize_tables`    Optimize database tables
  `optimize_all`       Perform all possible optimizations and clean ups

### litespeed-database clear_posts[¶](#litespeed-database-clear_posts "Permanent link"){.headerlink}

::: highlight
    wp litespeed-database clear_posts [blog <id>]
Clean up post data, which includes revisions, orphaned posts, auto
drafts, and trashed posts.

  Parameter                                                                                                                                                      Description
  -------------------------------------------------------------------------------------------------------------------------------------------------------------- -------------
  `blog <id>` Specify which blog number (`<id>`) the command applies to in a multisite installation. Optional. Defaults to the first blog, if not specified. .   

:::: {.admonition .example}
Example

Clean up post data for site 2:

::: highlight
    wp litespeed-database clear_posts blog 2
### litespeed-database clear_comments[¶](#litespeed-database-clear_comments "Permanent link"){.headerlink}

::: highlight
    wp litespeed-database clear_comments [blog <id>]
Clean up comment data, which includes spam comments and trashed
comments.

  Parameter     Description
  ------------- ------------------------------------------------------------------------------------------------------------------------------------------------
  `blog <id>`   Specify which blog number (`<id>`) the command applies to in a multisite installation. Optional. Defaults to the first blog, if not specified.

:::: {.admonition .example}
Example

Clean up comment data for site 2:

::: highlight
    wp litespeed-database clear_comments blog 2
### litespeed-database clear_trackbacks[¶](#litespeed-database-clear_trackbacks "Permanent link"){.headerlink}

::: highlight
    wp litespeed-database clear_trackbacks [blog <id>]
Clean up trackbacks and pingbacks.

  Parameter     Description
  ------------- ------------------------------------------------------------------------------------------------------------------------------------------------
  `blog <id>`   Specify which blog number (`<id>`) the command applies to in a multisite installation. Optional. Defaults to the first blog, if not specified.

:::: {.admonition .example}
Example

Clean up trackbacks and pingbacks for site 2:

::: highlight
    wp litespeed-database clear_trackbacks blog 2
### litespeed-database clear_transients[¶](#litespeed-database-clear_transients "Permanent link"){.headerlink}

::: highlight
    wp litespeed-database clear_transients [blog <id>]
Clean up transients.

  Parameter     Description
  ------------- ------------------------------------------------------------------------------------------------------------------------------------------------
  `blog <id>`   Specify which blog number (`<id>`) the command applies to in a multisite installation. Optional. Defaults to the first blog, if not specified.

:::: {.admonition .example}
Example

Clean up transients for site 2:

::: highlight
    wp litespeed-database clear_transients blog 2
### litespeed-database optimize_tables[¶](#litespeed-database-optimize_tables "Permanent link"){.headerlink}

::: highlight
    wp litespeed-database optimize_tables [blog <id>]
Optimize database tables.

  Parameter     Description
  ------------- ------------------------------------------------------------------------------------------------------------------------------------------------
  `blog <id>`   Specify which blog number (`<id>`) the command applies to in a multisite installation. Optional. Defaults to the first blog, if not specified.

:::: {.admonition .example}
Example

Optimize database tables for site 2:

::: highlight
    wp litespeed-database optimize_tables blog 2
### litespeed-database optimize_all[¶](#litespeed-database-optimize_all "Permanent link"){.headerlink}

::: highlight
    wp litespeed-database optimize_all [blog <id>]
Perform all possible optimizations and clean ups.

  Parameter     Description
  ------------- ------------------------------------------------------------------------------------------------------------------------------------------------
  `blog <id>`   Specify which blog number (`<id>`) the command applies to in a multisite installation. Optional. Defaults to the first blog, if not specified.

:::: {.admonition .example}
Example

Perform all possible optimizations and clean ups for site 2:

::: highlight
    wp litespeed-database clear_comments blog 2
