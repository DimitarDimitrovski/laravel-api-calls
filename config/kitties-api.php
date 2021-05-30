<?php
$apiUrl = env('KITTY_API_LIST', 'https://api.thecatapi.com/v1/images/search?limit=60&size=med&order=');
$userId = env('KITTY_API_SUB_ID', 'loremski-user');

return [
    'user_id' => $userId,
    'auth' => [
        'api_key' => env('KITTY_API_KEY', '1c224d92-8b0f-4d00-9d73-72d6fef23316')
    ],
    'endpoints' => [
        'kitties_list' => $apiUrl,
        'kitty_breeds' => env('KITY_API_BREEDS', 'https://api.thecatapi.com/v1/breeds'),
        'kitty_breed_search' => env('KITTY_API_BREED_SEARCH', 'https://api.thecatapi.com/v1/breeds/search?q='),
        'kitty_breed_images' => env('KITTY_API_BREED_IMAGES', 'https://api.thecatapi.com/images/search?limit=4&size=med&breed_id='),
        'kitty_user_images' => env('KITTY_API_USER_IMAGES', 'https://api.thecatapi.com/v1/images?limit=30&size=small&order=desc&sub_id=' . $userId),
        'kitty_image' => env('KITTY_API_IMAGE', 'https://api.thecatapi.com/v1/images/'),
        'kitty_image_upload' => env('KITTY_API_IMAGE_UPLOAD', 'https://api.thecatapi.com/v1/images/upload'),
        'kitty_favourites' => env('KITTY_API_FAVOURITE', 'https://api.thecatapi.com/v1/favourites'),
        'kitty_favourite_images' => env('KITTY_API_FAVOURITE_IMAGES', 'https://api.thecatapi.com/v1/favourites?limit=30&sub_id=' . $userId),
        'kitty_user_votes' => env('KITTY_API_VOTES', 'https://api.thecatapi.com/v1/votes?limit=30&sub_id=' . $userId),
        'kitty_image_vote' => env('KITTY_API_VOTE', 'https://api.thecatapi.com/v1/votes'),
    ]
];
