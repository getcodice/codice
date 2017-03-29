<?php

use Codice\Plugins\Filter;

Filter::register('core.search.clause', 'codice_search_clause', function($query) {
    return 'content LIKE "%' . escape_like($query) . '%"';
});
