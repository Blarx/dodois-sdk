<?php
namespace Dodois\Resources;

use Dodois\Concerns\Resource\CanAccessClient;
use Dodois\Contracts\ResourceContract;
use Dodois\Requests\Staff\IncentivesByMembersRequest;

class StaffResource implements ResourceContract
{
    use CanAccessClient;

    public function incentivesByMembers(): IncentivesByMembersRequest
    {
        return new IncentivesByMembersRequest($this);
    }
}
