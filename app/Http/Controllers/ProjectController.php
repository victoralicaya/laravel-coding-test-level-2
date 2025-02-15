<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = isset($request->pageSize) ? $request->pageSize : 3;
        $sortBy = isset($request->sortBy) ? $request->sortBy : 'name';
        $sortDirection = isset($request->sortDirection) ? $request->sortDirection : 'ASC';

        $projects = Project::where('name', 'like', '%'.$request->q.'%')
                ->orderBy($sortBy, $sortDirection)
                ->paginate($pageSize);

        $pagination = $projects->currentPage();
        $index = ($pagination - $request->pageIndex) * $pageSize + 1;

        $pageIndex = isset($request->pageIndex) ? $index : 0;

        return response()->json([
            'status' => true,
            'pageIndex' => $pageIndex,
            'data' => $projects
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Project added successfully',
            'data' => $project
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return response()->json([
            'status' => true,
            'data' => $project
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ProjectRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Project updated successfully',
            'data' => $project
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'status' => true,
            'message' => 'Project Deleted Successfully'
        ], 200);
    }
}
