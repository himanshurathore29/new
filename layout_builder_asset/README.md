INTRODUCTION
============

This enhances the Layout Builder with the functionality to add assets(JS / CSS) in the Layout Builder Blocks and allows user to add block specific CSS and JS by adding unique class name in the class text field provided by the module.
It has utilized the [Asset Injector](https://www.drupal.org/project/asset_injector) and [Layout Builder Styles](https://www.drupal.org/project/layout_builder_styles) logic and combined it with Layout builder.


REQUIREMENTS
============
This module requires Drupal Core Layout Builder  module.

INSTALLATION
============

Install as you would normally install a contributed Drupal module. See:
   https://drupal.org/documentation/install/modules-themes/modules-8
   for further information.


CONFIGURATION
=============
1. Add CSS and JS assets in the Layout Builder Blocks directly.
2. Configure user permissions in Administration » People » Permissions:
    * Administer CSS Layout Builder assets
       Update CSS added to the layout builder blocks through config entity.
    * Administer JS Layout Builder assets
       Update JS added to the layout builder blocks through config entity.
3. Incase there is a need to update existing Layout Builder JS and CSS Assets which have been 
   already added in the Layout Builder Block,
   It is recommended to go to the specific block where JS and CSS has been added and update it.
   OR
   Go to  Administration » Configuration » Development » Layout Builder Assets
   and edit the asset needed. Upon editing an existing asset, caches will automatically be flushed.
   This may result in a slowly loading page after submitting the form.
