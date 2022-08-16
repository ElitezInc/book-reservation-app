<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Reservation;
use App\Models\ReservationHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Auth;

class ReservationAPI extends Controller
{
    public function activeReservations(Request $request) {
        $reservations = auth()->user()->reservations;
        $response = [];

        foreach ($reservations as $reservation) {
            $response[] = [
                'book' => $reservation->book->makeHidden('description'),
                'reserved_at' => $reservation->reservation_start->format('Y-m-d'),
                'expiration_date' => $reservation->expiration_date->format('Y-m-d'),
            ];
        }

        return $response;
    }

    public function pastReservations(Request $request) {
        $reservations = auth()->user()->pastReservations;
        $response = [];

        foreach ($reservations as $reservation) {
            $response[] = [
                'book' => $reservation->book->makeHidden('description'),
                'reserved_at' => $reservation->reservation_start->format('Y-m-d'),
                'expiration_date' => $reservation->expiration_date->format('Y-m-d'),
                'returned_at' => $reservation->return_date->format('Y-m-d'),
            ];
        }

        return $response;
    }

    public function reservate(Request $request, $id) {
        $foundBook = Book::where('id','=', $id)->first();

        if (isset($foundBook)) {
            if ($foundBook->reservation !== null) {
                return response()->json(["error" => "Book already reserved"], 400);
            }
            else {
                Reservation::create([
                    'reservation_start' => Carbon::now(),
                    'expiration_date' => Carbon::now()->addDays(config('app.reservation_expiration_days')),
                    'book_id' => $foundBook->id,
                    'user_id' => auth()->user()->id,
                ]);

                return response()->json(["success" => "Book reserved"]);
            }
        }

        return response()->json(["error" => "Book not found"], 400);
    }

    public function return(Request $request, $id) {
        $foundBook = Book::where('id','=', $id)->first();

        if (isset($foundBook)) {
            if ($foundBook->reservation === null) {
                return response()->json(["error" => "Book is not reserved"], 400);
            }
            else {
                ReservationHistory::create([
                    'reservation_start' => $foundBook->reservation->reservation_start,
                    'expiration_date' => $foundBook->reservation->expiration_date,
                    'return_date' => Carbon::now(),
                    'book_id' => $foundBook->id,
                    'user_id' => auth()->user()->id,
                ]);

                $foundBook->reservation->forceDelete();

                return response()->json(["success" => "Book returned successfully"]);
            }
        }

        return response()->json(["error" => "Book not found"], 400);
    }

    public function cancel(Request $request, $id) {
        $foundBook = Book::where('id','=', $id)->first();

        if (isset($foundBook)) {
            if ($foundBook->reservation === null) {
                return response()->json(["error" => "Book is not reserved"], 400);
            }
            else {
                $foundBook->reservation->forceDelete();
                return response()->json(["success" => "Book reservation cancelled successfully"]);
            }
        }

        return response()->json(["error" => "Book not found"], 400);
    }
}
