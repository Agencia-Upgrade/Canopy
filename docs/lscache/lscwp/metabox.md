# LiteSpeed Options Metabox[¶](#litespeed-options-metabox "Permanent link"){.headerlink}

[![!LSCWP LiteSpeed Options
Metabox](../images/metabox01.png)](../images/metabox01.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

The **LiteSpeed Options** metabox was introduced in LSCWP v5.0 as a
convenient way to adjust cache and optimization settings on a per-post
basis. It appears in the post editor for new and existing posts of any
type, including `Post`, `Page`, and `Product` (for WooCommerce).

::: {.admonition .tip}
Tip

Want to hide the metabox from the post editing screen? In the Classic
Editor, select **Screen Options** at the top of the page and un-check
**LiteSpeed Options**. In the Block Editor, select the 3-dot menu, then
**Preferences \> Panels** and disable **LiteSpeed Options**.
## Disable Cache[¶](#disable-cache "Permanent link"){.headerlink}

This switch is an alternative to the [**Do Not Cache URIs**
setting](../cache/#do-not-cache-uris). It is turned off (gray, with the
circle to the left) by default, which means the page you are editing
*will* be cached, unless it is excluded by some other setting within the
[**Cache \> Excludes** tab](../cache/#excludes-tab).

Flip the switch to ON (blue, with the circle to the right), if you wish
to exclude this post from cache.

## Disable Image Lazyload[¶](#disable-image-lazyload "Permanent link"){.headerlink}

This switch is an alternative to the [**Lazy Load URI Excludes**
setting](../pageopt/#lazy-load-uri-excludes). It is turned off (gray,
with the circle to the left) by default, which means the images on the
page you are editing *will* be lazy loaded, unless they are excluded by
some other setting within the [**Page Optimization \> Media Excludes**
tab](../pageopt/#media-excludes-tab).

Flip the switch to ON (blue, with the circle to the right), if you wish
to exclude the images in this post from lazy loading.

## Viewport Images[¶](#viewport-images "Permanent link"){.headerlink}

::: {.admonition .tip}
Tip

You must have **Page Optimization \> Media Settings \> Lazy Load
Images** set to `ON` and **Page Optimization \> VPI \> Viewport Images**
set to `ON` for this setting to take effect.
If you have the the QUIC.cloud [**VPI** service](../pageopt/#vpi)
enabled, this box will display any Viewport Images already detected by
the service. For a new post, this setting will be empty, but once
published, the post will be added to the VPI queue, and Viewport Images
will be calculated in time. If you already know what image(s) you wish
to exclude from lazy loading, you can enter them here, and the post will
not be sent to the VPI service for calculation.

If you are editing an existing post, and the VPI service has already
detected that there are images above the fold which should be excluded
from lazy loading, they will be listed here. You can delete the contents
of this box to have the VPI recalculated by QUIC.cloud. You can also add
or remove images manually.

Images should be listed without a domain name (as in,
`/path/image.jpg`), one per line.

## Viewport Images - Mobile[¶](#viewport-images-mobile "Permanent link"){.headerlink} {#viewport-images-mobile}

This setting is exactly the same as the above **Viewport Images**
setting, except that it applies only to a mobile viewport. The list of
images here may differ from the setting above, if different images are
visible on mobile and desktop. **Cache \> Cache \> Cache Mobile** should
be set to `ON`. If you don\'t have **Cache Mobile** enabled, then this
setting is ignored.
