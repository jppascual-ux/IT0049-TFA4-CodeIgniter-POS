<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Customers extends BaseController
{
    private CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    /**
     * GET /customers — Customer Accounts list (TFA2).
     */
    public function index(): string
    {
        $data['title']     = 'Customer Accounts';
        $data['customers'] = $this->customerModel->orderBy('id', 'ASC')->findAll();

        return view('customers/index', $data);
    }

    /**
     * GET /customers/new — empty New Customer form.
     */
    public function new(): string
    {
        return view('customers/form', [
            'title'    => 'New Customer',
            'customer' => null,
            'action'   => site_url('customers'),
        ]);
    }

    /**
     * POST /customers — validate, then insert a new customer.
     */
    public function create(): RedirectResponse
    {
        if (! $this->validate($this->customerModel->formRules())) {
            // Redisplay the form with the errors and the user's previous entries.
            return redirect()->to('customers/new')->withInput();
        }

        $this->customerModel->insert($this->formData());

        return redirect()->to('customers')->with('success', 'Customer added.');
    }

    /**
     * GET /customers/{id}/edit — form pre-filled with the existing record.
     */
    public function edit(int $id): string
    {
        return view('customers/form', [
            'title'    => 'Edit Customer',
            'customer' => $this->findOr404($id),
            'action'   => site_url("customers/{$id}/update"),
        ]);
    }

    /**
     * POST /customers/{id}/update — validate, then update the existing record.
     */
    public function update(int $id): RedirectResponse
    {
        $this->findOr404($id);

        if (! $this->validate($this->customerModel->formRules())) {
            return redirect()->to("customers/{$id}/edit")->withInput();
        }

        $this->customerModel->update($id, $this->formData());

        return redirect()->to('customers')->with('success', 'Changes saved.');
    }

    /**
     * Only the fields the form is allowed to change, trimmed.
     */
    private function formData(): array
    {
        $phone = trim((string) $this->request->getPost('phone'));

        return [
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email'     => trim((string) $this->request->getPost('email')),
            'phone'     => $phone === '' ? null : $phone,
        ];
    }

    private function findOr404(int $id): array
    {
        $customer = $this->customerModel->find($id);

        if ($customer === null) {
            throw PageNotFoundException::forPageNotFound("Customer #{$id} was not found.");
        }

        return $customer;
    }
}
