<?php namespace MissionControl\Payments\Modules\Packages\Presenters;

use Karl456\Presenter\Presenter;

/**
 * Class PackagePresenter
 * @package MissionControl\Payments\Modules\Packages\Presenters
 */
class PackagePresenter extends Presenter
{
    /**
     * @return mixed|string
     */
    public function getSlug()
    {
        return '/packages/' . $this->slug;
    }

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
        return $this->description;
    }

    /**
     * @return mixed|string
     */
    public function getStatus()
    {
        if($this->active == true) {
            return 'Active';
        }
        return 'Disabled';
    }


    /**
     * @return string
     */
    public function getStatusLabel()
    {
        if($this->active == true) {
            return '<span class="label success">Active</span>';
        }
        return '<span class="label alert">Disabled</span>';
    }


    /**
     * @return string
     */
    public function getPrice()
    {
        return (config('packagecurrencies')[$this->currency]['html_symbol']) . (number_format($this->price, 2) + 0) . ' ' . $this->currency;
    }

    /**
     * @return string
     */
    public function getHumanFrequency()
    {
        switch($this->frequency) {
            case 'day':
                if($this->frequency_interval == 1) {
                    return 'Day';
                } else {
                    return $this->frequency_interval . ' Days';
                }
                break;
            case 'week':
                if($this->frequency_interval == 1) {
                    return 'Week';
                } elseif($this->frequency_interval == 2) {
                    return 'Fortnight';
                } else {
                    return $this->frequency_interval . ' Weeks';
                }
                break;
            case 'month':
                if($this->frequency_interval == 1) {
                    return 'Month';
                } elseif($this->frequency_interval == 3) {
                    return 'Quarter';
                } else {
                    return $this->frequency_interval . ' Months';
                }
                break;
            case 'year':
                if($this->frequency_interval == 1) {
                    return 'Year';
                } else {
                    return $this->frequency_interval . ' Years';
                }
                break;
        }
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
