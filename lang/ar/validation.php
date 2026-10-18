<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted' => 'يجب الموافقة على حقل :attribute.',
    'accepted_if' => 'يجب الموافقة على حقل :attribute عندما تكون قيمة :other هي :value.',
    'active_url' => 'يجب أن يكون حقل :attribute رابطًا صالحًا.',
    'after' => 'يجب أن يكون حقل :attribute تاريخًا بعد :date.',
    'after_or_equal' => 'يجب أن يكون حقل :attribute تاريخًا يساوي أو يأتي بعد :date.',
    'alpha' => 'يجب أن يحتوي حقل :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي حقل :attribute على أحرف أو أرقام أو شرطات أو شرطات سفلية فقط.',
    'alpha_num' => 'يجب أن يحتوي حقل :attribute على أحرف وأرقام فقط.',
    'any_of' => 'قيمة حقل :attribute غير صالحة.',
    'array' => 'يجب أن يكون حقل :attribute مصفوفة.',
    'array_keys' => 'يجب أن يحتوي حقل :attribute على المفاتيح التالية فقط: :values.',
    'ascii' => 'يجب أن يحتوي حقل :attribute على أحرف وأرقام ورموز أحادية البايت فقط.',
    'base64' => 'يجب أن يكون حقل :attribute نصًا صالحًا بترميز Base64.',
    'before' => 'يجب أن يكون حقل :attribute تاريخًا قبل :date.',
    'before_or_equal' => 'يجب أن يكون حقل :attribute تاريخًا يساوي أو يسبق :date.',

    'between' => [
        'array' => 'يجب أن يحتوي حقل :attribute على عدد عناصر بين :min و:max.',
        'file' => 'يجب أن يكون حجم الملف :attribute بين :min و:max كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute بين :min و:max.',
        'string' => 'يجب أن يتراوح طول حقل :attribute بين :min و:max حرفًا.',
    ],

    'boolean' => 'يجب أن تكون قيمة حقل :attribute إما صحيحة أو خاطئة.',
    'can' => 'يحتوي حقل :attribute على قيمة غير مسموح بها.',
    'confirmed' => 'تأكيد حقل :attribute غير متطابق.',
    'contains' => 'حقل :attribute لا يحتوي على قيمة مطلوبة.',
    'current_password' => 'كلمة المرور الحالية غير صحيحة.',
    'date' => 'يجب أن يكون حقل :attribute تاريخًا صالحًا.',
    'date_equals' => 'يجب أن يكون حقل :attribute مساويًا للتاريخ :date.',
    'date_format' => 'يجب أن يتوافق حقل :attribute مع التنسيق :format.',
    'decimal' => 'يجب أن يحتوي حقل :attribute على :decimal من المنازل العشرية.',
    'declined' => 'يجب رفض حقل :attribute.',
    'declined_if' => 'يجب رفض حقل :attribute عندما تكون قيمة :other هي :value.',
    'different' => 'يجب أن يختلف حقل :attribute عن حقل :other.',
    'digits' => 'يجب أن يتكون حقل :attribute من :digits أرقام.',
    'digits_between' => 'يجب أن يتكون حقل :attribute من عدد أرقام بين :min و:max.',
    'dimensions' => 'أبعاد الصورة في حقل :attribute غير صالحة.',
    'distinct' => 'يحتوي حقل :attribute على قيمة مكررة.',
    'doesnt_contain' => 'يجب ألا يحتوي حقل :attribute على أي من القيم التالية: :values.',
    'doesnt_end_with' => 'يجب ألا ينتهي حقل :attribute بأي من القيم التالية: :values.',
    'doesnt_start_with' => 'يجب ألا يبدأ حقل :attribute بأي من القيم التالية: :values.',
    'email' => 'يجب إدخال عنوان بريد إلكتروني صالح في حقل :attribute.',
    'encoding' => 'يجب أن يكون ترميز حقل :attribute هو :encoding.',
    'ends_with' => 'يجب أن ينتهي حقل :attribute بإحدى القيم التالية: :values.',
    'enum' => 'القيمة المحددة لحقل :attribute غير صالحة.',
    'exists' => 'القيمة المحددة لحقل :attribute غير صالحة.',
    'extensions' => 'يجب أن يكون امتداد ملف :attribute أحد الامتدادات التالية: :values.',
    'file' => 'يجب أن يكون حقل :attribute ملفًا.',
    'filled' => 'يجب أن يحتوي حقل :attribute على قيمة.',
    'gt' => [
        'array' => 'يجب أن يحتوي حقل :attribute على أكثر من :value عناصر.',
        'file' => 'يجب أن يكون حجم الملف :attribute أكبر من :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أكبر من :value.',
        'string' => 'يجب أن يتجاوز طول حقل :attribute :value حرفًا.',
    ],
    'gte' => [
        'array' => 'يجب أن يحتوي حقل :attribute على :value عناصر على الأقل.',
        'file' => 'يجب ألا يقل حجم الملف :attribute عن :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أكبر من أو تساوي :value.',
        'string' => 'يجب ألا يقل طول حقل :attribute عن :value حرفًا.',
    ],
    'hex_color' => 'يجب أن يكون حقل :attribute لونًا صالحًا بصيغة Hex.',
    'image' => 'يجب أن يكون حقل :attribute صورة.',
    'in' => 'القيمة المحددة لحقل :attribute غير صالحة.',
    'in_array' => 'يجب أن تكون قيمة حقل :attribute موجودة في :other.',
    'in_array_keys' => 'يجب أن يحتوي حقل :attribute على مفتاح واحد على الأقل من المفاتيح التالية: :values.',
    'integer' => 'يجب أن يكون حقل :attribute عددًا صحيحًا.',
    'ip' => 'يجب أن يكون حقل :attribute عنوان IP صالحًا.',
    'ipv4' => 'يجب أن يكون حقل :attribute عنوان IPv4 صالحًا.',
    'ipv6' => 'يجب أن يكون حقل :attribute عنوان IPv6 صالحًا.',
    'json' => 'يجب أن يكون حقل :attribute نص JSON صالحًا.',
    'list' => 'يجب أن يكون حقل :attribute قائمة.',
    'lowercase' => 'يجب أن يحتوي حقل :attribute على أحرف صغيرة فقط.',

    'lt' => [
        'array' => 'يجب أن يحتوي حقل :attribute على أقل من :value عناصر.',
        'file' => 'يجب أن يكون حجم الملف :attribute أقل من :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أقل من :value.',
        'string' => 'يجب أن يكون طول حقل :attribute أقل من :value حرفًا.',
    ],

    'lte' => [
        'array' => 'يجب ألا يحتوي حقل :attribute على أكثر من :value عناصر.',
        'file' => 'يجب ألا يزيد حجم الملف :attribute عن :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أقل من أو تساوي :value.',
        'string' => 'يجب ألا يتجاوز طول حقل :attribute :value حرفًا.',
    ],

    'mac_address' => 'يجب أن يكون حقل :attribute عنوان MAC صالحًا.',

    'max' => [
        'array' => 'يجب ألا يحتوي حقل :attribute على أكثر من :max عناصر.',
        'file' => 'يجب ألا يزيد حجم الملف :attribute عن :max كيلوبايت.',
        'numeric' => 'يجب ألا تزيد قيمة حقل :attribute عن :max.',
        'string' => 'يجب ألا يتجاوز طول حقل :attribute :max حرفًا.',
    ],

    'max_digits' => 'يجب ألا يحتوي حقل :attribute على أكثر من :max أرقام.',
    'mimes' => 'يجب أن يكون نوع ملف :attribute أحد الأنواع التالية: :values.',
    'mimetypes' => 'يجب أن يكون نوع ملف :attribute أحد الأنواع التالية: :values.',

    'min' => [
        'array' => 'يجب ألا يقل عدد عناصر حقل :attribute عن :min.',
        'file' => 'يجب ألا يقل حجم الملف :attribute عن :min كيلوبايت.',
        'numeric' => 'يجب ألا تقل قيمة حقل :attribute عن :min.',
        'string' => 'يجب ألا يقل طول حقل :attribute عن :min حرفًا.',
    ],

    'min_digits' => 'يجب أن يحتوي حقل :attribute على :min أرقام على الأقل.',
    'missing' => 'يجب ألا يكون حقل :attribute موجودًا.',
    'missing_if' => 'يجب ألا يكون حقل :attribute موجودًا عندما تكون قيمة :other هي :value.',
    'missing_unless' => 'يجب ألا يكون حقل :attribute موجودًا إلا إذا كانت قيمة :other هي :value.',
    'missing_with' => 'يجب ألا يكون حقل :attribute موجودًا عند وجود :values.',
    'missing_with_all' => 'يجب ألا يكون حقل :attribute موجودًا عند وجود جميع القيم التالية: :values.',
    'multiple_of' => 'يجب أن تكون قيمة حقل :attribute من مضاعفات :value.',
    'not_in' => 'القيمة المحددة لحقل :attribute غير صالحة.',
    'not_regex' => 'تنسيق حقل :attribute غير صالح.',
    'numeric' => 'يجب أن يكون حقل :attribute رقمًا.',
    'password' => [
        'letters' => 'يجب أن تحتوي كلمة المرور في حقل :attribute على حرف واحد على الأقل.',
        'mixed' => 'يجب أن تحتوي كلمة المرور في حقل :attribute على حرف كبير وحرف صغير على الأقل.',
        'numbers' => 'يجب أن تحتوي كلمة المرور في حقل :attribute على رقم واحد على الأقل.',
        'symbols' => 'يجب أن تحتوي كلمة المرور في حقل :attribute على رمز واحد على الأقل.',
        'uncompromised' => 'ظهرت قيمة :attribute في تسريب للبيانات. يُرجى اختيار قيمة أخرى.',
    ],

    'present' => 'يجب أن يكون حقل :attribute موجودًا.',
    'present_if' => 'يجب أن يكون حقل :attribute موجودًا عندما تكون قيمة :other هي :value.',
    'present_unless' => 'يجب أن يكون حقل :attribute موجودًا إلا إذا كانت قيمة :other هي :value.',
    'present_with' => 'يجب أن يكون حقل :attribute موجودًا عند وجود :values.',
    'present_with_all' => 'يجب أن يكون حقل :attribute موجودًا عند وجود جميع القيم التالية: :values.',
    'prohibited' => 'يُمنع وجود حقل :attribute.',
    'prohibited_if' => 'يُمنع وجود حقل :attribute عندما تكون قيمة :other هي :value.',
    'prohibited_if_accepted' => 'يُمنع وجود حقل :attribute عندما تكون قيمة :other مقبولة.',
    'prohibited_if_declined' => 'يُمنع وجود حقل :attribute عندما تكون قيمة :other مرفوضة.',
    'prohibited_unless' => 'يُمنع وجود حقل :attribute إلا إذا كانت قيمة :other ضمن :values.',
    'prohibits' => 'وجود حقل :attribute يمنع وجود حقل :other.',
    'regex' => 'تنسيق حقل :attribute غير صالح.',
    'required' => 'حقل :attribute مطلوب.',
    'required_array_keys' => 'يجب أن يحتوي حقل :attribute على المفاتيح التالية: :values.',
    'required_if' => 'حقل :attribute مطلوب عندما تكون قيمة :other هي :value.',
    'required_if_accepted' => 'حقل :attribute مطلوب عندما تكون قيمة :other مقبولة.',
    'required_if_declined' => 'حقل :attribute مطلوب عندما تكون قيمة :other مرفوضة.',
    'required_unless' => 'حقل :attribute مطلوب إلا إذا كانت قيمة :other ضمن :values.',
    'required_with' => 'حقل :attribute مطلوب عند وجود :values.',
    'required_with_all' => 'حقل :attribute مطلوب عند وجود جميع القيم التالية: :values.',
    'required_without' => 'حقل :attribute مطلوب عند عدم وجود :values.',
    'required_without_all' => 'حقل :attribute مطلوب عند عدم وجود أي من القيم التالية: :values.',
    'same' => 'يجب أن يتطابق حقل :attribute مع حقل :other.',

    'size' => [
        'array' => 'يجب أن يحتوي حقل :attribute على :size عناصر.',
        'file' => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute مساوية لـ :size.',
        'string' => 'يجب أن يكون طول حقل :attribute :size حرفًا.',
    ],

    'starts_with' => 'يجب أن يبدأ حقل :attribute بإحدى القيم التالية: :values.',
    'string' => 'يجب أن يكون حقل :attribute نصًا.',
    'timezone' => 'يجب أن يكون حقل :attribute منطقة زمنية صالحة.',
    'unique' => 'قيمة حقل :attribute مستخدمة بالفعل.',
    'uploaded' => 'فشل رفع حقل :attribute.',
    'uppercase' => 'يجب أن يحتوي حقل :attribute على أحرف كبيرة فقط.',
    'url' => 'يجب أن يكون حقل :attribute رابطًا صالحًا.',
    'ulid' => 'يجب أن يكون حقل :attribute معرّف ULID صالحًا.',
    'uuid' => 'يجب أن يكون حقل :attribute معرّف UUID صالحًا.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'رسالة تحقق مخصصة.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [],

    'invalid_value_or_hash' => 'القيمة أو الرابط غير صالح.',
    'max_visible' => 'تم الوصول إلى الحد الأقصى للعناصر الظاهرة.',
    'max_working_hours' => 'تم تجاوز الحد الأقصى لساعات العمل.',
    'end_time_outside_working_hours' => 'وقت الانتهاء خارج ساعات العمل المحددة.',
    'start_time_outside_working_hours' => 'وقت البدء خارج ساعات العمل المحددة.',
];
