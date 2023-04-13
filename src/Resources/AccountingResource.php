<?php
namespace Dodois\Resources;

use Dodois\Concerns\Resource\CanAccessClient;
use Dodois\Contracts\ResourceContract;
use Dodois\Requests\Accounting\CancelledSalesRequest;
use Dodois\Requests\Accounting\DefectiveProductsRequest;
use Dodois\Requests\Accounting\IncomingStockItemsRequest;
use Dodois\Requests\Accounting\ProductsRequest;
use Dodois\Requests\Accounting\SalesRequest;
use Dodois\Requests\Accounting\SemiFinishedProductsProductionRequest;
use Dodois\Requests\Accounting\StaffMealsRequest;
use Dodois\Requests\Accounting\StockConsumptionsByPeriodRequest;
use Dodois\Requests\Accounting\StockTransfersRequest;
use Dodois\Requests\Accounting\WriteOffsProductsRequest;
use Dodois\Requests\Accounting\WriteOffsStockItemsRequest;

class AccountingResource implements ResourceContract
{
    use CanAccessClient;

    public function cancelledSales(): CancelledSalesRequest
    {
        return new CancelledSalesRequest($this);
    }

    public function defectiveProducts(): DefectiveProductsRequest
    {
        return new DefectiveProductsRequest($this);
    }

    public function incomingStockItems(): IncomingStockItemsRequest
    {
        return new IncomingStockItemsRequest($this);
    }

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

    public function staffMeals(): StaffMealsRequest
    {
        return new StaffMealsRequest($this);
    }

    public function stockConsumptionsByPerios(): StockConsumptionsByPeriodRequest
    {
        return new StockConsumptionsByPeriodRequest($this);
    }

    public function stockTransfers(): StockTransfersRequest
    {
        return new StockTransfersRequest($this);
    }

    public function writeOffsProducts(): WriteOffsProductsRequest
    {
        return new WriteOffsProductsRequest($this);
    }

    public function writeOffsStockItems(): WriteOffsStockItemsRequest
    {
        return new WriteOffsStockItemsRequest($this);
    }
}
