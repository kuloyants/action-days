<?php
$return = [
    'filename' => 'registration.phtml',
    'data' => []
];

$db = Db::getInstance();
$formElements = [
    'gender' => [
        'name' => 'gender',
        'type' => 'select',
        'required' => true,
        'options' => [
            'label' => 'common.gender',
            'value_options' => [
                'male' => 'common.gender.male',
                'female' => 'common.gender.female'
            ]
        ],
        'attributes' => [
            'id' => 'gender',
            'value' => 'common.gender.male',
            'data-validation-required-message' => 'validate.message.required'
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
            'data-validation-required-message' => 'validate.message.required',
            'data-validation-validemail-message' => 'validation.message.invalid_email'
        ]
    ],
    'message' => [
        'name' => 'message',
        'type'  => 'textarea',
        'required' => false,
        'options' => [
            'label' => 'form.label.message.optional',
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
        'required' => true,
        'options' => [
            'label' => 'common.country'
        ],
        'attributes' => [
            'data-validation-required-message' => 'validate.message.required'
        ]
    ],
    'start_day' => [
        'name' => 'start_day',
        'type' => 'select',
        'required' => true,
        'options' => [
            'label' => 'form.label.start_day',
            'value_options' => [
                'friday' => 'form.start_day.value.friday',
                'saturday' => 'form.start_day.value.saturday'
            ]
        ],
        'attributes' => [
            'id' => 'start_day',
            'data-validation-required-message' => 'validate.message.required'
        ]
    ],
    'start_time' => [
        'name' => 'start_time',
        'type' => 'select',
        'required' => true,
        'options' => [
            'label' => 'form.label.start_time',
            'value_options' => [
                '1000' => 'form.start_time.value.1000',
                '1200' => 'form.start_time.value.1200',
            ],
        ],
        'attributes' => [
            'id' => 'start_time',
            'data-validation-required-message' => 'validate.message.required'
        ]
    ]
];

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    foreach($_POST as $element => $value) {
        if (!array_key_exists($element, $formElements)) {
            throw new Exception('unregistered post param: ' . $element . ' => '. $value);
        }
    }

    $errorMessages = [];
    foreach ($formElements as $element => $config) {
        if (
            (isset($config['required']) && $config['required'] == true)
            &&
            (!isset($_POST[$element]) || '' == trim($_POST[$element]))
        ) {
            $errorMessages[$element] = translate('error.validate.required.' . $element);
        }

//        if (!isset($_POST[$element])) {
//            $_POST[$element] = null;
//        }
    }
    if (!empty($errorMessages)) {
        $return['data'] = [
            'valid' => false,
            'errorMessages' => $errorMessages,
            'formElements' => $formElements
        ];
        return $return;
    }

    $sql = 'INSERT INTO
              player (created, firstname, surname, email, country_code, gender)
            VALUES
              (NOW(), ?, ?, ?, ?, ?)';
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new Exception('Es konnte kein SQL-Query vorbereitet werden: '.$db->error);
    }

    $stmt->bind_param('sssss', $_POST['firstname'], $_POST['surname'], $_POST['email'], $_POST['country'], $_POST['gender']);
    if (!$stmt->execute()) {
        throw new Exception ('Query konnte nicht ausgeführt werden: '.$stmt->error);
    }

    $sql = "INSERT INTO
              player_registration (player_id, reg_status, start_day, start_time, message)
            VALUES
              ({$stmt->insert_id}, 'pending', ?, ?, ?)";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new Exception('Es konnte kein SQL-Query vorbereitet werden: '.$db->error);
    }
    $stmt->bind_param('sss', $_POST['start_day'], $_POST['start_time'], $_POST['message']);
    if (!$stmt->execute()) {
        throw new Exception ('Query konnte nicht ausgeführt werden: '.$stmt->error);
    }
    $return['data']['valid'] = true;

//    mail('v.kuloyants@letspool24.com', 'Bestätigung', 'Sie wurden registriert', 'From: valery.kuloyants@live.de');

    return $return;
} else {
    $return['data']['formElements'] = $formElements;
    return $return;
}
?>
