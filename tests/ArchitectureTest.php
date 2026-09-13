<?php

arch('globals')
    ->expect(['dd', 'dump', 'env'])
    ->not->toBeUsed();

arch('controllers')
    ->expect('App\Modules\*\API\Controllers')
    ->toHaveSuffix('Controller');

arch('domain')
    ->expect('App\Modules\*\Domain')
    ->toOnlyUse('App\SharedKernel')
    ->ignoring('Illuminate');
