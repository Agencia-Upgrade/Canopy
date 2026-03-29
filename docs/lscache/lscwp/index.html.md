# LiteSpeed Cache for WordPress[¶](#litespeed-cache-for-wordpress "Permanent link"){.headerlink}

::: {.admonition .tip}
Tip

LiteSpeed Cache for WordPress is also compatible with
[ClassicPress](https://www.classicpress.net/).
## Plugin Requirements[¶](#plugin-requirements "Permanent link"){.headerlink}

- **WordPress**: Version 5.3 or higher
- **PHP**: Version 7.2.0 or higher

## What is LSCache?[¶](#what-is-lscache "Permanent link"){.headerlink}

LiteSpeed Cache (also called LSCache) is LiteSpeed\'s more efficient and
highly customizable answer to Apache `mod_cache` and Varnish.

LSCache is built from the ground up and integrated into all LiteSpeed
server products. It can:

- dramatically speed up dynamic website content (like PHP pages)
- provide more efficient handling of static content (like images)
- reduce server load

## Understanding Caching[¶](#understanding-caching "Permanent link"){.headerlink}

If you are new to website caching, allow us to demystify a few basic
concepts.

### What is Caching?[¶](#what-is-caching "Permanent link"){.headerlink}

Generally speaking, a cache is a mechanism for storing data in such a
way that it is easier or faster to retrieve than the original source.

Web application sites consist of dynamic pages that are built with PHP
or some other method. The pages of these sites don't exist anywhere in
the file system; they are constructed on-demand by the web app, and then
served to the visitor as HTML. Generating these dynamic pages can be
resource-intensive and slow.

There are actually several types of caches. LSCache is a \"page cache.\"
A page cache\'s job is to take this dynamically generated web page, and
store it as a static HTML snapshot. That way, the next time the page is
requested by a visitor, the snapshot can be served immediately. Serving
a snapshot is much faster and uses far fewer resources than generating
the page dynamically does.

### How does LSCache Work?[¶](#how-does-lscache-work "Permanent link"){.headerlink}

Imagine you have an uncached page.

A visitor requests that page, and a few things happen:

- LiteSpeed looks for the page among its stored cache objects and does
  not find it
- LiteSpeed returns a \"cache miss\"
- The web app dynamically generates a static HTML page while the visitor
  waits
- LiteSpeed serves the static HTML page to the visitor
- LiteSpeed stores the static HTML page as a cache object for later use

A few minutes later, another visitor requests that same page. Here\'s
what happens:

- LiteSpeed looks for the page among its stored cache objects and finds
  it
- LiteSpeed returns a \"cache hit\"
- LiteSpeed immediately serves the static HTML page to the visitor

Notice how the inefficient web app is not in the picture at all once the
page has been cached? From this point on, until the cache object
expires, any visitors who request that page will not have to wait around
for the web app.

You can see why caching is good for your visitors, and good for your
server load!

## Why Use a Plugin?[¶](#why-use-a-plugin "Permanent link"){.headerlink}

The LiteSpeed Cache Engine can be controlled through rewrite rules in
the .htaccess of a web app\'s document root. So what do you gain by
using an LSCache plugin?

An LSCache plugin bridges the knowledge gap between a web app and the
Cache Engine.

Put another way: web apps have rules about what content may be cached,
for how long it may be cached, and what events would cause a cache
object to become stale. LSCache plugins are a way of communicating these
web app rules to the Cache Engine. LSCache plugins allow you to manage
the cache in such a way that more objects may be stored for a longer
period, and with unparallelled accuracy.

## Server-Level Prerequisites[¶](#server-level-prerequisites "Permanent link"){.headerlink}

### Obtain a LiteSpeed Web Server[¶](#obtain-a-litespeed-web-server "Permanent link"){.headerlink}

You will either need [LiteSpeed-powered
hosting](https://www.litespeedtech.com/partners/hosting-providers), or
one of the following LiteSpeed server products in order to use LSCache:

- LiteSpeed Enterprise Web Server:
  - [Order a new license with caching
    enabled](https://store.litespeedtech.com/store/cart.php)
  - [Add caching to an existing
    license](/licenses/how-to#add-cache-to-an-existing-license)
- [LiteSpeed Web ADC](https://store.litespeedtech.com/store/cart.php)
- [OpenLiteSpeed](https://openlitespeed.org/#install)
- [QUIC.cloud CDN](https://quic.cloud)

::: {.admonition .note}
Note

It is possible to use the LSCache plugin for WordPress without a
LiteSpeed server product, however you will only access to the
optimization features. The caching functions require the LSCache server
component to work. Please see [Using LSCache Without a LiteSpeed
Server](./installation/#using-lscache-without-a-litespeed-server).
::: {.admonition .tip}
Tip

OpenLiteSpeed does not support ESI. If you wish to use ESI, you will
need a commercial LiteSpeed product such as LiteSpeed Web Server or
QUIC.cloud.
### Configure the Server[¶](#configure-the-server "Permanent link"){.headerlink}

LSCache must be set up at the server level before it can be made
available to any sites on the server.

::: {.admonition .tip}
Tip

If you are a site owner and you don\'t have access to your server\'s
admin functions, chances are your hosting provider has already done this
setup for you, or can help you to complete it.
See [Configure Cache Root and Cache
Policy](/lscache/start/#configure-cache-root-and-cache-policy) for
instructions.

## Install, Configure and Use the Plugin[¶](#install-configure-and-use-the-plugin "Permanent link"){.headerlink}

Once you\'ve set up your web server and configured the cache root and
cache policies, you\'re ready to install the plugin. The plugin will
allow you to configure and manage your LiteSpeed server\'s cache simply
and easily, through your site\'s own familiar admin interface.

Start with [Installation](./installation) and follow the documentation
menu to configure and use the plugin.

::: {.admonition .tip}
Tip

If you are a System Administrator for a shared hosting server and are
currently using WHM/cPanel, you may find the [LiteSpeed Cache for
WordPress Management section](../../../cp/cpanel/wp-cache-management/)
within the LiteSpeed WHM Plugin to be a big time saver. It allows you to
enable cache for all of your WordPress installations with one-click.
## Additional Support[¶](#additional-support "Permanent link"){.headerlink}

### Community Support[¶](#community-support "Permanent link"){.headerlink}

If you\'ve explored our documentation and still have questions, you can
find assistance from other LiteSpeed users through our Community Support
forums:

- Join [the GoLiteSpeed Slack
  community](https://www.litespeedtech.com/slack)
- Visit [the LiteSpeed
  Forums](https://www.litespeedtech.com/support/forum/)

### Official LiteSpeed Support[¶](#official-litespeed-support "Permanent link"){.headerlink}

To be assisted by a LiteSpeed support technician, you can submit [a
support ticket](https://store.litespeedtech.com/store/clientarea.php)
from the LiteSpeed website\'s client area or by emailing
`support@litespeedtech.com`.

::: {.admonition .note}
Note

We\'re happy to provide a level of free support for all LiteSpeed
products and offer guidance where documentation may fall short. However,
we are unable to offer general sysadmin support or hands-on assistance.

Should you require individualized configuration support or hands-on
assistance with our products, you can order additional services from
[the LiteSpeed
store](https://store.litespeedtech.com/store/index.php?rp=/store/supportservice).
Thank you for choosing LiteSpeed!

## Feedback[¶](#feedback "Permanent link"){.headerlink}

Do you have some thoughts about this documentation site? Join [our
GoLiteSpeed Slack](https://www.litespeedtech.com/slack), drop by [the
#documentation
channel](https://golitespeed.slack.com/messages/C3VSQTDGQ), and let us
know what you think!

Do you have ideas for improving the LiteSpeed Cache for WordPress
plugin? We have a Slack channel for that, too. Drop by [the
#suggestion-box-wp-qc
channel](https://golitespeed.slack.com/archives/C03EKC15Y93) and share
your suggestions with the team.
