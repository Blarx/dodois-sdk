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
     * @param \Illuminate\Http\RedirectResponse  $response
     * @param string  $errorMessage
     * @return void
     */
    public function __construct(
        public RedirectResponse $response,
        public string $errorMessage = '',
    ) {}
}
