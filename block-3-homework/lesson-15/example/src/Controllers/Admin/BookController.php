<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Exceptions\NotFoundException;
use App\Http\Request;
use App\Http\Response;
use App\Repositories\BookRepository;
use App\Repositories\GenreRepository;
use App\Services\Validator;

/** Ադմին՝ գրքեր — PHP Դաս 11-ի admin/*.php-ն՝ մեկ կոնտրոլերում, Դաս 15 */
class BookController
{
    public function __construct(
        private readonly BookRepository $books,
        private readonly GenreRepository $genres,
    ) {}

    public function index(Request $request): Response
    {
        $q = $request->query('q');

        return Response::html(page('admin/index', [
            'books' => $this->books->adminList($q),
            'q'     => $q,
        ], 'Գրքեր', 'admin', 'layout-admin'));
    }

    public function create(Request $request): Response
    {
        return $this->form([], ['is_active' => 1, 'read_pages' => 0, 'rating' => 0, 'color' => '#003459', 'genre_id' => '', 'format' => 'paper'], null);
    }

    public function store(Request $request): Response
    {
        return $this->save($request, null, null);
    }

    public function edit(Request $request, string $id): Response
    {
        return $this->form([], $this->findOr404($id), (int) $id);
    }

    public function update(Request $request, string $id): Response
    {
        return $this->save($request, (int) $id, $this->findOr404($id));
    }

    public function archive(Request $request): Response
    {
        $count = $this->books->archive($request->array('ids'));
        flash($count > 0 ? 'success' : 'error', $count > 0 ? "Թաքցվեց՝ {$count} գիրք" : 'Ոչինչ չնշվեց');

        return Response::redirect('/admin/books');
    }

    // ---------- Ներքին ----------

    private function findOr404(string $id): array
    {
        $book = filter_var($id, FILTER_VALIDATE_INT) !== false ? $this->books->findForAdmin((int) $id) : null;   // /admin/books/abc/edit → 404

        return $book ?? throw new NotFoundException('Գիրքը չի գտնվել');
    }

    private function save(Request $request, ?int $id, ?array $current): Response
    {
        $errors = Validator::book($request->all(), $this->genres->ids());
        $cover  = $current['cover'] ?? null;

        $file = $request->file('cover');
        if ($file !== null && $errors === []) {
            [$name, $error] = uploadImage($file, UPLOAD_DIR);

            if ($error !== null) {
                $errors['cover'] = $error;
            } else {
                $cover = $name;
            }
        }

        if ($errors !== []) {
            return $this->form($errors, $request->all() + ['cover' => $cover], $id, 422);
        }

        $data = $this->books->dataFrom($request->all(), $cover, $id);

        if ($id !== null) {
            flash('success', $this->books->update($id, $data) ? 'Պահպանվեց՝ ' . $data['title'] : 'Ոչինչ չփոխվեց');
        } else {
            $this->books->create($data);
            flash('success', 'Ավելացվեց՝ ' . $data['title']);
        }

        return Response::redirect('/admin/books');
    }

    private function form(array $errors, array $old, ?int $id, int $status = 200): Response
    {
        return Response::html(page('admin/form', [
            'errors' => $errors,
            'old'    => $old,
            'isEdit' => $id !== null,
            'id'     => $id,
            'genres' => $this->genres->all(),
        ], $id !== null ? 'Խմբագրում' : 'Նոր գիրք', 'admin', 'layout-admin'), $status);
    }
}
