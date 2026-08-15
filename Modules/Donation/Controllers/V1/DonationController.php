<?php

namespace Modules\Donation\Controllers\V1;

use Modules\Donation\DTO\V1\DonationDTO;
use Modules\Donation\Interfaces\V1\Donation\DonationServiceInterface;
use Modules\Donation\Requests\V1\Donation\CreateDonationRequest;
use Modules\Donation\Requests\V1\Donation\UpdateDonationRequest;
use Modules\Donation\Resources\V1\DonationResource;
use Modules\Core\Controllers\BaseController;
use Modules\Core\Utilities\Response;

class DonationController extends BaseController
{
    public function __construct(protected DonationServiceInterface $modelService)
    {
    }

    public function index()
    {
        $models = $this->modelService->index();
        return (new Response(DonationResource::collection($models)->resource))->success(message: "All donations.");
    }

    public function show(string $modelId)
    {
        $model = $this->modelService->show($modelId);
        return (new Response(DonationResource::make($model)))->success(message: "Donation details.");
    }

    public function create(CreateDonationRequest $request)
    {
        $this->modelService->create(DonationDTO::fromRequest($request));
        return (new Response())->success(message: "Donation created successfully.", code: Response::HTTP_CREATED);
    }

    public function update(UpdateDonationRequest $request, string $modelId)
    {
        $this->modelService->update($modelId, DonationDTO::fromRequest($request));
        return (new Response())->success(message: "Donation updated successfully.");
    }

    public function delete(string $modelId)
    {
        $this->modelService->delete($modelId);
        return (new Response())->success(message: "Donation deleted successfully.");
    }

    public function ForceDelete(string $modelId)
    {
        $this->modelService->ForceDelete($modelId);
        return (new Response())->success(message: "Donation force deleted successfully.");
    }

    public function restore(string $modelId)
    {
        $this->modelService->restore($modelId);
        return (new Response())->success(message: "Donation restored successfully.");
    }

    public function statistics() {
        $data = $this->modelService->statistics();
        return (new Response($data))->success(message: "Statistics retrieved successfully.");
    }
}
