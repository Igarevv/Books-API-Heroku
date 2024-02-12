<?php

namespace App\Http\Controllers;

use App\Core\Controller\Controller;

class RegisterController extends Controller
{

    public function register()
    {
        $rules = [
          'name' => ['required', 'max:255', 'alphanumeric'],
          'email' => ['email'],
          'password' => ['required'],
        ];

        if ( ! $this->isValidFields($rules)) {
            $this->response()->status(400)->message('Bad request')->send();
        }

        $validation = $this->request()->validate($rules);

        if ( ! $validation) {
            header("Content-Type: application/json");
            echo json_encode($this->request()->errors());
        }
    }

    private function isValidFields(array $rules): bool
    {
        $json_data = $this->request()->getJsonData();
        $checkKey = function ($key) use ($json_data, $rules) {
            if (array_key_exists($key, $rules)) {
                return true;
            }
            return false;
        };
        $check = array_map($checkKey, array_keys($json_data));
        return ! in_array(false, $check);
    }

}