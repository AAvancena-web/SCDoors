# SC Garage Doors 2026 theme files

Drop-in files for the existing `siteorigin-corp-child` theme. The static design
in `../index.html` stays as the reference build.

## Why this shape

**ACF registered in PHP, not the admin UI.** `inc/acf-fields.php` calls
`acf_add_local_field_group()`, so the fields travel with the theme, cannot be
deleted by accident in wp-admin, and need no import step on the live site.
Values still live in the database as ordinary post meta, so nothing is lost if
you later switch to the UI.

**Seeder runs itself, once.** `inc/seeder.php` hooks `admin_init`, checks an
option, and stops. No seeder page to create or visit. Two properties make it
safe to leave in the repository on a live site:

* every write goes through `scd_seed_set()`, which returns early when the field
  already holds a value, so re-running can only fill gaps, never overwrite;
* it only touches the page carrying the homepage template, plus its own options.
  It creates no pages and deletes nothing.

Bump `SCD_SEED_VERSION` to seed newly added fields. `wp scd seed` re-runs it
from the command line.

**Everything is prefixed `scd-`.** The design's original class names
(`.container`, `.btn`, `.section`, `.site-header`, `.contact`) all collide with
rules already in the child theme's `style.css`. Prefixing avoids an `!important`
war: the legacy selectors simply no longer match the new markup, and the old
WPBakery pages keep rendering exactly as they do today.

**`.scd-scope` carries the base typography, and it is not on `<body>`.** The
stylesheet still needs element level rules for `p`, `a`, `h1`&ndash;`h6` and
`img`. Putting the scope on `<body>` would push those onto every legacy page.
It sits on the header, footer, drawer, inner banner and the 2026 page content
instead.

**Contact Form 7 ids are stored, not hard coded.** The current `header.php`
hard codes `id="27565b8"`. The seeder creates the two forms if they are missing
and records their ids under SC Doors settings, and `scd_form()` reads from
there, so a form rebuild does not mean editing a template.

## Install

1. Copy everything except this file into `wp-content/themes/siteorigin-corp-child/`.
   `header.php` and `footer.php` replace the current ones, so keep a copy first.
2. Add one line to the bottom of the existing `functions.php`:

   ```php
   require_once get_stylesheet_directory() . '/inc/bootstrap.php';
   ```

3. Load any admin page. The seeder runs and reports what it did in a notice.
4. Pages &rarr; the page you want as the homepage &rarr; Template &rarr;
   **SC Homepage 2026**. Update, then reload an admin page so the seeder fills
   that page.
5. Appearance &rarr; Menus: assign the two new footer locations, *Footer: Quick
   Links* and *Footer: Our Services*.
6. SC Doors &rarr; settings: confirm the phone, email, address, social links and
   the two Contact Form 7 ids.
7. Upload the imagery into the Homepage 2026 fields.

## Before going live

Two things need a human decision.

**Confirm or delete the unverified claims.** The seeder deliberately leaves
opening hours and the top bar note empty, and the templates hide those rows
when empty, because those are claims about the business that were never
confirmed. The same applies to anything you add for "fully insured", "same day
service" or a response time promise.

**Reviews are real ones only.** The seeder writes the three testimonials that
are on the live site today, in their published wording. Do not add invented
ones to fill the row.

## Inner pages

`template-parts/global/inner-hero.php` renders on any singular page or post that
has a featured image, a banner heading or banner copy, giving the same split as
the homepage: copy left, quote form right. Per page control lives in the Page
Banner field group, which reuses the field names already in the current
`header.php` (`banner_heading`, `banner_heading_sub`, `banner_content`,
`banner_form`), so existing content keeps working.

## Files

```
header.php                     global header, top bar, nav, phone circle, burger
footer.php                     global footer, back to top, floating call button
page-templates/template-home.php   assignable homepage template
template-parts/global/         mobile drawer, inner page banner
template-parts/home/           the eleven homepage sections
inc/bootstrap.php              the single require target
inc/setup.php                  asset loading and body classes
inc/acf-fields.php             field groups in code
inc/seeder.php                 one time, idempotent content seeder
inc/helpers.php                field access, inline SVG icons, CF7 wrapper
inc/drawer-walker.php          menu walker for the drawer accordion
assets/css/scd.css             the design, prefixed and scoped
assets/js/scd.js               scroll effects, drawer, FAQ, counters
```
