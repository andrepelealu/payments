<?php namespace MissionControl\Payments\Modules\Packages\Models;

use Eloquent, SoftDeletingTrait;
use Karl456\Presenter\Traits\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Users\Models\User;
use Carbon\Carbon;

/**
 * Class Package
 * @package MissionControl\Payments\Modules\Packages\Models
 */
class Package extends Eloquent
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
    protected $presenter = 'MissionControl\Payments\Modules\Packages\Presenters\PackagePresenter';

    /**
     * @var string
     */
    protected $table = "packages";
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
     * @param User $user
     * @return bool
     */
    public function isSubscribedToPackage(User $user)
    {
        if($this->id == $user->subscription_package_id) {
            if($user->subscribed_until > Carbon::now()) {
                return true;
            }
            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isSubscribed(User $user)
    {
        if($user->subscribed_until > Carbon::now()) {
            return true;
        }
        return false;
    }

}
