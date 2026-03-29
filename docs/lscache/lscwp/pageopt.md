# Page Optimization[¶](#page-optimization "Permanent link"){.headerlink}

::: {.admonition .warning}
Warning

Please test these options thoroughly before enabling them on your
production site! Be sure to **Purge All** after changing these settings.
## CSS Settings Tab[¶](#css-settings-tab "Permanent link"){.headerlink}

[![!LSCWP Page Optimization Section CSS Settings
Tab](../images/pageopt01.png)](../images/pageopt01.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### CSS Minify[¶](#css-minify "Permanent link"){.headerlink}

*OFF*

Extra white space characters, new line characters, and comments will be
stripped from all included CSS files, if this option is enabled.

### CSS Combine[¶](#css-combine "Permanent link"){.headerlink}

*OFF*

All individual CSS files will be combined into a single CSS file.

::: {.admonition .tip}
Tip

If you notice your disk space filling up fast after enabling **CSS
Combine**, it may be due to your theme inserting a random string into
its CSS. Please [read this](../ts-optimize/#disk-space-filling-fast) for
more details.
#### Generate UCSS[¶](#generate-ucss "Permanent link"){.headerlink}

*OFF*

**Unique CSS** (UCSS) is a QUIC.cloud service that can be used along
with the **CSS Combine** setting, to create a single streamlined CSS
file for each page of your site. This combined file will potentially be
unique for each page, because it will only include the CSS that is
needed to render that specific page.

Benefit: By only including necessary CSS, the size for each combined CSS
file remains small and processing time may be significantly reduced.

::: {.admonition .warning}
Warning

On sites with a large number of different pages, the storage of these
unique combined CSS files may become problematic. Be sure that you have
enough space to store *at least* one CSS file for each page on the site.
You\'ll need space for two CSS files for each page, if you have **Cache
Mobile** enabled, and you\'ll need space for even more if your site uses
cache varies that impact what is displayed on the screen.
::: {.admonition .note}
Note

The first time that a page results in a 404 error, UCSS will be
generated. That same UCSS file will be used for all future 404 errors,
even when the error occurs on different pages. If multiple 404 pages are
added to the queue, all but the first will be skipped by the QUIC.cloud
processor.
::: {.admonition .tip}
Tip

It is possible to set UCSS to generate by post type instead of per URL,
but you\'ll need to be comfortable using an API filter. Please see
[Generate Single UCSS for Page
Type](../api/#generate-single-ucss-for-page-type) for more information.
::: {.admonition .tip}
Tip

You may have noticed the small QUIC.cloud logo in this area. If you\'re
having a problem with your QUIC.cloud services, try clicking the symbol
to redetect your closest available service node(s). If your previously
available node has gone down, a new node will be selected, and services
can continue.
A **Run UCSS Queue Manually** button will appear when there are queued
URLs waiting. The queued URLs may be either gray, indicating they have
not yet been submitted to QUIC.cloud, or green, indicating that they
have already been submitted for processing.

#### UCSS Inline[¶](#ucss-inline "Permanent link"){.headerlink}

*OFF*

Enable this setting to store generated UCSS inline with the HTML, rather
than as its own separate CSS file. This reduces extra CSS file loading.

::: {.admonition .note}
Note

This option is *not* automatically enabled for **Guest Mode** pages. If
you\'d like to use **UCSS Inline** with **Guest Mode** you must
explicitly set it to `ON` here.
::: {.admonition .note}
Note

If **UCSS Inline** is enabled, the **Load CSS Asynchronously** setting
is ignored and assumed to be `OFF`.
### CSS Combine External and Inline[¶](#css-combine-external-and-inline "Permanent link"){.headerlink}

*OFF*

By default, the **CSS Combine** option only combines local CSS files.
With this option turned on, external CSS files, and CSS that is found
inline with the HTML will also be included in the combined file.
Including all possible CSS in this manner, helps to maintain the
priorities of CSS, which should minimize potential errors caused by
**CSS Combine**.

### CSS HTTP/2 Push[¶](#css-http2-push "Permanent link"){.headerlink}

This setting has been deprecated as of v4.4.3.

### Load CSS Asynchronously[¶](#load-css-asynchronously "Permanent link"){.headerlink}

*OFF*

This option defaults to `OFF`. When it is OFF, web pages load the normal
way, where the browser loads the CSS from the HTML header before
continuing on to display the content in the HTML body.

When you turn this option `ON`, CSS and HTML will be loaded at the same
time. Pages can load more quickly by processing CSS and HTML
asynchronously, but they may initially load without formatting. To avoid
that problem, LiteSpeed automatically generates Critical CSS when **Load
CSS Asynchronously** is enabled.

Critical CSS is the collection of styles that are required in order to
properly display above-the-fold content. These styles are inserted
inline into the HTML code and are processed with the HTML, eliminating
the problem of unformatted content.

::: {.admonition .important}
Important

**Load CSS Asynchronously** uses the QUIC.cloud **Page Optimization**
service to generate Critical CSS. [Enabling QUIC.cloud
services](../general/#online-services-tab) is required for use, and [a
fee may apply](https://quic.cloud/online-services-costs/). If you choose
not to use QUIC.cloud services, CSS will *not* be loaded asynchronously,
regardless of whether this setting is turned `ON` or `OFF`.
::: {.admonition .tip}
Tip

You may have noticed the small QUIC.cloud logo in this area. If you\'re
having a problem with your QUIC.cloud services, try clicking the symbol
to redetect your closest available service node(s). If your previously
available node has gone down, a new node will be selected, and services
can continue.
A **Run CCSS Queue Manually** button will appear when there are queued
URLs waiting. The queued URLs may be either gray, indicating they have
not yet been submitted to QUIC.cloud, or green, indicating that they
have already been submitted for processing.

#### CSS Per URL[¶](#css-per-url "Permanent link"){.headerlink}

*ON*

Set this option to `OFF` to generate Critical CSS per Post Type instead
of per individual page. This can save significant CCSS quota and disk
space, however it may result in incorrect CSS styling if your site uses
a page builder.

#### Inline CSS Async Lib[¶](#inline-css-async-lib "Permanent link"){.headerlink}

*ON*

This will inline the asynchronous CSS library to avoid render blocking.

### Font Display Optimization[¶](#font-display-optimization "Permanent link"){.headerlink}

*Default*

Set this to append `font-display` to all `@font-face` rules before
caching CSS to specify how fonts should be displayed while being
downloaded.

## JS Settings Tab[¶](#js-settings-tab "Permanent link"){.headerlink}

[![!LSCWP Page Optimization Section JS Settings
Tab](../images/pageopt02.png)](../images/pageopt02.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### JS Minify[¶](#js-minify "Permanent link"){.headerlink}

*OFF*

Extra white space characters, new line characters, and comments will be
stripped from all JS, if this option is enabled.

### JS Combine[¶](#js-combine "Permanent link"){.headerlink}

*OFF*

All individual JS files will be combined into a single JS file.

::: {.admonition .tip}
Tip

If you notice your disk space filling up fast after enabling **JS
Combine**, it may be due to your theme inserting a random string into
its JavaScript code. Please [read
this](../ts-optimize/#disk-space-filling-fast) for more details.
### JS Combine External and Inline[¶](#js-combine-external-and-inline "Permanent link"){.headerlink}

*OFF*

Turn this option `ON` to include external JavaScript and inline
JavaScript in the combined file, when **JS Combine** is also enabled.
This option helps maintain the priorities of JS execution, which should
minimize potential errors caused by JS Combine.

### JS HTTP/2 Push[¶](#js-http2-push "Permanent link"){.headerlink}

This setting has been deprecated as of v4.4.3.

### Load JS Deferred[¶](#load-js-deferred "Permanent link"){.headerlink}

*OFF*

Both the `Deferred` and `Delayed` options hold all JavaScript processing
until after the HTML is finished loading. The difference is in the
timing.

- `Deferred` runs the JS as soon as the HTML is finished loading. This
  is the classic mode for deferred JavaScript.
- `Delayed` doesn\'t run the JS until it detects user activity (like a
  key click or mouse pointer movement).

Both options should improve your page speed score, but `Delayed` has the
greater potential for improvement, as it has the effect of removing JS
entirely from the page speed score calculation.

::: {.admonition .tip}
Tip

As always, you should weigh any improvement in page speed score against
the potential impact on user experience. We recommend testing `Delayed`
mode on your site before enabling this option.
[]{#optimization-settings-tab}

## HTML Settings Tab[¶](#html-settings-tab "Permanent link"){.headerlink}

[![!LSCWP Page Optimization Section HTML Settings
Tab](../images/pageopt03.png)](../images/pageopt03.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### HTML Minify[¶](#html-minify "Permanent link"){.headerlink}

*OFF*

Extra white space characters, new line characters, and comments will be
stripped from all HTML, if this option is enabled.

### DNS Prefetch[¶](#dns-prefetch "Permanent link"){.headerlink}

*Empty List*

With this setting you may perform DNS resolution for the listed
domain(s) before it is requested. By prefetching DNS results, latency
can be reduced significantly for your visitors as they click external
links, particularly on mobile networks. Domains should be entered one
per line in the format `//www.example.com`.

[Learn
more](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-DNS-Prefetch-Control).

### DNS Prefetch Control[¶](#dns-prefetch-control "Permanent link"){.headerlink}

*OFF*

Widely enable DNS Prefetch for all URLs in the document, including
images, CSS, JavaScript, and so forth. This can improve the page loading
speed.

### DNS Preconnect[¶](#dns-preconnect "Permanent link"){.headerlink}

*Empty List*

This setting is similar to **DNS Prefetch**, but where **DNS Prefetch**
stops at performing the DNS resolution, **DNS Preconnect** goes further
to actually perform the connection handshake with the specified sites.

Domains should be entered one per line in the format
`//www.example.com`.

[Learn
more](https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/rel/preconnect).

::: {.admonition .note}
Note

Prefetch and Preconnect don\'t actually speed up your site. But they
speed up the connection process to *other* sites, making your site feel
more snappy and responsive to user clicks.
### HTML Lazy Load Selectors[¶](#html-lazy-load-selectors "Permanent link"){.headerlink}

*Empty List*

You can choose to lazy load any HTML content by its selector (generally
an id or class). List selectors one per line.

:::: {.admonition .example}
Example

If you have this:

::: highlight
    <span id="example">
    <p>This is content I wish to lazy load.</p>
    </span>
You can enter `example` in the box, and that paragraph will not be
loaded until it scrolls into the viewport.
### HTML Keep Comments[¶](#html-keep-comments "Permanent link"){.headerlink}

*Empty List*

HTML Comments are discarded when you minimize HTML. This setting allows
you to keep any comments that match the patterns in the list. One
pattern per line.

::: {.admonition .example}
Example

To keep any comments that mention LiteSpeed, add `LiteSpeed` to the
list. That will preserve both `<!-- LiteSpeed -->` and
`<!-- a comment that mentions LiteSpeed -->`
### Remove Query Strings[¶](#remove-query-strings "Permanent link"){.headerlink}

*OFF*

This setting strips the query string from static resources. Static
resources with query strings may not be cached by browsers and proxy
servers. Removing the strings allows them to be cached, which translates
into a faster page load.

### Load Google Fonts Asynchronously[¶](#load-google-fonts-asynchronously "Permanent link"){.headerlink}

*OFF*

You may not wish to enable asynchronous loading of *all* of your CSS,
but maybe you do want it for just the Google Fonts. If enabled, this
option will allow you to load Google Fonts asynchronously without also
loading the other CSS that way. Additionally, this option implements a
[preconnect](https://web.dev/uses-rel-preconnect/) to Google.

::: {.admonition .tip}
Tip

Preconnecting does not cause the fonts to be downloaded. It simply
speeds things up by getting the download connection established ahead of
time.
### Remove Google Fonts[¶](#remove-google-fonts "Permanent link"){.headerlink}

*OFF*

This options removes all Google fonts from your site. Be sure to test
this. Unless you have suitable replacement fonts stored locally, the
style of your site could change dramatically.

### Remove WordPress Emoji[¶](#remove-wordpress-emoji "Permanent link"){.headerlink}

*OFF*

If enabled, this setting removes the extra JavaScript file that is used
to add support for emojis in older browsers. Visitors who use modern
browsers that have their own native emoji support will not notice a
difference.

### Remove Noscript Tags[¶](#remove-noscript-tags "Permanent link"){.headerlink}

*OFF*

`<noscript>` tags are used for compatibility with older browsers that
don\'t support JavaScript, or modern browsers where JS is turned off for
security reasons. They give the browser intructions for what to do if it
can\'t run the associated script. However, these tags do take up space.

With this option enabled, `<noscript>` tags are removed, resulting in a
smaller page size, but less compatibility for browsers with no
functioning JavaScript. You will need to decide for yourself whether the
efficiency gains are worth the compatibility losses.

## Media Settings Tab[¶](#media-settings-tab "Permanent link"){.headerlink}

[Learn more about Lazy Load on our
blog.](https://blog.litespeedtech.com/2017/11/08/wpw-when-its-good-to-be-lazy/)

[![!LSCWP Page Optimization Section Media
Tab](../images/pageopt04.png)](../images/pageopt04.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### Preload Featured Image[¶](#preload-featured-image "Permanent link"){.headerlink}

This feature has been removed, as we now automatically preload all
Viewport Images as part of our [VPI service](#viewport-images).

### Lazy Load Images[¶](#lazy-load-images "Permanent link"){.headerlink}

*OFF*

When enabled, this setting only loads the images once they are visible
in the viewport. The remaining images are only loaded as necessary when
they scroll into view. When you turn this option `ON`, the WordPress
core Lazy Load feature is automatically disabled.

As useful as this ability is, it can be inelegant to have the images
just appear suddenly. You can improve upon this with CSS3, and give the
loading images a fade-in (or other) effect.

::::: {.admonition .example}
Example

The following CSS can be used to create a \"fade-in\" effect for your
lazy-loaded images:

::: highlight
    /* PART 1 - Before Lazy Load */
    img[data-lazyloaded]{
        opacity: 0;
    }
::: highlight
    /* PART 2 - Upon Lazy Load */
    img.litespeed-loaded{
        -webkit-transition: opacity .5s linear 0.2s;
        -moz-transition: opacity .5s linear 0.2s;
        transition: opacity .5s linear 0.2s;
        opacity: 1;
    }
The key is in the `data-lazyloaded` [attribute
selector](https://css-tricks.com/attribute-selectors/), which is a
method for targeting elements based on their given attributes.

Before an image is lazy loaded, it has the `data="lazyloaded"` attribute
associated with it, which enables PART 1 of the CSS code.

Once the image is loaded, that attribute goes away, PART 1 is no longer
relevant, and PART 2 of the CSS code goes into effect. This example CSS
causes the image to fade in, but you can replace the code with any CSS
effect you wish.

::: {.admonition .note}
Note

If the browser does not support CSS3, the above code will be ignored.
### Basic Image Placeholder[¶](#basic-image-placeholder "Permanent link"){.headerlink}

*empty string*

When **Lazy Load Images** is enabled, a gray box is displayed as a
placeholder until an image can be loaded. If you\'d prefer something
more creative, you can specify your own base64 image. You can list the
image here, or you can use the `LITESPEED_PLACEHOLDER` constant in your
`wp-config.php` file. If both are defined, this setting takes precedence
over the `wp-config.php` constant.

### Responsive Placeholder[¶](#responsive-placeholder "Permanent link"){.headerlink}

*OFF*

Responsive image placeholders can be used in cases where the width and
height attributes for the images are set. Placeholders are generated
with the same dimensions as the images, which helps to reduce layout
reshuffling.

### Responsive Placeholder SVG[¶](#responsive-placeholder-svg "Permanent link"){.headerlink}

*![](data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSJ7d2lkdGh9IiBoZWlnaHQ9IntoZWlnaHR9IiB2aWV3Ym94PSIwIDAge3dpZHRofSB7aGVpZ2h0fSI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0ie2NvbG9yfSIgLz48L3N2Zz4=)*

If generating a placeholder locally, you can specify an SVG to be used,
and it will be converted to a base64 placeholder on-the-fly.

::: {.admonition .note}
Note

Variables `{width}` and `{height}` will be replaced with the
corresponding image properties. Variable `{color}` will be replaced with
the configured background color.
### Responsive Placeholder Color[¶](#responsive-placeholder-color "Permanent link"){.headerlink}

*#cfd4db*

Responsive placeholders can be generated in any color you like. Use the
color picker to choose.

### LQIP Cloud Generator[¶](#lqip-cloud-generator "Permanent link"){.headerlink}

*OFF*

Low Quality Image Placeholder (LQIP) is a QUIC.cloud service that lets
you generate a unique placeholder, which is a blurred and minified
version of the original high-quality image to be loaded.

### LQIP Quality[¶](#lqip-quality "Permanent link"){.headerlink}

*4*

Specify the quality of your generated LQIPs. Larger numbers will
generate higher resolution, better quality placeholders, but will result
in larger files, which will increase page size. Value range: `1` - `20`.

### LQIP Minimum Dimensions[¶](#lqip-minimum-dimensions "Permanent link"){.headerlink}

*150* x *150*

With images that are fairly small, it shouldn\'t be necessary to
generate LQIPs. Specify the dimensions (in pixels) of the smallest files
you wish to be sent for LQIP requests. Both sides must be smaller than
these values in order to be excluded from LQIP. If either length or
width exceeds the limit, then the image will be sent. Valid dimensions
may be anything larger than `10` pixels and smaller than `800`.

### Generate LQIP In Background[¶](#generate-lqip-in-background "Permanent link"){.headerlink}

*ON*

The first time a page is visted, the LQIPs must be generated. If this
setting is `ON`, the generation will be done in the background via a
cron-based queue. The settings for **Responsive Placeholder** will be
honored until the generation is complete.

If this setting is `OFF`, the placeholders will be generated while the
visitor waits. This may slow down page load for that first visitor.

If you have images in the queue, you will see a **Clear LQIP Queue**
button. You may press this to remove all existing items from the queue.
Just be aware that in doing so, these items will not be processed by the
LQIP service until they are added back into the queue.

::: {.admonition .tip}
Tip

You may have noticed the small QUIC.cloud logo in this area. If you\'re
having a problem with your QUIC.cloud services, try clicking the symbol
to redetect your closest available service node(s). If your previously
available node has gone down, a new node will be selected, and services
can continue.
### Lazy Load iframes[¶](#lazy-load-iframes "Permanent link"){.headerlink}

*OFF*

This setting behaves exactly as **Lazy Load Images**, only for iframes
instead of images.

### Add Missing Sizes[¶](#add-missing-sizes "Permanent link"){.headerlink}

*OFF*

Setting an explicit width and height on your images is good practice. It
reduces layout shift, which improves user experience and page score.
This option, when enabled, allows LSCache to automatically add the width
and height to any image where the attributes are missing.

::: {.admonition .note}
Note

This option only works when **Lazy Load Images** is `ON`.
### WordPress Image Quality Control[¶](#wordpress-image-quality-control "Permanent link"){.headerlink}

*82*

Use this option to set WordPress\'s image compression quality. Any
number smaller than `100` is accepted, but the smaller the number the
more noticeable the compression will be.

### Auto Rescale Original Images[¶](#auto-rescale-original-images "Permanent link"){.headerlink}

*OFF*

This options helps you save disk space and bandwidth by automatically
scaling your original images to a smaller size. The default scaled size
threshold is 2560px but may be adjusted using the WordPress API filter
`big_image_size_threshold` described [in their
documentation](https://developer.wordpress.org/reference/hooks/big_image_size_threshold/).

::: {.admonition .warning}
Warning

Because the images are scaled upon upload, and the original image
resized without a backup, this option is irreversible.
::: {.admonition .example}
Example

With option set to `ON` and using the default scaled size threshold, if
you upload an image to your Media Library that is 3000px x 4000px, it
will be resized to be 2560px on its longest side, and saved at that
size.
[]{#vpi-settings-tab}

## VPI Tab[¶](#vpi "Permanent link"){.headerlink} {#vpi}

VPI stands for \"Viewport Images\" and is a service that allows you to
exclude above-the-fold images from lazy loading. For each post URL
submitted to the queue, QUIC.cloud detects which images in the post
would be visible in the viewport on post load. These images are referred
to as Viewport Images (or VPI), and LiteSpeed Cache will load them along
with the page. VPI images will not be lazy loaded, but all of the other
below-the-fold images for the URL will still be lazy loaded.

[![!LSCWP Page Optimization Section VPI
Tab](../images/pageopt05.png)](../images/pageopt05.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### Viewport Images[¶](#viewport-images "Permanent link"){.headerlink}

*OFF*

Turn this setting `ON` to use the QUIC.cloud VPI service. Viewport
Images are generated automatically in the background on QUIC.cloud
servers so as not to impact your own servers.

Use the [**LiteSpeed Options** metabox](../metabox/) to override
detected VPIs on a post-by-post basis.

::: {.admonition .tip}
Tip

You must have **Page Optimization \> Media Settings \> Lazy Load
Images** set to `ON` for this setting to take effect.
::: {.admonition .important}
Important

**VPI** uses the QUIC.cloud **Page Optimization** service to generate
Viewport Images. [Enabling QUIC.cloud
services](../general/#online-services-tab) is required for use, and [a
fee may apply](https://quic.cloud/online-services-costs/). If you choose
not to use QUIC.cloud services, VPI will not be generated, regardless of
whether this setting is turned `ON` or `OFF`.
Viewport Images are preloaded, meaning they are given priority and are
among the first assets to load in a page\'s life cycle.

### Viewport Images Cron[¶](#viewport-images-cron "Permanent link"){.headerlink}

*OFF*

When **Viewport Images** is enabled, and this setting is set to `ON`,
Viewport Images will be generated in the background via a cron-based
queue. If left `OFF`, you can manually submit any URLs waiting to be
processed.

A **Run VPI Queue Manually** button will appear when there are queued
URLs waiting. The queued URLs may be either gray, indicating they have
not yet been submitted to QUIC.cloud, or green, indicating that they
have already been submitted for processing.

## Media Excludes Tab[¶](#media-excludes-tab "Permanent link"){.headerlink}

[![!LSCWP Page Optimization Section Media Excludes
Tab](../images/pageopt06.png)](../images/pageopt06.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### Lazy Load Image Excludes[¶](#lazy-load-image-excludes "Permanent link"){.headerlink}

*empty string*

Sometimes there are images that you want to load immediately no matter
where they appear on the screen. Often you would want to exclude images
visible in both the initial mobile & desktop viewports (like your site
logo) to avoid layout shifts. Enter them here, one per line. You may use
the full URI or a partial string. Partial strings come in handy if you
have an entire directory of images that must be immediately loaded.
Don\'t use wildcards here.

### Lazy Load Image Class Name Excludes[¶](#lazy-load-image-class-name-excludes "Permanent link"){.headerlink}

*empty string*

Images containing these class names will not be lazy loaded. Both full
and partial strings can be used. One per line.

### Lazy Load Image Parent Class Name Excludes[¶](#lazy-load-image-parent-class-name-excludes "Permanent link"){.headerlink}

*empty string*

Images whose parents contain these class names will not be lazy loaded.
Both full and partial strings can be used. One per line.

### Lazy Load iframe Class Name Excludes[¶](#lazy-load-iframe-class-name-excludes "Permanent link"){.headerlink}

*empty string*

iframes containing these class names will not be lazy loaded. Both full
and partial strings can be used. One per line.

### Lazy Load iframe Parent Class Name Excludes[¶](#lazy-load-iframe-parent-class-name-excludes "Permanent link"){.headerlink}

*empty string*

iframes whose parents contain these class names will not be lazy loaded.
Both full and partial strings can be used. One per line.

### Lazy Load URI Excludes[¶](#lazy-load-uri-excludes "Permanent link"){.headerlink}

*empty string*

Images and iframes on the pages listed here will not be lazy loaded. One
URI per line. Partial strings may be used. The URIs will be compared to
the `REQUEST_URI` server variable. To indicate the beginning of a URI,
add `^` to the beginning of the string. To do an exact match, add `$` to
the end of the string.

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
### LQIP Excludes[¶](#lqip-excludes "Permanent link"){.headerlink}

*empty string*

Images listed here will not be included when generating Low Quality
Image Placeholders. Images can be added one per line, and partial
strings are allowed.

If the LQIP service encounters an error processing an image, it will
automatically be added to this list and ignored by LQIP in the future.

## Localization Tab[¶](#localization-tab "Permanent link"){.headerlink}

[![!LSCWP Page Optimization Section Localization
Tab](../images/pageopt07.png)](../images/pageopt07.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### Gravatar Cache[¶](#gravatar-cache "Permanent link"){.headerlink}

*OFF*

Gravatars can be cached locally, if this is set to `ON`.

#### Gravatar Cache Cron[¶](#gravatar-cache-cron "Permanent link"){.headerlink}

*OFF*

Turn `ON` to refresh the Gravatar cache using a cron job.

#### Gravatar Cache TTL[¶](#gravatar-cache-ttl "Permanent link"){.headerlink}

*604800*

This setting specifies how long (in seconds) Gravatars should be cached.
Any value larger than `3600` is acceptable.

### Localize Resources[¶](#localize-resources "Permanent link"){.headerlink}

*OFF*

You might want to do this if a page score site is suggesting
optimizations to JavaScript and other resources that are hosted on
domains such as Google or Facebook. You have no control over such
resources, and no ability to optimize them. Set **Localize Resources**
to `ON`, and the resources will be copied to your local system, where
they may be optimized as necessary.

Use the **Localization Files** field to specify which resources you
would like to localize.

#### Localization Files[¶](#localization-files "Permanent link"){.headerlink}

This option allows you to use local copies of external JavaScript
resources. Enter a URL, and it will be localized. A list of
**Recommended URLs** is provided by default, but you can choose whatever
resources you wish.

If **Localize Resources** is set to `ON`, then resources listed here
will be copied and replaced with a local URL. This only works on HTTPS
URLs, and not HTTP.

URLs are listed one per line.

Comments are supported. Start a line with a `#` to turn it into a
comment line.

:::: {.admonition .example}
Example

::: highlight
    # example sites
    https://www.example1.com/a.js
    https://www.example1.com/b.js
    https://www.example2.com/a.js
## Tuning Tab[¶](#tuning-tab "Permanent link"){.headerlink}

[![!LSCWP Page Optimization Section Tuning
Tab](../images/pageopt08.png)](../images/pageopt08.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### JS Delayed Includes[¶](#js-delayed-includes "Permanent link"){.headerlink}

*empty string*

List JavaScript file names, or partial strings for inline JS code, one
per line, to ensure that the listed JS *will* always be delayed.

::: {.admonition .warning}
Warning

This setting does not work for *inline* JavaScript when [**Load JS
Deferred**](#load-js-deferred) is set to `Deferred`.
### JS Excludes[¶](#js-excludes "Permanent link"){.headerlink}

*empty string*

If you enabled minification, combination or push for JavaScript in the
**JS Settings** tab, you may exclude some JS here. Use this space to
list any JS files (one per line) you would like excluded from the
optimization functions. You may enter full URLs or a partial string.
There\'s no need to use wildcards in partial strings.

### JS Deferred/Delayed Excludes[¶](#js-deferreddelayed-excludes "Permanent link"){.headerlink}

*empty list*

If **Load JS Deferred** is enabled in the **JS Settings** tab, there may
be some JavaScript files you do not want to be deferred or delayed. List
them here, one per line. You may list the full URI or partial strings to
be matched (no wildcards).

### Guest Mode JS Excludes[¶](#guest-mode-js-excludes "Permanent link"){.headerlink}

*empty list*

There may be some JavaScript files or inline JS code that you wish to
exclude from Guest Mode. List them here, one per line. You may list the
full URI or partial strings to be matched (no wildcards).

::: {.admonition .tip}
Tip

Alternate ways to do the same thing:

- The API filter `litespeed_optm_gm_js_exc`.
- Mark elements in HTML code with the `data-no-defer="1"` attribute.
### URI Excludes[¶](#uri-excludes "Permanent link"){.headerlink}

*empty string*

If there are pages you would like to exclude from optimization, you may
list them here. You can use a full path, or a partial string.

### Optimize for Guest Only[¶](#optimize-for-guest-only "Permanent link"){.headerlink}

*ON*

When set to `ON`, CSS and JavaScript optimizations are performed for
non-logged-in visitors only.

When set to `OFF`, optimizations are performed for all visitors, which
means that each user role will have its own set of generated CSS and
Javascript.

### Role Excludes[¶](#role-excludes "Permanent link"){.headerlink}

*unchecked*

There may be user roles that you wish to exclude from any sort of
optimization. For example, if you are an admin, testing new
functionality, you may want to exclude your `administrator` role from
optimization until your testing is through.

## Tuning CSS Tab[¶](#tuning-css-tab "Permanent link"){.headerlink}

[![!LSCWP Page Optimization Section Tuning CSS
Tab](../images/pageopt09.png)](../images/pageopt09.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### CSS Excludes[¶](#css-excludes "Permanent link"){.headerlink}

*empty string*

If you enabled minification, combination or push for CSS in the **CSS
Settings** tab, you may exclude some CSS here. Use this space to list
any CSS files (one per line) you would like excluded from the
optimization functions. You may enter full URLs or a partial string.
There\'s no need to use wildcards in partial strings.

### UCSS Inline Excluded Files[¶](#ucss-inline-excluded-files "Permanent link"){.headerlink}

(Formerly **UCSS File Excludes and Inline**)

*empty string*

In some cases you may prefer not to include a particular CSS file in the
UCSS calculation, and instead add the full contents of that CSS file
inline with the HTML. List CSS files here, one per line. Both full URLs
and partial strings are accepted.

### UCSS Selector Allowlist[¶](#ucss-selector-allowlist "Permanent link"){.headerlink}

*empty string*

There may be CSS selectors (generally ids or classes) that you will
*always* want included in the calculated [Unique CSS](#generate-ucss).
You can list the selectors in this setting, one per line. Any selectors
added here will be combined with our predefined list that you can see
[here on
Github](https://github.com/litespeedtech/lscache_wp/blob/dev/data/ucss_whitelist.txt).
[Inspect the content](../ts-optimize/#use-browser-developer-tools) if
you need help identifying the appropriate selectors.

This setting is specifically for selectors that appear in the CSS.
Entering parent ids or classes from the HTML code will not work.

Additionally, you need to be sure you are referencing the CSS selector
exactly as it is displayed in the CSS file.

::::: {.admonition .example}
Example

HTML:

::: highlight
    <div class="parent-class">
        <p class="child-class">I am a child</p>
    </div>
CSS:

::: highlight
    .child-class {
        font-size: .75em;
    }
To allow `.child-class` for that paragraph, you would enter
`.child-class` in the text box. Although referring to it with
`.parent-class .child-class` may be understood in other contexts, it
won\'t work for **UCSS Allowlist** because it doesn\'t appear in the CSS
code that way.
Be careful with pseudo classes (like `:hover`, `:active`, `:visited`,
and so on). The UCSS engine ignores pseudo-class selectors in CSS
content (with the exception of `:not`). If you wish to include a
pseudo-class selector in the UCSS result, then you must add the main
selector to **UCSS Allowlist**, but do not include the pseudo class or
the colon (`:`).

:::: {.admonition .example}
Example

::: highlight
    .example-class  {
        font-weight: normal;
    }
    .example-class:hover  {
        font-weight: bold;
    }
The UCSS engine will ignore the `.example-class:hover` selector. If you
wish to include it, add `.example-class` to **UCSS Allowlist**.

Do *not* add `.example-class:hover`. The `:` will cause an error.
::: {.admonition .note}
Note

There are some CSS selectors that don\'t become visible until there is
user interaction on the page (scrolling, for example). These selectors
may be allowlisted, but you\'ll have to work harder to figure out what
the selectors actually are. In these cases, interacting with the page
and using the browser Inspect tool may be your best option.
### UCSS URI Excludes[¶](#ucss-uri-excludes "Permanent link"){.headerlink}

*empty string*

If there are pages you would like to exclude from UCSS optimization, you
may list them here. You can use a full path, or a partial string.

### Separate CCSS Cache Post Types[¶](#separate-ccss-cache-post-types "Permanent link"){.headerlink}

*page*

By default, one set of Critical CSS is saved for each post type. That
is, CCSS for Posts, CCSS for Pages, CCSS for Products (if you have a
custom post type called \"Product\"). If you have a post type where
every item within that post type has different formatting, then one set
of Critical CSS will not do. Add that post type to the box, and Critical
CSS will be generated for each item of that post type.

::: {.admonition .example}
Example

If every Page on the site has different formatting, enter `page` in the
box. Separate critical CSS files will be stored for every individual
post of type Page on the site.
### Separate CCSS Cache URIs[¶](#separate-ccss-cache-uris "Permanent link"){.headerlink}

*empty string*

If you have pages that don\'t follow the same formatting rules as the
rest of their post type, you can list the URIs (or partial URIs) for
those pages in this box. Separate critical CSS files will be generated
for paths containing these strings. The URIs will be compared to the
`REQUEST_URI` server variable. To indicate the beginning of a URI, add
`^` to the beginning of the string. To do an exact match, add `$` to the
end of the string.

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
### CCSS Selector Allowlist[¶](#ccss-selector-allowlist "Permanent link"){.headerlink}

*empty string*

There may be CSS selectors (generally ids or classes) that you will
*always* want included in the calculated [Critical
CSS](#load-css-asynchronously). You can list the selectors in this
setting, one per line. Any selectors added here will be combined with
our predefined list that you can see [here on
Github](https://github.com/litespeedtech/lscache_wp/blob/dev/data/ccss_whitelist.txt).
[Inspect the content](../ts-optimize/#use-browser-developer-tools) if
you need help identifying the appropriate selectors.

This setting is specifically for selectors that appear in the CSS.
Entering parent ids or classes from the HTML code will not work.

Additionally, you need to be sure you are referencing the CSS selector
exactly as it is displayed in the CSS file. If you want to see more
details, look at the examples listed under [**UCSS Selector
Allowlist**](#ucss-selector-allowlist). They also apply to this setting.

### Critical CSS Rules[¶](#critical-css-rules "Permanent link"){.headerlink}

*empty string*

When **Load CSS Asynchronously** is enabled in the **CSS Settings** tab,
critical CSS is generated automatically. You may wish to single out a
few additional definitions that must be loaded first in order to
properly style above-the-fold content. Enter those rules here in plain
CSS, just as they appear in your stylesheet. They will be appended to
the generated CSS.
