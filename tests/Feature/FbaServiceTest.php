<?php

use App\Data\AbstractOrder;
use App\Data\BuyerInterface;
use App\Data\Exceptions\FbaException;
use App\Services\FbaService;

beforeEach(function () {
    $this->order = $this->mock(AbstractOrder::class);
    $this->order->data = json_decode(file_get_contents(storage_path('app/fba-shipping/order.16400.json')), true);
    $this->buyer = $this->mock(BuyerInterface::class);
    $this->service = $this->mock(FbaService::class)
        ->makePartial()
        ->shouldAllowMockingProtectedMethods();
});

it('returns tracking number on successful shipment', function () {
    $this->service->shouldReceive('makeMockRequest')
        ->once()
        ->andReturn([
            'status' => 'success',
            'tracking_number' => 'FBA-MOCK123456',
            'estimated_delivery' => now()->addDays(2)->toDateString(),
        ]);

    $tracking = $this->service->ship($this->order, $this->buyer);

    expect($tracking)->toBe('FBA-MOCK123456');
});

it('throws FbaException when FBA responds with error', function () {
    $this->service->shouldReceive('makeMockRequest')
        ->once()
        ->andReturn([
            'status' => 'error',
            'message' => 'FBA rejected order: out of stock',
        ]);

    $this->expectException(FbaException::class);
    $this->expectExceptionMessage('FBA rejected order: out of stock');

    $this->service->ship($this->order, $this->buyer);
});

afterEach(fn () => Mockery::close());
