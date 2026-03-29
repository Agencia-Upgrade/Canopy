# Multisite Networks[¶](#multisite-networks "Permanent link"){.headerlink}

A WordPress multisite network is a collection of WordPress sites that
share a single WordPress installation. Each of these sites is called a
\"subsite.\"

This is different than simply having multiple independent WordPress
sites on your server. In a multisite network, a single admin has the
ability to control all of the subsites, and all of the subsites share
the same plugins and themes.

To learn more about WordPress multisite networks, please see [the
WordPress
tutorial](https://learn.wordpress.org/tutorial/introduction-to-wordpress-multisite-networks/).

## Network Admin[¶](#network-admin "Permanent link"){.headerlink}

In a WordPress multisite installationimages/multisite_board has a **My
Sites** dropdown on the Admin Bar. The first in the dropdown list is
**Network Admin** followed by a list of each of the sites in the
network.

Multisite networks share a common `.htaccess` file, which means that any
LiteSpeed Cache options that change `.htaccess` must apply to the whole
network. These options have been relocated to the **Network Admin**
area.

Some other LiteSpeed Cache options will be found only in the **Network
Admin** section. This allows for easier setup, and for all of the
network subsites to share a common configuration that is maintained at
the top level.

All of the LiteSpeed Cache settings listed below can be found at the
network level. Anything not listed here is accessible through the
network\'s subsites.

::: {.admonition .tip}
Tip

Any setting you change at the network level will apply to *all
subsites*.
### Dashboard[¶](#dashboard "Permanent link"){.headerlink}

[![](../images/multisite01.png)](../images/multisite01.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

This area provides a network-level view of the QUIC.cloud usage for all
of the subsites in the network. Each site is listed separately. Subsites
have their own versions of this section.

See the non-multisite documentation to [learn more about the
**Dashboard** view](../dashboard/).

### General[¶](#general "Permanent link"){.headerlink}

[![](../images/multisite02.png)](../images/multisite02.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

These settings are only at the network level and are not available to
subsites:

- **Automatically Upgrade**
- **Use Primary Site Configuration**: This option uses the network\'s
  primary site as a template to easily apply settings throughout the
  entire network. It will overwrite the subsites\' configurations with
  the network-level configuration. 
- **Guest Mode**

See the non-multisite documentation to [learn more about the **General**
options](../general/).

### Cache[¶](#cache "Permanent link"){.headerlink}

#### Cache Tab[¶](#cache-tab "Permanent link"){.headerlink}

[![](../images/multisite03.png)](../images/multisite03.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

These settings are only at the network level and are not available to
subsites:

- **Network Enable Cache**: This setting enables caching by default on
  all subsites in the network. Subsites can choose to turn caching `ON`
  or `OFF`, or they can choose to abide by the **Network Enable Cache**
  setting by choosing `Use Network Admin Setting`.
- **Cache PHP Resources**
- **Cache Mobile**
- **List of Mobile User Agents**
- **Drop Query String**

See the non-multisite documentation to [learn more about the **Cache
Tab** options](../cache/).

#### Purge Tab[¶](#purge-tab "Permanent link"){.headerlink}

[![](../images/multisite07.png)](../images/multisite07.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

This setting is only at the network level and is not available to
subsites:

- **Purge All On Upgrade**

See the non-multisite documentation to [learn more about the **Purge
Tab** options](../cache/#purge-tab).

#### Excludes[¶](#excludes "Permanent link"){.headerlink}

[![](../images/multisite08.png)](../images/multisite08.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

These settings are only at the network level and are not available to
subsites:

- **Do Not Cache Cookies**
- **Do Not Cache User Agents**

See the non-multisite documentation to [learn more about the **Excludes
Tab** options](../cache/#excludes-tab).

#### Object Tab[¶](#object-tab "Permanent link"){.headerlink}

[![](../images/multisite09.png)](../images/multisite09.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

This entire tab exists only at the network level and is not available to
subsites.

For explanations of all of the settings, please see [the **Object Tab**
documentation](../cache/#object-tab) for non-multisite networks.

#### Browser Tab[¶](#browser-tab "Permanent link"){.headerlink}

[![](../images/multisite10.png)](../images/multisite10.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

This entire tab exists only at the network level and is not available to
subsites.

For explanations of all of the settings, please see [the **Browser Tab**
documentation](../cache/#browser-tab) for non-multisite networks.

#### Advanced Tab[¶](#advanced-tab "Permanent link"){.headerlink}

[![](../images/multisite11.png)](../images/multisite11.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

These settings are only at the network level and are not available to
subsites:

- **Login Cookie**
- **Vary Cookies**

See the non-multisite documentation to [learn more about the **Advanced
Tab** options](../cache/#advanced-tab).

### Image Optimization[¶](#image-optimization "Permanent link"){.headerlink}

#### Image Optimization Settings Tab[¶](#image-optimization-settings-tab "Permanent link"){.headerlink}

[![](../images/multisite04.png)](../images/multisite04.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

This setting is only at the network level and is not available to
subsites:

- **Next-Gen Image Format**

See the non-multisite documentation to [learn more about the **Image
Optimization Settings Tab**
options](../imageopt/#image-optimization-settings-tab).

### Database[¶](#database "Permanent link"){.headerlink}

[![](../images/multisite05.png)](../images/multisite05.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

The **Database** tools are available both at the network level and the
subsite level. When executed at the network level, the optimizations
will apply to all subsites in the network. When executed at the subsite
level the optimizations will only apply to that subsite.

For explanations of all of the optimizations, please see [the
**Database** documentation](../database/) for non-multisite networks.

### Toolbox[¶](#toolbox "Permanent link"){.headerlink}

#### Purge Tab[¶](#purge-tab_1 "Permanent link"){.headerlink} {#purge-tab_1}

[![](../images/multisite06.png)](../images/multisite06.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

Most of the **Purge** tools are available both at the network level and
the subsite level. When executed at the network level, the purges will
apply to all subsites in the network. When executed at the subsite level
the purges will only apply to that subsite.

This setting is only at the network level and is not available to
subsites:

- **Empty Entire Cache**

For explanations of all of the purges, please see [the **Purge Tab**
documentation](../toolbox/#purge-tab) for non-multisite networks.

::: {.admonition .tip}
Tip

The same purge options can be found on the Admin Bar, and the same rules
apply as to the scope of these tools.
#### View .htaccess tab[¶](#view-htaccess-tab "Permanent link"){.headerlink} {#view-htaccess-tab}

[![](../images/multisite12.png)](../images/multisite12.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

This entire tab exists only at the network level and is not available to
subsites.

For explanations of all of the elements, please see [the **View
.htaccess Tab** documentation](../toolbox/#view-htaccess-tab) for
non-multisite networks.

#### Debug Settings tab[¶](#debug-settings-tab "Permanent link"){.headerlink}

[![](../images/multisite13.png)](../images/multisite13.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

This entire tab exists only at the network level and is not available to
subsites.

For explanations of all of the settings, please see [the **Debug
Settings Tab** documentation](../toolbox/#debug-settings-tab) for
non-multisite networks.

#### Log View Tab[¶](#log-view-tab "Permanent link"){.headerlink}

[![](../images/multisite14.png)](../images/multisite14.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

This entire tab exists only at the network level and is not available to
subsites.

For explanations of all of the buttons, please see [the **Log View Tab**
documentation](../toolbox/#log-view-tab) for non-multisite networks.

#### Beta Test Tab[¶](#beta-test-tab "Permanent link"){.headerlink}

[![](../images/multisite15.png)](../images/multisite15.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

This entire tab exists only at the network level and is not available to
subsites.

For explanations of all of the options, please see [the **Beta Test
Tab** documentation](../toolbox/#beta-test-tab) for non-multisite
networks.
