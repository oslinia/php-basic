<?php

namespace Framework\Foundation\Application;

use Framework\Routing\Rule;

Rule::route('/panel', 'panel', Main::class);
Rule::route('/panel/logout', 'panel.logout', Main::class);
