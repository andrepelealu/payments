<?php namespace MissionControl\Payments\Modules\PaymentMethods\Models;

use Eloquent, SoftDeletingTrait;
use Karl456\Presenter\Traits\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use MissionControl\Payments\Modules\Packages\Models\Package;
use MissionControl\Payments\Modules\Transactions\Models\Transaction;
use Modules\Users\Models\User;

/**
 * Class PaymentMethod
 * @package MissionControl\Payments\Modules\PaymentMethods\Models
 */
class PaymentMethod extends Eloquent
{
    use PresentableTrait;
    use SoftDeletes;

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string
     */
    protected $presenter = 'MissionControl\Payments\Modules\PaymentMethods\Presenters\PaymentMethodPresenter';

    /**
     * @var string
     */
    protected $table = "payment_methods";
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
     * @param $value
     * @return mixed
     */
    public function getDetailsAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * @param $value
     */
    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = json_encode($value);
    }

    /**
     * @param User $user
     * @param $subscribedAt
     * @param $subscribedUntil
     * @return mixed
     */
    public function subscriptionStarted(User $user, $subscribedAt, $subscribedUntil)
    {
        return $user->update([
            'subscription_cancelled_at' => null,
            'subscribed_at' => Carbon::parse($subscribedAt),
            'subscribed_until' => Carbon::parse($subscribedUntil)
        ]);
    }

    /**
     * @param User $user
     * @param Package $package
     * @param $vendorReference
     * @param $status
     * @param $subtotal
     * @param $vat
     * @param $total
     * @return mixed
     */
    public function paymentReceived(User $user, Package $package, $vendorReference, $status, $subtotal, $vat, $total)
    {
        $transaction = new Transaction();
        return $transaction->create([
            'user_id' => $user->id,
            'type_id' => $package->id,
            'type' => get_class($package),
            'reference' => $transaction->generateReference(),
            'vendor_reference' => $vendorReference,
            'status' => $status,
            'subtotal' => $subtotal,
            'vat' => $vat,
            'total' => $total
        ]);

        switch($package->frequency) {
            case 'day':
                $subscribed_until = Carbon::parse($user->subscribed_until)->addDays($package->frequency_interval);
                break;
            case 'week':
                $subscribed_until = Carbon::parse($user->subscribed_until)->addWeeks($package->frequency_interval);
                break;
            case 'month':
                $subscribed_until = Carbon::parse($user->subscribed_until)->addMonths($package->frequency_interval);
                break;
            case 'year':
                $subscribed_until = Carbon::parse($user->subscribed_until)->addYears($package->frequency_interval);
                break;
        }

        $user->update([
            'subscribed_until' => $subscribed_until
        ]);
    }

    /**
     * @param User $user
     * @param $cancelledAt
     * @return mixed
     */
    public function subscriptionCancelled(User $user, $cancelledAt)
    {
        return $user->update([
            'subscription_cancelled_at' => Carbon::parse($cancelledAt),
            'subscription_method_id' => null,
            'subscription_reference' => null
        ]);
    }
}
