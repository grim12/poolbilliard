<?php

return [

    /**
     * null (default): indexable exactly when the site isn't locked (see App\Support\Launch).
     * true/false: force the state regardless of the site lock — e.g. a soft-launch that's
     * unlocked for visitors but still shouldn't be indexed yet.
     */
    'indexable' => env('SEO_INDEXABLE'),

];
