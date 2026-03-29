# Database[¶](#database "Permanent link"){.headerlink}

Database optimization is a useful tool when it comes to speeding up your
site. LSCWP\'s DB Optimizer (available as of v1.2.1) gives you an easy
way to execute some of these tasks in your WordPress database. [Learn
more on our
blog.](https://blog.litespeedtech.com/2017/09/20/wpw-database-optimization/)

## Manage Tab[¶](#manage-tab "Permanent link"){.headerlink}

[![!LSCWP Database Section Manage
Tab](../images/database01.png)](../images/database01.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

You\'ll notice that some of the buttons have teal check-marks while
others have red X\'s. The check marks indicate that the area has already
been cleaned-up. If you see a red X, that is your opportunity to do some
optimization.

### Clean All[¶](#clean-all "Permanent link"){.headerlink}

Press this button to clean up everything at once. It executes all of the
listed cleanups, except for **Optimize Tables**.

### Post Revisions[¶](#post-revisions "Permanent link"){.headerlink}

Removes all old post revisions from the database. You will sacrifice
your ability to go into any of your post and restore previous versions
of them. Only the currently-published version of each post will be
saved.

### Orphaned Post Meta[¶](#orphaned-post-meta "Permanent link"){.headerlink}

Cleans up any metadata records that were left behind from previously
deleted posts.

### Auto Drafts[¶](#auto-drafts "Permanent link"){.headerlink}

When you edit a post, WordPress automatically saves drafts now and then.
This is to protect you in case you unexpectedly lose your connection, or
your computer crashes. If you know that all of your posts are either
published or saved correctly as drafts, then it is safe to remove the
automatic drafts.

### Trashed Posts[¶](#trashed-posts "Permanent link"){.headerlink}

This option permanently deletes any posts or pages that have been placed
in the Trash.

### Spam Comments[¶](#spam-comments "Permanent link"){.headerlink}

There should be no need to keep comments that have been marked as spam.
This option erases them permanently.

### Trashed Comments[¶](#trashed-comments "Permanent link"){.headerlink}

This option permanently deletes any comments that have been placed in
the Trash.

### Trackbacks & Pingbacks[¶](#trackbacks-pingbacks "Permanent link"){.headerlink}

When other blogs link to you, it can create trackbacks or pingbacks.
Some WordPress themes display these in the comments section. If
displaying these external links is not important to you, press this
button to clear them from the database.

### Expired Transients[¶](#expired-transients "Permanent link"){.headerlink}

Transients are the result of a form of caching that can happen in the
WordPress database with the results of remote API calls. This option
clears all of the expired transients from the database.

### All Transients[¶](#all-transients "Permanent link"){.headerlink}

This option clears all of the transients in the database, whether
expired or not. NOTE: It is normal for there to still be some transients
left after cleaning. Transients are caused by plugins, and are
regenerated every time you reload a page.

### Optimize Tables[¶](#optimize-tables "Permanent link"){.headerlink}

Use this button to optimize the database tables.

::: {.admonition .note}
Note

This function is not included in a **Clean All**.
### Database Table Engine Converter[¶](#database-table-engine-converter "Permanent link"){.headerlink}

If there are database tables listed in this area, then you can use this
tool to convert the tables from the MyISAM engine to the InnoDB engine,
if you wish. Click the `Convert to InnoDB` link next to any table that
you wish to convert.

### Database Summary[¶](#database-summary "Permanent link"){.headerlink}

This area summarizes the top twenty options that are autoloaded with
each page request. The size given for each option is in bytes.

## DB Optimization Settings Tab[¶](#db-optimization-settings-tab "Permanent link"){.headerlink}

These settings give you more control over what is deleted during
Database Optimization.

[![!LSCWP Database Section Settings
Tab](../images/database02.png)](../images/database02.png){.glightbox
data-type="image" data-width="auto" data-height="auto"
desc-position="bottom"}

### Revisions Max Number[¶](#revisions-max-number "Permanent link"){.headerlink}

*0*

Specify the number of most recent revisions to keep when cleaning up
revisions.

::: {.admonition .example}
Example

If you would feel more comfortable having one older revision saved for
each post, set this to `1`.
### Revisions Max Age[¶](#revisions-max-age "Permanent link"){.headerlink}

*0*

Revisions newer than this many days will be kept when cleaning up
revisions.

::: {.admonition .example}
Example

Set to `30` if you want to keep any revisions from within the last
thirty days.
