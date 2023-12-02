<?php

namespace App\Controllers;

use App\Config\Configuration;
use App\Core\AControllerBase;
use App\Core\HTTPException;
use App\Core\LinkGenerator;
use App\Core\Responses\Response;
use App\Core\Responses\ViewResponse;
use DateTime;
use Exception;

/**
 * Class AuthController
 * Controller for authentication actions
 * @package App\Controllers
 */
class AuthController extends AControllerBase
{
    /**
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->redirect(Configuration::LOGIN_URL);
    }

    public function registracia() : Response
    {
        return $this->html();
    }

    /**
     * Login a user
     * @return Response
     */
    public function login(): Response
    {
        $formData = $this->app->getRequest()->getPost();
        $logged = null;
        if (isset($formData['submit'])) {
            $logged = $this->app->getAuth()->login($formData['login'], $formData['password']);
            if ($logged) {
                return $this->redirect($this->url("admin.index"));
            }
        }

        $data = ($logged === false ? ['message' => 'Zlý login alebo heslo!'] : []);
        return $this->html($data);
    }

    /**
     * Logout a user
     * @return ViewResponse
     */
    public function logout(): Response
    {
        $this->app->getAuth()->logout();
        return $this->html();
    }

    /**
     * @throws HTTPException
     */
    public function register() : Response
    {
        $formData = $this->app->getRequest()->getPost();
        if ($this->checkForm($formData)
        ) {
            $name = $formData['name'];
            $surname = $formData['surname'];
            $gender = $formData['gender'];
            $birthDate = $formData['birthDate'];
            $street = $formData['street'];
            $city = $formData['city'];
            $postalCode = $formData['postalCode'];
            $email = $formData['email'];
            $password = $formData['password'];
        }
        else {
            throw new HTTPException(400, "Missing parameter");
        }

        return $this->html(LinkGenerator::url("auth.login"));
    }

    private function checkForm($formData) : bool
    {
        if (!isset($formData['submit'])
            || !isset($formData['name'])
            || !isset($formData['surname'])
            || !isset($formData['gender'])
            || !isset($formData['birthDate'])
            || !isset($formData['street'])
            || !isset($formData['city'])
            || !isset($formData['postalCode'])
            || !isset($formData['email'])
            || !isset($formData['password'])
        )
        {
            return false;
        }

        if (empty($formData['submit'])
            || empty($formData['name'])
            || empty($formData['surname'])
            || empty($formData['gender'])
            || empty($formData['birthDate'])
            || empty($formData['street'])
            || empty($formData['city'])
            || empty($formData['postalCode'])
            || empty($formData['email'])
            || empty($formData['password'])
        )
        {
            return false;
        }

        $gender = $formData['gender'];
        if ($gender != "female" || $gender != "male" || $gender != "other")
        {
            return false;
        }

        $birthDate = DateTime::createFromFormat('Y-m-d', $formData['birthDate']);
        if (!$birthDate || $birthDate->format('Y-m-d') !== $formData['birthDate'])
        {
            return false;
        }

        $postalCode = $formData['postalCode'];
        if (!preg_match('/\d{3} ?\d{2}$/', $postalCode))
        {
            return false;
        }

        $email = $formData['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return false;
        }

        return true;
    }
}

