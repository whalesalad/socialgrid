## The SocialGrid WordPress Plugin

By Michael Whalen

SocialGrid began as a simple addition to my [Tasty Theme](http://whalesalad.com/tasty), and has evolved into a next generation WordPress plugin available for anyone incorporate into their site.

**REQUIREMENTS: SocialGrid *requires PHP5***.

### Updates / Changelog

* **v2.3 (12/30/09)** -  Added a feature to disable tooltips completely.
* **v2.2 (12/29/09)** - Implemented a simple check to ensure that PHP4 users are notified that their servers are out of date, with instructions on how to switch to PHP5.
* **v2.11 (12/28/09)** - Added Technorati to the mix of new icons. It behaves similarly to the RSS icon, in that it doesn't require any input and generates your Technorati profile link automatically.
* **v2.1 (12/22/09)** - Added a TON of new icons (including Posterous) as well as new features like a master reset and the ability to display smaller 16x16 icons rather than the traditional 32x32.
* **v2.02 (12/15/09)** - Improved compatibility with many themes with some !important action on the CSS and fixed an issue where services could be added more than once.
* **v2.01 (12/03/09)** - Fixed a minor issue with the RSS feed url, and css styling.
* **v2.0  (11/26/09)** - This is the initial release of the SocialGrid plugin!

### Installation

* Upload the `socialgrid` directory to the `/wp-content/plugins/` directory, and enable it in the plugins area of your WordPress admin.
* In the **Widgets** area of your site, drag the SocialGrid widget into your sidebar and the desired location.
* In the **Appearance** area of your site, click on **SocialGrid Options** and you'll be greeted with the page to configure your SocialGrid.

### Problems

If you encounter any problems with SocialGrid, be sure to [contact me](http://whalesalad.com/contact) or [create an issue](http://github.com/whalesalad/socialgrid/issues) on Github.

If you are unable to modify SocialGrid settings, chances are you don't have PHP5 enabled on your server. This reminds me, I need to add a check to my various admin panels to prevent modification if there is anything less than PHP5 installed. If you don't have PHP5 enabled, contact your webhost or search around your hosting admin panel for a setting to enable it.

### Modification

At the moment, SocialGrid isn't very extensible and modifiable. It is, however, hosted here on Github for all of you to play with and modify. I encourage customization and feature suggestions!