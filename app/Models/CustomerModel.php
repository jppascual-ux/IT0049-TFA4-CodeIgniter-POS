<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Wraps the `customers` table.
 */
class CustomerModel extends Model
{
    protected $table         = 'customers';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['full_name', 'email', 'phone', 'created_at'];

    // Fill `created_at` automatically on insert (the table has no `updated_at`).
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Validation rules for the New / Edit Customer forms.
     * (TFA3: full name required, email required and valid.)
     */
    public function formRules(): array
    {
        return [
            'full_name' => [
                'label'  => 'Full Name',
                'rules'  => 'required|max_length[100]',
                'errors' => [
                    'required' => 'Full Name is required.',
                ],
            ],
            'email' => [
                'label'  => 'Email',
                'rules'  => 'required|valid_email|max_length[100]',
                'errors' => [
                    'required'    => 'Email is required.',
                    'valid_email' => 'Please enter a valid email address (e.g. name@example.com).',
                ],
            ],
            'phone' => [
                'label'  => 'Phone',
                'rules'  => 'permit_empty|max_length[20]|regex_match[/^[0-9+\-\s()]+$/]',
                'errors' => [
                    'regex_match' => 'Phone may only contain numbers, spaces, +, - and parentheses.',
                ],
            ],
        ];
    }
}
