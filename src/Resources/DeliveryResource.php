<?php
namespace Dodois\Resources;

use Dodois\Concerns\Resource\CanAccessClient;
use Dodois\Contracts\ResourceContract;
use Dodois\Requests\Delivery\StatisticRequest;
use Dodois\Requests\Delivery\VouchersRequest;

class DeliveryResource implements ResourceContract
{
    use CanAccessClient;

    public function statistic(): StatisticRequest
    {
        return new StatisticRequest($this);
    }

    public function vouchers(): VouchersRequest
    {
        return new VouchersRequest($this);
    }
}
