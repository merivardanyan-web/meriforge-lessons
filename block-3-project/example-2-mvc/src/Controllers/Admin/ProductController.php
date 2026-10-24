<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Exceptions\HttpException;
use App\Exceptions\NotFoundException;
use App\Http\Request;
use App\Http\Response;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Services\Auth;
use App\Services\ImageUploader;
use App\Services\Validator;

/** Ադմինի CRUD — PHP Դաս 11-ի ֆայլերը՝ մեկ կոնտրոլերում (Դաս 15) */
class ProductController
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly CategoryRepository $categories,
        private readonly ImageUploader $uploader,
        private readonly Auth $auth,
    ) {}

    public function index(Request $request): Response
    {
        $q = mb_substr($request->query('q'), 0, 100);

        return $this->admin('admin/index', [
            'products' => $this->products->allForAdmin($q),
            'q'        => $q,
            'canArchive' => $this->auth->isAdmin(),
        ], 'Ապրանքներ');
    }

    public function create(Request $request): Response
    {
        return $this->form(null, ['is_active' => 1, 'discount' => 0, 'stock' => 0, 'type' => 'physical', 'weight' => 0], []);
    }

    public function store(Request $request): Response
    {
        return $this->save($request, null, null);
    }

    public function edit(Request $request, string $id): Response
    {
        $row = $this->findRow($id);

        return $this->form((int) $row['id'], $row, []);
    }

    public function update(Request $request, string $id): Response
    {
        $row = $this->findRow($id);

        return $this->save($request, (int) $row['id'], $row['image']);
    }

    /** Միայն ադմինը — մենեջերը ստեղծում և խմբագրում է, բայց չի հեռացնում — PHP Դաս 16-ի տնային ⭐ */
    public function archive(Request $request): Response
    {
        if (!$this->auth->isAdmin()) {
            throw new HttpException('Արխիվացնել կարող է միայն ադմինը', 403);
        }

        $count = $this->products->archive($request->array('ids'));
        flash($count > 0 ? 'success' : 'error', $count > 0 ? "Արխիվացվեց՝ {$count} ապրանք" : 'Ոչինչ չնշվեց');

        return Response::redirect('/admin');
    }

    // ---------- Ներքին ----------

    private function save(Request $request, ?int $id, ?string $image): Response
    {
        $input = $request->all();
        $errors = Validator::product($input, $this->categories);

        $file = $request->file('image');
        if ($file !== null && $errors === []) {
            [$name, $error] = $this->uploader->upload($file);

            if ($error !== null) {
                $errors['image'] = $error;
            } else {
                $image = 'uploads/' . $name;
            }
        }

        if ($errors !== []) {
            return $this->form($id, $input + ['image' => $image], $errors);
        }

        $name = $request->input('name');

        // ⭐ Միայն թվարկված սյունակները, ոչ ամբողջ $_POST-ը
        $data = [
            'category_id' => $request->input('category_id') !== '' ? $request->int('category_id') : null,
            'name'        => $name,
            'slug'        => $this->products->uniqueSlug(slugify($name), $id),
            'specs'       => $request->input('specs') ?: null,
            'description' => $request->input('description') ?: null,
            'price'       => $request->int('price'),
            'discount'    => $request->int('discount'),
            'stock'       => $request->input('type') === 'digital' ? 0 : $request->int('stock'),
            'type'        => $request->input('type'),
            'weight'      => $request->int('weight'),
            'image'       => $image,
            'is_active'   => $request->has('is_active') ? 1 : 0,
        ];

        if ($id === null) {
            $this->products->create($data);
            flash('success', 'Ապրանքն ավելացվեց՝ ' . $name);
        } else {
            $this->products->update($id, $data);
            flash('success', 'Ապրանքը թարմացվեց՝ ' . $name);
        }

        return Response::redirect('/admin');
    }

    private function findRow(string $id): array
    {
        $row = ctype_digit($id) ? $this->products->findRowForAdmin((int) $id) : null;

        return $row ?? throw new NotFoundException('Ապրանքը չի գտնվել');
    }

    private function form(?int $id, array $old, array $errors): Response
    {
        return $this->admin('admin/form', [
            'id'         => $id,
            'old'        => $old,
            'errors'     => $errors,
            'categories' => $this->categories->all(),
        ], $id === null ? 'Նոր ապրանք' : 'Խմբագրում');
    }

    private function admin(string $view, array $data, string $title): Response
    {
        return Response::html(page($view, $data, $title, 'layout-admin'));
    }
}
