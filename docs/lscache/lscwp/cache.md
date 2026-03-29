# Cache[¶](#cache "Permanent link"){.headerlink}

## Cache Tab[¶](#cache-tab "Permanent link"){.headerlink}

[![!LSCWP Cache Section Cache
Tab](../images/cache01.png)](../images/cache01.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### Enable Cache[¶](#enable-cache "Permanent link"){.headerlink}

*ON*

This is the final step required for enabling the caching functionality
of the plugin. (If you have not completed the previous steps, please
[see here](../installation) for instruction.) When **Enable Cache** is
turned `ON`, the pages of your site will be cached. If you later turn it
`OFF`, all future caching will be stopped, and any existing pages will
be purged from cache.

For single site installations, only `ON` and `OFF` are available. For
Multisite Subsite admins, there is a third option,
`Use Network Admin Setting`. This last option uses what the Network
Admin chooses.

::: {.admonition .note}
Note

If you are seeing a warning that LSCache is disabled, and you can\'t
make it go away, please see [our troubleshooting
instructions](../troubleshoot/#litespeed-cache-is-disabled).
### Cache Logged-in Users[¶](#cache-logged-in-users "Permanent link"){.headerlink}

*ON*

This setting allows content to be cached for logged-in users. Pages will
be stored in private cache by IP and session ID.

### Cache Commenters[¶](#cache-commenters "Permanent link"){.headerlink}

*ON*

When a comment is submitted on a post, if moderation is not enabled, the
comment is published immediately, and the page is purged from cache.
Everyone (the commenter, and all future visitors to the page) will see
the newly-published comment when the page reloads.

If moderation is enabled, then the comment is not published immediately,
the page is not purged, and users will continue to be served the cached
version of the page, without the moderated comment.

The **Cache Commenters** option is useful in that second scenario. It
determines how the user leaving the comment will see the page, after
they\'ve submitted their comment. Here is how it behaves:

- It is `ON` by default, meaning the user will see the previously cached
  version of the page (and their comment will not appear).
- If you turn the option `OFF`, the user will not be served from cache.
  The page will be generated from scratch, and the user will see their
  under-moderation comment.

Regardless of how you set this option, all non-commenting visitors will
still be served the cached version of the page until the comment is
approved and the page is purged.

### Cache REST API[¶](#cache-rest-api "Permanent link"){.headerlink}

*ON*

This option allows you to cache requests that are made by WordPress REST
API calls.

### Cache Login Page[¶](#cache-login-page "Permanent link"){.headerlink}

*ON*

This option will cache the login page. Normally, there is no reason to
uncheck this option. However, if there is something that may identify a
user on the page, this should be off.

### Cache favicon.ico[¶](#cache-faviconico "Permanent link"){.headerlink} {#cache-faviconico}

This option was removed in version 6.2, as it was redundant. The
`favicon.ico` 404 response is already cached with the other 404
responses controlled via [**Default HTTP Status Code Page
TTL**](#default-http-status-code-page-ttl).

### Cache PHP Resources[¶](#cache-php-resources "Permanent link"){.headerlink}

This setting has been deprecated as of v7.2.

### Cache Mobile[¶](#cache-mobile "Permanent link"){.headerlink}

*OFF*

This option enables you to cache separate versions of a page for mobile
and desktop views. This is primarily used for non-responsive themes with
a mobile-specific design, but there are other situations where you would
want to set **Cache Mobile** to `ON`, such as:

- If your site has mobile-specific content, like widgets that only
  appear on Mobile (or only appear on Desktop)
- If you are using [AMP](https://amp.dev/) on your site
- If you are using the [CCSS](../pageopt/#generate-critical-css) service
- If you are using the [UCSS](../pageopt/) service
- If you have [**Guest Mode** + **Guest
  Optimization**](../general/#guest-mode) enabled

::: {.admonition .warning}
Warning

The **List of Mobile View User Agents** must not be empty when **Cache
Mobile** is set to `ON`.
::: {.admonition .warning}
Warning

Enabling this option will create additional cache varies. If you have
crawling enabled, cache varies cause multiple crawlers to be created.
Please be sure you have adequate server resources for multiple crawlers
before enabling this option. [Learn more about multiple crawlers on our
blog](https://blog.litespeedtech.com/2018/12/05/managing-multiple-cache-crawlers-lscache/).
::: {.admonition .info}
Info

This setting is moved to the **Network Admin** screen when used with a
multisite network
### List of Mobile User Agents[¶](#list-of-mobile-user-agents "Permanent link"){.headerlink}

*disabled/string*

This list should be filled in with a rewrite-rule-friendly list of user
agents. It is used in conjunction with the **Cache Mobile** setting and
will be ignored if **Cache Mobile** set to `OFF`.

::: {.admonition .syntax}
Syntax

Each entry should be separated with a bar, \'\|\'. Any spaces should be
escaped with a backslash before the space, \' \'. The default list
WordPress uses is
`Mobile|Android|Silk/|Kindle|BlackBerry|Opera Mini|Opera Mobi`
### Private Cached URIs[¶](#private-cached-uris "Permanent link"){.headerlink}

*empty string*

A list of path patterns that will be privately cached. These are paths
that should never be publicly cached. To indicate the beginning of a
URI, add `^` to the beginning of the string. To do an exact match, add
`$` to the end of the string.

::: {.admonition .example}
String Matching Examples

Assume you have the following URIs:

1.  `/recipes/baking/`
2.  `/recipes/baking/cakes`
3.  `/recipes/baking/brownies`
4.  `/popular/recipes/baking/`

The string `/recipes/baking/` will match all four URIs.

The string `/recipes/baking/$` will match #1 (because `$` indicates
exact match).

The string `^/recipes/baking` will match #1, #2, and #3 (because `^`
indicates the beginning of the URI).
### Force Cache URIs[¶](#force-cache-uris "Permanent link"){.headerlink}

*empty string*

Paths containing the listed strings *WILL* be cached, regardless of any
\"non-cacheable\" settings that may appear elsewhere. One per line. Each
string will be compared to the `REQUEST_URI` server variable. If there
is a match, the URI will be cached. To indicate the beginning of a URI,
add `^` to the beginning of the string. To do an exact match, add `$` to
the end of the string.

To define a custom TTL for a URI, add a space followed by the TTL value
to the end of the URI. For example, `/mypath/mypage 300` defines a TTL
of 300 seconds for `/mypath/mypage`.

### Force Public Cache URIs[¶](#force-public-cache-uris "Permanent link"){.headerlink}

*empty string*

Paths containing the listed strings *WILL* be cached in public cache,
regardless of any \"non-cacheable\" settings that may appear elsewhere.
One per line. Each string will be compared to the `REQUEST_URI` server
variable. If there is a match, the URI will be cached. To indicate the
beginning of a URI, add `^` to the beginning of the string. To do an
exact match, add `$` to the end of the string.

To define a custom TTL for a URI, add a space followed by the TTL value
to the end of the URI. For example, `/mypath/mypage 300` defines a TTL
of 300 seconds for `/mypath/mypage`.

### Drop Query String[¶](#drop-query-string "Permanent link"){.headerlink}

*empty string*

This setting allows you to specify the query strings that should be
ignored by LSCache.

Some query strings, particularly those that are used for marketing or
analytics purposes, have no effect on the content that is displayed on
the page. The page renders the same with and without these query
strings. As such, it should not be necessary to store multiple copies of
the page in the cache. [Learn
more](../../start/#method-1-apache-style-cachekeymodify).

::: {.admonition .warning}
Warning

The method used is only compatible with LiteSpeed Enterprise v5.2.3+
although one can [manually add rewrite rules for OpenLiteSpeed in
.htaccess](../../start/#method-2-rewrite-rules)
::: {.admonition .info}
Info

This setting is moved to the **Network Admin** screen when used with a
multisite network
## TTL Tab[¶](#ttl-tab "Permanent link"){.headerlink}

[![!LSCWP Cache Section TTL
Tab](../images/cache02.png)](../images/cache02.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

Possible TTL values are `30` seconds or more. Smaller values indicate do
not cache.

### Default Public Cache TTL[¶](#default-public-cache-ttl "Permanent link"){.headerlink}

*604800*

This TTL setting controls most of the pages. All the other TTLs are for
specific pages/types of pages.

The default value amounts to one week. Other possible values are 1 hour
(`3600`), 1 day (`86400`), 2 weeks (`1209600`) etc. Since most of these
pages will not change once posted, a longer TTL may be beneficial.

### Default Private Cache TTL[¶](#default-private-cache-ttl "Permanent link"){.headerlink}

*1800*

This TTL setting determines how long private pages are cached. Possible
values are between `60` and `3600`.

### Default Front Page TTL[¶](#default-front-page-ttl "Permanent link"){.headerlink}

*604800*

This TTL setting controls the front page.

::: {.admonition .note}
Note

This can be triggered by the `is_front_page()` check, or by a third
party plugin that chooses to use the front page TTL for one of its own
pages. For example, WooCommerce does this with its **Shop** page.
### Default Feed TTL[¶](#default-feed-ttl "Permanent link"){.headerlink}

*604800*

This TTL setting controls the feeds. Feeds are a great way for readers
to stay up to date on blog entries. They are generally set up to pull
from the blog in intervals, which, without caching, could cause a
constant load on the server. Cached feed pages are purged on update and
on comment, so they are guaranteed to remain up to date.

### Default REST TTL[¶](#default-rest-ttl "Permanent link"){.headerlink}

*604800*

This TTL setting controls how long calls to the REST API are cached.

### Default HTTP Status Code Page TTL[¶](#default-http-status-code-page-ttl "Permanent link"){.headerlink}

*403 3600 404 3600 500 3600*

This TTL controls the pages that return 404, 403, 500, or whatever
status codes you specify.

The default TTL for each of the default listed status codes is `3600`,
or one hour, though this recommendation might not be right for your
site.

If visiting 404 pages is a common occurrence, it may help to cache the
page for at least a short period.

Pages returning 403 are usually intentional, so it may be worth while to
have a longer TTL for this setting.

500 Errors are a more severe error. Caching this page may mask an issue
within WordPress, so that may not be desired.

You may wish to cache different status code pages for longer, or you may
wish to cache none of them at all.

## Purge Tab[¶](#purge-tab "Permanent link"){.headerlink}

[![!LSCWP Cache Section Purge
Tab](../images/cache03.png)](../images/cache03.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### Purge All on Upgrade[¶](#purge-all-on-upgrade "Permanent link"){.headerlink}

*OFF*

This option selects whether to purge all pages when any plugin, theme,
or the WordPress core is updated. As you never know what may change
across versions, it\'s recommended to leave this on.

::: {.admonition .info}
Info

This setting is moved to the **Network Admin** screen when used with a
multisite network
### Auto Purge Rules For Publish/Update[¶](#auto-purge-rules-for-publishupdate "Permanent link"){.headerlink}

When a post is published or updated, the post page is not the only one
that changes. Category listings, tag listings. the blog's front page,
and a variety of archives may all also change. As such, you can specify
which types of pages will be automatically purged every time a post is
updated or created.

Which of these pages you choose is dependent on your theme and how posts
are displayed on your site.

There is an option for **All pages**, which is disabled by default. When
you enable this, all other checkboxes are ignored. Choosing the **All
pages** option makes sense if you do not have ESI enabled, and you have
dynamic post-related widgets which display on every page, but in most
cases, it is best not to check **All pages**.

To optimize performance, the admin should only check the options that
are necessary. For example, with the historical archive, if the site
only has a monthly archive and does not have a yearly archive or daily
archive, only the "monthly archive" needs to be checked. If the site
does not have archive by author, then there is no need to select it as
extra checks will only slow down the process.

### Serve Stale[¶](#serve-stale "Permanent link"){.headerlink}

*OFF*

When enabled, this setting allows the most recently purged (stale)
cached copy of a page to be served to a visitor if the updated cache
copy is not yet generated.

To understand why you might want to enable Serve Stale, let\'s look at
how LSCache handles purged pages when **Serve Stale** is `OFF`.

- A user visits a page that has been purged from cache
- The request invokes PHP and begins the process of building the page
- 100 more users visit that same page before the PHP process finishes
- PHP is invoked 100 times, causing serious server load
- The first user\'s request completes and the page is newly cached
- Future visitors are served the up-to-date cached page

Now, let\'s see what happens if **Serve Stale** is `ON`:

- A user visits a page that has been purged from cache
- The request invokes PHP and begins the process of building the page
- 100 more users visit that same page before the PHP process finishes
- All 100 users are served the previously purged (stale) version of the
  page with minimal server impact
- The first user\'s request completes and the page is newly cached
- Future visitors are served the up-to-date cached page

#### Should you enable it?[¶](#should-you-enable-it "Permanent link"){.headerlink}

This is an option that benefits very busy sites, but has less of a
positive impact on quiet sites.

Should you enable it? It depends on what is a more acceptable risk for
your site. Weigh the potential for heavy server load (likely with
**Serve Stale** turned `OFF`) against the possiblity of serving stale
content once in a while (likely with **Serve Stale** turned `ON`), and
choose accordingly.

### Scheduled Purge URLs[¶](#scheduled-purge-urls "Permanent link"){.headerlink}

*empty string*

You can specify a list of full URLs (one per line, wildcards supported)
that will be purged automatically at a certain time of day. This is not
necessary under normal circumstances. LSCWP\'s sophisticated purge rules
are able to handle most situations. If, however, you have content that
is generated by an outside source, for example, you might want to purge
the relevant pages every day to be sure the outside content is correctly
displayed.

Unlike many similar fields in LSCWP, this is not a *partial string*
match, it is a *full URI* match, with the domain being optional. You
must enter the full path for each URI, using wildcards, if desired.

::: {.admonition .example}
Example

To include both `/path/u-1.html` and `/path/u-2.html`, you can list them
one per line, or you can use a wildcard (`*`) and simply list
`/path/u-*.html`. Just be aware that the wildcard will also refer to
other URIs, like `/path/u-3.html` and `/path/u-abc.html`, so use
carefully.
::: {.admonition .example}
URI Matching Examples

Assume your site consists of only the following URIs:

1.  `https://example.com/recipes/baking/`
2.  `https://example.com/recipes/baking/cakes`
3.  `https://example.com/recipes/baking/brownies`
4.  `https://example.com/popular/recipes/baking/`

List `https://example.com/recipes/baking/` to match only #1.

List `/recipes/baking/` to also match only #1.

List `/recipes/baking/*` to match #1, #2, and #3.

List `*/recipes/baking/` to match #1 and #4.

List `*/recipes/*` to match all of them.

List `/recipes/` to match none of them.
::: {.admonition .warning}
Warning

For URLs with wildcards, there may be a delay in initiating scheduled
purge. LSCache has no way of knowing which URLs match the pattern until
the first time those URLs are purged, or until they reach their natural
expiration. If you would like to speed this process along, you can enter
the wildcard string in **Scheduled Purge URLs**, press the **Save
Settings** button, and then manually purge the relevant pages so that
LSCache is aware of the URLs right away.
### Scheduled Purge Time[¶](#scheduled-purge-time "Permanent link"){.headerlink}

Use this field in conjunction with the one above. If you\'ve provided a
list of URLs to purge, specify the time they should be purged here.

### Purge All Hooks[¶](#purge-all-hooks "Permanent link"){.headerlink}

*a list of recommended hooks*

LSCWP executes a \"Purge All\" action on the cache when certain
WordPress hooks are run. You can change the purge behavior for your
LSCWP installation by changing these hooks. For example, if you don\'t
want to purge the cache every time you create a new tag or category,
remove the `create_term` hook from the list. Or, if you do want to purge
the cache every time a comment is posted on your site, you could add the
`comment_post` hook.

LiteSpeed recommends you Purge All when the following hooks are run:

::: highlight
        switch_theme
        wp_create_nav_menu
        wp_update_nav_menu
        wp_delete_nav_menu
        create_term
        edit_terms
        delete_term
        add_link
        edit_link
        delete_link
See [the WordPress Code
Reference](https://developer.wordpress.org/reference/hooks/) for a list
of available hooks. Many plugins also have their own hooks that you can
reference, as well.

## Excludes Tab[¶](#excludes-tab "Permanent link"){.headerlink}

[![!LSCWP Cache Section Excludes
Tab](../images/cache04.png)](../images/cache04.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### Do Not Cache URIs[¶](#do-not-cache-uris "Permanent link"){.headerlink}

*empty string*

By default, LiteSpeed caches as many pages as possible. If you have some
pages that should *not* be cached for whatever reason, you can list the
URIs here, one per line. Partial URIs are fine. They will be compared to
the `REQUEST_URI` server variable, and excluded from cache if a match is
found.

When listing URIs, it\'s best to include as much of the string as
possible, so that you don\'t inadvertantly match more URIs to the string
than intended. You can also narrow it down further with the special
characters `^` and `$`. To indicate the beginning of a URI, add `^` to
the beginning of the string. To do an exact match, add `$` to the end of
the string.

::: {.admonition .example}
String Matching Examples

Assume your site consists of only the following URIs:

1.  `/recipes/baking/`
2.  `/recipes/baking/cakes`
3.  `/recipes/baking/brownies`
4.  `/popular/recipes/baking/`

The string `/recipes/baking/` will match all four URIs.

The string `cakes` will only match #2.

The string `/recipes/baking/$` will only match #1 (because `$` indicates
exact match).

The string `^/recipes/baking` will match #1, #2, and #3 (because `^`
indicates the beginning of the URI).
### Do Not Cache Query Strings[¶](#do-not-cache-query-strings "Permanent link"){.headerlink}

*empty string*

You can eliminate URLs with certain query strings from being cached.

::: {.admonition .example}
Example

Let\'s say you have a site design that lets you change the color scheme
with a query string. For a purple color scheme, the URL would look like
`http://example.com/page?color=purple`. If you don\'t want to cache any
page that is rendered in a different color scheme, you would add `color`
to the **Do Not Cache Query Strings** list. This would exclude any page
with the `?color=` query string. Note that the actual value of `color`
is irrelevant. The key (`color`) is matched, while the value could be
anything from `purple` to `green` to
`vermillion-and-aquamarine-polka-dots`.
### Do Not Cache Categories[¶](#do-not-cache-categories "Permanent link"){.headerlink}

*empty string*

By default all categories are cached. If you have categories that you
wish to exclude from the cache, enter a list of the category slugs (one
per line) in this box.

::: {.admonition .example}
Example

To exclude `http://www.example.com/category/category-slug/`, insert
`category-slug`.
::: {.admonition .note}
Note

If the category slug is not found, the category will be removed from the
list on save.
### Do Not Cache Tags[¶](#do-not-cache-tags "Permanent link"){.headerlink}

*empty string*

Tags are treated the same way as categories: cached by default, but
ignored if entered by slug (one per line) in this box.

### Do Not Cache Cookies[¶](#do-not-cache-cookies "Permanent link"){.headerlink}

*empty string*

This is a list of cookies that should not be cached. Specifically, do
not cache any *page* where a cookie in this list appears in the request
headers.

::: {.admonition .tip}
Tip

This option can be more far-reaching than you may realize. If you
exclude a cookie that exists on every page of your site, then you are
essentially excluding your entire site from being cached.
::: {.admonition .info}
Info

This setting is moved to the **Network Admin** screen when used with a
multisite network
### Do Not Cache User Agents[¶](#do-not-cache-user-agents "Permanent link"){.headerlink}

*empty string*

Specific user agents may also be excluded from cache. This means that if
a visitor requests a page from your site via one of the listed user
agents, they will not be served from the cache. You can enter user
agents by name in this box, one per line.

::: {.admonition .note}
Note

Partial matches are allowed.
::: {.admonition .info}
Info

This setting is moved to the **Network Admin** screen when used with a
multisite network
### Do Not Cache Roles[¶](#do-not-cache-roles "Permanent link"){.headerlink}

*unchecked*

There may be user roles that you wish to exclude from caching. For
example, if you are an admin, testing new functionality, you may want to
exclude your `administrator` role from being served from cache until
your testing is through.

### Verify a Page is Not Being Cached[¶](#verify-a-page-is-not-being-cached "Permanent link"){.headerlink}

If you have configured LSCache to exclude certain content, you can use
this method to verify that it works as expected:

1.  From a non-logged-in browser, navigate to the page, open the
    **Network** tab in the developer tools, refresh the page, and click
    the first listed resource. This should be the URI of the page, as
    described above.
2.  Look for the `X-LiteSpeed-Cache-Control: no-cache` header. If you
    find it, then the page has successfully *not* been served via
    LSCache.

It\'s also a good idea to make sure that the browser is not caching the
page. For that to be true, you need to look for two headings: -
`cache-control: no-cache, must-revalidate, max-age=0` -
`expires: Wed, 11 Jan 1984 05:00:00 GMT`

::: {.admonition .tip}
Tip

The date in the `expires` header can be any date that is *prior to* the
current date.
If either of those headers is not present, or has a different value, the
browser is likely caching your page. This can lead to serving outdated
or stale content. Typically, browser caching is accidentally enabled via
bad optimization rules that add the cache control header to dynamic
requests. Check your `.htaccess` file to fix this.

## ESI Tab[¶](#esi-tab "Permanent link"){.headerlink}

[LiteSpeed Cache for
WordPress](https://wordpress.org/plugins/litespeed-cache/) supports
[Edge Side Includes](https://en.wikipedia.org/wiki/Edge_Side_Includes),
also known as ESI.

::: {.admonition .warning}
Warning

OpenLiteSpeed does not support ESI functionality. You will need
[LiteSpeed Web Server
Enterprise](https://www.litespeedtech.com/products/litespeed-web-server),
[LiteSpeed Web
ADC](https://www.litespeedtech.com/products/litespeed-web-adc), or
[QUIC.cloud CDN](https://QUIC.cloud) in order to use ESI and any of the
functionality in this tab.
With ESI, pages may be served from cache for logged-in users.

ESI allows you to designate parts of your dynamic page as separate
fragments that are then assembled together to make the whole page. In
other words, ESI lets you "punch holes" in a page, and then fill those
holes with content that may be cached privately, cached publicly with
its own TTL, or not cached at all.

::: {.admonition .note}
Note

ESI doesn\'t come without a cost. It is much simpler for the server to
return full pages than it is for it to piece together pages from several
different blocks, and so this must be a factor in your decision to
enable ESI. Will the speed benefits outweigh the efficiency hit?
There\'s no easy answer. It depends on your site.
::: {.admonition .help}
Video

See a video demonstration of **What is Edge Side Includes (ESI)?**
[here](https://www.youtube.com/watch?v=uYpR6D8n3oE).
[![!LSCWP Cache Section ESI
Tab](../images/cache05.png)](../images/cache05.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### Public Cache vs. Private Cache[¶](#public-cache-vs-private-cache "Permanent link"){.headerlink} {#public-cache-vs-private-cache}

LiteSpeed Cache has built-in public and private caches. In the public
cache you will find pages that are exactly the same for everyone.
Private caches contain content that pertains only to a specific user
specified by his/her IP address and session ID.

ESI allows you to disassemble a full page and treat the pieces
differently from each other.

LiteSpeed Web Server allows you to store content in either the public
cache or a private cache.

Combine these two elements and you get something very powerful. You get
a system that can break apart a page into public and private pieces,
cache each piece appropriately, and then re-compose the full-page
content from the relevant caches and serve it to a user without ever
hitting the PHP backend.

**This combination allows you to cache content for logged-in WordPress
users.** With ESI enabled you can cache a full page, punch holes in it
for the private content, and save that content in the private cache.

### Examples[¶](#examples "Permanent link"){.headerlink}

::: {.admonition .example}
Example #1: Admin Bar

A logged-in site admin visits the publicly-cached home page:

**Without ESI**: the request hits the backend, because the admin bar at
the top of the page is private content, and as such this page (and every
other page on the site, for that matter) cannot be served to the admin
from cache.

**With ESI**: most of the this page is served from the public cache,
while the admin bar is served from the site admin\'s private cache.
There is no need to invoke PHP.
::: {.admonition .example}
Example #2: Recent Posts Widget

A large site with much static content that rarely changes includes a
\"Recent Posts\" sidebar widget on each page.

**Without ESI**: Every time a new post is published, every single page
in the site must be purged so that the widget displays up-to-date data.
Re-populating the entire cache requires a crawler to run, or visitors to
hit all of the pages of the site.

**With ESI**: All of the pages in the site can remain cached with a long
TTL, while the Recent Posts widget is the only thing that needs to be
purged. Re-populating the widget in the cache requires just one visitor
to request any page one time.
### Enabling at the Server Level[¶](#enabling-at-the-server-level "Permanent link"){.headerlink}

Cache and ESI must be enabled on the web server before you can use it.
In a shared hosting environment, your system admin will control if a
specific virtual host account has `CacheEngine on/off; esi on/off`.
Consult your system admin to see if ESI has been enabled for your
domain.

If you are the system admin, see [Enabling Cache for an Individual
Virtual Host](../../start/#enable-caching) for further instruction.

### Enabling at the Plugin Level[¶](#enabling-at-the-plugin-level "Permanent link"){.headerlink}

LiteSpeed Cache for WordPress considers all cacheable full pages to be
publicly-cached.

When you enable ESI, you allow holes to be punched for content that will
either be privately-cached, publicly-cached with its own TTL, or not
cached at all.

Once enabled, the following ESI blocks are created by default:

- Admin Bar
- Comments
- Comment form
- Recent Posts widget
- Recent Comments widget

Any widget can be an ESI block if you want it to be.

Navigate to **LiteSpeed Cache \> Cache \> ESI**. Set **Enable ESI** to
`ON`.

This creates the ESI blocks listed above. You can disable caching for
the Admin Bar and the Comments form (they\'re `ON` be default) via the
**Cache Admin Bar** and **Cache Comment Form** settings. ESI caching for
widgets is handled in the individual widget settings under **Appearance
\> Widgets**.

::: {.admonition .tip}
Tip

If you are using CloudFlare, do not enable Automatic Platform
Optimization (APO). Remember, when using LSCWP with other optimization
solutions, you [must not duplicate
functions](../admin/#using-multiple-optimization-plugins). APO is a page
cache, so it must be turned off in order for this LiteSpeed Cache
feature to work correctly.
### Widget ESI Blocks[¶](#widget-esi-blocks "Permanent link"){.headerlink}

::: {.admonition .warning}
Warning

ESI widgets do not work with WordPress v5.8 and above. This is a known
issue and will be addressed in a future LSCWP version. Until then, if
you need ESI widgets, you can install and activate [the Classic Widgets
plugin](https://wordpress.org/plugins/classic-widgets/), and access ESI
widget functionality that way. We apologize for any inconvenience.
[![!](../images/cache06.jpg)](../images/cache06.jpg){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

Navigate to **WP Admin \> Appearance \> Widgets** and select the widget
that you want to turn into an ESI block.

By default a widget is not considered an ESI block (unless it is
**Recent Posts** or **Recent Comments**, as mentioned above). If you
want the widget to be treated differently than the pages on which it
appears, set one of the following configurations in the shaded
\"LightSpeed Cache\" area:

#### Private widget[¶](#private-widget "Permanent link"){.headerlink}

The contents will be stored in private cache, different copies for each
user by IP/session ID. (Examples: a list of recently-viewed posts, or a
personalized greeting.)

- Set **Enable ESI** to `Private`.
- Set **Widget Cache TTL** to a value appropriate for the contents of
  the widget.

#### Public widget[¶](#public-widget "Permanent link"){.headerlink}

The contents will be stored in public cache, with each user seeing the
exact same thing. (Examples: a list of recent posts, or a calendar of
upcoming events).

- Set **Enable ESI** to `Public`.
- Set **Widget Cache TTL** to a value appropriate for the contents of
  the widget.

#### Uncached widget[¶](#uncached-widget "Permanent link"){.headerlink}

The contents will not be cached at all, and will dynamically-generated
each time they are displayed on a page.

- Set **Enable ESI** to either `Public` or `Private` (it makes no
  difference, as long as it\'s not `Disable`)
- Set **Widget Cache TTL** to `0`.

### Third Party Plugins[¶](#third-party-plugins "Permanent link"){.headerlink}

Our ESI implementation supports a few other blocks that belong to
third-party plugins. For instance, the WooCommerce shopping cart is
considered a private ESI block.

As we mentioned earlier, with ESI enabled, your site pages are now
considered publicly-cacheable, because we are able to punch holes for
the occasional non-public content. This is true for all native WordPress
pages, and for all WooCommerce pages. It is not, however, true with
bbPress.

A bbPress page contains so many areas of private data, that it\'s
actually much more efficient to consider the entire page to be private.
So, that\'s what we\'ve done. All bbPress pages are considered private.

If one of your favorite plugins warrants special consideration, please
get in touch with us via [the WordPress plugin support
forum](https://wordpress.org/support/plugin/litespeed-cache) and let us
know.

### ESI Nonces[¶](#esi-nonces "Permanent link"){.headerlink}

*empty string*

List nonces one-per-line that should automatically be converted to ESI
blocks. Wildcards are supported.

Nonces often expire before the site TTL, and this can be problematic for
the pages that use them. By converting a nonce to an ESI block, you
allow the nonce to expire independently of the rest of the page, without
causing any cache conflicts.

LiteSpeed maintains a list of known third party plugin nonces
[here](https://github.com/litespeedtech/lscache_wp/blob/master/data/esi.nonces.txt).
This list will automatically be merged with the nonces you list in the
**ESI Nonces** setting. This functionality allows you to easily convert
every nonce we know about into ESI blocks without even thinking about
it.

If you have a nonce that you would like to be included in this list,
please [submit a Pull
Request](https://github.com/litespeedtech/lscache_wp/blob/master/data/esi.nonces.txt).

### Vary Groups[¶](#vary-groups "Permanent link"){.headerlink}

::: {.admonition .note}
Note

Despite its location on the ESI settings tab, the Vary Groups function
is not actually related to ESI.
Vary Group functionality combines the concepts of [cache
varies](../../devguide/advanced/#cache-varies) and [user
roles](https://wordpress.org/support/article/roles-and-capabilities/):
with Vary Groups you can have multiple publicly-cached versions of a
single page, based on the permissions of the users who view the page.

(Your list of user roles may vary from those in the image above. That\'s
normal.)

Vary Groups do not change the behavior of your application. They simply
allow separate cached copies to be saved for each public view that is
*already being generated by your app*. Without Vary Groups, apps that
generate different views for different user roles would need to leave
logged-in users uncached, or serve to them from private cache.

Learn more about Vary Groups [on our
blog](https://blog.litespeedtech.com/2017/09/13/wpw-vary-groups/).

::: {.admonition .example .example .1}
Example

In some themes administrator functions will appear right on the public
pages (like an "edit" link at the end of a post). If you create a vary
group for administrators, then LSCache will save two public copies of
the page: one with all of the editing permissions displayed on it for
anyone in the administrator group, and the default copy of the page
without the editing links for everyone else.
::: {.admonition .example .example .2}
Example

A shop has two user roles: `retail_customer` and `wholesale_customer`.
There are two sets of prices, and three different ways that the site can
be viewed: users in the group `retail_customers` will see the highest
prices. Users in the group `wholesale_customers` will see the lowest
prices. Users who are not yet customers will see the default page with
no pricing whatsoever. This scenario would require two Vary Groups: one
for `retail_customer` and one for `wholesale_customer`.
To create a vary group for any user role shown, enter a non-zero value
into the box next to that user role. If a user role has a `0` next to
it, then it will be served the default cached copy.

There is no significance to the numbers other than the fact that unique
views should have unique numbers.

If two user roles share the same view, put them in the same group by
giving them the same number.

::: {.admonition .warning}
Warning

Enabling this option will create additional cache varies. If you have
crawling enabled, cache varies cause multiple crawlers to be created.
Please be sure you have adequate server resources for multiple crawlers
before enabling this option. [Learn more about multiple crawlers on our
blog](https://blog.litespeedtech.com/2018/12/05/managing-multiple-cache-crawlers-lscache/).
## Object Tab[¶](#object-tab "Permanent link"){.headerlink}

[![!LSCWP Cache Section Object
Tab](../images/cache07.png)](../images/cache07.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

::: {.admonition .info}
Info

This tab is moved to the Network Admin screen when used with a multisite
network
LSCWP doesn\'t provide object caching directly. Rather, it supports your
use of an external object cache such
as [Memcached](http://memcached.org/about) or LiteSpeed\'s drop-in
Memcached
replacement, [LSMCD](https://www.litespeedtech.com/open-source/litespeed-memcached).
For more information about setting up your Object Cache, please see [How
to set up Object Cache
support](/lscache/lscwp/admin/#how-to-set-up-object-cache-support).

### Object Cache[¶](#object-cache "Permanent link"){.headerlink}

*OFF*

Object Cache is disabled by default. Select `ON` to enable it and then
configure it via the settings described below.

::: {.admonition .help}
Video

To see a demonstration of one possible object cache configuration,
please see [\# How to set up Redis with LiteSpeed
Cache](https://youtu.be/8-xtdnpCLHQ).
### Status[¶](#status "Permanent link"){.headerlink}

An informational area to let you know the status of your external object
cache. If you are getting errors here, please see [How to Debug your
Object Cache
Setup](https://www.litespeedtech.com/support/wiki/doku.php/litespeed_wiki:cache:lscwp:configuration:cache:object_cache#how_to_debug).

### Method[¶](#method "Permanent link"){.headerlink}

*Memcached*

If your object cache is Memcached or LSMCD, set **Method** to
`Memcached`. If your object cache is Redis, set **Method** to `Redis`.

:::: {.admonition .warning}
LSMCD Warning

If you are using LiteSpeed Memcached with
[SASL](../../../products/lsmcd/configuration/sasl/), please be aware
there is a known issue which sometimes results in a fatal error like
this:

::: highlight
    PHP Fatal error: Uncaught Error: Cannot use object of type stdClass as array in /home/domainname/public_html/wp-includes/meta.php:588
There are two ways to avoid this issue.

1.  Disable SASL if possible, or
2.  Add `posts` and `post_meta` to the **Do Not Cache Groups** setting.
    (If that doesn\'t work, there may be other problematic groups you
    will have to add. Try `users` and `user_meta`.)

Redis users will not encounter this issue.
### Host[¶](#host "Permanent link"){.headerlink}

*localhost*

The hostname or IP address used by your Memcached or LSMCD object cache.
The default setting should work fine for you, if your Memcached is set
up via a TCP connection. If you are using a UNIX socket, **Host** should
be set to `/path/to/memcached.sock`. (Substitute the actual path used
for your installation.)

::: {.admonition .tip}
Tip

It is common and more efficient to use a socket for object cache.
### Port[¶](#port "Permanent link"){.headerlink}

*11211*

The port number used by your object cache. The default setting should
work fine for you, if your Memcached is set up via a TCP connection. If
you are using a UNIX socket, **Port** should be set to `0`.

### Default Object Lifetime[¶](#default-object-lifetime "Permanent link"){.headerlink}

*360*

The TTL for items stored in the object cache. We recommend using a
relatively short time in order to avoid stale results.

### Username[¶](#username "Permanent link"){.headerlink}

Only available when SASL is installed and the object caching method is
Memcached.

### Password[¶](#password "Permanent link"){.headerlink}

Specify the password used when connecting.

### Redis Database ID[¶](#redis-database-id "Permanent link"){.headerlink}

Database to be used. This field is only used when the object caching
method is Redis. If using Memcached, please ignore this field.

### Global Groups[¶](#global-groups "Permanent link"){.headerlink}

*users userlogins usermeta user_meta site-transient site-options
site-lookup blog-lookup blog-details rss global-posts blog-id-cache*

A list of groups that should be cached at the network level.

### Do Not Cache Groups[¶](#do-not-cache-groups "Permanent link"){.headerlink}

*comment counts plugins*

A list of groups that should not be included in object cache.

### Persistent Connection[¶](#persistent-connection "Permanent link"){.headerlink}

*ON*

If enabled, the connection is kept alive in order to make Memcached
faster.

::: {.admonition .tip}
Tip

To ensure consistent results, be sure that the value of this setting
matches that of `memcached.sess_persistent` in PHP ini. If one of these
settings is disabled while the other is enabled, it can result in
intermittent connection test failures.
### Cache WP Admin[¶](#cache-wp-admin "Permanent link"){.headerlink}

*ON*

If enabled, WordPress admin will be sped up, but at the risk of
occasionally retrieving stale data from the object cache.

### Store Transients[¶](#store-transients "Permanent link"){.headerlink}

This setting is deprecated as of v7.8. Transients now always use Object
Cache, when available, to prevent potential database bloat from
uncleared expired transients.

## Browser Tab[¶](#browser-tab "Permanent link"){.headerlink}

LiteSpeed Cache is a full-page cache. It takes expensive-to-generate
dynamic content and stores it as easy-to-serve static files. While it
handles dynamically-generated content well, it *only handles
dynamically-generated content*. Static content such as images, video, or
fonts is not included in any full-page cache. And yet, this content may
be requested from the server repeatedly. Take, for instance, your
site\'s logo. That image is likely to be displayed on every page that
the user visits, which means the server has to repeatedly transfer that
same image to that same user.

This is where browser caching comes in handy. With browser caching
enabled, your logo (along with other static content) is stored locally
on the user\'s device the first time it is requested. After that, the
content is pulled from their local storage until the browser cache
expires. Displaying a local image will always use fewer resources than
transferring an image across the internet, no matter how fast your
connection may be.

[![!LSCWP Cache Section Browser
Tab](../images/cache08.png)](../images/cache08.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### How to Set it Up[¶](#how-to-set-it-up "Permanent link"){.headerlink}

Normally, browser caching is enabled at the server level. However, if
you do not have access to your server\'s admin, you can still enable
browser caching through the LiteSpeed Cache for WordPress plugin\'s
settings. You can choose to set this up at whichever level makes the
most sense for your site(s). If either level is turned on, then browser
caching will be enabled.

#### At the Plugin Level[¶](#at-the-plugin-level "Permanent link"){.headerlink}

::: {.admonition .info}
Info

This tab is moved to the Network Admin screen when used with a multisite
network
##### Browser Cache[¶](#browser-cache "Permanent link"){.headerlink}

*OFF*

When Browser Cache is enabled, static files (such as images, css, and
videos) are stored locally on the user\'s device to make subsequent
retrieval much faster.

##### Browser Cache TTL[¶](#browser-cache-ttl "Permanent link"){.headerlink}

*31557600*

The amount of time, in seconds, that files will be stored in the browser
cache before expiring. Minimum is `30` seconds. Recommended value is
`31557600` (which is one year).

#### At the Server Level[¶](#at-the-server-level "Permanent link"){.headerlink}

If you are a server admin, you have somewhat more control. In the
LiteSpeed Web Server Admin, navigate to **Server \> General** and scroll
down to **Expires Settings**.

[![!](../images/cache09.png)](../images/cache09.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

Set **Enable Expires** to `Yes`.

**Expires Default** may be set to a number of seconds or left blank if
you don\'t wish to provide a catch-all expiration.

::: {.admonition .note}
Note

Be careful with this setting. It applies to *all* types of content, even
HTML. This causes potential conflicts with LSCache, and can result in
stale content being served to the user. *If you are running LSCache,
always leave **Expires Default** unset.*
Set **Expires by Type** to a string similar to the example above,
changing any file types or expiration times as desired. The example
enables browser caching for all images, css, and javascript, and it sets
all of their expirations to `604800` seconds (or one week). If you leave
**Expires Default** blank (as you should, if you\'re using LSCache),
then you must specifically include every file type you want cached by
the browser in **Expires by Type**

## Advanced Tab[¶](#advanced-tab "Permanent link"){.headerlink}

[![!LSCWP Cache Section Advanced
Tab](../images/cache10.png)](../images/cache10.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### AJAX Cache TTL[¶](#ajax-cache-ttl "Permanent link"){.headerlink}

*empty list*

Specify an AJAX action in POST/GET and the number of seconds to cache
that request, separated by a space. One action-TTL pair per line.

::: {.admonition .example}
Example

To cache an AJAX action called `getads` for 30 seconds, add `getads 30`
to the list.
### Login Cookie[¶](#login-cookie "Permanent link"){.headerlink}

*empty string*

This option should be used to configure a unique login cookie if
[multiple web
applications](../../troubleshoot/#logged-in-cookie-conflicts) with an
LSCache plugin are used in a single virtual host.

::: {.admonition .example}
Example

An example login cookie is `_wp_login_1`
::: {.admonition .info}
Info

This setting is moved to the **Network Admin** screen when used with a
multisite network
### Vary Cookies[¶](#vary-cookies "Permanent link"){.headerlink}

*empty*

This option is for you if you are using a third party plugin that uses
cookies to change the content displayed on a page.

Enter one cookie per line. Cookies are case-sensitive, may not include
spaces, and must consist of alphanumeric characters or `_`.

::: {.admonition .example}
Example

A membership plugin shows one set of shop prices to members and a
different set of prices to non-members. This means that there are two
versions of each page, based on the value of the plugin\'s `_member`
cookie. In order to store both of those versions in the cache, you will
want LSCache to create a \"vary\" based on the `_member` cookie.

Enter `_member` in the **Vary Cookies** box on a line by itself.
Learn more about [Cache
Varies](/lscache/devguide/advanced/#cache-varies).

### Improve HTTP/HTTPS Compatibility[¶](#improve-httphttps-compatibility "Permanent link"){.headerlink}

*OFF*

When a site uses both HTTP and HTTPS, conflicts with the login cookie
may occur. Cookies are based on domain name, regardless of protocol,
however an HTTP connection can\'t read a cookie that was saved with
HTTPS. And so, if a user logs in with HTTPS and then connects with HTTP,
the user will be treated as a guest, and *not* as a logged-in user.

When you enable this option, the login cookie is saved as an HTTP cookie
at all times, regardless of the protocol used to access the page. This
ensures that the login cookie is always accessible to both HTTP and
HTTPS connections.

### Instant Click[¶](#instant-click "Permanent link"){.headerlink}

*OFF*

It takes time for a user to click a link. First they hover over it, then
they depress the mouse button, and *then*, only after the button is
released, is the link considered \"clicked\" and the new page loaded.
With Instant Click enabled, the page begins to load as soon as the user
hovers over the link. By the time the mouse button is released, enough
of the page has been loaded that the display can seem almost instant.

Be aware, though, that this function will generate extra requests to the
server, if your visitors do a lot of link hovering without clicking. As
such, it has the potential to impact server load.

## WooCommerce Tab[¶](#woocommerce-tab "Permanent link"){.headerlink}

If you don\'t see this tab on your LSCWP Settings page, then you don\'t
have WooCommerce installed and activated.

::: {.admonition .note}
Note

It is *highly recommended* that you [enable
ESI](https://www.litespeedtech.com/support/wiki/doku.php/litespeed_wiki/cache/lscwp/configuration/esi)
while using WooCommerce. ESI allows flexible caching of mixed public and
private data in an ecommerce environment.
::: {.admonition .note}
Note

By default, the **My Account**, **Checkout**, and **Cart** pages are
automatically excluded from caching. Misconfiguration of page
associations in WooCommerce settings may cause some pages to be
erroneously classified as cacheable or non-cacheable. You can verify
that a page is being cached or not by following [these
directions](../installation/#verify-your-site-is-being-cached)
[![!LSCWP Cache Section WooCommerce
Tab](../images/cache11.png)](../images/cache11.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### Product Update Interval[¶](#product-update-interval "Permanent link"){.headerlink}

*Purge product on changes to the quantity or stock status. Purge
categories only when stock status changes.*

Use this area to specify how aggressively you wish to purge the cache
when a product\'s stock status or quantity in stock has been updated.
Which should you choose? It depends on your store\'s configuration and
theme.

- If you don\'t use quantity or stock status in any meaningful way, then
  it\'s safe to do a minimal amount of caching tied to stock events.
- If you display stock quantities on your product pages and your
  category pages, you\'ll want to purge both pages any time a stock
  event occurs.

### Vary for Mini Cart[¶](#vary-for-mini-cart "Permanent link"){.headerlink}

*OFF*

Enable this option to generate a separate cache copy for the mini cart
when the cart is not empty.

Most themes use JavaScript to update the mini cart, which means cache is
not an issue. However, if your theme does not use JS to update the mini
cart, then the cart contents will be cached. Enable this option to
create a cache vary so that the correct cart contents are always
displayed.

::: {.admonition .note}
Note

This setting will automatically update the .htaccess file.
