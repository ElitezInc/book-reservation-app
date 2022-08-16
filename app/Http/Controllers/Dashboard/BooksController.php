<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BooksController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('trashed')) {
                $data = Book::onlyTrashed()->get();
            } else {
                $data = Book::latest()->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('release_date', function(Book $book) {
                    return $book->release_date->format('Y-m-d');
                })
                ->addColumn('reserved_by', function($item) {
                    return $item->reservation?->user->name;
                })
                ->addColumn('past_reservations', function($item) {
                    return count($item->pastReservations);
                })
                ->addColumn('actions', function($item) use ($request) {
                    if ($request->has('trashed')) {
                        $btn = '<form class="d-inline" method="POST" action="' . route('admin.book_restore', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<button type="submit" class="btn btn-success me-1">' . __("Restore") . '</button>' .
                            '</form>';
                        $btn = $btn . '<form class="d-inline" method="POST" action="' . route('admin.book_destroy', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<input name="_method" type="hidden" value="DELETE">' .
                            '<button type="submit" class="btn btn-danger">' . __("Delete Permanently") . '</button>' .
                            '</form>';
                    }
                    else {
                        $btn = '<a href="books/' . $item->id . '" class="edit btn btn-info btn-sm me-1 p-2" data-id="' . $item->id . '">' . __("View") . '</a>';
                        $btn = $btn . '<a href="books/edit/' . $item->id . '" class="edit btn btn-primary btn-sm me-1 p-2" data-id="' . $item->id.'">' . __("Edit") . '</a>';
                        $btn = $btn . '<form class="d-inline" method="POST" action="' . route('admin.book_delete', $item->id) . '">' .
                            '<input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                            '<input name="_method" type="hidden" value="DELETE">' .
                            '<button type="submit" class="btn btn-danger">' . __("Delete") . '</button>' .
                            '</form>';
                    }

                    return $btn;
                })
                ->addColumn('checkbox', function($row) {
                    $checkbox = '<input type="checkbox" name="books_checkbox" data-id="' . $row["id"] . '">';
                    return $checkbox;
                })
                ->rawColumns(['actions', 'checkbox'])
                ->make(true);
        }

        return view('pages.books.index');
    }

    public function delete($id) {
        $book = Book::find($id);

        if ($book) {
            $book->delete();
            return redirect()->route('admin.books')->with('success', __('Book Deleted Successfully'));
        }
        else return redirect()->route('admin.books')->with('error', __('Failed To Delete Book'));
    }

    public function destroy($id)
    {
        $book = Book::withTrashed()->find($id);

        if ($book) {
            $book->forceDelete();
            return redirect()->back()->with('success', __('Book Removed Successfully'));
        }
        else return redirect()->back()->with('error', __('Failed To Remove Book'));
    }

    public function restore($id)
    {
        $book = Book::withTrashed()->find($id);

        if ($book) {
            $book->restore();
            return redirect()->back()->with('success', __('Book Restored Successfully'));
        }
        else return redirect()->back()->with('error', __('Failed To Restore Book'));
    }
}
