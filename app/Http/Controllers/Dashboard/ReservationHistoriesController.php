<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ReservationHistory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReservationHistoriesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('trashed')) {
                $data = ReservationHistory::onlyTrashed()->get();
            } else {
                $data = ReservationHistory::latest()->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('reservation_start', function(ReservationHistory $reservationHistory) {
                    return $reservationHistory->reservation_start->format('Y-m-d');
                })
                ->editColumn('expiration_date', function(ReservationHistory $reservationHistory) {
                    return $reservationHistory->expiration_date->format('Y-m-d');
                })
                ->editColumn('return_date', function(ReservationHistory $reservationHistory) {
                    return $reservationHistory->return_date->format('Y-m-d');
                })
                ->addColumn('reserved_by', function($item) {
                    return $item->user->name;
                })
                ->addColumn('reserved_book', function($item) {
                    return $item->book->title;
                })
                ->addColumn('actions', function($item) use ($request) {
                    if ($request->has('trashed')) {
                        $btn = '<form class="d-inline" method="POST" action="' . route('admin.reservations_histories_restore', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<button type="submit" class="btn btn-success me-1">' . __("Restore") . '</button>' .
                            '</form>';
                        $btn = $btn . '<form class="d-inline" method="POST" action="' . route('admin.reservations_histories_destroy', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<input name="_method" type="hidden" value="DELETE">' .
                            '<button type="submit" class="btn btn-danger">' . __("Delete Permanently") . '</button>' .
                            '</form>';
                    }
                    else {
                        $btn = '<a href="reservations_histories/edit/' . $item->id . '" class="edit btn btn-warning btn-sm me-1 p-2" data-id="' . $item->id.'">' . __("Edit") . '</a>';
                        $btn = $btn . '<form class="d-inline" method="POST" action="' . route('admin.reservations_histories_delete', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<input name="_method" type="hidden" value="DELETE">' .
                            '<button type="submit" class="btn btn-danger">' . __("Delete") . '</button>' .
                            '</form>';
                    }

                    return $btn;
                })
                ->addColumn('checkbox', function($row) {
                    $checkbox = '<input type="checkbox" name="reservations_histories_checkbox" data-id="' . $row["id"] . '">';
                    return $checkbox;
                })
                ->rawColumns(['actions', 'checkbox'])
                ->make(true);
        }

        return view('pages.reservations_histories.index');
    }

    public function delete($id) {
        $reservationHistory = ReservationHistory::find($id);

        if ($reservationHistory) {
            $reservationHistory->delete();
            return redirect()->route('admin.reservations_histories')->with('success', __('Reservation History Deleted Successfully'));
        }
        else return redirect()->route('admin.reservations_histories')->with('error', __('Failed To Delete Reservation History'));
    }

    public function destroy($id)
    {
        $reservationHistory = ReservationHistory::withTrashed()->find($id);

        if ($reservationHistory) {
            $reservationHistory->forceDelete();
            return redirect()->back()->with('success', __('Reservation History Removed Successfully'));
        }
        else return redirect()->back()->with('error', __('Failed To Remove Reservation History'));
    }

    public function restore($id)
    {
        $reservationHistory = ReservationHistory::withTrashed()->find($id);

        if ($reservationHistory) {
            $reservationHistory->restore();
            return redirect()->back()->with('success', __('Reservation History Restored Successfully'));
        }
        else return redirect()->back()->with('error', __('Failed To Restore Reservation History'));
    }
}
