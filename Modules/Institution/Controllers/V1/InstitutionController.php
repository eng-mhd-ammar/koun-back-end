<?php

namespace Modules\Institution\Controllers\V1;

use Modules\Institution\DTO\V1\InstitutionDTO;
use Modules\Institution\Interfaces\V1\Institution\InstitutionServiceInterface;
use Modules\Institution\Requests\V1\Institution\CreateInstitutionRequest;
use Modules\Institution\Requests\V1\Institution\UpdateInstitutionRequest;
use Modules\Institution\Resources\V1\InstitutionResource;
use Modules\Core\Controllers\BaseController;
use Modules\Core\Utilities\Response;

class InstitutionController extends BaseController
{
    public function __construct(protected InstitutionServiceInterface $modelService)
    {
    }

    public function index()
    {
        $models = $this->modelService->index();
        return (new Response(InstitutionResource::collection($models)->resource))->success(message: "All institutions.");
    }

    public function show(string $modelId)
    {
        $model = $this->modelService->show($modelId);
        return (new Response(InstitutionResource::make($model)))->success(message: "Institution details.");
    }

    public function create(CreateInstitutionRequest $request)
    {
        $this->modelService->create(InstitutionDTO::fromRequest($request));
        return (new Response())->success(message: "Institution created successfully.", code: Response::HTTP_CREATED);
    }

    public function update(UpdateInstitutionRequest $request, string $modelId)
    {
        $this->modelService->update($modelId, InstitutionDTO::fromRequest($request));
        return (new Response())->success(message: "Institution updated successfully.");
    }

    public function delete(string $modelId)
    {
        $this->modelService->delete($modelId);
        return (new Response())->success(message: "Institution deleted successfully.");
    }

    public function ForceDelete(string $modelId)
    {
        $this->modelService->ForceDelete($modelId);
        return (new Response())->success(message: "Institution force deleted successfully.");
    }

    public function restore(string $modelId)
    {
        $this->modelService->restore($modelId);
        return (new Response())->success(message: "Institution restored successfully.");
    }
}
