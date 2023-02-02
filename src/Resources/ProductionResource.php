<?php
namespace Dodois\Resources;

use Dodois\Concerns\Resource\CanAccessClient;
use Dodois\Contracts\ResourceContract;
use Dodois\Requests\Production\OrdersHandoverTimeRequest;
use Dodois\Requests\Production\ProductivityRequest;
use Dodois\Requests\Production\StopSalesChannelsRequest;
use Dodois\Requests\Production\StopSalesIngredientsRequest;
use Dodois\Requests\Production\StopSalesProductsRequest;

class ProductionResource implements ResourceContract
{
    use CanAccessClient;

    public function ordersHandoverTime(): OrdersHandoverTimeRequest
    {
        return new OrdersHandoverTimeRequest($this);
    }

    public function productivity(): ProductivityRequest
    {
        return new ProductivityRequest($this);
    }

    public function stopSalesChannels(): StopSalesChannelsRequest
    {
        return new StopSalesChannelsRequest($this);
    }

    public function stopSalesIngredients(): StopSalesIngredientsRequest
    {
        return new StopSalesIngredientsRequest($this);
    }

    public function stopSalesProducts(): StopSalesProductsRequest
    {
        return new StopSalesProductsRequest($this);
    }
}
