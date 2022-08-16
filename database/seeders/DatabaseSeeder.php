<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Reader;
use App\Models\Reservation;
use App\Models\ReservationHistory;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        // \App\Models\User::factory(10)->create();

        // Create dashboard admin account
         User::factory()->create([
             'name' => 'Admin',
             'email' => 'admin@email.com',
             'password' => Hash::make('admin'),
             'admin' => true,
         ]);

        // Seed placeholder data for books
        for ($i = 1; $i <= 100; $i++) {
            $fileName = null;

            if (rand(1, 4) != 1) {
                $contents = file_get_contents("https://picsum.photos/640/480");
                $fileName = substr(md5(mt_rand()), 0, 7) . '_' . Carbon::now()->format('Y-m-d') . '.jpg';
                Storage::put('public/images/' . $fileName, $contents);
            }

            Book::create([
                'author' => $faker->name,
                'title' => implode(' ', $faker->words),
                'code' => substr(md5(mt_rand()), 0, 7),
                'description' => rand(1, 4) != 1 ? implode(" ", $faker->paragraphs(rand(1, 3))) : null,
                'release_date' => Carbon::now()->subYears(rand(2, 30))->subMonths(rand(1, 12))->subDays(rand(1, 30)),
                'image_name' => $fileName,
            ]);
        }

        for ($i = 1; $i <= 30; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => Hash::make('password'),
            ]);

            // Generate active reservations
            if (rand(1, 4) != 1) {
                for ($x = 1; $x <= rand(1, 7); $x++) {
                    $bookSearch = Book::doesntHave('reservation')->first();

                    if ($bookSearch) {
                        $reservationStart = Carbon::now()->subMonths(rand(0, 3))->subDays(rand(0, 30));
                        $expirationDate = $reservationStart->copy()->addMonths(rand(0, 2))->addDays(rand(0, 30));

                        Reservation::create([
                            'reservation_start' => $reservationStart,
                            'expiration_date' => $expirationDate,
                            'book_id' => $bookSearch->id,
                            'user_id' => $user->id
                        ]);
                    }
                    else break;
                }
            }

            // Generate past reservations
            foreach(Book::orderBy(DB::raw('RAND()'))->take(rand(1, 7))->get() as $book) {
                $reservationStart = Carbon::now()->subMonths(rand(0, 36))->subDays(0, 30);

                ReservationHistory::create([
                    'reservation_start' => $reservationStart,
                    'return_date' => $reservationStart->copy()->addMonths(rand(0, 4))->addDays(rand(0, 30)),
                    'expiration_date' => $reservationStart->copy()->addMonths(rand(0, 2))->addDays(rand(0, 30)),
                    'book_id' => $book->id,
                    'user_id' => $user->id
                ]);
            }
        }
    }
}
