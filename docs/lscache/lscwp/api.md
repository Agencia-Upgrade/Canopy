# LSCache for WordPress API[¶](#lscache-for-wordpress-api "Permanent link"){.headerlink}

Any WordPress plugin that populates front-end content that can be
publicly cached should work with LSCache.

However, if the plugin needs to update some data, and the cache does not
automatically purge the cached page, you may be required to write an
integration script to remedy this or invoke LSCWP\'s third party plugin
integration framework .

## Potential for Customization[¶](#potential-for-customization "Permanent link"){.headerlink}

LSCache for WordPress provides hooks that allow you to customize many
aspects of cache management, namely [WordPress `nonce` caching via ESI
blocks](#esi) as well as the other ones below.

### Customized Smart Purging[¶](#customized-smart-purging "Permanent link"){.headerlink}

LSCache works by tagging each cacheable page. In its most basic form,
each page is tagged with its Post ID, then sent to the server to be
cached. When someone makes a change to the page, that request will
notify the server to purge the cached items associated with that page\'s
post id.

A post will automatically be purged if the following events are
triggered (unless changed from the defaults):

- edit_post
- save_post
- deleted_post
- trashed_post
- delete_attachment

These cases cover most situations in which a cache purge is necessary.

If your plugin has additional situations in which a cache purge is
necessary, you have the ability to customize the notifications sent to
the server. As the page is stored in the cache, you can assign your own
tags to the page so that later, it may be purged as part of a custom
group. Multiple tags can be set on a single page, and a single tag may
be used on multiple pages. This many-to-many mapping provides a flexible
system enabling you to group pages in a variety of ways.

::: {.admonition .example}
Example

Page #1 is tagged with `MTPP_F.1, MTPP_G.4, MTPP_S.wyoming` (because the
page is in forum 1, group 4, and related to the state of Wyoming).

Page #2 is tagged with `MTPP_F.1, MTPP_G.2, MTPP_S.iowa`.

If a change is made where all pages tagged `MTPP_F.1` need to be purged,
the tag system enables the server to easily purge both of those pages at
once. If a request is sent to the server indicating that pages tagged
`MTPP_S.wyoming` need to be purged, then the tagging system knows to
only purge Page #1.
### Customized Do-Not-Cache Rules[¶](#customized-do-not-cache-rules "Permanent link"){.headerlink}

Below is a list of what LSCWP considers non-cacheable.

LSCache considers a page to be non-cacheable if

- It is an Admin page
- It is a post request
- `is_trackback()` is true
- `is_search()` is true
- No theme is used
- The URI is found in the Do Not Cache URIs list
- The post URL has a query string found in the Do Not Cache Query
  Strings list
- The post has a category found in the Do Not Cache Categories list
- The post has a tag found in the Do Not Cache Tags list
- The request has a cookie found in the Do Not Cache Cookies list
- The request has a user agent found in the Do Not Cache User Agents
  list
- The request is being made by a user whose role is checked in the Do
  Not Cache Roles List

If your plugin generates other private/transient data that cannot be
cached for certain responses, you can instruct LSCWP to not cache that
data.

## Hooks[¶](#hooks "Permanent link"){.headerlink}

These are the hooks to be used in other plugins and most are from
[`src/api.cls.php` under the `init()`
function](https://github.com/litespeedtech/lscache_wp/blob/master/src/api.cls.php#L51).
Please ignore the other non-public functions/hooks in that file. The
benefit to using hooks instead of functions is so there is no need to
detect if LSCWP is enabled and a function exists or not.

### Init[¶](#init "Permanent link"){.headerlink}

#### Hook to plugin init[¶](#hook-to-plugin-init "Permanent link"){.headerlink}

Action hook: `litespeed_init`

Parameter: the hook to be used

Usage:

::: highlight
    do_action( 'litespeed_init', 'the_hook' );
#### Hook after admin page init[¶](#hook-after-admin-page-init "Permanent link"){.headerlink}

Action hook: `litspeed_after_admin_init`

Usage:

::: highlight
    do_action( 'litspeed_after_admin_init' ) ;
### Config[¶](#config "Permanent link"){.headerlink}

#### Get plugin config setting[¶](#get-plugin-config-setting "Permanent link"){.headerlink}

Filter hook: `litespeed_conf`.

Parameter: the setting tag/slug (all setting slugs are at
[`src/base.cls.php`](https://github.com/litespeedtech/lscache_wp/blob/master/src/base.cls.php#L34)
& default settings are in
[`data/const.default.ini`](https://github.com/litespeedtech/lscache_wp/blob/master/data/const.default.ini))

Usage (this would get the private cache setting value):

::: highlight
    $val = apply_filters( 'litespeed_conf', 'cache-priv' );
#### Save Configuration[¶](#save-configuration "Permanent link"){.headerlink}

Save the LiteSpeed configuration and regenerate the rewrite rules for
`.htaccess`.

Action hook: `litespeed_save_conf`

Parameters: list of settings you want to change (optional)

Usage (this would save the configuration and enable auto upgrade):

::: highlight
    do_action('litespeed_save_conf', array('auto_upgrade' => true));
#### Append to plugin config setting[¶](#append-to-plugin-config-setting "Permanent link"){.headerlink}

Action hook: `litespeed_conf_append`

Parameters: name of setting & value

Usage:

::: highlight
    do_action( 'litespeed_conf_append', 'name', 'default' );
#### Append option save value filter[¶](#append-option-save-value-filter "Permanent link"){.headerlink}

Action hook: `litespeed_conf_multi_switch`

Usage:

::: highlight
    do_action( 'litespeed_conf_multi_switch' );
#### Change option dynamically[¶](#change-option-dynamically "Permanent link"){.headerlink}

::: {.admonition .note}
Note

This will only affect the AFTER usage of that option
Action hook: `litespeed_conf_force`

Parameters: key, variable

Usage:

::: highlight
    do_action( 'litespeed_conf_force', 'key', 'variable' );
#### Apply updated settings[¶](#apply-updated-settings "Permanent link"){.headerlink}

Action hook: `litespeed_update_confs`

Parameters: updated settings

Usage:

::: highlight
    do_action( 'litespeed_update_confs', $update_confs );
#### Modify configuration values[¶](#modify-configuration-values "Permanent link"){.headerlink}

Action hook: `litespeed_conf_load_option_{$option}`, where `{$option}`
is any configuration option.

Parameter: value to set `{$option}` to

Example:

Set the `esi` option to `false`.

::: highlight
    add_filter('litespeed_conf_load_option_esi', '__return_false');
### Cache Control[¶](#cache-control "Permanent link"){.headerlink}

Used to determine whether content should be cached and how to store it.

#### Set cache control hook[¶](#set-cache-control-hook "Permanent link"){.headerlink}

Specifies a hook for cache control. The hook will be triggered when the
cache plugin is checking whether the current page is cacheable. This
will not trigger on admin pages nor any page that has previously been
marked as noncacheable.

Action hooks: `litespeed_control_finalize` && `litespeed_api_control`

Parameter: tags

Usage:

::: highlight
    do_action( 'litespeed_control_finalize', 'tag' );
#### Set private cache[¶](#set-private-cache "Permanent link"){.headerlink}

Action hook: `litespeed_control_set_private`

Usage:

::: highlight
    do_action( 'litespeed_control_set_private' );
#### Set current page as non-cacheable[¶](#set-current-page-as-non-cacheable "Permanent link"){.headerlink}

Action hook: `litespeed_control_set_nocache`

Parameter: reason

Usage:

::: highlight
    do_action( 'litespeed_control_set_nocache', 'nocache due to logged in' );
#### Set current page as cacheable[¶](#set-current-page-as-cacheable "Permanent link"){.headerlink}

Might need if hook `wp` not called

Action hook: `litespeed_control_set_cacheable`

Parameter: reason

Usage:

::: highlight
    do_action( 'litespeed_control_set_cacheable', 'cache for scripted page retrieval' );
#### Force current page as cacheable[¶](#force-current-page-as-cacheable "Permanent link"){.headerlink}

Will ignore most kinds of non-cacheable conditions.

Action hook: `litespeed_control_force_cacheable`

Parameter: reason

Usage:

::: highlight
    do_action( 'litespeed_control_force_cacheable', 'force caching for special page' );
#### Set cache to force public cache if cacheable[¶](#set-cache-to-force-public-cache-if-cacheable "Permanent link"){.headerlink}

Will ignore most kinds of non-cacheable conditions.

Action hook: `litespeed_control_force_public`

Parameter: reason

Usage:

::: highlight
    do_action( 'litespeed_control_force_public', 'special page preferred to be cached public' );
#### Check if current page is cacheable[¶](#check-if-current-page-is-cacheable "Permanent link"){.headerlink}

Note: Read-Only. Directly appending to this filter won\'t work. Call
actions above to set cacheable or not.

Filter hook: `litespeed_control_cacheable`

Usage (this would define the cacheable status on the current page that
could be tested against later):

::: highlight
    $cstatus = apply_filters( 'litespeed_control_cacheable', false );
#### Set custom TTL[¶](#set-custom-ttl "Permanent link"){.headerlink}

Manually sets the TTL (Time to Live) for your cached object or ESI
block.

Action hook: `litespeed_control_set_ttl`

Parameter: value

Usage:

::: highlight
    do_action( 'litespeed_control_set_ttl', '1600' );
#### Get current page TTL[¶](#get-current-page-ttl "Permanent link"){.headerlink}

Filter hook: `litespeed_control_ttl`

Usage (this would get the TTL of the current page):

::: highlight
    $pagettl = apply_filters( 'litespeed_control_ttl', 0 );
### Tag[¶](#tag "Permanent link"){.headerlink}

Used to classify content for cache storage

#### Final tagging[¶](#final-tagging "Permanent link"){.headerlink}

This hook is called at the end of every cacheable request, and provides
an access point to your plugin, giving you the ability to add cache tags
to the current request.

Action hook: `litespeed_tag_finalize`

Parameter: the hook

Usage:

::: highlight
    do_action( 'litespeed_tag_finalize', 'the_hook' );
#### Add page tag[¶](#add-page-tag "Permanent link"){.headerlink}

Adds a single cache tag (or group of cache tags) to the list of cache
tags associated with the current page. These tags are appended the list
of built-in tags generated by LSCWP and may be used to purge by a custom
tag.

Action hook: `litespeed_tag_add`

Parameter: the tag to add

Usage:

::: highlight
    do_action( 'litespeed_tag_add', 'the_new_tag' );
#### Add tag to post[¶](#add-tag-to-post "Permanent link"){.headerlink}

Adds a custom cache tag to a post which could be used to trigger a purge
if changed.

Action hook: `litespeed_tag_add_post`

Parameter: the custom tag to add

Usage (could use a string within a loop or after a conditional test):

::: highlight
    do_action( 'litespeed_tag_add_post', 'the_new_tag' );
#### Add tag to widget[¶](#add-tag-to-widget "Permanent link"){.headerlink}

Adds a custom cache tag to a widget which could be used to trigger a
purge if changed.

Action hook: `litespeed_tag_add_widget`

Parameter: the custom tag to add

Usage (could use a string within a loop or after a conditional test):

::: highlight
    do_action( 'litespeed_tag_add_widget', 'the_new_tag' );
#### Add private cache tag[¶](#add-private-cache-tag "Permanent link"){.headerlink}

Adds private cache tags to the list of cache tags for the current page.

Action hook: `litespeed_tag_add_private`

Parameter: the tag to add

Usage:

::: highlight
    do_action( 'litespeed_tag_add_private', 'the_tag_to_add' );
#### Add private ESI cache tag[¶](#add-private-esi-cache-tag "Permanent link"){.headerlink}

Adds private ESI cache tags to the list of cache tags for the current
page.

Action hook: `litespeed_tag_add_private_esi`

Parameter: the ESI tag to add

Usage:

::: highlight
    do_action( 'litespeed_tag_add_private_esi', 'the_esi_tag_to_add' );
### Purge[¶](#purge "Permanent link"){.headerlink}

#### Define Post Events to Purge[¶](#define-post-events-to-purge "Permanent link"){.headerlink}

Define a custom set of post event that will trigger a purge.

Filter: `litespeed_purge_post_events`

Parameter: Array of post events. This list replaces the default list of
events.

Usage:

::: highlight
    add_filter( 'litespeed_post_purge_events', $events)
:::: {.admonition .example}
Example

If you only want to purge for the `delete_post` and `wp_trash_post`
events:

::: highlight
    add_filter('litespeed_purge_post_events', array(
            'delete_post',
            'wp_trash_post'
        );      
#### Final tagging[¶](#final-tagging_1 "Permanent link"){.headerlink} {#final-tagging_1}

This hook is called at the end of every cacheable request, and gives you
the ability to add purge tags to the current request.

Action hook: `litespeed_purge_finalize`

Parameter: the hook

Usage:

::: highlight
    do_action( 'litespeed_purge_finalize', 'the_hook' );
#### Purge a cache tag[¶](#purge-a-cache-tag "Permanent link"){.headerlink}

Action hook: `litespeed_purge`

Parameter: the cache tag to purge.

Usage:

::: highlight
    do_action( 'litespeed_purge', 'the_tag_to_purge' );
#### Manipulate purge tags[¶](#manipulate-purge-tags "Permanent link"){.headerlink}

Filter hook: `litespeed_purge_tags`

Parameter: \$purge_tags (array) for plugin\'s default tags and
\$is_private to determine if this is private purge or public purge

:::: {.admonition .example}
Example

Suppress automated purges, and only allow manual purge:

::: highlight
    add_filter('litespeed_purge_tags', function($purge_tags, $is_private) {
        if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'LSCWP_CTRL=purge') !== false) {
            do_action('litespeed_debug2', 'Perserve manual purge action');
            return $purge_tags;
        }
        return ['_nothing'];
    }
:::: {.admonition .example}
Example

Suppress all purges, including manual purge:

::: highlight
    add_filter('litespeed_purge_tags', function($purge_tags, $is_private) {
        return ['_nothing'];
    }
:::: {.admonition .example}
Example

Remove `XXX`, `YYY`, and `ZZZ` tags from purge, without removing any
other tags. The `do_action('litespeed_debug2'...)` actions are commented
out. Add them back, to enable logging for debugging purposes:

::: highlight
    add_filter('litespeed_purge_tags', function($purge_tags, $is_private) {
        if ($is_private) { 
    //      do_action('litespeed_debug2', 'No modification to private cache purge');
            return $purge_tags;
        }
        if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'LSCWP_CTRL=purge') !== false) {
    //      do_action('litespeed_debug2', 'Perserve manual purge action');
            return $purge_tags;
        }

        // tags to remove
        $tags_to_remove = [ 'XXX', 'YYY', 'ZZZ'];  

        // append '_' to each tag 
        $removed_tags = array_map(function($tag) { return '_' . $tag; }, $tags_to_remove);

    //  do_action('litespeed_debug2', 'Purge tags filter before: ' . implode(', ', $purge_tags));
    //  do_action('litespeed_debug2', 'Purge tags to remove: ' . implode(', ', $tags_to_remove));

        $purge_tags = array_values(array_diff($purge_tags, $removed_tags));

    //   do_action('litespeed_debug2', 'Purge tags filter after: ' . implode(', ', $purge_tags));
        return $purge_tags;
    }, 10, 2);
#### Purge all existing caches[¶](#purge-all-existing-caches "Permanent link"){.headerlink}

Action hook: `litespeed_purge_all`

Usage:

::: highlight
    do_action( 'litespeed_purge_all' );
#### Purge a single post by ID[¶](#purge-a-single-post-by-id "Permanent link"){.headerlink}

Action hook: `litespeed_purge_post`

Parameter: the post ID

Usage:

::: highlight
    do_action( 'litespeed_purge_post', 'post_id' );
#### Purge posts by post type[¶](#purge-posts-by-post-type "Permanent link"){.headerlink}

Action hook: `litespeed_purge_posttype`

Parameter: the post type

Usage:

::: highlight
    do_action( 'litespeed_purge_posttype', 'post_type' );
#### Purge posts by URL[¶](#purge-posts-by-url "Permanent link"){.headerlink}

Action hook: `litespeed_purge_url`

Parameter: URL to purge

Usage:

::: highlight
    do_action( 'litespeed_purge_url', 'the_url' );
::: {.admonition .tip}
Tip

The URL parameter may be passed with or without a domain. So, both
`/path/to/page/` and `https://www.example.com/path/to/page/` will work.
#### Purge posts by widget[¶](#purge-posts-by-widget "Permanent link"){.headerlink}

Action hook: `litespeed_purge_widget`

Parameter: widget to purge

Usage:

::: highlight
    do_action( 'litespeed_purge_widget', 'widget_to_purge' );
#### Purge posts by ESI[¶](#purge-posts-by-esi "Permanent link"){.headerlink}

Action hook: `litespeed_purge_widget`

Parameter: ESI name to purge

Usage:

::: highlight
    do_action( 'litespeed_purge_widget', 'esi_to_purge' );
#### Purge private cache by tag[¶](#purge-private-cache-by-tag "Permanent link"){.headerlink}

Action hook: `litespeed_purge_private`

Parameter: tag to purge

Usage:

::: highlight
    do_action( 'litespeed_purge_private', 'tag_to_purge' );
#### Purge private cache by ESI[¶](#purge-private-cache-by-esi "Permanent link"){.headerlink}

Action hook: `litespeed_purge_private_esi`

Parameter: ESI name to purge

Usage:

::: highlight
    do_action( 'litespeed_purge_private_esi', 'esi_to_purge' );
#### Purge all private cache[¶](#purge-all-private-cache "Permanent link"){.headerlink}

Action hook: `litespeed_purge_private_all`

Usage:

::: highlight
    do_action( 'litespeed_purge_private_all' );
#### Purge all object cache[¶](#purge-all-object-cache "Permanent link"){.headerlink}

Action hook: `litespeed_purge_all_object`

Usage:

::: highlight
    do_action( 'litespeed_purge_all_object' );
### Purge Hook Actions[¶](#purge-hook-actions "Permanent link"){.headerlink}

These actions are trigged after particular purge actions.

#### Hook action for Purge All LSCache[¶](#hook-action-for-purge-all-lscache "Permanent link"){.headerlink}

Triggered after a Purge All LSCache

Action hook: `litespeed_purged_all_lscache`

Usage:

::: highlight
    do_action( 'litespeed_purged_all_lscache');
#### Hook action for Purge All Pages[¶](#hook-action-for-purge-all-pages "Permanent link"){.headerlink}

Triggered after a Purge All Pages

Action hook: `litespeed_purged_pages`

Usage:

::: highlight
    do_action( 'litespeed_purged_pages');
#### Hook action for Purge Category[¶](#hook-action-for-purge-category "Permanent link"){.headerlink}

Triggered after a Purge for a particular Category

Action hook: `litespeed_purged_cat`

Parameter: purged category

Usage:

::: highlight
    do_action( 'litespeed_purged_cat', $category );
#### Hook action for Purge Tag[¶](#hook-action-for-purge-tag "Permanent link"){.headerlink}

Triggered after a Purge for a particular Tag

Action hook: `litespeed_purged_tag`

Parameter: purged tag

Usage:

::: highlight
    do_action( 'litespeed_purged_tag', $tag );
#### Hook action for Purge Link[¶](#hook-action-for-purge-link "Permanent link"){.headerlink}

Triggered after a Purge for a particular URL

Action hook: `litespeed_purged_link`

Parameter: purged URL

Usage:

::: highlight
    do_action( 'litespeed_purged_link', $url );
#### Hook action for Purge Post[¶](#hook-action-for-purge-post "Permanent link"){.headerlink}

Triggered after a Purge Post

Action hook: `litespeed_api_purge_post`

Parameter: the hook

Usage:

::: highlight
    do_action( 'litespeed_api_purge_post', 'esi_to_purge' );
#### Hook action for Purge Front Page[¶](#hook-action-for-purge-front-page "Permanent link"){.headerlink}

Triggered after a Purge Front Page

Action hook: `litespeed_purged_frontpage`

Usage:

::: highlight
    do_action( 'litespeed_purged_frontpage');
#### Hook action for Purge All[¶](#hook-action-for-purge-all "Permanent link"){.headerlink}

Triggered after a Purge All

Action hook: `litespeed_purged_all`

Usage:

::: highlight
    do_action( 'litespeed_purged_all' );
#### Hook action for Purge All Object[¶](#hook-action-for-purge-all-object "Permanent link"){.headerlink}

Triggered after a Purge All

Action hook: `litespeed_purged_all_object`

Usage:

::: highlight
    do_action( 'litespeed_purged_all_object' );
#### Hook action for Purge All opache[¶](#hook-action-for-purge-all-opache "Permanent link"){.headerlink}

Triggered after a Purge All opache

Action hook: `litespeed_purged_all_opcache`

Usage:

::: highlight
    do_action( 'litespeed_purged_all_opcache');
#### Hook action for Purge Single Tag[¶](#hook-action-for-purge-single-tag "Permanent link"){.headerlink}

Triggered after a Purge Single Tag

Action hook: `litespeed_purged_single`

Usage:

::: highlight
    do_action( 'litespeed_purged_single');
#### Hook action for Purge ESI Tag[¶](#hook-action-for-purge-esi-tag "Permanent link"){.headerlink}

Triggered after a Purge for a particular ESI Tag

Action hook: `litespeed_purged_esi`

Parameter: purged ESI tag

Usage:

::: highlight
    do_action( 'litespeed_purged_esi', $tag );
#### Hook action for Purge Recent Comments Widget[¶](#hook-action-for-purge-recent-comments-widget "Permanent link"){.headerlink}

Triggered after a Purge for a particular Recent Comments widget

Action hook: `litespeed_purged_comment_widget`

Parameter: Widget ID

Usage:

::: highlight
    do_action( 'litespeed_purged_comment_widget', $id );
#### Hook action for Purge Feed[¶](#hook-action-for-purge-feed "Permanent link"){.headerlink}

Triggered after a Purge RSS Feed

Action hook: `litespeed_purged_feeds`

Usage:

::: highlight
    do_action( 'litespeed_purged_feeds');
#### Hook action for Logout[¶](#hook-action-for-logout "Permanent link"){.headerlink}

Triggered after user logs out

Action hook: `litespeed_purged_on_logout`

Usage:

::: highlight
    do_action( 'litespeed_purged_on_logout' );
### ESI[¶](#esi "Permanent link"){.headerlink}

Edge Side Includes (ESI) allows you to "punch holes" in a page in order
to treat certain sections of the page differently. This is useful, for
example, if you want to include a private shopping cart widget in a
public page. For more on ESI and how it works, see [our blog post on the
subject](https://blog.litespeedtech.com/2017/09/06/wpw-esi-and-litespeed-cache/).

::: {.admonition .help}
Video

See a video demonstration of **What is Edge Side Includes (ESI)?**
[here](https://www.youtube.com/watch?v=uYpR6D8n3oE).
The WordPress `nonce` makes caching difficult with most plugins, however
the LiteSpeed API has an elegant solution, and ESI is the key.

If your plugin uses a default nonce, then LiteSpeed Cache will
automatically treat that nonce as an ESI block. This ensures the nonce
is cached for only 12 hours, separate from the TTL of the page that it
is on. You don\'t have to do anything special for this to work.

If your plugin uses a custom nonce, however, you will need to register
the nonce action with our API before you use it.

#### Convert custom nonce to ESI[¶](#convert-custom-nonce-to-esi "Permanent link"){.headerlink}

Action hook: `litespeed_nonce`

Parameter: custom nonce

::::: {.admonition .example}
Example

If you have the following line in your code

::: highlight
    wp_create_nonce( 'example_nonce' );
Then you need to call the API somewhere before that line, like so:

::: highlight
    do_action( 'litespeed_nonce', 'example_nonce' );
Once registered with our API, your custom nonce will be treated as an
ESI block as long as your users have ESI enabled. (If ESI is not
enabled, the API will call the native WordPress `nonce` function.)

#### Get ESI enable status[¶](#get-esi-enable-status "Permanent link"){.headerlink}

Filter hook: `litespeed_esi_status`

Usage (this would get the status on if ESI is enabled):

::: highlight
    $esi_status = apply_filters( 'litespeed_esi_status', false );
#### Generate ESI block URL[¶](#generate-esi-block-url "Permanent link"){.headerlink}

Filter hook: `litespeed_esi_url`

Parameters:
`$block_id, $wrapper, $params = array(), $control = 'private,no-vary', $silence = false, $preserved = false, $svar = false, $inline_val = false`
(the first 2 are required parameters & the others are optional)

Action hook: `litespeed_esi_load-$block` where `$block` is the block
name

::: {.admonition .warning}
Warning

Dynamic and uncached ESI blocks can slow a page down, if there are too
many of them. Keep such ESI blocks to a minimum. If there will be many
potential ESI blocks on a page, it might be more efficient to simply
keep the page itself uncached, and not use ESI at all.
:::::: {.admonition .example}
Usage example

First add the following code into the place where you want to insert the
block:

::: highlight
    apply_filters( 'litespeed_esi_url', 'my_esi_block', 'Custom ESI block' );
Like so:

::: highlight
    some_code_here
    <div>
    <?php
    echo apply_filters( 'litespeed_esi_url', 'my_esi_block', 'Custom ESI block' );
    ?>
    </div>
    some_code_here
Then add following code into your theme\'s `functions.php`:

::: highlight
    add_action( 'litespeed_esi_load-my_esi_block', 'my_esi_block_esi_load' );

    function my_esi_block_esi_load()
    {
    do_action( 'litespeed_control_set_ttl', 300 );
    #do_action( 'litespeed_control_set_nocache' );
    echo "Hello world".rand (1,99999);
    }
In this example, `my_esi_block` is the block name, `Custom ESI block` is
a short comment, and `300` is the TTL for this block.

You can swap the comments above to
`do_action( 'litespeed_control_set_nocache' );` if you want to set this
block to `no-cache`.
:::::: {.admonition .example}
Example with parameters

If you\'d like to pass a variable in as a parameter, you can modify this
code:

::: highlight
    $my_var = "test var";
    $my_var2 = "test var 2";
    add_action( 'litespeed_esi_load-my_esi_block', function() use ($my_var, $my_var2) {
        do_action( 'litespeed_control_set_nocache' );
        echo "Hello world".rand (1,99999);
        echo '<br>my var:' . $my_var . $my_var2;
    });
Or, you could pass in a variable through a filter:

::: highlight
    apply_filters( 'litespeed_esi_url', 'my_esi_block', 'Custom ESI block', array('123','abc') );
followed by:

::: highlight
    add_action( 'litespeed_esi_load-my_esi_block', 'my_esi_block_esi_load');

    function my_esi_block_esi_load($params)
    {
    do_action( 'litespeed_control_set_nocache' );
    echo "Hello world".rand (1,99999);
    echo var_dump($params);
    }
::::: {.admonition .example}
Random Number Example Plugin

In this example, we have a plugin called **LiteSpeed Cache Plugin ESI**,
in which a PHP file called `require_example.php`, generates a random
number, and is excluded from caching. This is accomplished via multiple
ESI API filters, including `litespeed_esi_url`:

First, the PHP file that generates the random number,
`/wp-content/plugins/lscwp-esi/require_example.php`:

::: highlight
    <?php 
    echo "Random number from required file: ".rand (1,99999);
    echo '<br>';
    echo 'variables received: <br>';
    echo var_dump($params);

    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        $user_name = $current_user->user_login;
    }
    else {
        $user_name = 'guest user';
    }

    echo '<br> hello, ' . $user_name;
Then, the plugin `/wp-content/plugins/lscwp-esi/lscwp-esi.php`:

::: highlight
    <?php
    /*
    Plugin Name: LiteSpeed Cache Plugin ESI
    Description: LiteSpeed ESI test
    */

    defined('WPINC') || exit;

    if (!defined('LSCWP_V') || ! apply_filters( 'litespeed_esi_status', false ))
    {
        return;
    }

    add_action( 'litespeed_esi_load-my_esi_block', 'my_esi_block_esi_load' );

    function my_esi_block_esi_load($params)
    {
        do_action( 'litespeed_control_set_nocache' );
        require_once( WP_CONTENT_DIR . "/plugins/lscwp-esi/require_example.php");
    }

    add_action('wp_head', 'lscwp_esi_test'); 
    add_action('wp_footer', 'lscwp_esi_test'); 


    function lscwp_esi_test() {
            $var1 = '123';
            $var2 = 'abc';
            echo '<div style="background: blue; color: white; text-align: center;">';
            echo apply_filters( 'litespeed_esi_url', 'my_esi_block', 'Custom ESI block', array($var1,$var2) );
            echo '</div>';

    }
#### Hook widget default settings value[¶](#hook-widget-default-settings-value "Permanent link"){.headerlink}

Filter hook `litespeed_widget_default_options`.

Parameters: the hook & widget name

Usage:

See
[thirdparty/woocommerce.cls.php](https://github.com/litespeedtech/lscache_wp/blob/master/thirdparty/woocommerce.cls.php#L111)
for in-depth example

#### Hook ESI parameters[¶](#hook-esi-parameters "Permanent link"){.headerlink}

Filter hook `litespeed_esi_params`.

Parameter: \$params, block_id

Usage:

::: highlight
    add_filter( 'litespeed_esi_params', $params, 'block_id' );
#### Hook not ESI template[¶](#hook-not-esi-template "Permanent link"){.headerlink}

Action hooks: `litespeed_tpl_normal` && `litespeed_is_not_esi_template`

Usage:

See examples in both `thirdparty/yith-wishlist.cls.php` &
`thirdparty/woocommerce.cls.php`

### Vary[¶](#vary "Permanent link"){.headerlink}

The following Cache Vary API elements have been deprecated as of LSCWP
v3.7:

Functions:

- `hook_vary_add()`
- `vary_add()`
- `filter_vary_cookies()`
- `hook_vary()`

Actions:

- `litespeed_vary_add`
- `litespeed_vary_append`

Filter:

\-`litespeed_api_vary`

Going forward, use `litespeed_vary_curr_cookies` and
`litespeed_vary_cookies` to build the cookie list.

#### Add to the Vary Cookies List[¶](#add-to-the-vary-cookies-list "Permanent link"){.headerlink}

To add permanent cookies that apply to every page, use the filter hook:
`litespeed_vary_cookies`

Parameter: Array of cookies

Usage:

::: highlight
    $vary_cookies = apply_filters( 'litespeed_vary_cookies', $vary_cookies );
:::: {.admonition .example}
Example

To add a vary cookie for GDPR, assuming the name of the cookie is
`GDPR_cookie`, add the following code to `functions.php`:

::: highlight
     add_filter('litespeed_vary_curr_cookies', 'lscwp_add_custom_cookie');
     add_filter('litespeed_vary_cookies', 'lscwp_add_custom_cookie'); 
    function lscwp_add_custom_cookie($list){ 
        $list[] = 'GDPR_cookie'; 
        return $list; 
    }
To add cookies that apply only to the current page, use the filter hook:
`litespeed_vary_curr_cookies`

Parameter: Array of cookies

Usage:

::: highlight
    $vary_cookies = apply_filters( 'litespeed_vary_curr_cookies', $vary_cookies );
#### Alter default vary cookie value[¶](#alter-default-vary-cookie-value "Permanent link"){.headerlink}

Default vary cookie is an array before finalization, after that it will
be combined to a string and stored as default vary cookie name.

Filter hook: `litespeed_vary`

Parameters: Cookie name and value

Usage:

::: highlight
    add_filter( 'litespeed_vary', array( $val, 'var_cookie_name' ) );
#### Force finalize vary[¶](#force-finalize-vary "Permanent link"){.headerlink}

Force a cache vary for the current page, even if the variation is caused
by an AJAX call.

Action hook: `litespeed_vary_ajax_force`

Usage:

::: highlight
    do_action( 'litespeed_vary_ajax_force' );
#### Set cache status to no vary[¶](#set-cache-status-to-no-vary "Permanent link"){.headerlink}

Do not create a cache vary for the current page.

Action hook: `litespeed_vary_no`

Usage:

::: highlight
    do_action( 'litespeed_vary_no' );
#### Get mobile cache status[¶](#get-mobile-cache-status "Permanent link"){.headerlink}

Filter hook: `litespeed_is_mobile`

Parameter: boolean

Usage (this would get the status on if is not mobile):

::: highlight
    $is_mobile = apply_filters( 'litespeed_is_mobile', false );
### Cloud[¶](#cloud "Permanent link"){.headerlink}

#### Verify Callback From QUIC.cloud CDN[¶](#verify-callback-from-quiccloud-cdn "Permanent link"){.headerlink} {#verify-callback-from-quiccloud-cdn}

Filter hook: `litespeed_is_from_cloud`

Parameter: boolean

This filter allows you to check the origin of a callback request, in
order to reject REST access to unauthorized sources. The filter returns
`true` if the callback request originates from a recognized QUIC.cloud
CDN node.

Usage:

::: highlight
    $is_from_cloud = apply_filters( 'litespeed_is_from_cloud', false );
### Optimize[¶](#optimize "Permanent link"){.headerlink}

#### Disable Page Optimization[¶](#disable-page-optimization "Permanent link"){.headerlink}

Filter hook: `litespeed_can_optm`

Usage:

::: highlight
    add_filter( 'litespeed_can_optm', '__return_false' );
#### Set a Custom CCSS Path[¶](#set-a-custom-ccss-path "Permanent link"){.headerlink}

Filter hook: `litespeed_ccss_url`

Usage:

::: highlight
    $val = apply_filters('litespeed_ccss_url', $request_url)
#### Set a Custom UCSS Path[¶](#set-a-custom-ucss-path "Permanent link"){.headerlink}

Filter hook: `litespeed_ucss_url`

Usage:

::: highlight
    $val = apply_filters('litespeed_ucss_url', $request_url)
#### Generate Single UCSS for Page Type[¶](#generate-single-ucss-for-page-type "Permanent link"){.headerlink}

Filter hook: `litespeed_ucss_per_pagetype`

UCSS is generated per URL. You can use this filter to generate a single
shared UCSS file for any posts where the type is `page`. UCSS will still
be generated by URL for all other post types.

Usage:

::: highlight
    add_filter( 'litespeed_ucss_per_pagetype', function(){return get_post_type() == 'page';} );
::::: {.admonition .example}
Examples

Let\'s say you have a site with a blog and a WooCommerce shop. You want
UCSS to be generated per URL for all blog posts and pages, but your
products can share a single UCSS file.

You would add the following to your `functions.php` file:

::: highlight
    add_filter( 'litespeed_ucss_per_pagetype', function(){return get_post_type() == 'product';} );
The function in this example returns `True` if the current post type is
`product` and `False` in any other situation. Another way of looking at
`litespeed_ucss_per_pagetype` is that it generates UCSS by URL when
passed a value of false, and generates UCSS by post type when passed a
value of true. If you wanted UCSS to *always* be generated by post type,
you wouldn\'t need to even check the current post type. You could just
always pass it a true value, like so:

::: highlight
    add_filter( 'litespeed_ucss_per_pagetype', '__return_true' );
#### Bypass UCSS for Page Type[¶](#bypass-ucss-for-page-type "Permanent link"){.headerlink}

Use the following to bypass generating UCSS for any posts where the type
is `page`. UCSS will continue to be generated for all other post types.

::: highlight
    add_action( 'litespeed_optm', function(){get_post_type() == 'page' && do_action( 'litespeed_conf_force', 'optm-ucss', false );});
#### Exclude a URI from Optimization[¶](#exclude-a-uri-from-optimization "Permanent link"){.headerlink}

Filter hook: `litespeed_optm_uri_exc`

Use the following to append a new URI to an existing list of excluded
URIs:

::: highlight
    add_filter('litespeed_optm_uri_exc', function($list){
       $list[]='aa';
       return $list;
    });
#### Exclude JavaScript from Optimization[¶](#exclude-javascript-from-optimization "Permanent link"){.headerlink}

There are three JavaScript exclude settings, and a filter that
corresponds to each one:

- [**JS Excludes**](/lscache/lscwp/pageopt/#js-excludes):
  `litespeed_optimize_js_excludes`
- [**JS Deferred/Delayed
  Excludes**](/lscache/lscwp/pageopt/#js-deferreddelayed-excludes):`litespeed_optm_js_defer_exc`
- [**Guest Mode JS
  Excludes**](/lscache/lscwp/pageopt/#guest-mode-js-excludes):`litespeed_optm_gm_js_exc`

:::: {.admonition .example}
Example

The following example shows you how to exclude the same three JS files
from each type of optimization:

::: highlight
    lscwp_custom_excludes($excludes) {
        $my_custom_excludes = array(
            'javascript_1.js',
            'javascript_2.js',
            'javascript_3.js',
        );
        return array_merge($excludes, $my_custom_excludes);
    }
    add_filter('litespeed_optimize_js_excludes', 'lscwp_custom_excludes');
    add_filter('litespeed_optm_js_defer_exc', 'lscwp_custom_excludes');
    add_filter('litespeed_optm_gm_js_exc', 'lscwp_custom_excludes');
#### Control VPI Queue[¶](#control-vpi-queue "Permanent link"){.headerlink}

Filter hook: `litespeed_vpi_should_queue`

This filter allows you to control whether a URL should be appended to
the VPI queue or not.

Usage:

::: highlight
    add_filter('litespeed_vpi_should_queue', function($should_queue, $request_url) { return strpos($request_url, '/no-optimize/'); } );
#### Place Optimizations before `/head`[¶](#place-optimizations-before-head "Permanent link"){.headerlink}

Filter hook: `litespeed_optm_html_after_head`

When you use this filter, the optimized code for UCSS, CCSS, Combined
CSS and/or Combined JS are placed just before the closing `</head>` tag.

Usage:

::: highlight
    add_filter('litespeed_optm_html_after_head','__return_true');
### GUI[¶](#gui "Permanent link"){.headerlink}

#### Start a to-be-removed html wrapper[¶](#start-a-to-be-removed-html-wrapper "Permanent link"){.headerlink}

Filter hook: `litespeed_clean_wrapper_begin`

Usage:

::: highlight
    echo apply_filters( 'litespeed_clean_wrapper_begin', '' );
#### End a to-be-removed html wrapper[¶](#end-a-to-be-removed-html-wrapper "Permanent link"){.headerlink}

Filter hook: `litespeed_clean_wrapper_end`

Usage:

::: highlight
    echo apply_filters( 'litespeed_clean_wrapper_end', '' );
#### Hooks for dropdown menu[¶](#hooks-for-dropdown-menu "Permanent link"){.headerlink}

Action hook: `litespeed_frontend_shortcut` &
`litespeed_backend_shortcut`

Usage:

::: highlight
    do_action( 'litespeed_frontend_shortcut' );
### Misc[¶](#misc "Permanent link"){.headerlink}

#### Enables standard debug logging[¶](#enables-standard-debug-logging "Permanent link"){.headerlink}

Logs standard level of debugging information to `wp-content/debug.log`.

Action hook: `litespeed_debug`

Parameter: reason

Usage:

::: highlight
    do_action( 'litespeed_debug', 'Reason to start basic debugging' );
#### Enables advanced debug logging[¶](#enables-advanced-debug-logging "Permanent link"){.headerlink}

Logs advanced level of debugging information to `wp-content/debug.log`.

Action hook: `litespeed_debug2`

Parameter: reason

Usage:

::: highlight
    do_action( 'litespeed_debug2', 'Reason to start thorough debugging' );
#### Disables all debug logging[¶](#disables-all-debug-logging "Permanent link"){.headerlink}

Action hook: `litespeed_disable_all`

Parameter: reason

Usage:

::: highlight
    do_action( 'litespeed_disable_all', 'Reason to stop debugging' );
#### Modify output buffer before[¶](#modify-output-buffer-before "Permanent link"){.headerlink}

`litespeed_buffer_before` acts on content before LSCache has manipulated
it. So, before applying CDN rules, before applying CSS/JS optimizations,
etc.

Action hook: `litespeed_buffer_before`

Usage:

::: highlight
    function remove_pingback_link( $content ) {
      return str_replace( '<link rel="pingback" href="https://example.com/xmlrpc.php">', '', $content );
    }
    add_filter( 'litespeed_buffer_before', 'remove_pingback_link', 0);
#### Modify output buffer after[¶](#modify-output-buffer-after "Permanent link"){.headerlink}

`litespeed_buffer_after` acts on content after LSCache has manipulated
it. So, after applying CDN rules, after applying CSS/JS optimizations,
etc. Remember though, due to the way page caching works, LiteSpeed hooks
cannot be executed on cached content, so when we talk about the buffer
content, it is always prior to caching.

Action hook: `litespeed_buffer_after`

Usage:

::: highlight
    function remove_broken_style( $content ) {
        return str_replace( '.content{opacity:0}', '', $content );
    }
    add_filter( 'litespeed_buffer_after', 'remove_broken_style', 0);
### Bypasses adding missing media sizes[¶](#bypasses-adding-missing-media-sizes "Permanent link"){.headerlink}

Use `litespeed_media_add_missing_sizes` to bypass LiteSpeed Cache\'s
**Add Missing Sizes** media option, if your application doesn\'t require
media sizes, such as for Guest Optimization.

Filter: `litespeed_media_add_missing_sizes`

## Debugging/Developer Tips[¶](#debuggingdeveloper-tips "Permanent link"){.headerlink}

Many full usage examples are available in source under `thirdparty/` in
case of typos or incomplete examples. GitHub has most of it indexed
although is better to search local file contents.

Besides the basic [compatibility
tests](../thirdparty/#testing-for-compatibility) and [troubleshooting
steps](../troubleshoot/), one can open a support ticket or even join our
[#wpcache-dev channel on
Slack](https://golitespeed.slack.com/archives/CC0UB7EKA) if further
assistance is needed and/or you want to share your progress in
cross-compatibility & improving functionality.
