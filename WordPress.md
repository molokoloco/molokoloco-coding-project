# Documentation #

  * http://codex.wordpress.org
  * http://codex.wordpress.org/Template_Tags
  * http://codex.wordpress.org/Function_Reference


![http://codex.wordpress.org/images/1/18/Template_Hierarchy.png](http://codex.wordpress.org/images/1/18/Template_Hierarchy.png)


# Some sources #

  * http://carsonified.com/blog/dev/create-your-first-wordpress-custom-post-type/
  * http://www.herewithme.fr/wordpress-plugins/simple-tags#adv-use-tag-cloud

# Some snippets #


### A Page of Posts ###

A Page Template that displays posts from a specific category depending on a Custom Field assigned to a Page. In this example the value of the Custom Field "category" is retrieved and used as the category to retrieve the posts in that category. So if the category of posts you want to display is called "Events" then assign the Custom Field "category" with a value of "Events" to the Page. Note that this will adhere to pagination rules meaning that four (4) posts will display per page with links to older/newer posts provided. This is designed to work with the WordPress Default theme (aka Kubrick), but should work with other themes with a little modification.

Save this to pageofposts.php and then assign PageofPosts as the Template when creating the action Page:

```
<?php
/*
Template Name: PageOfPosts
*/

get_header(); ?>

<div id="content" class="narrowcolumn">

<?php
if (is_page() ) {
$category = get_post_meta($posts[0]->ID, 'category', true);
}
if ($category) {
  $cat = get_cat_ID($category);
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  $post_per_page = 4; // -1 shows all posts
  $do_not_show_stickies = 1; // 0 to show stickies
  $args=array(
    'category__in' => array($cat),
    'orderby' => 'date',
    'order' => 'DESC',
    'paged' => $paged,
    'posts_per_page' => $post_per_page,
    'caller_get_posts' => $do_not_show_stickies
  );
  $temp = $wp_query;  // assign orginal query to temp variable for later use   
  $wp_query = null;
  $wp_query = new WP_Query($args); 
  if( have_posts() ) : 
		while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
	    <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
        <small><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></small>
        <div class="entry">
          <?php the_content('Read the rest of this entry »'); ?>
        </div>
        <p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments »', '1 Comment »', '% Comments »'); ?></p>
      </div>
    <?php endwhile; ?>
    <div class="navigation">
      <div class="alignleft"><?php next_posts_link('« Older Entries') ?></div>
      <div class="alignright"><?php previous_posts_link('Newer Entries »') ?></div>
    </div>
  <?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?>

	<?php endif; 
	
	$wp_query = $temp;  //reset back to original query
	
}  // if ($category)
?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
```

### Categories list ###

```
wp_list_categories('orderby=name&title_li=' . __('Categories') . '' );
```

### Post Pagination ###

```

<?php if (have_posts()) : ?>
<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;  query_posts("posts_per_page=50&paged=$paged"); ?>
<?php while (have_posts()) : the_post(); ?>

<ul>
<li><a href="<?php the_permalink() ?>" rel="bookmark"  title="<?php the_title(); ?>"><?php the_title();  ?></a></li>
</ul>

<?php endwhile; ?>

```


### Scheduling Posts for RSS ###

From : http://webdesignledger.com/tips/13-useful-code-snippets-for-wordpress-development

If you regularly publish articles and you care about the quality of your posts then this is a good hack for you. The main purpose of this hack is that it lets you schedule your posts to be viewed in your RSS at a later time, this will allow you enough time to get those last minute fixes and additions in before your post is forever published in your feed. Place the following code in your .htaccess file. In order to change the length of the delay, change the value of the $waitvariable on line 9.

```
function publish_later_on_feed($where) {
	global $wpdb;

	if ( is_feed() ) {
		// timestamp in WP-format
		$now = gmdate('Y-m-d H:i:s');

		// value for wait; + device
		$wait = '5'; // integer

		// http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_timestampdiff
		$unit = 'MINUTE'; //MINUTE, HOUR, DAY, WEEK, MONTH, YEAR

		// add SQL-sytax to default $where
		$where .= " AND TIMESTAMPDIFF($unit, $wpdb->posts.post_date_gmt, '$now') > $wait ";
	}
	return $where;
}

add_filter('posts_where', 'publish_later_on_feed');
```

### External access... ###

```
$link = get_permalink($post->ID);
$key = 'YOURKEY';

$url = 'http://api.backtype.com/tweetcount.xml?q='.$link.'&amp;key='.$key;
$request = new WP_Http();
$result = $request->request($url);

echo $result['body'].' tweets';
```