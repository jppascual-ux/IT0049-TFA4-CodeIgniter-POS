<?php

namespace App\Libraries;

/**
 * Content for the activity pages (/tfa2, /tfa3, /tfa4) and the home page tiles.
 *
 * Every "action" and "requirement" link points at the real feature in this app,
 * so the pages double as a guided tour for checking each activity.
 */
class ActivityCatalog
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            2 => [
                'number'  => 2,
                'slug'    => 'tfa2',
                'theme'   => 'data',
                'icon'    => 'database',
                'art'     => 'partials/art_data',
                'title'   => 'From arrays to a real database',
                'short'   => 'Database',
                'tagline' => 'Records that survive a restart.',
                'summary' => 'The POS used to keep customers and users in PHP arrays that reset every time the server restarted. TFA 2 moved them into MySQL and reads them through CodeIgniter Models using Query Builder.',
                'actions' => [
                    ['label' => 'Customer Accounts', 'text' => 'Every customer, read with CustomerModel::findAll().', 'url' => 'customers', 'icon' => 'list'],
                    ['label' => 'User Accounts', 'text' => 'Every user, read with UserModel::findAll().', 'url' => 'users', 'icon' => 'list'],
                ],
                'outcomes' => [
                    ['text' => 'Configure CodeIgniter’s database connection settings.', 'where' => '.env → database.default.*'],
                    ['text' => 'Create a MySQL database and tables from the provided schema.', 'where' => 'database/pos_db.sql'],
                    ['text' => 'Build Models for the Customer Accounts and User Accounts pages.', 'where' => 'app/Models/CustomerModel.php, UserModel.php'],
                    ['text' => 'Retrieve records with Query Builder and display them in a view.', 'where' => 'Customers::index(), Users::index()'],
                ],
                'requirements' => [
                    ['text' => 'Configure the .env database settings for a local MySQL database.', 'evidence' => '.env.example', 'url' => null],
                    ['text' => 'Create the customers and users tables from the schema.', 'evidence' => 'database/pos_db.sql, app/Database/Migrations', 'url' => null],
                    ['text' => 'Populate each table with at least 5 sample records.', 'evidence' => '6 customers, 6 users', 'url' => 'customers'],
                    ['text' => 'Create a CustomerModel and a UserModel.', 'evidence' => 'app/Models', 'url' => null],
                    ['text' => 'Controllers retrieve records through their Models.', 'evidence' => 'findAll() — no raw SQL', 'url' => 'users'],
                    ['text' => 'Views display the database records.', 'evidence' => 'Customer and User Accounts pages', 'url' => 'customers'],
                ],
                'code' => [
                    [
                        'file' => 'app/Models/CustomerModel.php',
                        'code' => <<<'PHP'
class CustomerModel extends Model
{
    protected $table         = 'customers';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['full_name', 'email',
                                'phone', 'created_at'];
}
PHP,
                    ],
                    [
                        'file' => 'app/Controllers/Customers.php',
                        'code' => <<<'PHP'
public function index(): string
{
    $data['customers'] = $this->customerModel
        ->orderBy('id', 'ASC')
        ->findAll();          // Query Builder, no raw SQL

    return view('customers/index', $data);
}
PHP,
                    ],
                ],
            ],

            3 => [
                'number'  => 3,
                'slug'    => 'tfa3',
                'theme'   => 'form',
                'icon'    => 'form',
                'art'     => 'partials/art_form',
                'title'   => 'Making it editable',
                'short'   => 'Forms',
                'tagline' => 'Forms that check before they save.',
                'summary' => 'TFA 3 added forms for creating and editing customers and users. Bad input is rejected with a clear message, what you typed stays in the form, and users can upload a profile picture that is resized into a thumbnail.',
                'actions' => [
                    ['label' => 'New customer', 'text' => 'Full name required; email required and valid.', 'url' => 'customers/new', 'icon' => 'plus'],
                    ['label' => 'New user', 'text' => 'Username required and unique; full name required.', 'url' => 'users/new', 'icon' => 'plus'],
                    ['label' => 'Edit a customer', 'text' => 'The form opens pre-filled with the saved record.', 'url' => 'customers/1/edit', 'icon' => 'pencil'],
                    ['label' => 'Upload a profile picture', 'text' => 'JPG or PNG up to 2 MB, cropped to 200 × 200.', 'url' => 'users/4/edit', 'icon' => 'image'],
                ],
                'outcomes' => [
                    ['text' => 'Build and validate HTML forms for creating new records.', 'where' => 'CustomerModel::formRules(), UserModel::formRules()'],
                    ['text' => 'Redisplay a form with errors and the previously entered values.', 'where' => 'redirect()->withInput() + old()'],
                    ['text' => 'Implement an edit and update workflow for existing records.', 'where' => 'edit() and update() in both controllers'],
                    ['text' => 'Accept, validate, prepare and store an uploaded image, saving only its filename.', 'where' => 'Users::storeAvatar()'],
                ],
                'requirements' => [
                    ['text' => 'New Customer form (/customers/new): full name required, email required and valid.', 'evidence' => 'customers/form.php', 'url' => 'customers/new'],
                    ['text' => 'New User form (/users/new): username required and unique, full name required.', 'evidence' => 'users/form.php', 'url' => 'users/new'],
                    ['text' => 'Edit pages pre-fill the existing record and update it on submit.', 'evidence' => 'Customers::edit(), Users::edit()', 'url' => 'customers/1/edit'],
                    ['text' => 'Avatar column; JPG or PNG up to 2 MB; thumbnail stored in a public folder; only the filename saved.', 'evidence' => 'public/uploads/avatars', 'url' => 'users/4/edit'],
                    ['text' => 'User Accounts shows each avatar, or a placeholder when there is none.', 'evidence' => 'UserModel::avatarUrl()', 'url' => 'users'],
                ],
                'code' => [
                    [
                        'file' => 'app/Controllers/Customers.php',
                        'code' => <<<'PHP'
public function create(): RedirectResponse
{
    if (! $this->validate($this->customerModel->formRules())) {
        // errors + what the user typed go back to the form
        return redirect()->to('customers/new')->withInput();
    }

    $this->customerModel->insert($this->formData());

    return redirect()->to('customers')
        ->with('success', 'Customer added.');
}
PHP,
                    ],
                    [
                        'file' => 'app/Controllers/Users.php',
                        'code' => <<<'PHP'
$filename = $file->getRandomName();

service('image')
    ->withFile($file->getTempName())
    ->fit(200, 200, 'center')        // display-ready thumbnail
    ->save(FCPATH . 'uploads/avatars/' . $filename, 85);

$data['avatar'] = $filename;         // only the filename is stored
PHP,
                    ],
                ],
            ],

            4 => [
                'number'  => 4,
                'slug'    => 'tfa4',
                'theme'   => 'access',
                'icon'    => 'lock',
                'art'     => 'partials/art_lock',
                'title'   => 'Who’s allowed in?',
                'short'   => 'Login',
                'tagline' => 'Only staff get past the door.',
                'summary' => 'TFA 4 added a login. Passwords are stored as hashes, checked with password_verify(), and a CodeIgniter Filter sends anyone who is not logged in back to the login page before a customer or user page can load.',
                'actions' => [
                    ['label' => 'Log in', 'text' => 'Demo account: admin / password123.', 'url' => 'login', 'icon' => 'key', 'public' => true],
                    ['label' => 'Open a protected page', 'text' => 'Logged out, /customers sends you to the login page first.', 'url' => 'customers', 'icon' => 'shield'],
                    ['label' => 'Set a user’s password', 'text' => 'New users need one; it is saved with password_hash().', 'url' => 'users/new', 'icon' => 'lock'],
                ],
                'outcomes' => [
                    ['text' => 'Store and retrieve session data.', 'where' => 'Auth::attempt(), AuthFilter, the navigation bar'],
                    ['text' => 'Build a login form that verifies a hashed password.', 'where' => 'auth/login.php + password_verify()'],
                    ['text' => 'Protect pages using a CodeIgniter Filter.', 'where' => 'app/Filters/AuthFilter.php'],
                    ['text' => 'Implement a logout workflow that destroys the session.', 'where' => 'Auth::logout()'],
                ],
                'requirements' => [
                    ['text' => 'Add a password column and set a password_hash() password for every existing user.', 'evidence' => 'database/tfa4_add_password_column.sql, UserSeeder', 'url' => null],
                    ['text' => 'Login page verifies the password with password_verify() and starts a session.', 'evidence' => 'Auth::attempt()', 'url' => 'login'],
                    ['text' => 'A Filter on the Customer and User routes, including their new and edit forms.', 'evidence' => 'Routes.php → auth group', 'url' => 'customers/new'],
                    ['text' => 'Logout destroys the session and redirects to the login page.', 'evidence' => 'Auth::logout()', 'url' => null],
                    ['text' => 'Logged out → redirected to login; logged in → page loads normally.', 'evidence' => 'Try any protected page', 'url' => 'users'],
                ],
                'code' => [
                    [
                        'file' => 'app/Filters/AuthFilter.php',
                        'code' => <<<'PHP'
public function before(RequestInterface $request, $arguments = null)
{
    if (session()->get('isLoggedIn') === true) {
        return null;                       // let the request through
    }

    session()->set('redirect_url', current_url());

    return redirect()->to('login')
        ->with('error', 'Please log in to access that page.');
}
PHP,
                    ],
                    [
                        'file' => 'app/Controllers/Auth.php',
                        'code' => <<<'PHP'
$user = $userModel->where('username', $username)->first();

if ($user === null || ! password_verify($password, $user['password'])) {
    return redirect()->to('login')->withInput()
        ->with('error', 'Invalid username or password.');
}

$session->regenerate(true);              // new session ID
$session->set(['isLoggedIn' => true, 'user_id' => $user['id'], ...]);
PHP,
                    ],
                ],
            ],
        ];
    }

    public static function find(int $number): ?array
    {
        return self::all()[$number] ?? null;
    }
}
