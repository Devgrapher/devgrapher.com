=== Harmonic ===
Contributors: automattic
Requires at least: 4.2
Tested up to: 4.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Image license: https://unsplash.com/license

== Description ==
Harmonic makes your content sing. No matter if you are a band looking to get a record deal, a travel blogger wanting to document your trip around the world or just someone that wants to make their home on WordPress.

* Responsive layout
* Portfolio Page Template
* Full-width Page Template
* Custom Header
* Post Formats
* Front scrolling page
* Jetpack.com compatibility for Infinite Scroll, Portfolio Custom Post Type.
* The GPL v2.0 or later license. :) Use it to make something cool.

== Installation ==

1. In your admin panel, go to Appearance -> Themes and click the Add New button.
2. Click Upload and Choose File, then select the theme's .zip file. Click Install Now.
3. Click Activate to use your new theme right away.

== Frequently Asked Questions ==

= How do I have the Front Page Template? =
Simply add a page template to a page and select 'Front Page' as the template.
From here, you can go to Apperance > Customizer and select 'Theme'. Under this section you can:
1. Choose which sections to display on the front page.
2. Add a background, change the menu text and also add a shade to the background; for each section.

= How do I add the social links to the navigation? =

Harmonic allows you display links to your social media profiles, like Twitter and Facebook, with icons.

1. Create a new Custom Menu, and assign it to the Social Links Menu location.
2. Add links to each of your social services using the Links panel.
3. Icons for your social links will automatically appear if it’s available.

== Quick Specs ==

1. The main column width is 830px.
2. The slide-out sidebar width is 25%.
3. Large images and videos have a width of 1216px.


== Changelog ==

= 7 July 2016 =
* Let WordPress manage the document title by adding theme support

= 28 June 2016 =
* Adjusting padding around list items in social media widget, to avoid icons being unclickable when they're more than two levels high.

= 10 June 2016 =
* Add support for new Portfolio CPT theme options

= 12 May 2016 =
* Add new classic-menu tag.

= 5 May 2016 =
* Move annotations into the `inc` directory.

= 4 May 2016 =
* Move existing annotations into their respective theme directories.

= 28 April 2016 =
* Added clearing after posts to prevent disappearing posts; See #3825;

= 23 February 2016 =
* Update screenshot.png with a little more business theme like image to use it in vertical test in signup.

= 6 January 2016 =
* Updating page template descriptions for accuracy.
* Remove unused styles.

= 26 November 2015 =
* Make sure only Post Author comments are bold and not the replies.

= 23 November 2015 =
* Increase Comment title line height.

= 6 November 2015 =
* Add support for missing Genericons and update to 3.4.1.

= 28 October 2015 =
* Fix navigation on portfolio pages, make sure it's not hidden behind projects; See #2982;
* Wrapping harmonic_the_site_logo function in conditional check to make it pluggable via child theme; See #3429;

= 27 October 2015 =
* Update path to fonts in genericons.css -

= 24 September 2015 =
* adding missing closing span tag;
* replacing comments_popup_link with static link to #respond, and comment_number for link text.

= 21 September 2015 =
* Change warning message to reference correct 'Theme Options' panel.

= 25 August 2015 =
* Removed negative z-index rule on the featured slide.

= 21 August 2015 =
* Reduce z-index of featured image area.

= 20 August 2015 =
* Add text domain and/or remove domain path. (E-I)

= 19 August 2015 =
* Remove padding from last slide element for smaller screens.

= 18 August 2015 =
* Rename "Theme" section in Customizer to "Theme Options".

= 12 August 2015 =
* IOncrease footer z-index.

= 31 July 2015 =
* Remove .`screen-reader-text:hover` and `.screen-reader-text:active` style rules.

= 22 July 2015 =
* Replace get_stylesheet_directory_uri() when calling up custom header image and Backstretch JS with get_template_directory_uri() to point child themes to the correct location for images/JS.

= 21 July 2015 =
* Adjust font size of entry titles for the jetpack portfolio shortcode;

= 16 July 2015 =
* Always use https when loading Google Fonts. See #3221;

= 29 June 2015 =
* Rolled back to r27892. Added updater file in error.
* Added updater.php file and include. May have removed by mistake during dotorg submission.
* Version bump - removed updater.php call in dotorg version.

= 21 June 2015 =
* Updated changelog. Version bump for WP.org resubmit.

= 9 June 2015 =
* Fixing tagline line-height; Fixes: #3050;

= 5 June 2015 =
* adding missing URL escaping;
* adding missing URL escaping;

= 2 June 2015 =
* fix `sprintf` placeholder.

= 5 March 2015 =
* Ensure Gravatar hovercards appear above comment content;

= 3 March 2015 =
* Simplify front-page.php template by combining multiple variables into one variable each, then ensure there's an error message displayed to the user when they've hidden all sections on the front page; this error message is more useful than a black screen.

= 30 January 2015 =
* version bump for sending to .org
* Variable name change for theme review request

= 27 January 2015 =
* Add license information

= 15 January 2015 =
* Removes current portfolio item on pagination
* Add in fix for single images to be wider on portfolio

= 6 January 2015 =
* Refine the masonry fix from previous commit
* Change to call on id not article for masonry

= 17 December 2014 =
* more credits updated.
* update credits and remove unneeded RTL comments.

= 25 November 2014 =
* Changelog move to file of it's own
* Changelog update
* Screenshot.png size change to scale down

= 12 November 2014 =
* Position the header and the footer so that to make z-index to work.

= 7 November 2014 =
* Increase iPad spacing on the bottom of pagination

= 6 November 2014 =
* iPad adjustment for portfolio archives
* Adds in skrollr-body div to all portfolio pages as fallback
* Fix for page scrolling
* Adds in missing skroll-body div on single portfolio

= 2 November 2014 =
* Add Jetpack prefixing to Site Logo template tags.

= 31 October 2014 =
* Define different content widths for different templates
* Standardize display of custom headers on portfolio archives;

= 30 October 2014 =
* Reduce bottom padding on last slide to avoid extra whitespace at the end.
* Add bottom padding to last slide in skrollr section so content doesn't get cut off at the bottom.

= 29 October 2014 =
* Set a minimum height on the news section of the front page template so if a user has very few posts, they will still display.

= 27 October 2014 =
* Fix typos that were causing inconsistencies with portfolio section on front page template.

= 23 October 2014 =
* force deploy pt-br translations

= 22 October 2014 =
* Update functions.php with name change

= 20 October 2014 =
* add missing .pot file

= 16 October 2014 =
* Adjust big images

= 14 October 2014 =
* Increase time before the menu kicks in and is position fixed
* Adjust height on medium screens page feature titles
* Adds in social links menu how to in readme
* Tidy up readme file
* Fix in bypost author styling

= 10 October 2014 =
* Change description to say tagline.
* Bug fix for customizer description hide

= 9 October 2014 =
* remove transparency on the page front section
* Increase padding on portfolios
* Adjust js dependencies
* Simply js scripts
* Adds in padding bottom to portfolio
* Edit link returning back
* Adds in portfolio page template div to fix up page titles
* Adds in page div to fix up the position of titles
* Adds top and bottom padding
* Reduce padding on top of portfolio items on front
* Adjust back the number of portfolio items
* Reduces amount of portfolio items on front
* Removes transparency on portfolio items
* IS fix
* Fix for black background on blog

= 7 October 2014 =
* Adjust position of loader to work better on medium screens
* Adds back in clears for the Safari mobile work around
* Reduces padding on site titles
* Make skip to slides easier to read

= 6 October 2014 =
* Add in html comment to close section on footer for feature images
* Move down loader by a little
* Page feature image fix and correct url
* Adds in theme.wordpress.com rather than wordpress.com
* Removes fixed footer on pages if larger screens
* Makes page slides not have opacity
* Template
* On pages always load the feature header
* Updating loading image
* Loader
* Adds in loader again
* Remove scrolls and make the experience better on Firefox
* Fix for overflow on widget area
* Update description and tags
* Set footer to darker color
* Fix clearing and footer position
* Adjustment for titles of pages
* Tighter mobile experience
* On safari have a sticky footer to get around the footer issue with Skrollr
* Fix for mobile safari being grumpy with transitions
* Adds fix in for single view pages
* Reduce script load
* Adds back in skrollr div
* Adds better tablet support
* adds music to the tags

= 3 October 2014 =
* Replace and optimise default images
* Add in div/class end points and general html cleanup
* Change title text description to work better for users
* Adds in section term to customizer options to be clearer
* Adds in readme.txt and changelog
* Adds screenshot
* Adds rtl support
* Adds in editor styles
* Body text color was missing so add back in
* Adds more padding to tablet and smaller screens
* Remove the extra padding on portfolio pages
* Adds in more padding when on page and fixed footer
* Offset single
* Adds in offset for scrolling single page
* revert the clearings on the site wrapper
* Fix the footer when not on single pages
* flip around the clearings to content.site-wrapper
* Add in content-wrapper clearing
* Revert clearings
* Adjust clearings
* Footer repositioning
* Remove right padding on site info
* Extra catch for Android padding
* Android fix for header image
* Decrease side padding on mobile menu
* Remove the entry meta footer class around the page footer
* Removes the large images unless it's a full width template
* Increase footer for non mobile devices
* Removes the fixed colophon
* Stop the fixed footer as it interfers on smaller pages
* Reduces line length on the portfolio page
* Increases the percentage margin on larger images
* Adds image full size to the width exception
* Makes portfolio images wider
* Fixing up some code cleaning for portfolio
* Adjust the height again on padding for larger screens and portfolio
* Decrease portfolio top when on larger screens

= 2 October 2014 =
* Removes next and previous on photos that show on front
* Adds in show and hide portfolio section for front
* Don't set articles to display: table or images within will not shrink for mobile views
* Margins on tags needed to be lessened for mobile
* Remove duplicate footer tags on page content; ensure navigation clears floats; minor style tweaks
* Only enqueue Masonry if on the Portfolio archives/templates; rearrange markup and change ID for Infinite Scroll so it works for both blog and portfolio areas
* Changes button color on hover
* Remove sidebars on portfolio archives
* Set background to none for buttons
* Remove !important color rules so annotations can work properly
* Style.css refactoring and cleaning up

= 1 October 2014 =
* Reduce size of reply-title h3
* Prevent undefined variable warnings if header image is not set.
* Give aligned images more breathing room
* Remove sidebar on single portfolio entries
* Ensure paging navigation on portfolio takes up 100% of the width; add padding to secondary sidebar for wide screens so it's not overlapped by the footer
* Fix for h2 size in widgets
* Improvements to list item styles in widgets
* Style "More Projects" area on single portfolio items; remove custom header image from portfolio archives
* Ensure portfolio archives look the same as the portfolio page template
* Fixes/standardization for Portfolio archives and templates, using the same classes and reloading masonry items properly
* Begin styling portfolio taxonomy archive pages; prefix font function with theme slug; add infinite scroll render function for archives
* Add margins to tags links
* Clean up spacing on mobile view for portfolio page template
* Reduce heading sizes for small screens
* Show page information for portfolio if enabled in the Customizer
* Link portfolio images to single portfolio item view
* Make spacing between portfolio items equal
* Styles for portfolio for mobile view
* Escaping; add portfolio paging navigation to template; style portfolio masonry columns
* Clean up single portfolio view, add edit link to single content
* Remove widgets from 404 page
* Standardize page template titles with proper caps; escape URL in content
* Sanitize post thumbnail aspect ratio in Customizer; add link around .sticky post format to match other post format icons; minor stylesheet cleanup
* Don't display widgets section if there are no active widgets on the front page
* Add WordPress.com-specific styles for widgets, change size of Rate This headline
* Change section title to Portfolio for clarity; add padding to blockquotes
* Set a max-width on the logo image so it appears the same way in the Customizer as in the front end; minor style.css cleanup
* Standardize Theme Options panel title and make strings translatable
* Minor fixes for spacing, main navigation, and entry meta hover styles
* Run CSSComb on style.css; minor tweak to navigation submenu arrow position
* Update capitalization in stylesheet header, update credit links, add wpcom to verison number
* Standardize navigation post labels
* Capital H in footer templates for theme name

= 11 September 2014 =
* Better IS footer with maonry
* Adds in portfolio CPT and also same mobile menu as Adaption

= 7 September 2014 =
* Right aligns the footer menu
* Comment styles
* Various text styles
* Remove sticky posts from showing on front page news section
* Tidy and cleanup

= 6 September 2014 =
* Adjust page height for medium screens to avoid slide overlap
* Fix slide being called on widget background
* Simplification of parallax effect

= 5 September 2014 =
* Removing preloader in prep for better method
* Adjusts the line height on h1 titles to be a bit tighter
* Medium screen adjustments
* Better header height
* Reverse header menu and title
* Full width template
* Reduce size of custom header
* Remove title text from custom header
* Better text treatment for headers
* Better blockquotes
* Increase comment bottom spacing
* removes indenting from comments on larger screens
* Better single post header placing
* Add better header styling and increase number of recent news items
* Better positioning for site logo
* Tidy up for site logo
* Fix CSS path
* remove wpstats
* Remove double blockquote on post format
* set background to white

= 4 September 2014 =
* Adds in footer padding on smaller devices
* Infinite scroll and pagination styling
* Better header section styling and spacing

= 3 September 2014 =
* Refine some styling on navigation
* Better mobile navigation
* Better grid handling for headers
* Better widget font size on front page
* Adds customizer.js
* Add better theme option handling

= 2 September 2014 =
* Infinite scroll styling
* Adjusts menu links
* Adds site logo
* Adds in wpcom specific file
* Updates content width

= 29 August 2014 =
* First round at mobile version

= 28 August 2014 =
* Update theme demo site url
* First commit onto .com of new theme Harmonic
