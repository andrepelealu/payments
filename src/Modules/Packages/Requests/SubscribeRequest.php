<?php namespace MissionControl\Payments\Modules\Packages\Requests;

use Illuminate\Foundation\Http\FormRequest as Request;

/**
 * Class SubscribeRequest
 * @package MissionControl\Payments\Modules\Packages\Requests
 */
class SubscribeRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'paymentmethod_id' => 'required'
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
