<?php

// Pola ACF 

function em_acf_add_local_field_group() {
    
    acf_add_local_field_group([
        'key' => 'em_group',
        'title' => 'Event Manager',
        'fields' => [
			[
				'key' => 'em_field_date',
                'name' => 'em_field_date',
				'label' => 'Data i godzina rozpoczęcia',
				'type' => 'date_time_picker',
                'required' => 1,

                'display_format' => 'd.m.Y H:i',
                'return_format' => 'd.m.Y H:i',
                'first_day' => 1,
			],
			[
				'key' => 'em_participant_limit',
                'name' => 'em_participant_limit',
				'label' => 'Limit uczestników',
				'type' => 'number',
                'required' => 1,
			],
            [
                'key' => 'em_description',
                'name' => 'em_description',
				'label' => 'Opis/szczegóły',
				'type' => 'textarea',
                'required' => 1,
            ]
		],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'event',
                ]
            ]
        ],
    ]);
}
