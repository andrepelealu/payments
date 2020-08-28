<?php namespace MissionControl\Payments\Modules\Transactions\Models;

use Eloquent, SoftDeletingTrait;
use Karl456\Presenter\Traits\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eyeweb\MissionControl\Modules\Users\Models\User;
use MissionControl\Payments\Modules\PaymentMethods\Models\PaymentMethod;

/**
 * Class Transaction
 * @package MissionControl\Payments\Modules\Transactions\Models
 */
class Transaction extends Eloquent
{

    use PresentableTrait;
    use SoftDeletes;

    /**
     * @var array
     */
    protected $with = [
        'user',
        'paymentMethod'
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string
     */
    protected $presenter = 'MissionControl\Payments\Modules\Transactions\Presenters\TransactionPresenter';

    /**
     * @var string
     */
    protected $table = "transactions";
    /**
     * @var array
     */
    protected $guarded = [
        'id',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * @return string
     */
    public function generateReference()
    {
        $string = strtoupper(substr(date('D'), 0, 1) . substr(date('F'), 0, 1) . '-' . str_random(10));

        if($order = self::where('reference', $string)->first()) {
            self::generateReference();
        }

        return $string;
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'paymentmethod_id', 'id');
    }
}
