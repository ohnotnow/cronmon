<?php

namespace App\Http\Requests;

use App\Cronjob;
use App\Rules\ValidCronExpression;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCronjob extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->is_admin) {
            return true;
        }
        $job = Cronjob::findOrFail($this->id);
        if ($this->user()->id == $job->user_id) {
            return true;
        }
        if ($this->user()->onTeam($job->team_id)) {
            return true;
        }

        return false;
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
                Rule::unique('cronjobs')->ignore($this->id)->where(function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                }),
            ],
            'period' => 'required|min:1',
            'period_units' => 'required|in:minute,day,hour,week',
            'grace' => 'required|min:1',
            'grace_units' => 'required|in:minute,day,hour,week',
            'email' => 'emails',
            'cron_schedule' => ['nullable', new ValidCronExpression],
        ];
    }
}
