<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateValidation extends FormRequest
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
            'title' => 'required|max:191',
            'description' => 'required|max:5000',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
            'publication_date' => 'required|date|before:today',
            'author_id' => 'required|numeric|min:1',
            'genre_id' => 'required|numeric|min:1',
        ];
    }

    /**
     *  Get the validation messages that correspond to the validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Title is required.',
            'title.max' => 'Title shouldn\'t exceed 191 characters limit.',
            'description.required' => 'Description is required.',
            'description.max' => 'Description shouldn\'t exceed 5000 characters limit.',
            'cover_image.image' => 'Please upload image file.',
            'cover_image.mimes' => 'Image should belong to one of these formats: .jpg, .jpeg, .png, .svg, .gif.',
            'cover_image.max' => 'Image shouldn\'t exceed 2 MB memory limit.',
            'publication_date.required' => 'Publication date is required.',
            'publication_date.date' => 'Invalid date was sent.',
            'publication_date.before' => 'Publication date need to be in the past.',
            'author_id.required' => 'Plese pick an author.',
            'genre_id.required' => 'Plese pick a genre.',
        ];
    }
}
