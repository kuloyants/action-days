<?php
$return = [
    'filename' => 'registration.phtml',
    'data' => []
];

$db = Db::getInstance();
$formElements = [
    'email' => [
        'name' => 'email',
        'type' => 'text',
        'options' => [
            'label' => 'don`t fill out',
        ],
        'attributes' => [
            'id' => 'email',
            'size' => 60,
            'value' => ''
        ]
    ],
    'time' => [
        'name' => 'time',
        'type' => 'hidden',
        'attributes' => [
            'value' => time()
        ]
    ],
//    'gender' => [
//        'name' => 'gender',
//        'type' => 'select',
//        'required' => true,
//        'options' => [
//            'label' => 'common.gender',
//            'value_options' => [
//                'male' => 'common.gender.male',
//                'female' => 'common.gender.female'
//            ]
//        ],
//        'attributes' => [
//            'id' => 'gender',
//            'value' => 'common.gender.male',
//            'data-validation-required-message' => 'validate.message.required'
//        ]
//    ],
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
    'playerEmail' => [
        'name' => 'playerEmail',
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
    'playerPhone' => [
        'name' => 'playerPhone',
        'type'  => 'tel',
        'required' => true,
        'options' => [
            'label' => 'common.mobilephone',
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
    if(isset($_POST['email']) && $_POST['email']) {
        throw new Exception('email field has been filled out => spam suspicion');
    }

    if ( isset($_POST['time']) && is_numeric($_POST['time'])) {
        $posted = intval($_POST['time']);
        $sendTime = (time() - $posted);
        if ($sendTime < 10 || $sendTime > 36000) {
            throw new Exception('that was too fast => spam suspicion');
        }
    } else {
        throw new Exception('No time parameter given or time parameter is not integer => spam suspicion');
    }

    foreach($_POST as $element => $value) {
        if (!array_key_exists($element, $formElements)) {
            throw new Exception('unregistered post param: ' . $element . ' => '. $value);
        }
        $_POST[$element] = trim($value);
    }

    $errorMessages = [];
    foreach ($formElements as $element => $config) {
        if (
            (isset($config['required']) && $config['required'] == true)
            &&
            (!isset($_POST[$element]) || '' == $_POST[$element])
        ) {
            $errorMessages[$element] = translate('error.validate.required.' . $element);
        }
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
              player (created, firstname, surname, email, phone, country_code)
            VALUES
              (NOW(), ?, ?, ?, ?, ?)';
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new Exception('Es konnte kein SQL-Query vorbereitet werden: '.$db->error);
    }

    $stmt->bind_param('sssss', $_POST['firstname'], $_POST['surname'], $_POST['playerEmail'], $_POST['playerPhone'], $_POST['country']);
    if (!$stmt->execute()) {
        throw new Exception ('Query konnte nicht ausgeführt werden: '.$stmt->error);
    }

    $sql = "INSERT INTO
              player_registration (player_id, reg_status, start_day, start_time, message)
            VALUES
              ({$stmt->insert_id}, 'registered', ?, ?, ?)";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new Exception('Es konnte kein SQL-Query vorbereitet werden: '.$db->error);
    }
    $stmt->bind_param('sss', $_POST['start_day'], $_POST['start_time'], $_POST['message']);
    if (!$stmt->execute()) {
        throw new Exception ('Query konnte nicht ausgeführt werden: '.$stmt->error);
    }
    $return['data']['valid'] = true;

    include("service/Mail.php");

    $headers = "From: info@action-days.de\r\n" .
        "Content-Type: text/plain;charset=UTF-8\r\n";
    mail(
        $_POST['playerEmail'],
        getRegistrationSuccessSubject($GLOBALS['locale']),
        getRegistrationSuccessMail($_POST['firstname'], $_POST['surname'], $GLOBALS['locale']),
        $headers
    );
    mail(
        'info@action-days.de',
        "Anmeldung {$_POST['firstname']} {$_POST['surname']}",
        "Nachricht: {$_POST['message']}",
        $headers
    );
    return $return;
} else {
    $return['data']['formElements'] = $formElements;
    return $return;
}
?>
