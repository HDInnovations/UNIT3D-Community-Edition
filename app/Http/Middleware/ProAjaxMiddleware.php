<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class ProAjaxMiddleware
{
    /**
     * All your flash names.
     * E.g. you might have a flash message named "flash_message" for your standard bootstrap alert flash messages,.
     *
     * @var array
     */
    public $flash_name = 'flash_message';

    /**
     * After the request has been made, determine if an alert should be shown,
     * or if the user should be redirected to another page.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //return  $response = $next($request);
        $response = $next($request);

        // If the response is a successful response or,
        // If the request is not an ajax request or,
        // If there is already a JSON response,
        // We do not need to do anything, just skip and continue
        //dd(!$response->isSuccessful());
        //if ($response instanceof JsonResponse || !$this->isAjaxRequest($request) || $response->isSuccessful()) {
        if ($response instanceof JsonResponse || !$this->isAjaxRequest($request) || $response->isServerError() || $response->isSuccessful()) {
            return $response;
        }

        // Should the user be redirected?
        // For example, if from the controller, this is returned:
        // return redirect('/contact')
        // then the user should be redirected
        if ($this->shouldRedirectRequest($request, $response)) {
            return response()->json(['redirect' => $response->getTargetUrl()]);
        }

        // If we've gotten this far, it looks like its an ajax request
        // That means that it must have some sort of flash message.
        // Let's see if we actually have flash message.
        if ($this->sessionHasFlashData($request)) {
            // Since we actually do have flash data/message in the session,
            // Lets get the flash message and display it to the user
            $flash_message = $this->getFlashMessage($request);

            // Lets forget the flash message because we already have it stored in the $flash_message variable
            // to show it to the user
            $request->session()->forget($this->flash_name);

            // Finally, let's return a json with the flash message
            return response()->json([
                'type'    => $flash_message['type'],
                'message' => $flash_message['message'],
                //'redirect' => $flash_message['redirect'], // Returns false if no redirect request
            ]);
        }

        // So... if the request wants json, return json
        return $request->wantsJson() ? response()->json() : $response;
        //return $response;
    }

    /**
     * Determine if the request is an ajax request.
     *
     * @param $request
     *
     * @return bool
     */
    public function isAjaxRequest($request)
    {
        return $request->ajax() && $request->wantsJson();
    }

    /**
     * Check if the session has flash data.
     *
     * @param $request
     *
     * @return bool
     */
    public function sessionHasFlashData($request)
    {
        return $request->session()->has($this->flash_name);
    }

    /**
     * Get the flash message itself.
     *
     * @param $request
     *
     * @return array
     */
    public function getFlashMessage($request)
    {
        $session = $request->session();

        $flash_message['type'] = $session->get(sprintf('%s.type', $this->flash_name));
        $flash_message['message'] = $session->get(sprintf('%s.message', $this->flash_name));

        return $flash_message;
    }

    /**
     * Check if the user should be redirected.
     *
     * @param $request
     * @param $response
     *
     * @return bool
     */
    public function shouldRedirectRequest($request, $response)
    {
        // If there is no target URL, we know that it is not a redirect request
        if (!method_exists($response, 'getTargetUrl')) {
            return false;
        }

        //// Quickly return false since the user is just downloading files or saving settings
        // Does the referrer URI NOT match the target URI?
        // Does the flash message or session have any errors caused by redirect()->withError('error')?
        // If any of those are true, it is a redirect request
        //return ($response->getStatusCode() == 302 && ($request->server('HTTP_REFERER') != rtrim($response->getTargetUrl(), '/'))) || $request->session()->has('errors');

        //dd($request->server('HTTP_REFERER'));
        //dd($response->getTargetUrl());
        return ($response->getStatusCode() == 302 && ($request->server('HTTP_REFERER') != $response->getTargetUrl()))
            || $request->session()->has('errors');
    }
}
