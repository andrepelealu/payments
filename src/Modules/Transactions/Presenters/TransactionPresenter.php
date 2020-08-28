<?php namespace MissionControl\Payments\Modules\Transactions\Presenters;

use Carbon\Carbon;
use Karl456\Presenter\Presenter;
use NumberFormatter;

/**
 * Class TransactionPresenter
 * @package MissionControl\Payments\Modules\Transactions\Presenters
 */
class TransactionPresenter extends Presenter
{
    /**
     * @return string
     */
    public function getStatus()
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return mixed
     */
    public function getVendorReference()
    {
        return $this->vendor_reference;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return mixed
     */
    public function getConversionRate()
    {
        return $this->conversion_rate;
    }

    /**
     * @return mixed
     */
    public function getConvertedSubtotal()
    {
        $format = new NumberFormatter("en_GB", NumberFormatter::CURRENCY);
        return $format->formatCurrency($this->subtotal * $this->conversion_rate, $this->currency);
    }

    /**
     * @return mixed
     */
    public function getConvertedVatTotal()
    {
        $format = new NumberFormatter("en_GB", NumberFormatter::CURRENCY);
        return $format->formatCurrency($this->vat * $this->conversion_rate, $this->currency);
    }

    /**
     * @return mixed
     */
    public function getConvertedTotal()
    {
        $format = new NumberFormatter("en_GB", NumberFormatter::CURRENCY);
        return $format->formatCurrency($this->total * $this->conversion_rate, $this->currency);
    }

    /**
     * @return mixed
     */
    public function getSubtotal()
    {
        return money_format('%n', $this->subtotal);
    }

    /**
     * @return mixed
     */
    public function getVatTotal()
    {
        return money_format('%n', $this->vat);
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return money_format('%n', $this->total);
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

        /**
     * @return mixed|string
     */
    public function getStatusLabel()
    {
        if($this->status == 'pending') {
            return '<span class="label info">Pending</span>';
        }
        if($this->status == 'complete') {
            return '<span class="label success">Complete</span>';
        }
        if($this->status == 'failed') {
            return '<span class="label alert">Failed</span>';
        }
        if($this->status == 'refunded') {
            return '<span class="label alert">Refunded</span>';
        }
        return ucwords(str_replace('_', ' ', $this->status));
    }
}
