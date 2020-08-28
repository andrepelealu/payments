<?php namespace MissionControl\Payments\Modules\PaymentMethods\Presenters;

use Karl456\Presenter\Presenter;

/**
 * Class PaymentMethodPresenter
 * @package Modules\PaymentMethods\Presenters
 */
class PaymentMethodPresenter extends Presenter
{
    /**
     * @return mixed|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return nl2br($this->description);
    }

    /**
     * @return mixed
     */
    public function getActiveLabel()
    {
        if($this->active == true) {
            return '<span class="label success">Active</span>';
        }
        return '<span class="label alert">Disabled</span>';
    }

    /**
     * @return mixed
     */
    public function getModeLabel()
    {
        if($this->mode == 'live') {
            return '<span class="label info">Live</span>';
        }
        return '<span class="label warning">Test</span>';
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at->format('d/m/Y - g:i A');
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at->format('d/m/Y - g:i A');
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deleted_at->format('d/m/Y - g:i A');
    }
}
