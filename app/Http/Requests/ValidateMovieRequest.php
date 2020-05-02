<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'movie_title' => ['required', 'string', 'min:3', 'max:55'],
            'cat_1_image' => ['required', 'image'],
            'cat_2_image' => ['required', 'image'],
            'cat_1_name' => ['required', 'min:3', 'max:55'],
            'cat_2_name' => ['required', 'min:3', 'max:55'],
            'fight_duration' => ['required', 'max:55'],
            'description' => ['min:20', 'nullable'],
            'fight_video' => ['required', 'video']
        ];
        // $messages = [
        //     'fight_video.video' => 'The video input must be a video',
        //     'description.min' => 'The description field can not be less than 20 characters',
        // ];
        // $validator = Validator::make($data, [
        //     'movie_title' => ['required', 'string', 'min:3', 'max:55'],
        //     'cat_1_image' => ['required', 'image'],
        //     'cat_2_image' => ['required', 'image'],
        //     'cat_1_name' => ['required', 'min:3', 'max:55'],
        //     'cat_2_name' => ['required', 'min:3', 'max:55'],
        //     'fight_duration' => ['required', 'max:55'],
        //     'description' => ['min:20', 'sometimes'],
        //     'fight_video' => ['required', 'video']
        // ], $messages);
        // return $validator;
    }
}
