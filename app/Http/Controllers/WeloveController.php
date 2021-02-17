<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\Project;
use App\Models\Status;

use App\Http\Requests\ProjectRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WeloveController extends Controller
{
    // Show view for creating a project
    public function create() {
        $statuses = Status::all();
        return view('edit')->with(compact(['statuses']));
    }
    // Create a project
    public function insert(ProjectRequest $request) {
        $project = new Project;

        $project->title = $request->title;
        $project->description = $request->description;

        $owner_new = Owner::where('name', $request->owner_name)
                    ->where('email', $request->owner_email)
                    ->first();

        if($owner_new) {
            if(! $project->save()) {
                abort(500);
            }
            // We already have an owner with the same data, insert id
            DB::table('project_owner_pivot')
                ->insert(['project_id' => $project->id, 'owner_id' => $owner_new->id]);
        } else {
            // Check if we can find the name or e-mail
            if(Owner::where('name', $request->owner_name)->first()) {
                $error = ValidationException::withMessages([
                    '' => [__('app.owner_name_taken')],
                ]);
                throw $error;
            }
            if(Owner::where('email', $request->owner_email)->first()) {
                $error = ValidationException::withMessages([
                    '' => [__('app.owner_email_taken')],
                ]);
                throw $error;
            }

            // We don't have an owner with the same data, create new
            $owner = new Owner;

            $owner->name = $request->owner_name;
            $owner->email = $request->owner_email;

            if(! $owner->save()) {
                abort(500);
            }
            if(! $project->save()) {
                abort(500);
            }

            DB::table('project_owner_pivot')
                ->insert(['project_id' => $project->id, 'owner_id' => $owner->id]);
        }
        DB::table('project_status_pivot')
            ->insert(['project_id' => $project->id, 'status_id' => $request->status]);

        return redirect('/');
    }
    // Show view for editing a specific project
    public function edit($id) {
        $project = Project::find($id);
        $statuses = Status::all();
        $status_id = DB::table('project_status_pivot')
                   ->where('project_id', '=', $project->id)
                   ->first()->status_id;
        $owner = DB::table('project_owner_pivot')
                ->join('owners', 'owners.id', '=', 'project_owner_pivot.owner_id')
                ->where('project_id', '=', $project->id)
                ->first();
        return view('edit')->with(compact([
            'project',
            'statuses',
            'status_id',
            'owner',
        ]));
    }
    // Edit a specific project
    public function modify(ProjectRequest $request, $id) {
        $project = Project::find($id);
        if($project) {
            $project->title = $request->title;
            $project->description = $request->description;

            $status_id_old = DB::table('project_status_pivot')
                           ->where('project_id', $project->id)
                           ->first()->status_id;
            if($status_id_old != $project->status) {
                DB::table('project_status_pivot')
                    ->where('project_id', $project->id)
                    ->update(['status_id' => $request->status]);
            }
            $owner_new = Owner::where('name', $request->owner_name)
                       ->where('email', $request->owner_email)
                       ->first();
            if($owner_new) {
                // We already have an owner with the same data, change id
                DB::table('project_owner_pivot')
                    ->where('project_id', $project->id)
                    ->update(['owner_id' => $owner_new->id]);
            } else {
                // Check if we can find the name or e-mail
                if(Owner::where('name', $request->owner_name)->first()) {
                    $error = ValidationException::withMessages([
                        '' => [__('app.owner_name_taken')],
                    ]);
                    throw $error;
                }
                if(Owner::where('email', $request->owner_email)->first()) {
                    $error = ValidationException::withMessages([
                        '' => [__('app.owner_email_taken')],
                    ]);
                    throw $error;
                }

                // We don't have an owner with the same data, create new
                $owner = new Owner;

                $owner->name = $request->owner_name;
                $owner->email = $request->owner_email;

                if(! $owner->save()) {
                    abort(500);
                }

                DB::table('project_owner_pivot')
                    ->where('project_id', $project->id)
                    ->update(['owner_id' => $owner->id]);
            }

            if($project->save()) {
                return redirect('/');
            } else {
                abort(500);
            }
        } else {
            // If we can't find the project, error out
            abort(404);
        }
        return view('edit');
    }
    // Delete a project
    public function delete($id) {
        $project = Project::find($id);
        if($project) {
            DB::table('project_owner_pivot')
                ->where('project_id', '=', $project->id)
                ->delete();
            DB::table('project_status_pivot')
                ->where('project_id', '=', $project->id)
                ->delete();
            if($project->delete()) {
                return 'OK';
            } else {
                abort(500);
            }
        } else {
            abort(404);
        }
    }
    // Index page
    public function index(Request $request) {
        if(isset($request->filter)) {
            $projects = Project::join('project_status_pivot', 'projects.id', '=', 'project_id')
                      ->where('status_id', $request->filter)
                      ->paginate(10)
                      ->withQueryString();
        } else {
            $projects = Project::paginate(10)->withQueryString();
        }
        foreach($projects as $project) {
            $project->owner = DB::table('project_owner_pivot')
                            ->join('owners', 'owners.id', '=', 'owner_id')
                            ->where('project_id', '=', $project->id)
                            ->first();
            $project->status = DB::table('project_status_pivot')
                             ->join('statuses', 'statuses.id', '=', 'status_id')
                             ->where('project_id', '=', $project->id)
                             ->first();
        }
        $statuses = Status::all();
        return view('index')->with(compact([
            'projects',
            'statuses',
        ]));
    }
}
