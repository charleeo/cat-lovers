<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class MoviesCreationRule implements Rule
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

    }

    // create the rule
    public function validateMovies(array $data)
    {
        $messages = [
            'fight_video.video' => 'The video input must be a video',
            'description.min' => 'The description field can not be less than 20 characters',
        ];
        $validator = Validator::make($data, [
            'movie_title' => ['required', 'string', 'min:3', 'max:55'],
            'cat_1_image' => ['required', 'image'],
            'cat_2_image' => ['required', 'image'],
            'cat_1_name' => ['required', 'min:3', 'max:55'],
            'cat_2_name' => ['required', 'min:3', 'max:55'],
            'fight_duration' => ['required', 'max:55'],
            'description' => ['min:20', 'sometimes'],
            'fight_video' => ['required', 'video']
        ], $messages);
        return $validator;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
