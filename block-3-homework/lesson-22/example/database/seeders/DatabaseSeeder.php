<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/** Դաս 09-ի seed.sql-ը և Դաս 13, 16-ի upgrade-ները՝ PHP-ով — PHP Դաս 20 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [];

        foreach ([
            'vep' => 'վեպ',
            'patmavep' => 'պատմավեպ',
            'patmvatsq' => 'պատմվածք',
            'heqiat' => 'հեքիաթ',
            'poezia' => 'պոեզիա',
            'fantastika' => 'ֆանտաստիկա',
            'detektiv' => 'դետեկտիվ',
        ] as $slug => $name) {
            $genres[$name] = Genre::create(['name' => $name, 'slug' => $slug]);
        }

        // [վերնագիր, հեղինակ, տարի, էջ, կարդացած, ժանր, գնահատական, գույն, հղում, տեսակ, րոպե, ընթերցող, տվել եմ, ավելացվել է]
        $books = [
            ['Փոքրիկ իշխանը', 'Անտուան դը Սենտ-Էքզյուպերի', 1943, 96, 96, 'հեքիաթ', 5, '#2f5d8a', 'https://hy.wikipedia.org/w/index.php?search=Փոքրիկ+իշխանը', 'paper', null, null, null, '2026-09-01'],
            ['Սամվել', 'Րաֆֆի', 1886, 560, 140, 'պատմավեպ', 0, '#8a3b2f', 'https://hy.wikipedia.org/w/index.php?search=Սամվել+Րաֆֆի', 'paper', null, null, null, '2026-09-03'],
            ['Գիքորը', 'Հովհաննես Թումանյան', 1907, 40, 40, 'պատմվածք', 5, '#5b7a3a', 'https://hy.wikipedia.org/w/index.php?search=Գիքորը', 'paper', null, null, null, '2026-09-05'],
            ['1984', 'Ջորջ Օրուել', 1949, 328, 0, 'վեպ', 0, '#3d3d55', 'https://en.wikipedia.org/wiki/Nineteen_Eighty-Four', 'ebook', null, null, null, '2026-09-10'],
            ['Ալքիմիկոսը', 'Պաուլո Կոելյո', 1988, 208, 208, 'վեպ', 3, '#b07a1f', 'https://en.wikipedia.org/wiki/The_Alchemist_(novel)', 'audio', 240, 'Արմեն Ղազարյան', null, '2026-09-12'],
            ['Վերք Հայաստանի', 'Խաչատուր Աբովյան', 1858, 320, 90, 'պատմավեպ', 0, '#6b4a7a', 'https://hy.wikipedia.org/w/index.php?search=Վերք+Հայաստանի', 'paper', null, null, null, '2026-09-15'],
            ['Հոբիտը', 'Ջ. Ռ. Ռ. Թոլքին', null, 310, 120, 'ֆանտաստիկա', 0, '#3f6b5a', null, 'audio', 660, 'Էնդի Սերկիս', null, '2026-09-20'],
            ['Խենթը', 'Րաֆֆի', 1881, 300, 300, 'պատմավեպ', 4, '#7a2f2f', null, 'paper', null, null, 'Արամ', '2026-09-25'],
            ['Անուշ', 'Հովհաննես Թումանյան', 1902, 64, 64, 'պոեզիա', 5, '#2f6b7a', null, 'paper', null, null, null, '2026-10-01'],
            ['Մեծ սպասումներ', 'Չարլզ Դիքենս', 1861, 544, 0, 'վեպ', 0, '#4a5a2f', null, 'paper', null, null, null, '2026-10-05'],
            ['Կարմիր գլխարկ 🐺', 'Շառլ Պերո', 1697, 24, 24, 'հեքիաթ', 4, '#a33a3a', null, 'paper', null, null, null, '2026-10-10'],
            ['Հարրի Փոթերը և փիլիսոփայական քարը', 'Ջ. Ք. Ռոուլինգ', 1997, 332, 50, 'ֆանտաստիկա', 0, '#5a3f7a', null, 'paper', null, null, null, '2026-10-14'],
        ];

        foreach ($books as [$title, $author, $year, $pages, $read, $genre, $rating, $color, $link, $format, $minutes, $narrator, $lentTo, $date]) {
            $book = new Book([
                'title' => $title, 'author' => $author, 'year' => $year, 'pages' => $pages, 'read_pages' => $read,
                'rating' => $rating, 'color' => $color, 'link' => $link,
                'format' => $format, 'minutes' => $minutes, 'narrator' => $narrator, 'lent_to' => $lentTo,
            ]);
            $book->slug = Book::uniqueSlugFor($title);
            $book->genre()->associate($genres[$genre]);
            $book->created_at = Carbon::parse($date . ' 10:00');
            $book->save();
        }

        Book::where('slug', 'mets-spasumner')->update(['is_active' => false]);

        // Ընթերցանության օրերը․ [գրքի id, էջ, օր] — ամեն գրքի գումարը = read_pages
        foreach ([
            [1, 40, '2026-09-01'],
            [1, 30, '2026-09-03'],
            [1, 26, '2026-09-05'],
            [2, 60, '2026-09-04'],
            [2, 50, '2026-09-06'],
            [2, 30, '2026-09-08'],
            [3, 40, '2026-09-06'],
            [5, 70, '2026-09-12'],
            [5, 70, '2026-09-14'],
            [5, 68, '2026-09-16'],
            [6, 45, '2026-09-16'],
            [6, 45, '2026-09-18'],
            [7, 50, '2026-09-21'],
            [7, 40, '2026-09-23'],
            [7, 30, '2026-09-25'],
            [8, 100, '2026-09-25'],
            [8, 120, '2026-09-27'],
            [8, 80, '2026-09-29'],
            [9, 64, '2026-10-02'],
            [11, 24, '2026-10-11'],
            [12, 30, '2026-10-14'],
            [12, 20, '2026-10-16'],
        ] as [$bookId, $pages, $date]) {
            Book::find($bookId)->sessions()->create(['pages' => $pages, 'read_on' => $date]);
        }

        // ⭐ role-ը՝ ուղիղ վերագրմամբ, ոչ create([...])-ով․ $fillable-ում չկա (Դաս 20, բաժին 07)
        foreach ([
            ['Անի',   'ani@example.com',   'Ani-12345',   'admin'],
            ['Լիլիթ', 'lilit@example.com', 'Lilit-12345', 'editor'],
            ['Արամ',  'aram@example.com',  'Aram-12345',  'member'],
        ] as [$name, $email, $password, $role]) {
            $user = User::create(['name' => $name, 'email' => $email, 'password' => $password]);   // hashed cast
            $user->role = $role;
            $user->save();
        }
    }
}
