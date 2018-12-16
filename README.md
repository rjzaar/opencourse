
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

Apache Environment Presumed

There is a post install script applied to add "+Options +FollowSymLinks"
to .htaccess which is needed for Apache since a composer install strips it out.
