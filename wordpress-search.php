<?php

do_action( 'save_post', 'send_to_elastic_search' );

function send_to_elastic_search($post_ID, $post, $update){
    $post_to_save = Array(
        "id" => $post->ID,
        "name" => $post->name,
        "content" => strip_tags($post->content),
        "image" => get_the_post_thumbnail_url($post),
        "url" => get_permalink($post),
        "post_type" => get_post_type_object(get_post_type($post)),
        "keywords" => tags_to_array($post)
    );

    $ch = curl_init("http://search-index:9300");
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_to_save);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

}
function tags_to_array($post){
    $post_tags = get_the_tags($post);
    $tags = Array();
    if ( $post_tags ) {
        foreach( $post_tags as $tag ) {
            $tags[] = $tag->name;
        }
    }
    return $tags;
}