<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use \GuzzleHttp\Client as Guzzle;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $response = (new Guzzle)->request(
            'POST',
            'https://google.com/recaptcha/api/siteverify',
            [
                'form_params' => [
                    'secret' => config('services.recaptcha.server_secret'),
                    'response' => $value,
                    'remoteip' => request()->ip()
                ]
            ]
        );

        $r = json_decode($response->getBody()->getContents());

        return !! $r->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The recaptcha verification failed. Try again.';
    }
}
