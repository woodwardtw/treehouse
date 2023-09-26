<?php if( have_rows('content') ): ?>
    <?php while( have_rows('content') ): the_row(); ?>
    <?php $index = get_row_index(); ?>   
        <!--IMAGE LOOP-->            
        <?php if( get_row_layout() == 'text_with_image' ): 
            $title = get_sub_field('title');
            $slug = sanitize_title($title);
            $content = get_sub_field('content');
            $image = get_sub_field('image');
            $direction = get_sub_field('image_align');
            $color = get_sub_field('color');
            $order_left = ' order-first ';
            $order_right = ' order-last ';
            if($direction == 'right'){
                $order_left = ' order-last ';
                $order_right = ' order-first ';
            }
            ?>
        <div class='row topic-row <?php echo $color;?>'>
				<div class='col-md-5<?php echo $order_left;?>'>    
                    <figure>
                        <?php echo wp_get_attachment_image( $image['ID'], 'large', '', array('class'=>'img-fluid alignright') ); ?>
                        <figcaption><?php echo $image['caption']; ?></figcaption>
                    </figure>
                </div>
            <div class='col-md-1 order-2'></div>
            <div class='col-md-5 <?php echo $order_right;?>'>
                <?php if($title) :?>
                    <h2 id="<?php echo $slug;?>"><?php echo $title; ?></h2>
                <?php endif;?>
                <?php echo $content; ?>
			</div>
        </div>
        <?php endif; ?>
        <!--full block loop-->
         <?php if( get_row_layout() == 'full_block' ): 
            $title = get_sub_field('title');
            $content = get_sub_field('content');
            $slug = sanitize_title($title);
        ?>
            <div class='row topic-row full-width-row'>
				<div class='col-md-6 offset-md-3'>
                    <?php if($title):?>
                        <h2 id="<?php echo $slug?>"><?php echo $title;?></h2>
                    <?php endif;?>
                    <?php echo $content;?>
                </div>
            </div>
        <?php endif;?>
         <!--person loop-->
         <?php if( get_row_layout() == 'people' ): 
            $persons = get_sub_field('individuals');
            $title = get_sub_field('title');
            $slug = sanitize_title($title);
        ?>
            <div class='row topic-row full-width-row d-flex justify-content-around people-row'>
            <?php if($title):?>
                <div class="col-md-12">
                    <h2 id="<?php echo $slug?>"><?php echo $title;?></h2>
                </div>
            <?php endif;?>
				<?php                   
                    foreach($persons as $person){
                        $post_id = $person;
                        $name = get_the_title($post_id);
                        $title = get_field('job_title', $post_id);
                        $img = dlinq_person_thumb_check($post_id, 'medium', 'free-bio-pic img-fluid');
                        $email_html = '';
                        if(get_field('email', $post_id)){
                            $email = get_field('email', $post_id);
                            $email_html = "<a href='mailto:{$email}' aria-lable='Email to {$name}'>✉️ Connect</a>";
                        }
                        $link = get_permalink( $post_id);
                        echo "
                        <div class='col-md-4 person-holder'>
                            <div class='person-block'>
                                {$img}
                                <a href='{$link}'><h2 class='small-name'>{$name}</h2></a>
                                <div class='title'>{$title}</div>
                                <div class='small-contact'>
                                    {$email_html}
                                </div>
                            </div>
                        </div>
                        ";
                    }
                ?>
            </div>
        <?php endif;?>
         
        <!--CUSTOM POSTS BY CATEGORY-->
        <?php if( get_row_layout() == 'posts' ):
        $title = 'Learn more';
        if(get_sub_field('title')){
             $title = get_sub_field('title');
        }
        $slug = sanitize_title( $title);
            echo "<div class='row topic-row full-width-row'>
                    <div class='col-md-8 offset-md-2'>
                        <h2 id='{$slug}'>{$title}</h2>
                    </div>
                        ";
         
            $cats = get_sub_field('category');
            $type = get_sub_field('post_type');
            $args = array(
                'category__and' => $cats,
                'post_type' => $type,
                'posts_per_page' => 10,
                'paged' => get_query_var('paged')
            );
            $the_query = new WP_Query( $args );

            // The Loop
            if ( $the_query->have_posts() ) :
                while ( $the_query->have_posts() ) : $the_query->the_post();
                // Do Stuff
                $title = get_the_title();
                $url = get_the_permalink();
                if(get_the_content()){
                     $excerpt = wp_trim_words(get_the_content(), 30);
                }
                if(get_field('project_summary')){
                   $excerpt =  wp_trim_words(get_field('project_summary'), 30); 
                }
               
                echo "
                    <div class='col-md-8 offset-md-2'>
                        <div class='post-block'>
                            <a class='post-link stretched-link' href='{$url}'>
                                <h3>{$title}</h3>                           
                                <p>{$excerpt}</p>
                             </a>
                        </div>
                    </div>
                ";
                endwhile;
            endif;

            // Reset Post Data
            wp_reset_postdata();
            echo "</div>";
        ?>
        <?php endif;?>
    <?php endwhile; ?>
<?php endif; ?>