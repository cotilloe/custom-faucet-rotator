<?php

namespace App\Libraries\Matthewbdaly\ETagMiddleware;

use Closure;
use Illuminate\Http\Request;

/**
 * ETag middleware.
 *
 * @author  Matthew Daly <matthew@matthewdaly.co.uk> <https://matthewdaly.co.uk>
 * @package App\Libraries\Matthewbdaly\ETagMiddleware
 *
 * @see https://raw.githubusercontent.com/matthewbdaly/laravel-etag-middleware/master/src/ETag.php
 */
class ETag
{
    /**
     * Implement Etag support.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
     * @param \Closure                 $next    Closure for the response.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get response
        $response = $next($request);
        // If this was a GET request...
        if ($request->isMethod('get')) {
            // Generate Etag
            $etag = md5($response->getContent());
            $requestEtag = str_replace('"', '', $request->getETags());
            // Check to see if Etag has changed
            if ($requestEtag && $requestEtag[0] == $etag) {
                $response->setNotModified();
            }
            // Set Etag
            $response->setEtag($etag);
        }
        // Send response
        return $response;
    }
}
