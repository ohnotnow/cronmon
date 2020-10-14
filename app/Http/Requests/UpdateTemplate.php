<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\ValidCronExpression;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTemplate extends FormRequest
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
        $request = $this;
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('templates')->ignore($this->id)->where(function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                }),
            ],
            'period' => 'required|min:1',
            'period_units' => 'required|in:minute,day,hour,week',
            'grace' => 'required|min:1',
            'grace_units' => 'required|in:minute,day,hour,week',
            'cron_schedule' => ['nullable', new ValidCronExpression],
            'team_id' => 'nullable|integer',
            'email' => 'nullable|email',
        ];
    }
}
