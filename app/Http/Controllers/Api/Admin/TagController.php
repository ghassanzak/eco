<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IdRequest;
use App\Http\Requests\TagRequest;
use App\Http\Requests\TagUpRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index(Request $request)
    {
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
        if (!$tags->count()>0) {
            return $this->returnError('tags not found',404);
        }
        $tags = TagResource::collection($tags);
        return $this->returnData('tags',$tags,'tags');
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
            return $this->returnSuccess('Tag created successfully',200);
        } else {
            return $this->returnError('worning',200);
        }
    }

    public function show(IdRequest $request)
    {
        $tag = Tag::where('id',$request->id)->first();
        if(!$tag) return $this->returnError('id not found',404);
        $tag = new TagResource($tag);
        return $this->returnData('tag',$tag);
    }

    public function edit($id)
    {
        //
    }

    public function update(TagUpRequest $request)
    {
        $tag = Tag::where('id',$request->id)->first();

        if ($tag) {
            $data['name']               = $request->name;
            $data['slug']               = null;

            $tag->update($data);

            return $this->returnSuccess('Tag updated successfully',200);

        }
        return $this->returnError('id not found',404);
    }

    public function destroy(IdRequest $request)
    {
        $tag = Tag::whereId($request->id)->first();
        if ($tag) {
            $tag->delete();
            return $this->returnSuccess('Tag deleted successfully',200);
        }
        return $this->returnError('id not found',404);
        
    }
}
