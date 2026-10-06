<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;

class Users extends BaseController
{
    /** Size (px) of the square, display-ready avatar thumbnail. */
    private const AVATAR_SIZE = 200;

    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * GET /users — User Accounts list with avatar thumbnails.
     */
    public function index(): string
    {
        $data['title'] = 'User Accounts';
        $data['users'] = $this->userModel->withoutPassword()->orderBy('id', 'ASC')->findAll();

        return view('users/index', $data);
    }

    /**
     * GET /users/new — empty New User form.
     */
    public function new(): string
    {
        return view('users/form', [
            'title'  => 'New User',
            'user'   => null,
            'action' => site_url('users'),
        ]);
    }

    /**
     * POST /users — validate (username required + unique, full name required), then insert.
     */
    public function create(): RedirectResponse
    {
        if (! $this->validate($this->userModel->formRules())) {
            return redirect()->to('users/new')->withInput();
        }

        $this->userModel->insert($this->formData());

        return redirect()->to('users')->with('success', 'User added.');
    }

    /**
     * GET /users/{id}/edit — form pre-filled with the existing record + avatar upload.
     */
    public function edit(int $id): string
    {
        return view('users/form', [
            'title'  => 'Edit User',
            'user'   => $this->findOr404($id),
            'action' => site_url("users/{$id}/update"),
        ]);
    }

    /**
     * POST /users/{id}/update — validate fields (and the avatar, if one was chosen),
     * prepare a thumbnail, store it in public/uploads/avatars/, save only its filename.
     */
    public function update(int $id): RedirectResponse
    {
        $user = $this->findOr404($id);

        $rules = $this->userModel->formRules($id);

        $avatar = $this->request->getFile('avatar');
        $hasNewAvatar = $avatar instanceof UploadedFile && $avatar->getError() !== UPLOAD_ERR_NO_FILE;

        if ($hasNewAvatar) {
            $rules += $this->userModel->avatarRules();
        }

        if (! $this->validate($rules)) {
            return redirect()->to("users/{$id}/edit")->withInput();
        }

        $data = $this->formData();

        if ($hasNewAvatar) {
            $data['avatar'] = $this->storeAvatar($avatar);
            $this->deleteAvatar($user['avatar'] ?? null);
        }

        $this->userModel->update($id, $data);

        // Keep the navigation bar in sync if the logged-in user edited their own account.
        if ((int) session()->get('user_id') === $id) {
            $fresh = $this->userModel->withoutPassword()->find($id);
            session()->set([
                'username'  => $fresh['username'],
                'full_name' => $fresh['full_name'],
                'avatar'    => $fresh['avatar'],
            ]);
        }

        return redirect()->to('users')->with('success', 'Changes saved.');
    }

    /**
     * Prepares a square, display-ready thumbnail from the validated upload using
     * CodeIgniter's Image service, saves it to public/uploads/avatars/ under a
     * random name, and returns ONLY the filename (which is what goes in the DB).
     */
    private function storeAvatar(UploadedFile $file): string
    {
        $directory = FCPATH . UserModel::AVATAR_DIR;

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Random name (e.g. 1727861234_a1b2c3d4e5f6.jpg) so users can't overwrite
        // each other's files or guess paths; extension comes from the real MIME type.
        $filename = $file->getRandomName();

        service('image')
            ->withFile($file->getTempName())
            ->fit(self::AVATAR_SIZE, self::AVATAR_SIZE, 'center')
            ->save($directory . $filename, 85);

        return $filename;
    }

    /**
     * Removes a user's previous avatar file when it is replaced.
     */
    private function deleteAvatar(?string $filename): void
    {
        if ($filename === null || $filename === '') {
            return;
        }

        $path = FCPATH . UserModel::AVATAR_DIR . basename($filename);

        if (is_file($path) && ! str_starts_with(basename($filename), 'sample-')) {
            unlink($path);
        }
    }

    /**
     * Fields the form may change. The plain-text password is hashed by
     * UserModel::hashPassword() before it reaches the database; a blank
     * password on edit means "keep the current password".
     */
    private function formData(): array
    {
        $data = [
            'username'  => trim((string) $this->request->getPost('username')),
            'full_name' => trim((string) $this->request->getPost('full_name')),
        ];

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $data['password'] = $password;
        }

        return $data;
    }

    private function findOr404(int $id): array
    {
        $user = $this->userModel->withoutPassword()->find($id);

        if ($user === null) {
            throw PageNotFoundException::forPageNotFound("User #{$id} was not found.");
        }

        return $user;
    }
}
