<?php

namespace Modules\DonationRequest\Controllers\V1;

use Modules\DonationRequest\DTO\V1\DonationRequestItemDTO;
use Modules\DonationRequest\Interfaces\V1\DonationRequestItem\DonationRequestItemServiceInterface;
use Modules\DonationRequest\Requests\V1\DonationRequestItem\CreateDonationRequestItemRequest;
use Modules\DonationRequest\Requests\V1\DonationRequestItem\UpdateDonationRequestItemRequest;
use Modules\DonationRequest\Resources\V1\DonationRequestItemResource;
use Modules\Core\Controllers\BaseController;
use Modules\Core\Utilities\Response;

class DonationRequestItemController extends BaseController
{
    public function __construct(protected DonationRequestItemServiceInterface $modelService)
    {
    }

    public function index()
    {
        $models = $this->modelService->index();
        return (new Response(DonationRequestItemResource::collection($models)->resource))->success(message: "All donation request items.");
    }

    public function show(string $modelId)
    {
        $model = $this->modelService->show($modelId);
        return (new Response(DonationRequestItemResource::make($model)))->success(message: "Donation request item details.");
    }

    public function create(CreateDonationRequestItemRequest $request)
    {
        $this->modelService->create(DonationRequestItemDTO::fromRequest($request));
        return (new Response())->success(message: "Donation request item created successfully.", code: Response::HTTP_CREATED);
    }

    public function update(UpdateDonationRequestItemRequest $request, string $modelId)
    {
        $this->modelService->update($modelId, DonationRequestItemDTO::fromRequest($request));
        return (new Response())->success(message: "Donation request item updated successfully.");
    }

    public function delete(string $modelId)
    {
        $this->modelService->delete($modelId);
        return (new Response())->success(message: "Donation request item deleted successfully.");
    }

    public function ForceDelete(string $modelId)
    {
        $this->modelService->ForceDelete($modelId);
        return (new Response())->success(message: "Donation request item force deleted successfully.");
    }

    public function restore(string $modelId)
    {
        $this->modelService->restore($modelId);
        return (new Response())->success(message: "Donation request item restored successfully.");
    }
}
