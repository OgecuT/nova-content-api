<?php

use Ogecut\ContentApi\Http\Controllers\ContentController;

Route::get('blocks/{blockCode}', [ContentController::class, 'showBlock']);