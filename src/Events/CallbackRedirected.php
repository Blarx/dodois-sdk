<?php
namespace Dodois\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\RedirectResponse;

class CallbackRedirected
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
        public RedirectResponse $response,
        public string $errorMessage = '',
    ) {}
}
