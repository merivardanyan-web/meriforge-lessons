<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Request;
use App\Http\Response;
use App\Models\Book;
use App\Models\Genre;
use App\Repositories\BookRepository;
use App\Repositories\GenreRepository;
use App\Repositories\SessionRepository;
use App\Services\Validator;
use RuntimeException;

/** Դարակ և գրքի էջ — PHP Դաս 15։ Ոչ SQL, ոչ HTML․ միայն «ինչ վերցնել» և «որ շաբլոնը» */
class BookController
{
    public function __construct(
        private readonly BookRepository $books,
        private readonly GenreRepository $genres,
        private readonly SessionRepository $sessions,
    ) {}

    public function index(Request $request): Response
    {
        return $this->shelf($request, '/');
    }

    /** /genre/patmavep — տնային 2 */
    public function genre(Request $request, string $slug): Response
    {
        $genre = $this->genres->findBySlug($slug) ?? throw new NotFoundException('Ժանրը չի գտնվել');

        return $this->shelf($request, $genre->url(), $genre);
    }

    /** /search?q=Րաֆֆի — տնային 3 */
    public function search(Request $request): Response
    {
        return $this->shelf($request, '/search');
    }

    public function show(Request $request, string $slug): Response
    {
        $book = $this->findOr404($slug);

        return Response::html(page('pages/book', [
            'book'     => $book,
            'sessions' => $this->sessions->forBook($book->id),
        ], $book->title . ' — ' . SITE_NAME, 'books'));
    }

    public function cover(Request $request, string $slug): Response
    {
        $book = $this->findOr404($slug);

        [$name, $error] = uploadImage($request->file('cover') ?? [], UPLOAD_DIR);

        if ($error !== null) {
            flash('error', $error);
        } else {
            $this->books->saveCover($book->id, $name);
            flash('success', 'Շապիկը պահպանվեց');
        }

        return Response::redirect($book->url());
    }

    /** «Կարդացի այսօր» — Դաս 11-ի տրանզակցիան, հիմա SessionRepository-ում */
    public function read(Request $request, string $slug): Response
    {
        $book = $this->findOr404($slug);
        $errors = Validator::reading($request->all(), $book);

        if ($errors !== []) {
            flash('error', implode(' · ', $errors));
            return Response::redirect($book->url());
        }

        try {
            $this->sessions->log($book->id, $request->int('pages'), $request->input('read_on'), $request->input('note') ?: null);
            flash('success', '+' . $request->int('pages') . ' էջ «' . $book->title . '»-ից');
        } catch (RuntimeException $e) {
            flash('error', $e->getMessage());
        }

        return Response::redirect($book->url());
    }

    // ---------- Ներքին ----------

    private function findOr404(string $slug): Book
    {
        return $this->books->findBySlug($slug) ?? throw new NotFoundException('Գիրքը չի գտնվել');
    }

    /** Դարակը՝ ֆիլտրերով։ Նույնը՝ /, /genre/... և /search ուղիների համար */
    private function shelf(Request $request, string $path, ?Genre $genre = null): Response
    {
        $filters = BookRepository::filtersFrom($request->allQuery());
        if ($genre !== null) {
            $filters['genre'] = $genre->slug;           // ժանրը ուղուց է, ոչ ?genre=-ից
        }

        $sort = $request->query('sort');
        $sort = isset(BookRepository::SORTS[$sort]) ? $sort : 'progress';
        $perPage = BookRepository::PER_PAGE;

        $total = $this->books->count($filters);
        $pages = max(1, (int) (($total + $perPage - 1) / $perPage));
        $page  = min($pages, max(1, $request->int('page', 1)));

        return Response::html(page('pages/home', [
            'books'   => $this->books->search($filters, $sort, $perPage, ($page - 1) * $perPage),
            'stats'   => $this->books->stats(),
            'genres'  => $this->genres->all(),
            'genre'   => $genre,
            'sorts'   => BookRepository::SORTS,
            'filters' => $filters,
            'sort'    => $sort,
            'total'   => $total,
            'page'    => $page,
            'pages'   => $pages,
            'path'    => $path,
        ], ($genre ? $genre->name . ' — ' : 'Գրքեր — ') . SITE_NAME, 'books'));
    }
}
