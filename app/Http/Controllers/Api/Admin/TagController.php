<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index(Request $request)
    {
        try {
            $keyword = (isset($request->keyword) && $request->keyword != '') ? $request->keyword : null;
            $sort_by = (isset($request->sort_by) && $request->sort_by != '') ? $request->sort_by : 'id';
            $order_by = (isset($request->order_by) && $request->order_by != '') ? $request->order_by : 'desc';
            $limit_by = (isset($request->limit_by) && $request->limit_by != '') ? $request->limit_by : '10';
    
            $tags = Tag::query();
            if ($keyword != null) {
                $tags = $tags->search($keyword);
            }
    
            $tags = $tags->orderBy($sort_by, $order_by);
            $tags = $tags->paginate($limit_by);
            return TagResource::collection($tags);
            
        } catch (Exception $e) {
            return response()->json(['error'=> true, 'message' => $e],200);
        }
    }

    public function create()
    {
        //
    }

    public function store(TagRequest $request)
    {

        
        $data['name'] = $request->name;
        $tag = Tag::create($data);
        if ($tag ) {
            return response()->json(['error'=> false, 'message' => 'Tag created successfully'],200);
        } else {
            return response()->json(['error'=> true, 'message' => 'worning'],200);
        }
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'                    => 'required|numeric',
        ]);
        if($validator->fails()) {return response()->json(['errors' => true, 'messages' => $validator->errors()]);}

        $tag = Tag::where('id',$request->id)->first();
        return new TagResource($tag);
    }

    public function edit($id)
    {
        //
    }

    public function update(TagRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'id'                    => 'required|numeric',
        ]);
        if($validator->fails()) {return response()->json(['errors' => true, 'messages' => $validator->errors()]);}

        $tag = Tag::where('id',$request->id)->first();

        if ($tag) {
            $data['name']               = $request->name;
            $data['slug']               = null;

            $tag->update($data);

            return response()->json(['error'=> false, 'message' => 'Tag updated successfully'],200);

        }
        return response()->json(['error'=> true, 'message' => 'worning'],200);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'                    => 'required|numeric',
        ]);
        if($validator->fails()) {return response()->json(['errors' => true, 'messages' => $validator->errors()]);}

        $tag = Tag::whereId($request->id)->first();
        if ($tag) {
            $tag->delete();
            return response()->json(['error'=> false, 'message' => 'Tag deleted successfully'],200);
        }
        return response()->json(['error'=> true, 'message' => 'worning'],200);
        
    }
}
