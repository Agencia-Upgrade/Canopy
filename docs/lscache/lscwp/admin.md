# Admin[¶](#admin "Permanent link"){.headerlink}

## Suppressing Non-Critical Banners[¶](#suppressing-non-critical-banners "Permanent link"){.headerlink}

Sometimes the LiteSpeed Cache plugin for WordPress adds informational
banners to your WordPress Dashboard, such as this one:

[![!](../images/admin-lscwp-banner.png)](../images/admin-lscwp-banner.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

These banners are meant to be informational, but they are not critical
to the functioning of the plugin. As of LSCWP v3.0, these banners are
opt-in only, meaning by default, such non-critical banners will not be
displayed. If you would like to opt in to seeing LiteSpeed news
(hotfixes, new releases, available beta versions, promotions, etc.)
displayed on your dashboard, navigate to **LiteSpeed Cache \> General \>
General Settings** and set **Notifications** to `ON`.

::: {.admonition .note}
Note

This setting does *not* suppress notifications such as
`Purged all caches successfully.` and other messages related to the
functioning of the plugin.
## Admin IP Commands[¶](#admin-ip-commands "Permanent link"){.headerlink}

The LSCWP_CTRL Admin IP commands give you access to certain actions from
the browser window by way of a simple query string.

To trigger one of these actions for a page, access the page with the
`?LSCWP_CTRL=ACTION` query string appended to the end of the URL.

::: {.admonition .example}
Example

To purge the `https://example.com/2023/todays-blog-post/` blog post, you
would visit this URL:
`https://example.com/2023/todays-blog-post/?LSCWP_CTRL=PURGE`
The `PURGE` action, and most others, are restricted by IP address. You
can give trusted users and admins access to all of the actions by adding
their IP addresses in **Toolbox \> Debug Settings \> Admin IPs**. You do
not need to be logged in to use these actions.

+-----------------+-----------------+-----------------+-----------------+
| ::: {style      | Used for        | Admin IP        |                 |
| ="width:125px"} |                 | required        |                 |
| Action          |                 |                 |                 |
| :::             |                 |                 |                 |
+=================+=================+=================+=================+
| `NOCACHE`       | Display a page  | Yes             |                 |
|                 | without caching |                 |                 |
|                 | it. An example  |                 |                 |
|                 | use case is to  |                 |                 |
|                 | compare a       |                 |                 |
|                 | cached version  |                 |                 |
|                 | of a page with  |                 |                 |
|                 | an uncached     |                 |                 |
|                 | version.        |                 |                 |
+-----------------+-----------------+-----------------+-----------------+
| `before_optm`   | View the page   | No              |                 |
|                 | without any of  |                 |                 |
|                 | the             |                 |                 |
|                 | optimizations   |                 |                 |
|                 | enabled.        |                 |                 |
+-----------------+-----------------+-----------------+-----------------+
| `SHOWHEADERS`   | Display all of  | Yes             | This may be     |
|                 | the cache       |                 | useful for      |
|                 | headers         |                 | debugging       |
|                 | associated with |                 | purposes, as    |
|                 | a page in the   |                 | certain cache   |
|                 | Inspect tool.   |                 | headers are     |
|                 |                 |                 | normally not    |
|                 |                 |                 | shown.          |
+-----------------+-----------------+-----------------+-----------------+
| `PURGE`         | Purge all cache | Yes             |                 |
|                 | tags associated |                 |                 |
|                 | with the page,  |                 |                 |
|                 | except the blog |                 |                 |
|                 | ID tag. Pages   |                 |                 |
|                 | with the same   |                 |                 |
|                 | cache tag will  |                 |                 |
|                 | be purged as    |                 |                 |
|                 | well.           |                 |                 |
+-----------------+-----------------+-----------------+-----------------+
| `PURGESINGLE`   | Purge only the  | Yes             |                 |
|                 | URL cache tag   |                 |                 |
|                 | associated with |                 |                 |
|                 | the page.       |                 |                 |
+-----------------+-----------------+-----------------+-----------------+

::: {.admonition .note}
Note

Actions are case-sensitive.
## WordPress CLI[¶](#wordpress-cli "Permanent link"){.headerlink}

This documentation has moved to it\'s own page. Please see [WordPress
CLI](../cli/).

## Using a Default Configuration[¶](#using-a-default-configuration "Permanent link"){.headerlink}

The `const.default.ini` file contains the default configuration for
LSCWP. It can be used, for example by hosting providers, to change the
default settings for the plugin. The file is located in the
`/wp-content/litespeed-cache/data` folder.

As of `v3.3` of our [WHM
plugin](../../../cp/cpanel/wp-cache-management/), hosting providers can
use a custom `const.default.ini` file when enabling or mass enabling
LSCWP by placing the file under the `/usr/src/litespeed-wp-plugin`
directory. This file will then be used for all sites installing a new
copy of LSCWP.

Changes to `const.default.ini` do not prevent users from changing their
plugin settings.

## Using Multiple Optimization Plugins[¶](#using-multiple-optimization-plugins "Permanent link"){.headerlink}

LiteSpeed Cache for WordPress has many optimization features in addition
to our signature full-page cache, and as such, you probably don\'t need
any other similar plugins. If you still want to use one of the other
WordPress optimization plugins for whatever reason, that shouldn\'t be a
problem, as long as you don\'t try to use the same features in both.

For example, if you are using our full-page cache and our CDN support,
then you will need to make sure that page cache and CDN support are
disabled in whatever other plugin you use. Similarly, if you are using a
minification function (for example) in another plugin, you will need to
keep minification disabled in our plugin.

Duplicating functionality at best bogs down your server, and at worst
breaks your site. So don\'t do it!

[To learn more about this, see our
blog.](https://blog.litespeedtech.com/2017/08/02/wpw-using-multiple-cache-plugins/)

### Compatibility Check[¶](#compatibility-check "Permanent link"){.headerlink}

Not all cache plugins are good candidates to pair with LiteSpeed. In
order to avoid duplicating our functions, a plugin must either:

1.  not include the same cache and optimization functions as LiteSpeed
    Cache at all, or
2.  include cache and optimization functions that can be disabled
    one-by-one.

### Set up Other Plugin[¶](#set-up-other-plugin "Permanent link"){.headerlink}

Before you install and activate LiteSpeed Cache, you should first get
the other plugin working to your satisfaction. Doing this part first
will make it easier because you can follow the plugin's given directions
without having to worry about how it will impact LiteSpeed's setup.

Once the plugin is installed, activated, and set up to your liking,
*purge that plugin's cache* to ensure there are no conflicts from the
start, and then *disable the cache and all of the duplicate optimization
functions that you plan to use in LSCWP*.

### Set up LSCWP[¶](#set-up-lscwp "Permanent link"){.headerlink}

[Install and activate LSCWP](../installation). Enable the cache and any
optimization features you wish to use in LSCWP.

### Verify[¶](#verify "Permanent link"){.headerlink}

At this point, you should have both plugins working together in harmony,
but you'll want to do a quick test, just to be sure. To verify that your
pages are actually being cached by LiteSpeed, you can [follow these
steps](../installation/#verify-your-site-is-being-cached).

If the page was not cached by LiteSpeed, then something in your setup is
not quite right. [Contact
us](https://wordpress.org/support/plugin/litespeed-cache), if you need
help!

If the page *was* cached by LiteSpeed, then the setup is finished. Don't
forget to take a look at your LiteSpeed Cache settings and see if
anything needs adjustment. In general, the default settings are fine,
but you might want to tweak a few things since you've got the other
plugin running, too.

## Translate LSCache for WordPress[¶](#translate-lscache-for-wordpress "Permanent link"){.headerlink}

LSCWP is written in United States English, and so we rely our our
international users to help us translate the plugin for worldwide use.
If you are fluent in a language other than English (US), and you have a
few minutes to contribute to our plugin, we would appreciate it!

### Is Your Language Needed?[¶](#is-your-language-needed "Permanent link"){.headerlink}

[![!](../images/admin-lscwp-translate1.png)](../images/admin-lscwp-translate1.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

We have a few languages very well covered, so you\'ll want to check the
[Translating WordPress page for LiteSpeed
Cache](https://translate.wordpress.org/projects/wp-plugins/litespeed-cache)
and look for your language (and geographic location if applicable). If
there are red or yellow boxes next to the language, then your expertise
is needed.

As you can see, we have quite a few red boxes as of this writing (and
several pages more of them past where the screenshot ends). The most
important column is the \"Stable\" column. Languages with shades of red
in the stable column have less than a third of the plugin translated.

### Submit a Translation[¶](#submit-a-translation "Permanent link"){.headerlink}

All you need is a wordpress.org login. Once you are logged in, you can
[click the
link](https://translate.wordpress.org/projects/wp-plugins/litespeed-cache)
and start translating at your own pace.

The instructions will be the same for whichever language and geographic
location you choose, but for simplicity\'s sake, let\'s say you\'re from
Spain and would like to contribute to the Spain Spanish translation.

Click **Spanish (Spain)** to be brought to the es_ES translation page.

[![!](../images/admin-lscwp-translate2.png)](../images/admin-lscwp-translate2.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

The most important section to work on first is **Stable (latest
release)**, so click on that to see what strings are still missing
translation.

[![!](../images/admin-lscwp-translate3.png)](../images/admin-lscwp-translate3.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

You\'ll be brought to a list of strings and their current translations
(if any).

This list, if it\'s not well-populated, may look overwhelming. However,
*it is not required for you to translate every single string*. You could
spend half an hour and do thirty of them. Or ten. Or even just one.
Every contribution, even a small one, gets us closer to full
translation.

When you see a string you\'d like to translate (for example,
`Communicated with Cloudflare successfully`), double click on the
**Translation** column for that string, and enter your translation in
the box.

[![!](../images/admin-lscwp-translate4.png)](../images/admin-lscwp-translate4.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

Click the **Suggest new translation -\>** button. Congratulations, you
have successfully translated your first string.

### Approval[¶](#approval "Permanent link"){.headerlink}

All translations must be approved by an editor for your language before
they are incorporated into the plugin.

If you would like to be a translation editor for LSCache, just keep
translating! We will notice you, and apply to wordpress.org to give you
editor access. Additionally, we\'ll add you to our Slack team, where you
can communicate with our other editors, and be kept in the loop for new
plugin updates and needed translations.

Thank you for helping us make LSCWP accessible for a global audience!

## Enabling and Limiting the Crawler[¶](#enabling-and-limiting-the-crawler "Permanent link"){.headerlink}

These instructions apply to the WordPress LSCache crawler and other CMS
LSCache crawlers where available.

Due to the potential of the crawler to consume considerable resources,
we have put the on/off switch in the hands of the server administrators.
In [a control panel
environment](#shared-hosting-control-panel-environment), such as cPanel,
the crawler is *disabled* by default and can only be enabled by an admin
through Apache configuration. In the [LSWS Native
environment](#litespeed-native), the crawler is *enabled* by default and
can be disabled at the server level or virtual host level in LSWS v5.3.5
and above.

::: {.admonition .warning}
Warning

We do not recommend enabling the crawler for shared hosting setups
unless the server has enough capacity to handle it!
### Shared Hosting / Control Panel Environment[¶](#shared-hosting-control-panel-environment "Permanent link"){.headerlink}

As of LSWS v5.1.16, there are four different approaches you can take to
crawling on your server:

- You can disable it for the entire server
- You can disable it for the entire server, and selectively enable it
  for specific vHosts
- You can enable it for the entire server
- You can enable it for the entire server, and selectively disable it
  for specific vHosts

#### Enabling the Crawler[¶](#enabling-the-crawler "Permanent link"){.headerlink}

To enable the crawler in either of the second two scenarios, you need to
add this "Crawler Snippet" to the appropriate configuration or include
file:

::: highlight
        <IfModule Litespeed>
         CacheEngine on crawler
        </IfModule>
The exact location of the relevant configuration or include file varies,
depending on the control panel you use (or if you use no control panel
at all), and which of the above options you are looking to enact. See
below for instructions relevant to your setup.

::: {.admonition .tip}
Tip

This snippet should not be added to `.htaccess`. It must be added to an
Apache configuration file.
After you\'ve added the Crawler Snippet in the appropriate location, you
should gracefully restart the server.

#### Limiting the Crawler[¶](#limiting-the-crawler "Permanent link"){.headerlink}

Currently, the following variables are available for use with [the
Crawler function](../crawler):

- `CRAWLER_USLEEP` puts a minimum allowed value on the **Delay** field.
- `CRAWLER_LOAD_LIMIT` sets a default for the **Server Load Limit**
  field.
- `CRAWLER_LOAD_LIMIT_ENFORCE` sets a maximum allowed value on the
  **Server Load Limit** field.

To use these variables, add them one-per-line to the appropriate
configuration file. For example:

::: highlight
        <IfModule LiteSpeed>
        CacheEngine on crawler
        SetEnv CRAWLER_USLEEP 1000
        SetEnv CRAWLER_LOAD_LIMIT 5.2
        </IfModule>
#### Disabling the Crawler[¶](#disabling-the-crawler "Permanent link"){.headerlink}

You may disable the crawler for an Apache virtual host, in any
situation. Simply add `CacheEngine -crawler` to the Apache virtual host
configuration, like so:

::: highlight
        <IfModule LiteSpeed>
        CacheEngine -crawler
        </IfModule>
#### cPanel/WHM[¶](#cpanelwhm "Permanent link"){.headerlink}

##### Server Level[¶](#server-level "Permanent link"){.headerlink}

Change your working directory to:

- `/usr/local/apache/conf/includes/` for EA3 or
- `/etc/apache2/conf.d/includes/` for EA4.

Add the Crawler Snippet and optional server variables to the
`pre_main_global.conf` file.

##### Global Virtual Host Level[¶](#global-virtual-host-level "Permanent link"){.headerlink}

Change your working directory to:

- `/usr/local/apache/conf/userdata/`for EA3 or
- `/etc/apache2/conf.d/userdata/` for EA4

If these directories do not exist, create them.

Add the Crawler Snippet and optional server variables to the
`lscache_vhosts.conf` file.

Apply these changes to all Virtual Hosts by running the following
command:

::: highlight
        /scripts/ensure_vhost_includes --all-users
::: {.admonition .note}
Note

You only need to run this command once and it will activate for all
users, including new users created by WHM later. There is no need to
edit the cPanel skeleton file.
##### Individual Virtual Host Level[¶](#individual-virtual-host-level "Permanent link"){.headerlink}

Change your working directory to:

- For EA3: `/usr/local/apache/conf/userdata/std/2_4/<user>/<domain>/`
- For EA4: `/etc/apache2/conf.d/userdata/std/2_4/<user>/<domain>/`

If your site supports HTTPS (SSL), please also change that working
directory to:

- For EA3: `/usr/local/apache/conf/userdata/ssl/2_4/<user>/<domain>/`
- For EA4: `/etc/apache2/conf.d/userdata/ssl/2_4/<user>/<domain>/`

::: {.admonition .note}
Note

The `2_4` in the path is an example. You can replace it with your
appropriate version, such as `2` or `2_2`.
If these directories do not exist, create them.

Add the Crawler Snippet and optional server variables to the
`lscache_vhosts.conf` file. This will enable the crawler for this
Virtual Host only.

Apply these changes by running the following command:

::: highlight
        /scripts/ensure_vhost_includes --user=$user
#### Plesk[¶](#plesk "Permanent link"){.headerlink}

##### Server Level[¶](#server-level_1 "Permanent link"){.headerlink} {#server-level_1}

Change your working directory to:

- `/etc/httpd/conf.d/` for CentOS
- `/etc/apache2/conf.d/` for Debian
- `/etc/apache2/conf-enabled` for Ubuntu

Add the Crawler Snippet and optional server variables to `lscache.conf`.
If it doesn't exist, create it.

##### Global Virtual Host Level[¶](#global-virtual-host-level_1 "Permanent link"){.headerlink} {#global-virtual-host-level_1}

Change your working directory to
`/usr/local/psa/admin/conf/templates/custom/domain`

Create it if it doesn't exist.

Copy
`/usr/local/psa/admin/conf/templates/default/domain/domainVirtualHost.php`
to this location.

Edit the file and add the Crawler Snippet and optional server variables
after the `mod_suexec.c` block.

Reconfigure all virtual hosts (this will regenerate new configuration
files for all vhosts), like so::

::: highlight
        /usr/local/psa/admin/bin/httpdmng --reconfigure-all
##### Individual Virtual Host Level[¶](#individual-virtual-host-level_1 "Permanent link"){.headerlink} {#individual-virtual-host-level_1}

Change your working directory to
`/var/www/vhosts/system/<domain_name>/conf/`

Create a file called `vhost.conf` if it does not already exist ( or
`vhost_ssl.conf` for HTTPS sites).

Add the Crawler Snippet and optional server variables to this file.

Reconfigure this Virtual Host (this will regenerate new configuration
files for this vhost), like so:

::: highlight
        /usr/local/psa/admin/bin/httpdmng --reconfigure-domain <domain_name>
#### DirectAdmin[¶](#directadmin "Permanent link"){.headerlink}

##### Server Level[¶](#server-level_2 "Permanent link"){.headerlink} {#server-level_2}

Add the Crawler Snippet and optional server variables to the
`/etc/httpd/conf/extra/httpd-includes.conf` file.

##### Global virtual host level[¶](#global-virtual-host-level_2 "Permanent link"){.headerlink} {#global-virtual-host-level_2}

Create a
`/usr/local/directadmin/data/templates/custom/cust_httpd.CUSTOM.2.pre`
file and add the Crawler Snippet and optional server variables to it.

Apply these changes to all Virtual Hosts by running the following
commands:

::: highlight
        cd /usr/local/directadmin/custombuild
        ./build rewrite_confs
### LiteSpeed Native[¶](#litespeed-native "Permanent link"){.headerlink}

The cache crawler is enabled by default in a LSWS Native environment.

To disable it at the Server Level, you will need to use LSWS 5.4 and
above. There was a **Cache Features** function added to control this.

In the LSWS WebAdmin interface, navigate to **LSWS Admin \>
Configuration \> Server \> Cache**. In **Cache Features**, check `On`,
uncheck `Crawler`, check `ESI`, and uncheck `Not Set`.

If `Not Set` is checked, the other three values will be ignored and the
default values will be used. (By default, all three are checked.)

[![!](../images/admin-disable-crawler-lsws-native-1.png)](../images/admin-disable-crawler-lsws-native-1.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

To disable the cache crawler at the LSWS Native Virtual Host level,
navigate to **LSWS Admin \> Configuration \> Virtual Host \> VH Name \>
Cache \>**, and set **Cache Features** in the same manner as above. If
`Not Set` is checked, the other three values will be ignored and the
server-level configuration will be inherited.

::: {.admonition .warning}
Warning

Do not set **Enable LiteMage** to `On`, as this setting will also enable
the crawler, even if `Crawler` is unchecked.
[![!](../images/admin-disable-crawler-lsws-native-vh-1.png)](../images/admin-disable-crawler-lsws-native-vh-1.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

To add any of the optional server variables, navigate to **Server \>
External App** and add the variable(s) to the **Environment** setting,
one per line. For example:

::: highlight
        CRAWLER_USLEEP=1000
        CRAWLER_LOAD_LIMIT=5.2
[![!](../images/admin-lscwp-admin-crawler.png)](../images/admin-lscwp-admin-crawler.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

[]{#testing}

### Verifying Crawler Status[¶](#verifying-crawler-status "Permanent link"){.headerlink}

To determine whether the crawler is enabled or disabled, you can check
the `phpinfo` page and look at the value of the `X-LSCACHE` server
variable. If the variable contains `crawler`, then the crawler is
enabled at the server level. If it does not contain `crawler`, as in the
example and screenshot below, then you know the crawler is disabled at
the server level.

::: highlight
        $_SERVER['X-LSCACHE'] on,esi
[![!](../images/admin-disable-crawler-lsws-native-phpinfo-1.png)](../images/admin-disable-crawler-lsws-native-phpinfo-1.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

::: {.admonition .tip}
Tip

The `X-LSCACHE` server variable only controls LiteSpeed\'s own crawler.
Third party crawlers are not beholden to the value of `X-LSCACHE` and
cannot be controlled that way.
Additionally, you can check the status of the crawler in the LiteSpeed
cache for WordPress plugin. Navigate to **Crawler \> Summary**. If
**Crawler Cron** is set to `Disable`, and you see the following warning,
then the crawler is disabled at the server level:

::: highlight
        Warning: The crawler feature is not enabled on the LiteSpeed server. Please consult your server admin.
## Changing your cache storage location[¶](#changing-your-cache-storage-location "Permanent link"){.headerlink}

If you would like to change the location where LiteSpeed stores your
cached content, you can use the `LITESPEED_DATA_FOLDER` constant.

:::: {.admonition .example}
Example

To set your cache storage directory to `cache/litespeed`, add the
following line to your `wp-config.php` file:

::: highlight
    define('LITESPEED_DATA_FOLDER', 'cache/litespeed');
## Replacing WordPress Cron[¶](#replacing-wordpress-cron "Permanent link"){.headerlink}

The WordPress cron controls the publishing of scheduled posts and the
running of LiteSpeed\'s QUIC.cloud optimization queues, among other
things.

WP cron runs when PHP is triggered. However, in order to speed up your
site, LiteSpeed Cache\'s goal is to *minimize* the usage of PHP.
Avoiding PHP is great for performance, but it\'s not so good for timely
executing scheduled tasks.

We recommend that you take control of the WP cron system away from PHP
and give to your system cron instead.

Here\'s how to set that up in cPanel:

### Disable WP cron[¶](#disable-wp-cron "Permanent link"){.headerlink}

Add the following to your site\'s `wp-config.php`:

::: highlight
    define('DISABLE_WP_CRON', true);
This tells WordPress not to automatically run its cron when PHP is
triggered.

### Add a new job to system cron[¶](#add-a-new-job-to-system-cron "Permanent link"){.headerlink}

In cPanel, navigate to **Tools \> Advanced \> Cron jobs**.

Under **Common Settings**, choose one of the predefined intervals. We
like `Once Per Five Minutes(*/5 * * * *)`, but you can run it as
frequently or as infrequently as you wish.

Set **Command** to the following:

::: highlight
    wget -q -O - https://example.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1
Press the **Add New Cron job** button.

You\'ve now set `wp-cron` to run on a reliable every-five-minutes
schedule for the `example.com` domain.

::: {.admonition .tip}
Tip

There are a number of ways to execute `wp-cron`, but in order to avoid
negating the benefits of our caching system, we recommend you use `wget`
instead of the WP CLI or PHP CLI.
### Debug[¶](#debug "Permanent link"){.headerlink}

In some cases, a strict security system may block a WP cron request. To
test whether this is happening on `example.com`, run this command, and
replace `USERNAME` with your cPanel username:

::: highlight
    wget -O /dev/null  https://example.com/wp-cron.php?doing_wp_cron >> /home/USERNAME/cron.log 2>&1
Check the `/home/USERNAME/cron.log` file and make sure the HTTP response
header is returning `200 OK`.

## Setting Up CloudFront CDN[¶](#setting-up-cloudfront-cdn "Permanent link"){.headerlink}

### Create CDN with CloudFront[¶](#create-cdn-with-cloudfront "Permanent link"){.headerlink}

[![!](../images/admin-cloudfront-1.png)](../images/admin-cloudfront-1.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

1.  Set up [AWS CloudFront
    CDN](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/GettingStarted.html)
2.  Get your CloudFront **Domain Name**
3.  Make sure your site can be visited directly through the CloudFront
    **Domain Name**

### Set Up in LSCache Plugin[¶](#set-up-in-lscache-plugin "Permanent link"){.headerlink}

1.  From the WP Dashboard, navigate to **LiteSpeed Cache \> CDN \> CDN
    Settings**.
2.  Set **Use CDN Mapping** to `ON`
3.  Enter **CDN URL** as your CloudFront Domain Name
4.  Enable the relevant **Include** buttons. e.g. **Images** and
    **CSS**. For this example, since we don\'t **Include JS**, then we
    also need to remove `.js` from **Include File Types**
5.  Set up **Original URL** as your original domain name (and sub-folder
    if you are using one)

### Verify[¶](#verify_1 "Permanent link"){.headerlink} {#verify_1}

Check that the CSS is served from CloudFront
[![!](../images/admin-cloudfront-3.png)](../images/admin-cloudfront-3.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

Check that the JS is served from the original domain
[![!](../images/admin-cloudfront-2.png)](../images/admin-cloudfront-2.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

## Turning WordPress Shortcodes into ESI Blocks[¶](#turning-wordpress-shortcodes-into-esi-blocks "Permanent link"){.headerlink}

You can turn WordPress shortcodes into ESI blocks with LiteSpeed Cache.
This allows you to cache the contents of the shortcode *in a different
way* than you\'ve cached the rest of the page. (You can learn more about
ESI [on our
blog](https://blog.litespeedtech.com/2017/09/06/wpw-esi-and-litespeed-cache/),
if this concept is new to you.)

If you have a `mycalendar` shortcode, for example, and it inserts a
calendar into your page, you might use it like this:

::: highlight
        [mycalendar month="November" year="2018"]
To turn it into an ESI block, you would instead use it like this:

::: highlight
        [esi mycalendar month="November" year="2018"]
By default, shortcode contents are stored in public cache, and the TTL
defaults to whatever value you have stored in **LiteSpeed Cache \> Cache
\> TTL \> Default Public Cache TTL**, but you can change that with a few
parameters. To store the shortcode contents in private cache for five
minutes (or 300 seconds), you can say this:

::: highlight
        [esi mycalendar month="November" year="2018" cache="private" ttl="300"]
### Limitations[¶](#limitations "Permanent link"){.headerlink}

While LiteSpeed Cache can easily *cache* your shortcode contents, it is
not possible for LSCache to *purge* the shortcode contents on demand.
Shortcode ESI blocks can naturally expire when the TTL is reached, but a
purge cannot be triggered by particular events. This makes sense,
because LiteSpeed can\'t know which occurences should trigger a purge.
Different shortcodes all have different events that render them
out-of-date, and there\'s no way for LiteSpeed to know what they are.

Using the example of the calendar plugin above, let\'s say you use the
following shortcode:

::: highlight
        [esi mycalendar month="November" year="2018"]
This will cache the `mycalendar` block for the same length of time as
your site\'s default TTL. If someone edits an event before the TTL is
reached, then the ESI block will, unfortunately, be out-of-date.

There are two ways to handle this issue:

- Have the shortcode\'s plugin author use our API to trigger a purge
  when block content changes.
- Use a short TTL, and live with the possiblity that contents may be
  out-of-date for a short time.

#### Get the Plugin Author Involved[¶](#get-the-plugin-author-involved "Permanent link"){.headerlink}

If it\'s important that the shortcode contents be purged by specific
events, you can share [this API call](../api/#purge-a-cache-tag) with
the author of the shortcode\'s plugin (just be sure to replace
`mycalendar` with the actual name of the shortcode you want to purge:

::: highlight
    do_action( 'litespeed_purge', 'esi.mycalendar' ) ;
This is the most precise way to keep the content in the shortcode
up-to-date and accurately cached according to the shortcode\'s own
requirements.

#### Do it Yourself[¶](#do-it-yourself "Permanent link"){.headerlink}

If it is not critical for the contents of the shortcode to have
up-to-the-minute accuracy, then you can use the `ttl` parameter to cache
the content for a short time. If you can live with content that is an
hour old, set `ttl="3600"`. If you are thinking more along the lines of
five minutes, set it to `ttl="300"`.

While it is possible to set the content to not be cached at all (
`ttl="0"`), it is not recommended. Any time there is uncached content on
a page, PHP must be invoked in order to generate that content. PHP uses
valuable resources, and significantly slows down a page. It\'s far
better to cache your content for a small amount of time than to set it
not to be cached at all.

## Cookies and Cache[¶](#cookies-and-cache "Permanent link"){.headerlink}

The relationship between cookies and caching can be easily
misunderstood. When you talk about \"caching cookies\" or \"not caching
cookies,\" it\'s not the *cookies themselves* that are being cached or
not cached. It\'s the *pages of the site* that are being cached or not
cached based on whether a user has those cookies stored.

Cookies, generally, are ignored unless you specify otherwise. Cookies
become important when they impact the user experience in some way.

### Cookies Set or Read by WordPress[¶](#cookies-set-or-read-by-wordpress "Permanent link"){.headerlink}

If a cookie must be *set or read by WordPress*, then it has to be
excluded from cache. And, if the cookies are set on your site (i.e. they
are not set somewhere before arriving at your site), then you will
*also* have to exclude the page that sets the cookie\'s value.

::: {.admonition .example}
Example

Let\'s say your site is part of an affiliate network. When a user
arrives at `example.com/afilliate_home` an `aff-example` cookie is set.
As they navigate the site, the cookie is updated with tracking
information.

In this case, the cookie `aff-example` must be added to the **Do Not
Cache Cookies** list in **LiteSpeed Cache \> Cache \> Excludes**, and
`^/afilliate_home` must be added to **Do Not Cache URIs** list on the
same page. (For more on the **Excludes** page, see [the screen-by-screen
documentation](../cache/#excludes-tab).)

If, in this example, the cookie was set at `example.com/afilliate_home`,
but then never referenced again, you would not have to exclude the
cookie from cache.

Alternately, if the cookie was set offsite somewhere, but was used for
tracking as the visitor wandered around your site, then you *would* have
to exclude the cookie from cache, but you *wouldn\'t* have to exclude
the `^/afilliate_home` URI.
### Cookies That Indicate Variations[¶](#cookies-that-indicate-variations "Permanent link"){.headerlink}

Sometimes cookies can be used to indicate important information about
the user to WordPress, to help determine what content should be shown to
the user. In these cases, you can use the cookies to create cache
varies. When LSCache varies on a cookie, it caches separate public
versions of the pages of the site, based on the value of the cookie.

::: {.admonition .example}
Example

Let\'s say your WP site is a shop, and you have special pricing for your
friends that is activated when they visit `example.com/friends_home`.
That page sets a `myfriend` cookie, and from that point on, every page
they visit in your shop shows pricing that is 20% less than normal. When
a visitor without the `myfriend` cookie looks at the pages in your shop,
they see regular prices.

Because the cookie is set on the `example.com/friends_home` page, that
URI will need to be excluded from cache as described above.

There are two ways to deal with the cookie itself:

- You could do as the previous example, and exclude it from cache.
  That\'s the easiest way, but it means your friends will always have
  uncached content, and that\'s not an ideal experience for them.
- You could create a cache vary based on the `myfriend` cookie.
#### Configuring a Cookie Cache Vary[¶](#configuring-a-cookie-cache-vary "Permanent link"){.headerlink}

::: {.admonition .example}
Example

Assume you have a WooCommerce site with a
\"woocommerce_products_per_page\" cookie. For some users, the value will
be `10`. For others it will be `100`. And still others may have a value
of `200`. These three scenarios require three different views.
There are two ways to handle different views based on a cookie value:

##### JavaScript[¶](#javascript "Permanent link"){.headerlink}

The more efficient option is to find a JavaScript-based solution. A
JavaScript plugin would only need to store one copy of the page and
would build the display based on the existence of the cookie.

##### Rewrite Rules[¶](#rewrite-rules "Permanent link"){.headerlink}

If a rewrite rule-based answer is preferred, the site can be configured
to vary on the cookie by adding the following rule to your site\'s
`.htaccess` file:

::: highlight
    <IfModule LiteSpeed>
    CacheLookup on
    RewriteRule .* - [E=Cache-Vary:woocommerce_products_per_page]
    </IfModule>
When a user visits your WooCommerce site, the
`woocommerce_products_per_page=xxxxxx`cookie will be created. Using the
rewrite rule above, the cache will vary on that cookie. This means the
cache will store multiple copies: one for every value of the cookie that
requests the page.

::: {.admonition .warning}
Warning

`woocommerce_products_per_page` is an example. Be sure to substitute the
appropriate cookie name.
### Further Reading[¶](#further-reading "Permanent link"){.headerlink}

You can learn more about cookies and cache varies in our [Developer\'s
Guide to Cache Vary](/lscache/devguide/advanced/#cache-varies).

## Memcached, LSMCD and Redis (Object Cache) Support in LSCWP[¶](#memcached-lsmcd-and-redis-object-cache-support-in-lscwp "Permanent link"){.headerlink}

LiteSpeed Cache for WordPress supports Object Cache.

### What is an Object Cache?[¶](#what-is-an-object-cache "Permanent link"){.headerlink}

An object cache stores the results of expensive and/or frequent database
queries in a way that makes them easy to retrieve, and eliminates the
need for repeated access to the database. Object caching greatly reduces
the time it takes to retrieve query results.

For example, your WordPress site\'s options are stored in the database.
Site options include things like the site\'s name and URL. Every time a
page is assembled for a visitor, it is necessary to access the database
to read the site options. As you can imagine, these repeated queries for
the same information represent wasted resources. With an object cache,
you can query the database once, and save the results for a set period
of time. During that time, whenever a page must be assembled, WordPress
can get the site information from the cache. Accessing object cache is a
much less resource-intensive prospect than accessing a database.

Some queries are time-consuming, and other queries are repeated
frequently. Both of these scenarios can be improved by storing the query
results in object cache.

::: {.admonition .note}
Note

If you have a site that is fully-cached by LSCWP, you won\'t use object
cache very often. Object cache is only necessary when WordPress is
building a page through PHP. If PHP is not being invoked (and minimizing
PHP usage *is* the goal with LSCache) then there are no queries to
process and therefore nothing to look up in object cache.
### How to set up Object Cache Support[¶](#how-to-set-up-object-cache-support "Permanent link"){.headerlink}

LSCWP doesn\'t provide object caching directly. Rather, it supports your
use of an external object cache such as
[Memcached](http://memcached.org/about) or LiteSpeed\'s drop-in
Memcached replacement,
[LSMCD](https://www.litespeedtech.com/open-source/litespeed-memcached).

#### Install Memcached, LSCMD or Redis and PHP Extension[¶](#install-memcached-lscmd-or-redis-and-php-extension "Permanent link"){.headerlink}

You will need a working and fully tested installation of Redis,
Memcached, or LSMCD, as well as the related PHP extension (i.e.
php-memcached or php-redis) in order to make your object cache work
properly with WordPress.

See the following for additional instructions:

- [Installing Memcached or LSMCD and
  php-memcached](https://www.litespeedtech.com/support/wiki/doku.php/litespeed_wiki:lsmcd:install-memcached)
- [Integrate Redis with WordPress through
  LSCWP](#integrate-redis-with-wordpress)
- [Using Memcached in a UNIX Socket](#using-memcached-in-a-unix-socket)
- [Using Redis in a UNIX Socket](#using-redis-in-a-unix-socket)

#### Config Object Cache in LSCWP[¶](#config-object-cache-in-lscwp "Permanent link"){.headerlink}

If you are using LSMCD, Memcached or Redis, you can set up LSCWP support
in the Cache Settings tab. Navigate to **LiteSpeed Cache \> Cache \>
Object**. You will need to give LSCWP some parameters, including where
your Memcached or LSMCD lives, which objects you\'d like to have cached,
and how long you want objects to remain in cache, among other things.

Before enabling Object Cache, the default values will already be filled
in for you.

After enabling Object Cache, the LSCache plugin will automatically run
both connection testing and Memcached/Redis extension detection.

Detailed instructions for all of these settings can be found
[here](../cache/#object-tab).

#### Set a custom prefix (optional)[¶](#set-a-custom-prefix-optional "Permanent link"){.headerlink}

The LiteSpeed Cache plugin supports object cache prefixes, which prevent
users on a server from reading the keys for other sites on the same
server. By default we generate keys based on the md5 sum of the site\'s
path, but you can define your own custom prefix for a site by setting
the `LSOC_PREFIX` variable for that site.

:::: {.admonition .example}
Example

To give a site an object cache prefix of `ABC`, add the following line
to the site\'s `wp-config.php` file:

::: highlight
    define('LSOC_PREFIX', 'ABC');
### How to Verify[¶](#how-to-verify "Permanent link"){.headerlink}

There are not too many methods to check the Object Cache log, but if you
set **LiteSpeed Cache \> Toolbox \> Debug Settings \> Debug Log** to
`ON` or `Admin IP`, and view your page source code, you should see
something like this at the bottom of the code:

::: highlight
        <!-- Object Cache [total] 5190 [hit_incall] 5056 [hit] 6 [miss_incall] 21 [miss] 107 [set] 171 -->
- `total` is the total number of objects the page requested.
- `hit_incall` is the number of objects that did not hit Memcached but
  hit the runtime data from above.
- `hit` is the number of objects retrieved from Memcached.
- `miss_incall` is the number of objects not set in runtime. That is to
  say, when php ran into the current line, no data was set before.
- `miss` is the number of objects not found in Memcached.
- `set` is the number of objects set in Memcached.

### How to Debug[¶](#how-to-debug "Permanent link"){.headerlink}

If your Connection Test shows `Failed`, there are a few things you can
try.

1.  Try `service memcached status`, to make sure the service is active
    (running).
2.  Try `ss -lptun | grep 11211`, to make sure the Memcached port is
    listening.
3.  Try `telnet localhost 11211`, to make sure you can connect to
    localhost successfully.

### Test files[¶](#test-files "Permanent link"){.headerlink}

You can create test PHP files to test connection

For Memcached:

::: highlight
        <?php

        $conn = new Memcached ;
        $address = '/path/to/memcached.sock' ; // set the address here
        $port = 0 ; // set the port
        $conn->addServer( $address, $port ) ;
        var_dump( $address ) ;
        var_dump( $port ) ;
        var_dump( $conn->getStats() ) ;
        echo '<hr>';
        var_dump($conn->getServerList());
        ?>
For redis:

::: highlight
        <?php

        $cfg_host = 'redis address' ;
        $cfg_port = '6379' ; // or 0 if use socket
        $cfg_pswd = '' ; // Set if has
        $cfg_db = 0 ;


        $conn = new Redis() ;
        $conn->connect( $cfg_host, $cfg_port ) ;
        if ( $cfg_pswd ) $conn->auth( $cfg_pswd ) ;
        if ( $cfg_db ) $conn->select( $cfg_db ) ;

        var_dump( $conn->ping() ) ; // Should give a `+PONG`
        ?>
## Integrate Redis with WordPress[¶](#integrate-redis-with-wordpress "Permanent link"){.headerlink}

Redis is an open source, in-memory data structure store, used as a
database, cache and message broker. LSCache is the world\'s fastest full
page caching. So it\'s useful for you to have both of them setup on your
server. This guide can be used both with and without a control panel.

### Install Redis daemon[¶](#install-redis-daemon "Permanent link"){.headerlink}

#### CentOS 7[¶](#centos-7 "Permanent link"){.headerlink}

1.  Add the EPEL repository:

    ::: highlight
        yum install epel-release
    :::
2.  Install Redis:

    ::: highlight
        yum install redis
    :::
3.  Start Redis:

    ::: highlight
        systemctl start redis
    :::

#### Ubuntu[¶](#ubuntu "Permanent link"){.headerlink}

1.  Install Redis:

    ::: highlight
        apt install redis
    :::
2.  Start Redis:

    ::: highlight
        systemctl start redis-server
    :::

### Install Redis PHP extension[¶](#install-redis-php-extension "Permanent link"){.headerlink}

The `phpredis` extension provides an API for communicating with the
Redis key-value store.

#### cPanel EasyApache 4 + CentOS[¶](#cpanel-easyapache-4-centos "Permanent link"){.headerlink}

The following commands work for both CentOS 8 and 7:

::: highlight
    /opt/cpanel/ea-php72/root/usr/bin/pecl install redis
    echo 'extension=redis.so' > /opt/cpanel/ea-php72/root/etc/php.d/redis.ini
Or, navigate to **WHM \> Module Installer**, choose **PHP Pecl**, select
the appropriate PHP version, and install the extension from there.

::: {.admonition .tip}
Tip

Replace `ea-php72` with whichever version of PHP you want to install the
extension for.

If you\'d like to install the extension for all available versions of
PHP with a single block of code, see [these instructions from
BigScoots](https://help.bigscoots.com/en/articles/512508-cpanel-easyapache-4-installing-redis-and-redis-php-extension).
#### With Plesk[¶](#with-plesk "Permanent link"){.headerlink}

Generally, Plesk supports the `php-redis` extension. If your Plesk
version does not support `php-redis` by default, please [see the
following instructions at
Plesk](https://talk.plesk.com/threads/redis-activation-installation-problems-plesk-onyx.342245/).

#### With DirectAdmin[¶](#with-directadmin "Permanent link"){.headerlink}

1.  DirectAdmin custombuild 2 will install `phpxx` to
    `/usr/local/phpxx`. You can go to the version you want (such as
    `php73`) to build `php-redis` through `pecl`:

    ::: highlight
        cd /usr/local/php73/bin
        ./pecl install igbinary igbinary-devel
        ./pecl install redis
    :::
2.  Check the path of extensions:

    ::: highlight
        ll /usr/local/php73/lib/php/extensions/
        drwxr-xr-x 2 root root 76 Mar  3 14:05 no-debug-non-zts-20180731
    :::
3.  Add both `igbinary.so` and `redis.so` to a newly created
    `10-directadmin.ini`:

    ::: highlight
        vi /usr/local/php73/lib/php.conf.d/10-directadmin.ini
    :::

    ::: highlight
        extension=/usr/local/php73/lib/php/extensions/no-debug-non-zts-20180731/redis.so
        extension=/usr/local/php73/lib/php/extensions/no-debug-non-zts-20180731/igbinary.so
    :::
4.  Restart LSPHP to make the change effective:

    ::: highlight
        killall -9 lsphp
    :::

#### Without a Control Panel[¶](#without-a-control-panel "Permanent link"){.headerlink}

1.  Add the LiteSpeed repository.

CentOS 7:\

::: highlight
    rpm -ivh https://rpms.litespeedtech.com/centos/litespeed-repo-1.3-1.el7.noarch.rpm
CentOS 8:\

::: highlight
    rpm -ivh https://rpms.litespeedtech.com/centos/litespeed-repo-1.3-1.el8.noarch.rpm
2\. List the LiteSpeed Redis PHP extension:

::: highlight
    yum list | awk '/lsphp/&&/redis/'
3\. Install PHP (substitute your LSPHP version if it is different):\

::: highlight
    yum -y install lsphp71-pecl-redis
### Verify the Installation[¶](#verify-the-installation "Permanent link"){.headerlink}

1.  Verify that Redis is running with `redis-cli`. If Redis is running,
    it will return: `PONG`:\

    ::: highlight
        redis-cli ping
    :::
2.  Verify by using LiteSpeed default PHP info page
    `http://Server_IP:8088/phpinfo.php`. Look for the **Redis Support**
    section.

### Try the redis-benchmark utility[¶](#try-the-redis-benchmark-utility "Permanent link"){.headerlink}

A typical example would be:\

::: highlight
    redis-benchmark -q -n 100000
Output:

::: highlight
    PING_INLINE: 31826.86 requests per second
    PING_BULK: 31595.58 requests per second
    SET: 33568.31 requests per second
    GET: 31908.10 requests per second
    INCR: 32647.73 requests per second
    LPUSH: 31220.73 requests per second
    RPUSH: 31565.66 requests per second
    LPOP: 31555.70 requests per second
If you want to run one million SET operations, using a random key for
every operation out of 100k possible keys, you can use the following
command line:\

::: highlight
    redis-cli flushall 
    redis-benchmark -t set -r 100000 -n 1000000
Output:

::: highlight
    ====== SET ======
      1000000 requests completed in 32.43 seconds
      50 parallel clients
      3 bytes payload
      keep alive: 1
    99.98% <= 10 milliseconds
    99.99% <= 11 milliseconds
    99.99% <= 12 milliseconds
    100.00% <= 17 milliseconds
    30833.75 requests per second
::: {.admonition .tip}
Tip

For more redis benchmark information, please refer to
[this](https://redis.io/topics/benchmarks).
### Integrate WordPress with Redis[¶](#integrate-wordpress-with-redis "Permanent link"){.headerlink}

[See above](#memcached-lsmcd-and-redis-object-cache-support-in-lscwp)

### Other Settings[¶](#other-settings "Permanent link"){.headerlink}

- If you want to set up Master-Slave Replication, you may need to enable
  the firewall for port 6379:

  ::: highlight
      firewall-cmd --permanent --zone=public --add-port=6379/tcp
      firewall-cmd --reload
  :::
- To automatically start Redis on boot:

  ::: highlight
      systemctl enable redis
  :::
- For disk persistence, you can set `/etc/redis.conf`:
  - `appendonly yes`
  - `appendfsync everysec`
- For more Redis Security information please refer to
  [this](https://redis.io/topics/security)

## Using Memcached in a UNIX socket[¶](#using-memcached-in-a-unix-socket "Permanent link"){.headerlink}

Memcached can run in a UNIX socket, which provides better performance
than a TCP connection.

::: {.admonition .note}
Note

If Memcached fails to start, it is usually due to permission and user
problems. Please use root privilege to execute the following
instructions, and verify that the socket path is writable to the
designated user.
### Centos7.X[¶](#centos7x "Permanent link"){.headerlink} {#centos7x}

1.  Stop Memcached `systemctl stop memcached`
2.  Copy the service file
    `cp /usr/lib/systemd/system/memcached.service /etc/systemd/system/memcached.service`
3.  Add the following content to
    `/etc/systemd/system/memcached.service`. After `[Service]`, please
    change username to the same user that runs PHP:
    `User=username Group=username` The contents of the file should look
    like this:
    [![!](../images/admin-object-cache3.jpg)](../images/admin-object-cache3.jpg){.glightbox
    data-type="image" data-width="auto" data-height="auto"
    desc-position="bottom"}
4.  Edit `/etc/sysconfig/memcached`, changing the path to your desired
    location, and the username to the same one used in Step 3:
    `OPTIONS="" USER="memcached"` becomes
    `OPTIONS="-s /path/to/memcached.sock -a 0770" USER="username"`
5.  Start Memcached again: `systemctl start memcached`
6.  Verify it started successfully: `systemctl status memcached`
7.  Check if everything is working well:
    `nc -U /path/to/memcached.sock stats`
8.  If there is still a permission issue, please check selinux status:
    `getenforce`
9.  Disable selinux if status shows `Enforcing`: `setenforce 0` (reboot
    will re-enable selinux)
10. To permanently disable selinux, edit `/etc/selinux/config`, change
    `enforcing` to `permissive`or`disabled` and then reboot.

### Centos6.X[¶](#centos6x "Permanent link"){.headerlink} {#centos6x}

1.  Stop Memcached `systemctl stop memcached`
2.  Edit `/etc/sysconfig/memcached` and change `OPTIONS="" USER=""` to
    `OPTIONS="-s /path/to/memcached.sock -a 0770" USER="username"` where
    **USER** is the same user that runs PHP.
3.  Start Memcached `service memcached start`
4.  Check if everything is working well:
    `nc -U /path/to/memcached.sock stats`
5.  If there is still a permission issue, please check selinux status:
    `getenforce`
6.  Disable selinux if status shows `Enforcing`: `setenforce 0` (reboot
    will re-enable selinux)
7.  To permanently disable selinux, edit `/etc/selinux/config`, change
    `enforcing` to `permissive`or`disabled` and then reboot.

### Ubuntu 17.10, Ubuntu 16.04, Debian 8 and Debian 9[¶](#ubuntu-1710-ubuntu-1604-debian-8-and-debian-9 "Permanent link"){.headerlink} {#ubuntu-1710-ubuntu-1604-debian-8-and-debian-9}

1.  Stop Memcached `systemctl stop memcached`
2.  Edit `/etc/memcached.conf`, comment out host and port, add socket
    path and permission `-s /path/to/memcached.sock -a 0770` and change
    `-u memcache` to `-u username` where `username` is the same user
    that runs PHP.
3.  Start Memcached again `systemctl start memcached`
4.  Check if everything is working well:
    `nc -U /path/to/memcached.sock stats`

### Ubuntu 14.04 and Debian 7[¶](#ubuntu-1404-and-debian-7 "Permanent link"){.headerlink} {#ubuntu-1404-and-debian-7}

1.  Stop Memcached `service memcached stop`
2.  Edit `/etc/memcached.conf`, comment out host and port, add socket
    path and permission `-s /path/to/memcached.sock -a 0770` and change
    `-u memcache` to `-u username` where `username` is the same user
    that runs PHP.
3.  Start Memcached again `service memcached start`
4.  Check if everything is working well:
    `nc -U /path/to/memcached.sock stats`

## Using Redis in a UNIX Socket[¶](#using-redis-in-a-unix-socket "Permanent link"){.headerlink}

Please use root privilege to execute the following instructions. If
Redis fails to start, please verify SELinux is disabled , and all
mentioned directories and files have correct permissions to the
designated user.

### Centos 7.X[¶](#centos-7x "Permanent link"){.headerlink} {#centos-7x}

1.  Stop Redis. `systemctl stop redis`
2.  Copy the service file.
    `cp /usr/lib/systemd/system/redis.service /etc/systemd/system/redis.service`
3.  Edit `/etc/systemd/system/redis.service`.
    `User=username Group=username` Change `username` to same user that
    runs PHP.
4.  Edit `/etc/redis.conf` and change the following (Change **port** to
    `0` if TCP socket is no longer needed):

    ::: highlight
            unixsocket /path/to/redis.sock
            unixsocketperm 770
            logfile /path/to/redis.log
            dir /path/to/redis
    :::
5.  Change owner of redis.conf to same username in step 3.
    `chown username:group /etc/redis.conf` If `/path/to/redis` directory
    does not exist, please manually create it, and make sure above
    mentioned `socket path`, `log path`and `dir path` and are writable
    by the designated user.
6.  Start Redis. `systemctl start redis`
7.  Verify it started successfully. `systemctl status redis`
8.  Check whether everything is working well.
    `nc -U /path/to/redis.sock info`
