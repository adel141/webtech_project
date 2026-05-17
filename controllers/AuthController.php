<?php
class AuthController extends Controller {

    public function home() {
        if (Auth::check()) {
            $this->redirect('/' . Auth::role() . '/dashboard');
        }
        $this->redirect('/login');
    }

    public function loginForm() {
        if (Auth::check()) $this->redirect('/' . Auth::role() . '/dashboard');
        $pageTitle = 'Login';
        $this->view('auth/login', compact('pageTitle'), false);
    }

    public function login() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];

        if (!$email) $errors[] = 'Email is required.';
        if (!$password) $errors[] = 'Password is required.';

        if (empty($errors)) {
            $userModel = new UserModel();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                if (!$user['is_active']) {
                    $errors[] = 'Your account has been suspended. Contact admin.';
                } else {
                    Auth::login($user);
                    $this->redirect('/' . $user['role'] . '/dashboard');
                }
            } else {
                $errors[] = 'Invalid email or password.';
            }
        }

        $pageTitle = 'Login';
        $this->view('auth/login', compact('pageTitle', 'errors', 'email'), false);
    }

    public function registerForm() {
        if (Auth::check()) $this->redirect('/' . Auth::role() . '/dashboard');
        $pageTitle = 'Register';
        $this->view('auth/register', compact('pageTitle'), false);
    }

    public function register() {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? '';
        $errors = [];

        if (!$name) $errors[] = 'Name is required.';
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        if (!$phone) $errors[] = 'Phone number is required.';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        if ($password !== $confirm) $errors[] = 'Passwords do not match.';
        if (!in_array($role, ['seeker', 'employer', 'recruiter'])) $errors[] = 'Please select a valid role.';

        $userModel = new UserModel();
        if (empty($errors) && $userModel->findByEmail($email)) {
            $errors[] = 'Email is already registered.';
        }

        if (empty($errors)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $userId = $userModel->create($name, $email, $hash, $phone, $role);

            // Create role-specific profile
            if ($role === 'seeker') {
                (new SeekerModel())->createProfile($userId);
            } elseif ($role === 'employer') {
                (new EmployerModel())->createProfile($userId);
            } elseif ($role === 'recruiter') {
                (new RecruiterModel())->createProfile($userId);
            }

            $user = $userModel->findById($userId);
            Auth::login($user);
            $this->flash('success', 'Account created successfully! Welcome to JobPortal.');
            $this->redirect('/' . $role . '/dashboard');
        }

        $pageTitle = 'Register';
        $this->view('auth/register', compact('pageTitle', 'errors', 'name', 'email', 'phone', 'role'), false);
    }

    public function logout() {
        Auth::logout();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
