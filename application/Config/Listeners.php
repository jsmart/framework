<?php

return [
    'kernel.handle' => [
        \App\Modules\Example\Listeners\KernelHandle::class,
        \App\Modules\Example\Listeners\MemoryUsage::class,
    ],
    'router.match' => [
        \App\Modules\Backend\Listeners\ViewPath::class,
    ]
];