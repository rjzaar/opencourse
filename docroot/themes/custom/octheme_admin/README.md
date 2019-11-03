This theme was created since the ckeditor was not picking up the custom templates.

A new admin theme was needed to these templates could be picked up.
See this issue: https://www.drupal.org/project/entity_embed/issues/3026433

Then the theme added some unwanted blocks so I needed to copy some blocks to the theme hence the config folder was added.
See this issue: https://www.drupal.org/project/drupal/issues/2635978#comment-12786554
Drupal does suggest some code to fix this, but it didn't work for me: Inheriting Block Templates:
https://www.drupal.org/docs/8/theming-drupal-8/creating-a-drupal-8-sub-theme-or-sub-theme-of-sub-theme

Weirder still is how to add in the media css. For some reason the css in this theme is not picked up.
What does work is adding the css to /oc_theme/bootstrap/less/media.less
and then compiling with "lessc media.less media.css"

Also for the browser to show the new css hold down the control key and reload. or CTRL F5.

If anyone can suggest some improvements, I'd love to use them.

This is such a hack, but it now works! :)