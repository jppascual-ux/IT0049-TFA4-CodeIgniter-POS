<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Wraps the `users` table.
 */
class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['username', 'full_name', 'password', 'avatar', 'created_at'];

    // TFA4: plain-text passwords are hashed automatically before they are saved.
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    // Fill `created_at` automatically on insert (the table has no `updated_at`).
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /** Folder (inside public/) where prepared avatar thumbnails are stored. */
    public const AVATAR_DIR = 'uploads/avatars/';

    /** Placeholder shown when a user has no avatar. */
    public const AVATAR_PLACEHOLDER = 'assets/img/avatar-placeholder.png';

    /**
     * Validation rules for the New / Edit User forms.
     * (TFA3: username required and unique, full name required.)
     * (TFA4: password required for new users; optional on edit = keep current.)
     *
     * @param int|null $ignoreId When editing, the current user's id so their own
     *                           username is not reported as "already taken".
     */
    public function formRules(?int $ignoreId = null): array
    {
        $isEdit = $ignoreId !== null;
        $unique = $isEdit
            ? "is_unique[users.username,id,{$ignoreId}]"
            : 'is_unique[users.username]';

        return [
            'username' => [
                'label'  => 'Username',
                'rules'  => "required|min_length[3]|max_length[50]|alpha_dash|{$unique}",
                'errors' => [
                    'required'   => 'Username is required.',
                    'alpha_dash' => 'Username may only contain letters, numbers, underscores and dashes.',
                    'is_unique'  => 'That username is already taken. Please choose another.',
                ],
            ],
            'full_name' => [
                'label'  => 'Full Name',
                'rules'  => 'required|max_length[100]',
                'errors' => [
                    'required' => 'Full Name is required.',
                ],
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => ($isEdit ? 'permit_empty' : 'required') . '|min_length[8]|max_length[72]',
                'errors' => [
                    'required'   => 'Password is required.',
                    'min_length' => 'Password must be at least 8 characters long.',
                    'max_length' => 'Password must not be longer than 72 characters.',
                ],
            ],
            'password_confirm' => [
                'label'  => 'Confirm Password',
                'rules'  => ($isEdit ? 'permit_empty|' : 'required|') . 'matches[password]',
                'errors' => [
                    'required' => 'Please re-type the password.',
                    'matches'  => 'The two passwords do not match.',
                ],
            ],
        ];
    }

    /**
     * Validation rules for the avatar upload: JPG or PNG, max 2 MB.
     */
    public function avatarRules(): array
    {
        return [
            'avatar' => [
                'label'  => 'Profile Picture',
                'rules'  => 'uploaded[avatar]'
                    . '|is_image[avatar]'
                    . '|mime_in[avatar,image/jpeg,image/jpg,image/pjpeg,image/png,image/x-png]'
                    . '|ext_in[avatar,jpg,jpeg,png]'
                    . '|max_size[avatar,2048]',
                'errors' => [
                    'uploaded' => 'The profile picture could not be uploaded. Please try again (max 2 MB).',
                    'is_image' => 'The profile picture must be an image file.',
                    'mime_in'  => 'The profile picture must be a JPG or PNG image.',
                    'ext_in'   => 'The profile picture must have a .jpg, .jpeg or .png extension.',
                    'max_size' => 'The profile picture must not be larger than 2 MB.',
                ],
            ],
        ];
    }

    /**
     * Public URL of a user's avatar, or the placeholder if none was uploaded
     * (or the file is missing on disk).
     */
    public static function avatarUrl(?string $filename): string
    {
        if ($filename !== null && $filename !== '' && is_file(FCPATH . self::AVATAR_DIR . $filename)) {
            return base_url(self::AVATAR_DIR . $filename);
        }

        return base_url(self::AVATAR_PLACEHOLDER);
    }

    /**
     * Model callback (TFA4): turns a plain-text password into a password_hash()
     * value before INSERT/UPDATE. An empty password on update is removed, so the
     * current hash is kept.
     */
    protected function hashPassword(array $data): array
    {
        if (! array_key_exists('password', $data['data'] ?? [])) {
            return $data;
        }

        if ($data['data']['password'] === null || $data['data']['password'] === '') {
            unset($data['data']['password']);

            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

        return $data;
    }

    /**
     * Columns safe to send to views (never the password hash).
     */
    public function withoutPassword(): self
    {
        return $this->select('id, username, full_name, avatar, created_at');
    }
}
