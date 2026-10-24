<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Repositories\UserRepository;
use App\Services\Auth;
use App\Services\LoginThrottle;
use App\Services\Validator;
use PDOException;

/** Ակումբին միանալ՝ միայն «member» դերով — PHP Դաս 16 */
class RegisterController
{
    private LoginThrottle $throttle;

    public function __construct(
        private readonly UserRepository $users,
        private readonly Auth $auth,
    ) {
        // 3 նոր հաշիվ մեկ սեսիայից ժամում (տնային 11)
        $this->throttle = new LoginThrottle('register_attempts', 3, 3600);
    }

    public function form(Request $request): Response
    {
        if ($this->auth->check()) {
            return Response::redirect('/');
        }

        return $this->render([], []);
    }

    public function store(Request $request): Response
    {
        if ($this->throttle->tooManyAttempts()) {
            return $this->render(['email' => 'Չափազանց շատ գրանցումներ։ Փորձիր մեկ ժամից։'], [], 429);
        }

        // ⭐ Միայն այս երկու դաշտը։ DevTools-ով ավելացված role=admin-ը ոչ ոք չի կարդում (տնային 10)
        $data = [
            'name'  => $request->input('name'),
            'email' => mb_strtolower($request->input('email')),
        ];
        $password = $request->input('password');

        $errors = Validator::register($data, $password, $request->input('password_confirmation'));

        if ($errors === [] && $this->users->findByEmail($data['email']) !== null) {
            $errors['email'] = 'Այս էլ. հասցեով արդեն կա հաշիվ';
        }

        if ($errors === []) {
            try {
                $id = $this->users->create($data['email'], password_hash($password, PASSWORD_DEFAULT), $data['name']);
            } catch (PDOException $e) {
                if ($e->getCode() !== '23000') {           // 23000 = UNIQUE-ի խախտում
                    throw $e;
                }
                $errors['email'] = 'Այս էլ. հասցեով արդեն կա հաշիվ';
            }
        }

        if ($errors !== []) {
            return $this->render($errors, $data, 422);      // ⭐ գաղտնաբառը երբեք չի վերադառնում ֆորմա
        }

        $this->throttle->hit();
        $this->auth->login($this->users->find($id));
        flash('success', 'Բարի գալուստ ակումբ, ' . $data['name']);

        return Response::redirect('/');
    }

    private function render(array $errors, array $old, int $status = 200): Response
    {
        return Response::html(page('pages/register', ['errors' => $errors, 'old' => $old], 'Միանալ ակումբին — ' . SITE_NAME, 'register'), $status);
    }
}
