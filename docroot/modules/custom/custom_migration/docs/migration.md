Drupal 7 to 8 migrations are tricky. In this document I'm going to explain some of the things that I found helped me.

Currently the [list of plugins](https://www.drupal.org/docs/8/api/migrate-api/migrate-process-plugins) on Drupal.org does not include the entity_lookup and entity_generate plugins which come with the [migrate_plus module](https://www.drupal.org/project/migrate_plus).

entity_generate extends entity_lookup by generating a new entity if it is not present. This has been very useful since the D7 site had a string field for authors on multiple content types which on the D8 site had a taxonomy. I wrote a migration which created the taxonomy terms based on the author field of one content type. I then used an entity lookup to correctly link the D8 content type with the new taxonomy terms. I then used an entity_generate for other D8 content types to first lookup the author taxonomy and create it if it did not exist. Neat! But when using entity_lookup and entity_generate it can be confusing what to put in each property, so here is my explanation.

I will explain how I did it and I hope this will help you.

Here is the relevant yaml I have added an order to help the process:

    plugin: entity_lookup
    value_key: name
    bundle_key: vid
    bundle: oc_authors
    entity_type: taxonomy_term
    source: field_author_s_

plugin: entity_lookup   # 1) Just doing a lookup since no new terms need to be created.

value_key: name         # 6) Name comes from the table field name for the actual taxonomy terms in the taxonomy_term_field_data table.

bundle_key: vid         # 5) AFAIK you can find the bundle key from looking at the taxonomy_term_field_data table and it is the field key where 'oc_authors' is. In this case 'vid'. It is also present in the taxonomy migration.

bundle: oc_authors      # 4) This is the actual name of the taxonomy terms. You can find this by going to Structure > Taxonomy > "name of your taxonomy">edit. Don't use the name, but the machine name, ie look at the edit url and it will be the last part of the url, eg, /admin/structure/taxonomy/manage/oc_authors 

entity_type: taxonomy_term # 3) I'm now jumping from the original field to the new D8 created taxonomy which is a 'taxonomy_term' entity type.

source: field_author_s_ # 2) What field are you migrating from? This is the original field. If you lookup the content type on the D7 site and choose 'Manage Fields' you will see the machine name for the needed field.



Migrating video content became easy once I realised I needed to use an entity reference field. Choose other, and then choose media for it. You can then create your own entity reference browser to suit (/admin/config/content/entity_browser) Its not under media, but content authoring.

When migrating video content from D7 to D8 there are many decisions to be made. In D7 I have a file field with a media browser widget which gives me a lot of flexibility in configuration. There is no D8 equivalent. There are three choices:

1) A [video embed field](https://www.drupal.org/project/video_embed_field) which has integration for [media entities](https://www.drupal.org/project/media_entity) which as of D8.4 are in core. 

2) A file type which if you use the [entity browser widget](https://www.drupal.org/project/entity_browser) will allow you to add in migrated youtubes, but not add new ones. There are some widgets included and some extra ones such as dropzone image upload, but no video widgets like in D7. You can add on [Slick media](https://www.drupal.org/project/slick_media) which provides a nice UI, but there is also not an easy was to add youtube videos.

3) A [video](https://www.drupal.org/project/video) field. Which is very basic and does not have entity browser integration. It does have a variety of providers such as youtube.

4) An entity reference. This is the way to go.

Migrating Group content

It became easy with this [post](https://www.drupal.org/project/group/issues/2797845#comment-11636281) thanks to [Rachel Norfolk](https://www.drupal.org/u/rachel_norfolk)

Migrating Book content

It should work: https://www.drupal.org/node/2409435 
But the best lead I have is: https://webdev.iac.gatech.edu/drupal-8-migration 