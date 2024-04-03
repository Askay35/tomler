<?php

return [

    'payment'=>[
        'address' => env('PAYMENT_ADDRESS','GBK5AN7XWA7WA672QHYNRKA3SSESUA7PBI4HUOJSRTID26MTTSECXG7P'),
        'price'=> env('PAYMENT_PRICE', 253),
        'time'=> env('PAYMENT_MINUTES', 10)
    ]
];
