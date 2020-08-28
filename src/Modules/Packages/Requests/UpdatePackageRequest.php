<?php namespace MissionControl\Payments\Modules\Packages\Requests;

use Illuminate\Foundation\Http\FormRequest as Request;

/**
 * Class UpdatePackageRequest
 * @package MissionControl\Payments\Modules\Packages\Requests
 */
class UpdatePackageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'active' => 'required'
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
