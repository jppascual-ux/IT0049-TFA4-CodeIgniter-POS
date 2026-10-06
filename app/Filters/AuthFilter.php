<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * TFA4 — Blocks access to protected pages unless a user is logged in.
 *
 * Registered as the "auth" alias in app/Config/Filters.php and applied to the
 * Customer Accounts and User Accounts route groups in app/Config/Routes.php.
 * Runs BEFORE the controller, so a logged-out visitor never reaches it.
 */
class AuthFilter implements FilterInterface
{
    /**
     * @param list<string>|null $arguments
     */
    public function before(RequestInterface $request, $arguments = null): ?RedirectResponse
    {
        $session = session();

        if ($session->get('isLoggedIn') === true) {
            return null; // logged in → continue to the controller
        }

        // Remember the page they wanted (GET only) so login can send them back to it.
        if (strtolower($request->getMethod()) === 'get') {
            $session->set('redirect_url', current_url());
        }

        return redirect()->to('login')->with('error', 'Please log in to access that page.');
    }

    /**
     * Protected pages must not be cached by the browser, so pressing "Back"
     * after logging out does not show customer or user data again.
     *
     * @param list<string>|null $arguments
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): ResponseInterface
    {
        return $response
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0');
    }
}
