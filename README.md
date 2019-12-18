# Opencourse


This is the opencourse project

# Development

Anonymous can't access various pages. This needs to set once a site is setup since it cannot be incorporated into a feature.
I may add a product to fix this issue.

For template development:
cd opencourse/docroot/themes/custom/oc_theme
npm install
npm install gulp-less
chmod -R u+x node_modules/
gulp

Some css may need to be changed to - instead of _

        "group-3061321-30.patch": "https://www.drupal.org/files/issues/2019-06-24/group-3061321-30.patch",

The correct sequence for oc modules to load is
standalone: oc_site oc_field2para oc_blocks


oc_fields/ 
oc_taxonomy/oc_lp
oc_link/oc_image
oc_book
oc_doc
oc_sequence/ oc_forum
oc_home/ oc_groups (home should require groups?)
  oc_content

  - oc_home

  - 
  - 
  - oc_sequence

