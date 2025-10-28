<?php

namespace Application;

use Framework\Routing\Rule;

Rule::route('/', 'main', Main::class);

Rule::route('/media/{name}', '.media', Main::class)
    ->where(name: '[a-z\/\.]+');

Rule::route('/redirect/{name}.html', '.redirect', Main::class)
    ->where(name: '[a-z]+');

Rule::route('/page/{name}.html', 'page', Page::class)
    ->where(name: '[a-z]+');

Rule::route('/archive/{year}', '.archive', Page::class)
    ->where(year: '[0-9]{4}');
Rule::route('/archive/{year}/{month}', '.archive', Page::class)
    ->where(year: '[0-9]{4}', month: '[0-9]{1,2}');
Rule::route('/archive/{year}/{month}/{day}', '.archive', Page::class)
    ->where(year: '[0-9]{4}', month: '[0-9]{1,2}', day: '[0-9]{1,2}');
