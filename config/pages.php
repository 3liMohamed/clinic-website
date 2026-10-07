<?php

return [

    'home' => [
        'route' => 'home',
        'sections' => [
            'slider' => [
                'component' => 'sections.sliders',
                'requires_entity' => false,
            ],

            'clinic infos' => [
                'component' => 'sections.clinic-infos',
                'requires_entity' => false,
            ],

            'features' => [
                'component' => 'sections.features',
                'requires_entity' => false,
            ],

            'statistics' => [
                'component' => 'sections.statistics',
                'requires_entity' => false,
            ],

            'why choose' => [
                'component' => 'sections.why-choose',
                'requires_entity' => false,
            ],

            'call action' => [
                'component' => 'sections.call-action',
                'requires_entity' => false,
            ],

            'portfolios' => [
                'component' => 'sections.portfolio',
                'requires_entity' => false,
            ],

            'services' => [
                'component' => 'sections.services',
                'requires_entity' => false,
            ],

            'testimonials' => [
                'component' => 'sections.testimonials',
                'requires_entity' => false,
            ],
            'departments' => [
                'component' => 'sections.departments',
                'requires_entity' => false,
            ],
            'pricings' => [
                'component' => 'sections.pricings',
                'requires_entity' => false,
            ],

            'doctors' => [
                'component' => 'sections.doctors',
                'requires_entity' => false,
            ],
            'blogs' => [
                'component' => 'sections.blogs',
                'requires_entity' => false,
            ],
            'clients' => [
                'component' => 'sections.clients',
                'requires_entity' => false,
            ],
            'appointment' => [
                'component' => 'sections.appointment',
                'requires_entity' => false,
            ],
            'subscription' => [
                'component' => 'sections.subscription',
                'requires_entity' => false,
            ],
        ],
    ],

    'about-us' => [
        'route' => 'about',
        'sections' => [
            'about us' => [
                'component' => 'sections.about',
                'requires_entity' => false,
            ],
            'visions' => [
                'component' => 'sections.vision',
                'requires_entity' => false,
            ],
            'mission' => [
                'component' => 'sections.mission',
                'requires_entity' => false,
            ],
            'subscription' => [
                'component' => 'sections.subscription',
                'requires_entity' => false,
            ],
        ],
    ],

    'doctors' => [
        'route' => 'doctors',
        'sections' => [
            'doctors' => [
                'component' => 'sections.doctors-page',
                'requires_entity' => false,
            ],
            'subscription' => [
                'component' => 'sections.subscription',
                'requires_entity' => false,
            ],
        ],
    ],
    'services' => [
        'route' => 'services',
        'sections' => [
            'services' => [
                'component' => 'sections.services-page',
                'requires_entity' => false,
            ],
            'clients' => [
                'component' => 'sections.clients',
                'requires_entity' => false,
            ],
            'subscription' => [
                'component' => 'sections.subscription',
                'requires_entity' => false,
            ],
        ],
    ],
    'blogs' => [
        'route' => 'blogs',
        'sections' => [
            'blogs' => [
                'component' => 'sections.blogs-page',
                'requires_entity' => false,
            ],
            'subscription' => [
                'component' => 'sections.subscription',
                'requires_entity' => false,
            ],
        ],
    ],
    'faqs' => [
        'route' => 'faqs',
        'sections' => [
            'faqs' => [
                'component' => 'sections.faqs',
                'requires_entity' => false,
            ],
            'subscription' => [
                'component' => 'sections.subscription',
                'requires_entity' => false,
            ],
        ],
    ],

    'contact' => [
        'route' => 'contact',
        'sections' => [],
    ],



    'portfolio details' => [
        'sections' => [
            'portfolio details' => [
                'component' => 'sections.portfolio-details',
                'requires_entity' => true,
            ],
        ],
    ],
    'service details' => [
        'sections' => [
            'service details' => [
                'component' => 'sections.service-details',
                'requires_entity' => true,
            ],
        ],
    ],
    'doctor details' => [
        'sections' => [
            'doctor details' => [
                'component' => 'sections.doctor-details',
                'requires_entity' => true,
            ],
        ],
    ],
    'blog details' => [
        'sections' => [
            'blog details' => [
                'component' => 'sections.blog-details',
                'requires_entity' => true,
            ],
        ],
    ],



];
