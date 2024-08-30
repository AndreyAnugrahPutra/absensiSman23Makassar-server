<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class apiResource extends JsonResource
{
    ///define properti
    public $api_status;
    public $api_message;
    public $resource;

    /**
     * __construct
     *
     * @param  mixed $status
     * @param  mixed $message
     * @param  mixed $resource
     * @return void
     */
    public function __construct($api_status, $api_message, $resource)
    {
        parent::__construct($resource);
        $this->api_status  = $api_status;
        $this->api_message = $api_message;
    }

    /**
     * toArray
     *
     * @param  mixed $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'api_status'   => $this->api_status,
            'api_message'   => $this->api_message,
            'api_data'      => $this->resource,
        ];
    }
}
