<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('trashed')) {
                $data = User::onlyTrashed()->get();
            } else {
                $data = User::latest()->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('registered_at', function($item) {
                    return $item->created_at;
                })
                ->addColumn('reserved_books', function($item) {
                    return count($item->reservedBooks());
                })
                ->addColumn('past_reservations', function($item) {
                    return count($item->pastReservations);
                })
                ->addColumn('actions', function($item) use ($request) {
                    if ($request->has('trashed')) {
                        $btn = '<form class="d-inline" method="POST" action="' . route('admin.user_restore', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<button type="submit" class="btn btn-success me-1">' . __("Restore") . '</button>' .
                            '</form>';
                        $btn = $btn . '<form class="d-inline" method="POST" action="' . route('admin.user_destroy', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                                '<input name="_method" type="hidden" value="DELETE">' .
                                '<button type="submit" class="btn btn-danger">' . __("Delete Permanently") . '</button>' .
                            '</form>';
                    }
                    else {
                        $btn = '<a href="users/' . $item->id . '" class="edit btn btn-info btn-sm me-1 p-2" data-id="' . $item->id . '">' . __("View") . '</a>';
                        $btn = $btn . '<a href="users/edit/' . $item->id . '" class="edit btn btn-primary btn-sm me-1 p-2" data-id="' . $item->id.'">' . __("Edit") . '</a>';
                        $btn = $btn . '<form class="d-inline" method="POST" action="' . route('admin.user_delete', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<input name="_method" type="hidden" value="DELETE">' .
                            '<button type="submit" class="btn btn-danger">' . __("Delete") . '</button>' .
                            '</form>';
                    }

                    return $btn;
                })
                ->addColumn('checkbox', function($row) {
                    $checkbox = '<input type="checkbox" name="user_checkbox" data-id="' . $row["id"] . '">';
                    return $checkbox;
                })
                ->rawColumns(['actions', 'checkbox'])
                ->make(true);
        }

        return view('pages.users.index');
    }

    public function show(Request $request, $id) {
        $user = User::find($id);

        if ($user) {
            return view('pages.users.show', ["user" => $user]);
        }
        else return view('pages.users.show', ["error" => __("User Not Found")]);
    }

    public function edit(Request $request, $id) {
        $user = User::find($id);

        if ($user) {
            return view('pages.users.edit', ["user" => $user]);
        }
        else return view('pages.users.edit', ["error" => __("User Not Found")]);
    }

    public function updateDetails(Request $request, $id) {
        $user = User::find($id);

        if ($user) {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $user->id,
            ],
            [
                'name.required' => __('User name is required'),
                'email.required' => __('User email is required'),
                'email.unique' => __('User email has to be unique')
            ]);

            $user['name'] = $request->get('name');
            $user['email'] = $request->get('email');

            $user->update();

            return redirect()->back()->with('success', __('User Updated Successfully'));
        }
        else return redirect()->back()->with('error', __('User Not Found'));
    }

    public function delete($id) {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return redirect()->route('admin.users')->with('success', __('User Deleted Successfully'));
        }
        else return redirect()->route('admin.users')->with('error', __('Failed To Delete User'));
    }

    public function destroy($id)
    {
        $user = User::withTrashed()->find($id);

        if ($user) {
            $user->forceDelete();
            return redirect()->back()->with('success', __('User Removed Successfully'));
        }
        else return redirect()->back()->with('error', __('Failed To Remove User'));
    }

    public function restore($id)
    {
        $user = User::withTrashed()->find($id);

        if ($user) {
            $user->restore();
            return redirect()->back()->with('success', __('User Restored Successfully'));
        }
        else return redirect()->back()->with('error', __('Failed To Restore User'));
    }
}
