<?php

namespace App\Controllers;

use App\Libraries\ActivityCatalog;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * GET /tfa2, /tfa3, /tfa4 — one page per activity: what it added,
 * where to try it, the requirements it meets, and the key code.
 */
class Activities extends BaseController
{
    public function show(int $number): string
    {
        $activity = ActivityCatalog::find($number);

        if ($activity === null) {
            throw PageNotFoundException::forPageNotFound("TFA {$number} is not part of this project.");
        }

        $all = ActivityCatalog::all();

        return view('activities/show', [
            'title'      => 'TFA ' . $number . ': ' . $activity['title'],
            'activity'   => $activity,
            'previous'   => $all[$number - 1] ?? null,
            'next'       => $all[$number + 1] ?? null,
            'isLoggedIn' => session()->get('isLoggedIn') === true,
            'session'    => $this->sessionSnapshot(),
        ]);
    }

    /**
     * TFA 4: reads back what Auth::attempt() stored in the session,
     * shown live on the TFA 4 page.
     */
    private function sessionSnapshot(): array
    {
        $session = session();

        return [
            'isLoggedIn'   => $session->get('isLoggedIn') === true,
            'user_id'      => $session->get('user_id'),
            'username'     => $session->get('username'),
            'full_name'    => $session->get('full_name'),
            'logged_in_at' => $session->get('logged_in_at'),
            'session_id'   => session_id() !== '' ? substr(session_id(), 0, 8) . '…' : null,
        ];
    }
}
