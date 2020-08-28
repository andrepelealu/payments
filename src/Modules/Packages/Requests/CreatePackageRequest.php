<?php namespace MissionControl\Payments\Modules\Packages\Requests;

use Illuminate\Foundation\Http\FormRequest as Request;

/**
 * Class CreatePackageRequest
 * @package MissionControl\Payments\Modules\Packages\Requests
 */
class CreatePackageRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'frequency_interval' => [
                function($attribute, $value, $fail) {
                    ;
                    if($this->frequency == 'year' && $value > 1) {
                        return $fail('Frequency Interval cannot be greater than 1 if choosing a Frequency of Year');
                    }
                }
            ]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

}
