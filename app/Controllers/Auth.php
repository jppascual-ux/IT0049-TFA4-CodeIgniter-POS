<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * TFA4 — Login and logout.
 */
class Auth extends BaseController
{
    /** Failed login attempts allowed per IP address per minute. */
    private const MAX_ATTEMPTS_PER_MINUTE = 10;

    /**
     * GET /login — show the login form (already logged in → go to the app).
     */
    public function login(): string|RedirectResponse
    {
        if (session()->get('isLoggedIn') === true) {
            return redirect()->to('customers');
        }

        return view('auth/login', ['title' => 'Log In']);
    }

    /**
     * POST /login — validate input, look up the user, verify the hash,
     * then start the session.
     */
    public function attempt(): RedirectResponse|ResponseInterface
    {
        // Basic brute-force protection.
        $throttler = service('throttler');
        if ($throttler->check('login-' . md5($this->request->getIPAddress()), self::MAX_ATTEMPTS_PER_MINUTE, MINUTE) === false) {
            return redirect()->to('login')->withInput()
                ->with('error', 'Too many login attempts. Please wait a minute and try again.');
        }

        $rules = [
            'username' => ['label' => 'Username', 'rules' => 'required', 'errors' => ['required' => 'Username is required.']],
            'password' => ['label' => 'Password', 'rules' => 'required', 'errors' => ['required' => 'Password is required.']],
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('login')->withInput();
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $userModel = new UserModel();
        $user      = $userModel->where('username', $username)->first();

        // Same message whether the username or the password is wrong,
        // so attackers can't discover which usernames exist.
        if ($user === null || ! password_verify($password, $user['password'])) {
            return redirect()->to('login')->withInput()
                ->with('error', 'Invalid username or password.');
        }

        // Upgrade the hash automatically if PHP's default algorithm/cost changes.
        if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
            $userModel->update($user['id'], ['password' => $password]);
        }

        $session = session();

        // New session ID on login prevents session-fixation attacks.
        $session->regenerate(true);

        $session->set([
            'isLoggedIn' => true,
            'user_id'    => (int) $user['id'],
            'username'   => $user['username'],
            'full_name'  => $user['full_name'],
            'avatar'     => $user['avatar'],
            'logged_in_at' => date('Y-m-d H:i:s'),
        ]);

        $target = $session->get('redirect_url') ?? site_url('customers');
        $session->remove('redirect_url');

        return redirect()->to($target)->with('success', 'Welcome back, ' . $user['full_name'] . '!');
    }

    /**
     * POST /logout — destroy the whole session and return to the login page.
     */
    public function logout(): RedirectResponse
    {
        session()->destroy();

        // The session no longer exists, so the "logged out" notice is passed in the URL.
        return redirect()->to('login?logged_out=1');
    }
}
