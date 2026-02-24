<?php

return [

    /*
    | Mensajes de validación en español con tono cercano (Blueprint 4.2).
    */

    'accepted' => 'Necesitas aceptar :attribute para continuar.',
    'active_url' => 'La URL que ingresaste en :attribute no parece válida.',
    'after' => 'La fecha en :attribute debe ser posterior a :date.',
    'after_or_equal' => 'La fecha en :attribute debe ser igual o posterior a :date.',
    'alpha' => ':Attribute solo puede contener letras.',
    'alpha_dash' => ':Attribute solo puede contener letras, números y guiones.',
    'alpha_num' => ':Attribute solo puede contener letras y números.',
    'array' => ':Attribute debe ser una lista de elementos.',
    'before' => 'La fecha en :attribute debe ser anterior a :date.',
    'before_or_equal' => 'La fecha en :attribute debe ser igual o anterior a :date.',
    'between' => [
        'numeric' => ':Attribute debe estar entre :min y :max.',
        'file' => ':Attribute debe pesar entre :min y :max kilobytes.',
        'string' => ':Attribute debe tener entre :min y :max caracteres.',
        'array' => ':Attribute debe tener entre :min y :max elementos.',
    ],
    'boolean' => ':Attribute debe ser verdadero o falso.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'date' => 'La fecha en :attribute no tiene un formato válido.',
    'date_equals' => 'La fecha en :attribute debe ser igual a :date.',
    'date_format' => 'La fecha en :attribute no coincide con el formato :format.',
    'different' => ':Attribute y :other deben ser diferentes.',
    'digits' => ':Attribute debe tener exactamente :digits dígitos.',
    'digits_between' => ':Attribute debe tener entre :min y :max dígitos.',
    'dimensions' => 'Las dimensiones de la imagen en :attribute no son válidas.',
    'distinct' => ':Attribute tiene un valor que aparece más de una vez.',
    'email' => 'Ingresa un correo electrónico válido en :attribute.',
    'ends_with' => ':Attribute debe terminar con: :values.',
    'exists' => 'El valor seleccionado en :attribute no existe.',
    'file' => ':Attribute debe ser un archivo.',
    'filled' => 'Por favor completa el campo :attribute.',
    'gt' => [
        'numeric' => ':Attribute debe ser mayor que :value.',
        'file' => ':Attribute debe pesar más de :value kilobytes.',
        'string' => ':Attribute debe tener más de :value caracteres.',
        'array' => ':Attribute debe tener más de :value elementos.',
    ],
    'gte' => [
        'numeric' => ':Attribute debe ser mayor o igual que :value.',
        'file' => ':Attribute debe pesar al menos :value kilobytes.',
        'string' => ':Attribute debe tener al menos :value caracteres.',
        'array' => ':Attribute debe tener al menos :value elementos.',
    ],
    'image' => ':Attribute debe ser una imagen.',
    'in' => 'El valor que elegiste en :attribute no es válido.',
    'in_array' => ':Attribute no existe en :other.',
    'integer' => ':Attribute debe ser un número entero.',
    'ip' => ':Attribute debe ser una dirección IP válida.',
    'ipv4' => ':Attribute debe ser una dirección IPv4 válida.',
    'ipv6' => ':Attribute debe ser una dirección IPv6 válida.',
    'json' => ':Attribute debe ser un texto JSON válido.',
    'lt' => [
        'numeric' => ':Attribute debe ser menor que :value.',
        'file' => ':Attribute debe pesar menos de :value kilobytes.',
        'string' => ':Attribute debe tener menos de :value caracteres.',
        'array' => ':Attribute debe tener menos de :value elementos.',
    ],
    'lte' => [
        'numeric' => ':Attribute debe ser menor o igual que :value.',
        'file' => ':Attribute debe pesar como máximo :value kilobytes.',
        'string' => ':Attribute debe tener como máximo :value caracteres.',
        'array' => ':Attribute debe tener como máximo :value elementos.',
    ],
    'max' => [
        'numeric' => ':Attribute no puede ser mayor que :max.',
        'file' => ':Attribute no puede pesar más de :max kilobytes.',
        'string' => ':Attribute no puede tener más de :max caracteres.',
        'array' => ':Attribute no puede tener más de :max elementos.',
    ],
    'mimes' => ':Attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => ':Attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => ':Attribute debe ser al menos :min.',
        'file' => ':Attribute debe pesar al menos :min kilobytes.',
        'string' => ':Attribute debe tener al menos :min caracteres.',
        'array' => ':Attribute debe tener al menos :min elementos.',
    ],
    'not_in' => 'El valor que elegiste en :attribute no es válido.',
    'not_regex' => 'El formato de :attribute no es válido.',
    'numeric' => ':Attribute debe ser un número.',
    'present' => ':Attribute debe estar presente.',
    'regex' => 'El formato de :attribute no es válido.',
    'required' => 'Este dato es necesario para continuar.',
    'required_if' => 'Necesitamos :attribute cuando :other es :value.',
    'required_unless' => 'Necesitamos :attribute a menos que :other esté en :values.',
    'required_with' => 'Necesitamos :attribute cuando :values está presente.',
    'required_with_all' => 'Necesitamos :attribute cuando :values están presentes.',
    'required_without' => 'Necesitamos :attribute cuando :values no está presente.',
    'required_without_all' => 'Necesitamos :attribute cuando ninguno de :values están presentes.',
    'same' => ':Attribute y :other deben coincidir.',
    'size' => [
        'numeric' => ':Attribute debe ser :size.',
        'file' => ':Attribute debe pesar :size kilobytes.',
        'string' => ':Attribute debe tener :size caracteres.',
        'array' => ':Attribute debe tener :size elementos.',
    ],
    'starts_with' => ':Attribute debe comenzar con: :values.',
    'string' => ':Attribute debe ser texto.',
    'timezone' => ':Attribute debe ser una zona horaria válida.',
    'unique' => 'Ya existe un registro con ese :attribute. Por favor elige otro.',
    'uploaded' => ':Attribute no se pudo cargar correctamente.',
    'url' => 'El formato de :attribute no es una URL válida.',
    'uuid' => ':Attribute debe ser un UUID válido.',

    /*
    | Mensajes cercanos para atributos específicos de TasteTracker (Blueprint 4.2)
    */
    'custom' => [
        'name' => [
            'required' => 'Necesitamos el nombre de la sucursal para continuar.',
            'max' => 'El nombre de la sucursal es demasiado largo (máximo 255 caracteres).',
        ],
        'email' => [
            'required' => 'El correo electrónico es necesario para crear la cuenta.',
            'unique' => 'Ya existe una cuenta con ese correo. ¿Quizás ya tienes una cuenta?',
            'email' => 'Por favor ingresa un correo electrónico válido.',
        ],
        'password' => [
            'required' => 'Por favor crea una contraseña para proteger tu cuenta.',
            'min' => 'Tu contraseña debe tener al menos :min caracteres.',
        ],
        'role' => [
            'required' => 'Es necesario asignar un rol al usuario.',
            'in' => 'El rol seleccionado no es válido.',
        ],
        'branch_id' => [
            'required' => 'Por favor asigna al usuario a una sucursal.',
            'exists' => 'La sucursal seleccionada no existe o fue desactivada.',
        ],
    ],

    /*
    | Atributos en español (para mensajes de error más legibles)
    */
    'attributes' => [
        'name' => 'nombre',
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'role' => 'rol',
        'branch_id' => 'sucursal',
        'is_active' => 'estado activo',
        'photo' => 'foto',
        'gender' => 'género',
    ],

];
