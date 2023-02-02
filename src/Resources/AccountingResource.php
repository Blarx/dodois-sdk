<?php
namespace Dodois\Resources;

use Dodois\Concerns\Resource\CanAccessClient;
use Dodois\Contracts\ResourceContract;
use Dodois\Requests\Accounting\ProductsRequest;
use Dodois\Requests\Accounting\SalesRequest;
use Dodois\Requests\Accounting\SemiFinishedProductsProductionRequest;

class AccountingResource implements ResourceContract
{
    use CanAccessClient;

    public function products(): ProductsRequest
    {
        return new ProductsRequest($this);
    }

    public function sales(): SalesRequest
    {
        return new SalesRequest($this);
    }

    public function semiFinishedProductsProduction(): SemiFinishedProductsProductionRequest
    {
        return new SemiFinishedProductsProductionRequest($this);
    }
}
