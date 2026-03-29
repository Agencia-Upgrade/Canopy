# Presets Section[¶](#presets-section "Permanent link"){.headerlink}

You can use Presets to easily configure your LiteSpeed Cache plugin.
When you apply a Preset, your existing settings are backed up, and then
replaced with the chosen preset.

::: {.admonition .help}
Video

See a video demonstration of **How to Install and Configure LiteSpeed
Cache using Presets** [here](https://youtu.be/KsymNHgM3w4).
## Standard Presets Tab[¶](#standard-presets-tab "Permanent link"){.headerlink}

[![!LSCWP Presets Section Standard Presets
Tab](../images/presets01.png)](../images/presets01.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

Eventually, we hope to include Presets that have been submitted by
members of our community. For now, we have these LiteSpeed Cache
Standard Presets that were developed by our team. We\'ve included a
selection of presets for every comfort level.

To use a preset, press the **Apply Preset** button below your chosen
preset and answer **OK** when prompted to continue. Your old settings
will be backed up, and the new preset settings will be applied.

Be sure to test your site and make sure everything is working as
expected. This is especially important if you\'ve chosen one of the
higher-risk presets. If the preset causes a problem, you can revert back
to your previous settings via the [**History** section](#history) at the
bottom of the tab.

### Essentials[¶](#essentials "Permanent link"){.headerlink}

This preset enables the following:

- [Default Cache](../cache/#enable-cache)
- [Higher TTL](../cache/#default-public-cache-ttl)
- [Browser Cache](../cache/#browser-cache)

About this preset:

- **Appropriate for**: Beginners; those who don\'t understand cache
  plugins; those who want to \"set it and forget it\"
- **QUIC.cloud services**: Not required; you do not need to connect to
  QUIC.cloud to use this preset
- **Expertise required**: No; these settings do not have the potential
  to break your site; you should not need to make any adjustments in
  order for these settings to have some impact
- **User experience impact**: Potentially good; caching can make a
  noticeable improvement in your site\'s page loading time
- **Page score impact**: Medium; page scoring sites like to see that you
  have caching enabled

### Basic[¶](#basic "Permanent link"){.headerlink}

This preset enables the following:

- Everything in **Essentials**
- [Image Optimization](../imageopt)
- [Mobile Cache](../cache/#cache-mobile)

About this preset:

- **Appropriate for**: Enthusiastic beginners; those who want to ease
  into a basic level of optimization
- **QUIC.cloud services**: Required; you will need to connect to
  QUIC.cloud to use this preset; the Image Optimization service is
  provided by QUIC.cloud, and is free
- **Expertise required**: Probably not; you should not need to make many
  adjustments in order for these settings to have some impact
- **User experience impact**: Potentially good; caching and optimized
  images can both speed up page load time.
- **Page score impact**: Good; page scoring sites like to see that you
  have caching enabled, and that your images are optimized

### Advanced[¶](#advanced "Permanent link"){.headerlink}

This preset enables the following:

- Everything in **Basic**
- [Guest Mode](../general/#guest-mode) and [Guest
  Optimization](../general/#guest-optimization)
- [CSS](../pageopt/#css-minify), [JS](../pageopt/#js-minify) and [HTML
  Minification](../pageopt/#html-minify)
- [Font Display
  Optimization](../pageopt/#load-google-fonts-asynchronously)
- [JS Defer](../pageopt/#load-js-deferred) for both external and inline
  JS
- [DNS Prefetch](../pageopt/#dns-prefetch-control) for static files
- [Gravatar Cache](../pageopt/#gravatar-cache)
- [Remove Query Strings](../pageopt/#remove-query-strings) from static
  files
- [Remove WordPress Emoji](../pageopt/#remove-wordpress-emoji)
- [Remove Noscript Tags](../pageopt/#remove-noscript-tags)

About this preset:

- **Appropriate for**: Those who have a basic comfort level with
  adjusting optimization settings
- **QUIC.cloud services**: Required; you will need to connect to
  QUIC.cloud to use this preset; the Online Services are provided by
  QUIC.cloud, and may incur a charge after any monthly free quota is
  depleted
- **Expertise required**: Maybe; these settings are unlikely to cause
  conflicts, but if they do, you should be comfortable using the tools
  in [**Page Optimization \> Tuning**](../pageopt/#tuning-tab) to
  exclude CSS or JS files from optimization
- **User experience impact**: Potentially great; Guest Mode is aimed at
  giving users a fast page load from their very first visit
- **Page score impact**: Great; Guest Optimization was designed for
  improving your page score

### Aggressive[¶](#aggressive "Permanent link"){.headerlink}

This preset enables the following:

- Everything in **Advanced**
- [CSS](../pageopt/#css-combine) & [JS Combine](../pageopt/#js-combine)
- [Asynchronous CSS Loading with Critical
  CSS](../pageopt/#load-css-asynchronously)
- [Unique CSS](../pageopt/#generate-ucss)
- [Lazy Load for iframes](../pageopt/#lazy-load-iframes)

About this preset:

- **Appropriate for**: Those who enjoy experimenting with optimization
  settings
- **QUIC.cloud services**: Required; you will need to connect to
  QUIC.cloud to use this preset; the Online Services are provided by
  QUIC.cloud, and may incur a charge after any monthly free quota is
  depleted
- **Expertise required**: Yes; it\'s possible these settings may
  introduce CSS or JS conflicts, especially if you have a lot of plugins
  or a complicated theme; you should be comfortable using the tools in
  [**Page Optimization \> Tuning**](../pageopt/#tuning-tab) to exclude
  CSS or JS files from optimization
- **User experience impact**: Potentially excellent; as long as you
  address any potential conflicts, these optimizations can make your
  site a pleasure to use
- **Page score impact**: Excellent; page scoring sites love to see all
  of these optimizations in effect

### Extreme[¶](#extreme "Permanent link"){.headerlink}

This preset enables the following:

- Everything in **Aggressive**
- [Lazy Load for Images](../pageopt/#lazy-load-images)
- [Viewport Image Generation](../pageopt/#viewport-images)
- [JS Delayed](../pageopt/#load-js-deferred)
- [Inline JS added to
  Combine](../pageopt/#js-combine-external-and-inline)
- [Inline CSS added to
  Combine](../pageopt/#css-combine-external-and-inline)

About this preset:

- **Appropriate for**: Optimization experts; fearless explorers
- **QUIC.cloud services**: Required; you will need to connect to
  QUIC.cloud to use this preset; the Online Services are provided by
  QUIC.cloud, and may incur a charge after any monthly free quota is
  depleted
- **Expertise required**: Yes; these settings are likely to introduce
  CSS or JS conflicts, especially if you have a lot of plugins or a
  complicated theme; you should be comfortable using the tools in
  [**Page Optimization \> Tuning**](../pageopt/#tuning-tab) to exclude
  CSS or JS files from optimization
- **User experience impact**: Potentially excellent; as long as you
  address any potential conflicts, these optimizations can make your
  site a pleasure to use
- **Page score impact**: Excellent; this preset includes the maximum
  level of optimization that page speed tools suggest

### History[¶](#history "Permanent link"){.headerlink}

[![!LSCWP Presets Section Standard Presets Tab
History](../images/presets02.png)](../images/presets02.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

This area only appears on the **Standard Presets** tab if you have
previously applied a preset. Every time you apply a preset, it is listed
in the history, along with a link that will allow you to revert back to
the previous settings.

## Import / Export Tab[¶](#import-export-tab "Permanent link"){.headerlink}

This **Presets** tab is an exact copy of the one that appears in the
**Toolbox** section. There\'s a copy here, because it\'s helpful to have
easy access to export, reset, or import your settings when you are
experimenting with Presets.

Please see [**Toolbox \> Import/Export
Tab**](../toolbox/#importexport-tab) for a full explanation of the
settings in this tab.
