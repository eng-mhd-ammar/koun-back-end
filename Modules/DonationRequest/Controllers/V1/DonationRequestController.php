<?php

namespace Modules\DonationRequest\Controllers\V1;

use Modules\DonationRequest\DTO\V1\DonationRequestDTO;
use Modules\DonationRequest\Interfaces\V1\DonationRequest\DonationRequestServiceInterface;
use Modules\DonationRequest\Requests\V1\DonationRequest\CreateDonationRequestRequest;
use Modules\DonationRequest\Requests\V1\DonationRequest\UpdateDonationRequestRequest;
use Modules\DonationRequest\Resources\V1\DonationRequestResource;
use Modules\Core\Controllers\BaseController;
use Modules\Core\Utilities\Response;

class DonationRequestController extends BaseController
{
    public function __construct(protected DonationRequestServiceInterface $modelService)
    {
    }

    public function index()
    {
        $models = $this->modelService->index();
        return (new Response(DonationRequestResource::collection($models)->resource))->success(message: "All donation requests.");
    }

    public function show(string $modelId)
    {
        $model = $this->modelService->show($modelId);
        return (new Response(DonationRequestResource::make($model)))->success(message: "Donation request details.");
    }

    public function create(CreateDonationRequestRequest $request)
    {
        $this->modelService->create(DonationRequestDTO::fromRequest($request));
        return (new Response())->success(message: "Donation request created successfully.", code: Response::HTTP_CREATED);
    }

    public function update(UpdateDonationRequestRequest $request, string $modelId)
    {
        $this->modelService->update($modelId, DonationRequestDTO::fromRequest($request));
        return (new Response())->success(message: "Donation request updated successfully.");
    }

    public function delete(string $modelId)
    {
        $this->modelService->delete($modelId);
        return (new Response())->success(message: "Donation request deleted successfully.");
    }

    public function ForceDelete(string $modelId)
    {
        $this->modelService->ForceDelete($modelId);
        return (new Response())->success(message: "Donation request force deleted successfully.");
    }

    public function restore(string $modelId)
    {
        $this->modelService->restore($modelId);
        return (new Response())->success(message: "Donation request restored successfully.");
    }
}
