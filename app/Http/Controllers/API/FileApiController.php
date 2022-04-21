<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlobResource;
use App\Models\Blob;
use App\Models\ImageAssign;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class FileApiController extends Controller
{
    public function getListBlob(Request $request)
    {
        try {
            $query = Blob::query();
            if (isset($request['product_detail_id']) && $request['product_detail_id'] != 0) {
                $query->join('image_assigns', 'blobs.id', '=', 'image_assigns.blob_id')
                    ->where('image_assigns.imageable_type', '=', 'App\\Models\\ProductDetail')
                    ->where('image_assigns.imageable_id', '=', $request['product_detail_id'])
                    ->get(['blobs.*']);
            } else if (isset($request['product_id']) && $request['product_id'] != 0) {
                $query->join('image_assigns', 'blobs.id', '=', 'image_assigns.blob_id')
                    ->where('image_assigns.imageable_type', '=', 'App\\Models\\Product')
                    ->where('image_assigns.imageable_id', '=', $request['product_id'])
                    ->get(['blobs.*']);
            }
            $page_index = $request->get('page') ?? 1;
            $page_size = $request->get('limit') ?? 10;
            if ($request->get('search')) {
                $query->where('name', 'LIKE', '%' . $request->get('search') . '%');
            }
            if ($request->get('column') && $request->get('sort')) {
                $query->orderBy($request->get('column'), $request->get('sort'));
            }

            $result =  BlobResource::collection($query->paginate($page_size, page: $page_index));
            return response()->json([
                'code' => Response::HTTP_OK,
                'status' => true,
                'data' => $result->items(),
                'meta' => [
                    'total' => $result->total(),
                    'perPage' => $result->perPage(),
                    'currentPage' => $result->currentPage()
                ]
            ]);
        } catch (Throwable $th) {
            $response = response()->json([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $result = $file->store('');
        if ($result) {
            $blob = Blob::create([
                'name' => $request->get('name') ?? $file->getClientOriginalName(),
                'file_path' => $result,
                'created_by' => 20
            ]);
            return response()->json([
                'code' => Response::HTTP_OK,
                'status' => true,
                'data' => new BlobResource($blob),
            ]);
        }
        return response()->json([
            'code' => Response::HTTP_BAD_REQUEST,
            'status' => false,
        ]);
    }

    public function updateBlob(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $blob = Blob::find($id);
        if ($blob) {
            $blob->name = $request['name'];
            return response()->json([
                'code' => Response::HTTP_OK,
                'status' => true,
                'data' => $id,
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_NOT_FOUND,
                'status' => false,
            ]);
        }
    }

    public function uploadRange(Request $request)
    {
        $files = $request->files;
        $inserted = 0;
        foreach ($files as $file) {
            $result = $file->store('');
            if ($result) {
                Blob::create([
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $result,
                    'created_by' => Auth::user()->id
                ]);
                $inserted++;
            }
        }
        return response()->json([
            'code' => Response::HTTP_OK,
            'status' => $inserted > 0,
            'data' => $inserted,
        ]);
    }

    public function get(Request $request, string $name)
    {
        $fileName = storage_path('app/' . $name);
        if (file_exists($fileName)) {
            return response()->file($fileName);
        }
    }
    public function download(Request $request, string $name)
    {
        $fileName = storage_path('app/' . $name);
        if (file_exists($fileName)) {
            return response()->download($fileName, $name);
        }
    }
    public function downloadById(Request $request, string $id)
    {
        $blob = Blob::find($id);
        if (isset($blob)) {
            $fileName = storage_path('app/' . $blob->file_path);
            if (file_exists($fileName)) {
                return response()->download($fileName, $blob->name);
            }
        }
    }
    public function getByBlob(Request $request, string $id)
    {
        $blob = Blob::find($id);
        if (isset($blob)) {
            $fileName = storage_path('app/' . $blob->file_path);
            if (file_exists($fileName)) {
                return response()->file($fileName);
            }
        }
    }
    public function delete(Request $request, $id)
    {
        $blob = Blob::find($id);
        if (isset($blob)) {
            $fileName = storage_path('app/' . $blob->file_path);
            if (file_exists($fileName)) {
                unlink($fileName);
            }
            $deleted = $blob->delete();
            if ($deleted > 0) {
                ImageAssign::where('blob_id', $blob->id)->delete();
            }
            return response()->json([
                'code' => Response::HTTP_OK,
                'status' => true,
                'data' => $blob->id,
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_NOT_FOUND,
                'status' => false,
            ]);
        }
    }
}
