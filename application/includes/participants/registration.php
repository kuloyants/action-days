<?php
$return = [
    'filename' => 'registration.phtml',
    'data' => []
];

$db = getDb();
$formElements = [
    'gender' => [
        'name' => 'gender',
        'type' => 'select',
        'options' => [
            'label' => 'common.gender',
            'value_options' => [
                'male' => 'common.gender.male',
                'female' => 'common.gender.female'
            ]
        ],
        'attributes' => [
            'value' => 'common.gender.mail',
        ]
    ],
    'firstname' => [
        'name' => 'firstname',
        'type'  => 'text',
        'required' => true,
        'options' => [
            'label' => 'common.firstname',
        ],
        'attributes' => [
            'id' => 'firstname',
            'data-validation-required-message' => 'validate.message.required'
        ]
    ],
    'surname' => [
        'name' => 'surname',
        'type'  => 'text',
        'required' => true,
        'options' => [
            'label' => 'common.surname',
        ],
        'attributes' => [
            'id' => 'surname',
            'data-validation-required-message' => 'validate.message.required'
        ],
    ],
    'email' => [
        'name' => 'email',
        'type'  => 'email',
        'required' => true,
        'options' => [
            'label' => 'common.email',
        ],
        'attributes' => [
            'id' => 'email',
            'data-validation-required-message' => 'validate.message.required'
        ]
    ],
    'message' => [
        'name' => 'message',
        'type'  => 'textarea',
        'options' => [
            'label' => 'common.message',
        ],
        'attributes' => [
            'id' => 'message',
        ]
    ],
    'phone' => [
        'name' => 'phone',
        'type'  => 'tel',
        'required' => true,
        'options' => [
            'label' => 'common.phone',
        ],
        'attributes' => [
            'id' => 'phone',
            'data-validation-required-message' => 'validate.message.required'
        ]
    ],
    'country' => [
        'name' => 'country',
        'type'  => 'select',
        'options' => [
            'label' => 'common.country'
        ],
        'attributes' => [
        ]
    ],
    'start_day' => [
        'name' => 'start_day',
        'type' => 'select',
        'options' => [
            'label' => 'common.start_day',
            'value_options' => [
                'friday' => 'form.start_day.friday',
                'saturday' => 'form.start_day.saturday'
            ]
        ],
        'attributes' => [
        ]
    ],
    'start_time' => [
        'name' => 'start_time',
        'type' => 'select',
        'options' => [
            'label' => 'common.start_time',
            'value_options' => [
                '9:00' => '9:00',
                '11:00' => '11:00',
                '13:00' => '13:00',
                '15:00' => '15:00',
                '17:00' => '17:00',
            ],
        ],
        'attributes' => [
        ]
    ]
];

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    foreach (array_keys($formElements) as $elementName) {
        if (!isset($_POST[$elementName])) {
            die ('Benutzen sie nur Formulare von der Homepage.');
        } elseif ('' == $content = trim($_POST[$elementName])) {
            die ('Bitte füllen sie das Formular vollständig aus.');
        }
    }
    $sql = 'INSERT INTO player (created, firstname, surname, email, country_code, gender)
            VALUES(NOW(), ?, ?, ?, ?, ?)';
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        die ('Es konnte kein SQL-Query vorbereitet werden: '.$db->error);
    }
    $stmt->bind_param('sssss', $_POST['firstname'], $_POST['surname'], $_POST['email'], $_POST['country'], $_POST['gender']);
    if (!$stmt->execute()) {
        die ('Query konnte nicht ausgeführt werden: '.$stmt->error);
    }

    $sql = "INSERT INTO player_registration (player_id, registration_status, start_day, start_time)
            VALUES({$stmt->insert_id}, 'pending', ?, ?)";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        die ('Es konnte kein SQL-Query vorbereitet werden: '.$db->error);
    }
    $stmt->bind_param('ss', $_POST['start_day'], $_POST['start_time']);
    if (!$stmt->execute()) {
        die ('Query konnte nicht ausgeführt werden: '.$stmt->error);
    }
    // @todo REMOVE;
    var_dump((__METHOD__ ? __METHOD__ : __FILE__) . ' (' . __LINE__ . '): ','DONE' );die;
} else {
    $return['data']['formElements'] = $formElements;

    return $return;
}
?>
