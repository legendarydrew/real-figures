<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use tidy as PhpTidy;

class Beautify
{
    /**
     * Options passed to HTML Tidy parseString() function.
     * See https://api.html-tidy.org/tidy/quickref_5.8.0.html for settings
     *
     * @var array
     */
    protected array $default_options = [
        'indent'               => 2,      // corresponds to auto.
        'indent-spaces'        => 4,
        'wrap'                 => 240,
        'wrap-sections'        => false,
        'markup'               => true,
        'output-xhtml'         => false,
        'char-encoding'        => 'utf8',
        'hide-comments'        => true,
        'uppercase-tags'       => false,
        'uppercase-attributes' => false,
        'break-before-br'      => false,
        'drop-empty-elements'  => false,

        // HTML5 workarounds
        'doctype'              => 'omit', //The filter will add the configured doctype later
        'new-blocklevel-tags'  => 'article,aside,canvas,dialog,embed,figcaption,figure,footer,header,hgroup,nav,output,progress,section,video',
        'new-inline-tags'      => 'audio,bdi,command,datagrid,datalist,details,keygen,mark,meter,rp,rt,ruby,source,summary,time,track,wbr',
    ];

    protected string $default_encoding = 'utf8';

    /**
     * Handle an incoming request.
     * Taken from https://github.com/Stolz/laravel-html-tidy
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check PHP extension
        if (!(extension_loaded('tidy') && config('services.beautify.enabled')))
        {
            return $response;
        }

        // Skip special response types
        if ($request->ajax() || ($response instanceof BinaryFileResponse) ||
            ($response instanceof JsonResponse) ||
            ($response instanceof RedirectResponse) ||
            ($response instanceof StreamedResponse))
        {
            return $response;
        }

        // Convert unknown responses
        if (!$response instanceof Response)
        {
            $response = new Response($response);
            if (!$response->headers->has('content-type'))
            {
                $response->headers->set('content-type', 'text/html');
            }
        }

        // If response is HTML parse it
        $contentType = $response->headers->get('content-type');
        if (Str::contains($contentType, 'text/html'))
        {
            $tidy          = new PhpTidy;
            $tidy_options  = config('services.beautify.settings', $this->default_options);
            $tidy_encoding = config('services.beautify.encoding', $this->default_encoding);
            $tidy->parseString($response->getContent(), $tidy_options, $tidy_encoding);

            $output = "<!doctype>\n{$tidy->html()->value}";
            $response->setContent($output);
        }

        return $response;
    }
}
