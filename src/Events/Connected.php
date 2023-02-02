<?php
namespace Dodois\Events;

use Illuminate\Foundation\Events\Dispatchable;

class Connected
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @param array  $response
     * @param bool  $refresh
     * @return void
     */
    public function __construct(
        public array $response,
        public bool $refresh = false,
    ) {}
}
