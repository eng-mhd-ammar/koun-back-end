<?php

namespace Modules\DonationRequest\Controllers\V1;

use Modules\DonationRequest\DTO\V1\DeliveryDTO;
use Modules\DonationRequest\Interfaces\V1\Delivery\DeliveryServiceInterface;
use Modules\DonationRequest\Requests\V1\Delivery\CreateDeliveryRequest;
use Modules\DonationRequest\Requests\V1\Delivery\UpdateDeliveryRequest;
use Modules\DonationRequest\Resources\V1\DeliveryResource;
use Modules\Core\Controllers\BaseController;
use Modules\Core\Utilities\Response;

class DeliveryController extends BaseController
{
    public function __construct(protected DeliveryServiceInterface $modelService)
    {
    }

    public function index()
    {
        $models = $this->modelService->index();
        return (new Response(DeliveryResource::collection($models)->resource))->success(message: "All deliveries.");
    }

    public function show(string $modelId)
    {
        $model = $this->modelService->show($modelId);
        return (new Response(DeliveryResource::make($model)))->success(message: "Delivery details.");
    }

    public function create(CreateDeliveryRequest $request)
    {
        $this->modelService->create(DeliveryDTO::fromRequest($request));
        return (new Response())->success(message: "Delivery created successfully.", code: Response::HTTP_CREATED);
    }

    public function update(UpdateDeliveryRequest $request, string $modelId)
    {
        $this->modelService->update($modelId, DeliveryDTO::fromRequest($request));
        return (new Response())->success(message: "Delivery updated successfully.");
    }

    public function delete(string $modelId)
    {
        $this->modelService->delete($modelId);
        return (new Response())->success(message: "Delivery deleted successfully.");
    }

    public function ForceDelete(string $modelId)
    {
        $this->modelService->ForceDelete($modelId);
        return (new Response())->success(message: "Delivery force deleted successfully.");
    }

    public function restore(string $modelId)
    {
        $this->modelService->restore($modelId);
        return (new Response())->success(message: "Delivery restored successfully.");
    }
}
