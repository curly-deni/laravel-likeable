<?php

return [
    'tables' => [
        'like' => [
            'entity_table' => 'likes',
            'count_table' => 'likes_count'
        ],

        'dislike' => [
            'entity_table' => 'dislikes',
            'count_table' => 'dislikes_count'
        ],

        'user_id_type' => 'id', // ulid, uuid, id
        'morph_type'   => 'uuid' // uuid, ulid, biginteger, varchar
    ],
];
