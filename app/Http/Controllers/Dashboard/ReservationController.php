<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\ReservationHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('trashed')) {
                $data = Reservation::onlyTrashed()->get();
            } else {
                $data = Reservation::latest()->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('reservation_start', function(Reservation $reservation) {
                    return $reservation->reservation_start->format('Y-m-d');
                })
                ->editColumn('expiration_date', function(Reservation $reservation) {
                    return $reservation->expiration_date->format('Y-m-d');
                })
                ->addColumn('reserved_by', function($item) {
                    return $item->user->name;
                })
                ->addColumn('reserved_book', function($item) {
                    return $item->book->title;
                })
                ->addColumn('actions', function($item) use ($request) {
                    if ($request->has('trashed')) {
                        $btn = '<form class="d-inline" method="POST" action="' . route('admin.reservation_restore', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<button type="submit" class="btn btn-success me-1">' . __("Restore") . '</button>' .
                            '</form>';
                        $btn = $btn . '<form class="d-inline" method="POST" action="' . route('admin.reservation_destroy', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<input name="_method" type="hidden" value="DELETE">' .
                            '<button type="submit" class="btn btn-danger">' . __("Delete Permanently") . '</button>' .
                            '</form>';
                    }
                    else {
                        $btn = '<form class="d-inline me-1" method="POST" action="' . route('admin.reservation_return', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<button type="submit" class="btn btn-success">' . __("Return") . '</button>' .
                            '</form>';
                        $btn = $btn . '<form class="d-inline me-1" method="POST" action="' . route('admin.reservation_cancel', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<button type="submit" class="btn btn-warning">' . __("Cancel") . '</button>' .
                            '</form>';
                        $btn = $btn . '<form class="d-inline" method="POST" action="' . route('admin.reservation_delete', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<input name="_method" type="hidden" value="DELETE">' .
                            '<button type="submit" class="btn btn-danger">' . __("Delete") . '</button>' .
                            '</form>';
                    }

                    return $btn;
                })
                ->addColumn('checkbox', function($row) {
                    $checkbox = '<input type="checkbox" name="reservations_checkbox" data-id="' . $row["id"] . '">';
                    return $checkbox;
                })
                ->rawColumns(['actions', 'checkbox'])
                ->make(true);
        }

        return view('pages.reservations.index');
    }

    public function return($id) {
        $reservation = Reservation::find($id);

        if ($reservation) {
            ReservationHistory::create(
                [
                    'reservation_start' => $reservation->reservation_start,
                    'expiration_date' => $reservation->expiration_date,
                    'return_date' => Carbon::now(),
                    'book_id' => $reservation->book_id,
                    'user_id' => $reservation->user_id,
                ]
            );

            $reservation->forceDelete();

            return redirect()->route('admin.reservations')->with('success', __('Reservation Returned Successfully'));
        }
        else return redirect()->route('admin.reservations')->with('error', __('Failed To Return Reservation'));
    }

    public function cancel($id) {
        $reservation = Reservation::find($id);

        if ($reservation) {
            $reservation->forceDelete();
            return redirect()->route('admin.reservations')->with('success', __('Reservation Cancelled Successfully'));
        }
        else return redirect()->route('admin.reservations')->with('error', __('Failed To Cancel Reservation'));
    }

    public function delete($id) {
        $reservation = Reservation::find($id);

        if ($reservation) {
            $reservation->delete();
            return redirect()->route('admin.reservations')->with('success', __('Reservation Deleted Successfully'));
        }
        else return redirect()->route('admin.reservations')->with('error', __('Failed To Delete Reservation'));
    }

    public function destroy($id)
    {
        $reservation = Reservation::withTrashed()->find($id);

        if ($reservation) {
            $reservation->forceDelete();
            return redirect()->back()->with('success', __('Reservation Removed Successfully'));
        }
        else return redirect()->back()->with('error', __('Failed To Remove Reservation'));
    }

    public function restore($id)
    {
        $reservation = Reservation::withTrashed()->find($id);

        if ($reservation) {
            $reservation->restore();
            return redirect()->back()->with('success', __('Reservation Restored Successfully'));
        }
        else return redirect()->back()->with('error', __('Failed To Restore Reservation'));
    }
}
