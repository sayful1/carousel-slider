# Carousel Slider

**Contributors:** sayful  
**Tags:** woocommerce, shortcode, images, carousel, carousel slider, image carousel, product carousel, slider, owl carousel  
**Requires at least:** 4.5  
**Tested up to:** 4.8  
**Stable tag:** 1.7.4  
**License:** GPLv3  
**License URI:** http://www.gnu.org/licenses/gpl-3.0.html  

The Easiest Way to Create SEO friendly Image, Logo, Video, Post, Product Carousel and Content Carousel.

## Description

**The Easiest Way to Create SEO friendly Image, Logo, Video, Post and Product Carousel.**
Carousel Slider is a touch enabled WordPress plugin that lets you create highly customizable, stylish responsive carousel slider. With Carousel Slider, you can create image carousel using media gallery or custom url, post carousel, video carousel. We have integrated [Owl Carousel 2](http://www.owlcarousel.owlgraphic.com/) into our plugin for the ultimate device support.

**If you like this plugin, please give us [5 star](https://wordpress.org/support/plugin/carousel-slider/reviews/?rate=5#new-post) to encourage for future improvement.**

#### Full Feature Set

* **Multiple types carousel**, images from media gallery, images from URL, videos from youtube and vimeo, posts, and WooCommerce products carousel slider
* **Posts carousel**, support Specific posts, Post Categories, Post Tags, Posts per page, Date range query and ordering
* **Video carousel**, support custom height and width (Currently only support video from Youtube and Vimeo)
* **WooCommerce Product carousel**, support Product Categories, Product Tags, Specific Products, Featured Products, Recent Products, Sale Products, Best-Selling Products, Top Rated Products
* Options to hide/show product Title, Rating, Price, Cart Button, Sale Tag, Wishlist Button, Quick View button and options to change color for Title, Button Background, Button text
* **Fully responsive**, configure the number of items to display for desktop, small desktop, tablet and mobile devices
* **Lightweight**, only loads stuff when carousel is used
* **Navigation and pagination**, choose what type of navigation is displayed for your carousel with unlimited colors option
* **Works great in touch devices**, Touch and Grab enabled
* Supported in all major browsers
* CSS3 3D Acceleration
* Multiple carousel on same page
* Lazy load images
* Support image title, caption, link url
* and more options

##### Images Carousel using gallery images
https://www.youtube.com/watch?v=ZzI1JhElrxc

##### Images Carousel using custom URLs
https://www.youtube.com/watch?v=a7hqn1yNzwM

##### Posts Carousel
https://www.youtube.com/watch?v=ImJB946azy0

##### WooCommerce Products Carousel
https://www.youtube.com/watch?v=yiAkvXyfakg

##### With Page Builder by SiteOrigin
https://www.youtube.com/watch?v=-OaYQZfr1RM

##### With WPBakery Visual Composer
https://www.youtube.com/watch?v=4LhDXH81whk

##### Using as a Widget
https://www.youtube.com/watch?v=kYgp6wp27lM

## Installation

* From your WordPress dashboard go to **Plugins > Add New**.
* Search for **Carousel Slider** in **Search Plugins** box.
* Find the WordPress Plugin named **Carousel Slider** by **Sayful Islam**.
* Click **Install Now** to install the **Carousel Slider** Plugin.
* The plugin will begin to download and install.
* Now just click **Activate** to activate the plugin.

If you still need help. visit [WordPress codex](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

## Frequently Asked Questions

##### How to use shortcode/slider in theme template file?
By default, Carousel slider only load scripts when you use shortcode in TinyMCE editor or use Carousel Slider Widget. This helps to reduce two unnecessary HTTP request for Style and Script when you are not using the slider.
If you want to use shortcode in your theme directly, you need to tell the plugin to load scripts for your perticular page or template in your (theme/child theme) functions.php file. To load scripts, you can use the following filter hook.
For example, if you want to use shortcode only for your front page, add the following filter hook in your theme functions.php file.

```
add_filter('carousel_slider_load_scripts', 'carousel_slider_load_scripts');
function carousel_slider_load_scripts( $load_scripts ) {
	// To use only for front page
	if ( is_front_page() ) {
		return true;
	}
	return $load_scripts;
}
```

or write the following code to always load the slider scripts and style.

```
add_filter('carousel_slider_load_scripts', 'carousel_slider_load_scripts');
function carousel_slider_load_scripts( $load_scripts ) {
	return true;
}
```

Now you can use the following function at your theme template file replacing `YOUR_SLIDER_ID` with actual carousel slider id.

```
echo do_shortcode('[carousel_slide id='YOUR_SLIDER_ID']");
```

## Screenshots

1. Carousel slider admin page
2. Front-end example of posts carousel slider.
3. Front-end example of videos carousel slider.
4. Front-end example of images carousel slider.
5. Front-end example of products carousel slider.
6. Front-end example of products Quick View.
7. Front-end example of image lightbox.

## Changelog

##### Version 1.8.0-dev
* Added - Arrow Nav: Position (Inside or Outside)
* Added - Arrow Nav: Custom Size
* Tweak - Arrow Nav: Always visibility
* Added - Arrow Nav: Background and background hover color
* Added - Bullet Nav: Position (Left, Center or Right)
* Added - Bullet Nav: Custom Size
* Tweak - Arrow Nav: Option to enable visibility only on hover
* Tweak - Alpha color picker for choosing color
* Added - Content Carousel/Slider
* Added - Content Carousel: Add unlimited number of slide
* Added - Content Carousel: Delete and sort(up, down, top, bottom) slide
* Added - Content Carousel: Slide background with color, image and more
* Added - Content Carousel: Slide content with wp_editor()


##### Version 1.7.4 (updated on 2017-08-26)
* Fixed - Fixed syntax error for short array syntax on PHP 5.3

##### Version 1.7.3 (updated on 2017-08-25)
* Added - JSON-LD structured data for post carousel.
* Added - Added uninstall.php file to remove data on uninstall.
* Added - Added admin only notice for deprecated shortcode.
* Fixed - get_product() deprecated notice on WooCommerce version 3
* Fixed - Fixed error on WooCommerce version 2.6.* and 2.5.*
* Tweak - Remove dependency over jquery.livequery.js and update admin javascript.
* Tweak - Refactor code with WordPress coding style.

##### Version 1.7.2 (updated on 2017-04-07)
* New   - Structured data generator using JSON-LD format for Product Carousel and Gallery Image Carousel.

##### Version 1.7.1 (updated on 2017-04-05)
* New   - WooCommerce 3.0.0 compatibility
* Fixed - get_product() has been replaced with wc_get_product()
* Fixed - get_rating_html() has been replaced with wc_get_rating_html()
* Fixed - Fixed id was called incorrectly (Product properties should not be accessed directly) notice.

##### Version 1.7.0 (updated on 2017-03-12)
* Added - WooCommerce Product carousel.
* Added - WooCommerce Product Quick View button.
* Added - WooCommerce Product wishlist button (Required YITH WooCommerce Wishlist).
* Added - Unlimited colors for Product button and title.
* Added - Lightbox support for images carousel.

##### Version 1.6.3 (updated on 2017-01-31)
* Added - Added title option for carousel slider widget.
* Tweak - Tweak at post carousel admin interface.
* Tweak - Added new carousel_slider_load_scripts filter hook for modifying scripts load conditions.
* And some other tweak for coding improvement.

##### Version 1.6.2 (updated on 2017-01-27)
* Fixed  - Removed PHP trait to add support to PHP 5.3 as some users still use PHP 5.3
* Fixed  - Fixed issue to make all carousel slider type to **Image Carousel** on activation.

##### Version 1.6.1 (updated on 2017-01-12)
* Added   - Show posts by Post Categories, Post Tags.
* Added   - Option to choose query type - Latest Posts, Date Range, Post Categories, Post Tags or Specific Posts.
* Added   - Added option to set link target for image carousel when click on image.
* Fixed   - Fixed issue for not saving value from multiple select when no/empty value.
* Updated - Pre and Next Navigation buttons has been changed by inline SVG images.

##### Version 1.6.0 (updated on 2016-12-22)
* Added   - "Link to URL" field at WordPress media uploader for linking carousel image.
* Added   - Video carousel slider with custom height and width.
* Added   - Posts carousel slider supporting Specific posts, Posts per page, Date range query and ordering posts.
* Added   - Images carousel using custom URL with sorting, custom title, custom caption and link to URL features.
* Added   - New breakpoint for Extra Large Desktop Layout
* Updated - Owl Carousel v2.2.0 javaScript library
* Shortcode **[carousel]** and **[item]** has been deprecated but backup up for previous versions.
* Documentation enhancement and improvements.

##### Version 1.5.3 (updated on 2016-12-08)
* Fixed   - Issue for not saving zero value for margin.

##### Version 1.5.2 (updated on 2016-12-03)
* Added   - Added options to show title and caption on carousel item.
* Added   - Lazy Load of images.
* Added   - Support multiple carousel slide at same page.
* Added   - New Carousel Slider Widget to add carousel at widget. Especially helpful for page builder that use widget like "SiteOrigin Page Builder".
* Added   - New Element added for WPBakery Visual Composer page builder. If you are using WPBakery Visual Composer page.
* Merged  - All CSS styles in on file.

##### Version 1.5.1 (updated on 2016-08-11)
* Version compatibility check and some bug fix.

##### Version 1.5.0 (updated on 2016-02-05)

* Added graphical interface to add carousel
* Added shortcode attributes 'inifnity_loop', 'autoplay_timeout', 'autoplay_speed', 'slide_by', 'nav_color', 'nav_active_color', 'margin_right'
* Removed shortcode 'carousel_slider' and 'all-carousels'
* Removed shortcode attributes 'pagination_speed', 'rewind_speed', 'scroll_per_page', 'pagination_numbers', 'auto_height', 'single_item'

##### Version 1.4.0

* Added option to add custom image size
* Added option to link each slide to a URL
* Added option to open link in the same frame as it was clicked or in a new window or tab.
* Added feature to add multiple slider at page, post or theme by custom post category slug
* Re-write with Object-oriented programming (OOP)

##### Version 1.3.0

* Tested with WordPress version 4.1

##### Version 1.2.0

* Fixed bugs regarding shortcode.
* Added href="" to add link to post, page or media
* Translation ready

##### Version 1.1.0 (updated on 2014-07-15)

* Fixed some bugs.

##### Version 1.0.0 (added on 2014-06-30)
* Initial release.