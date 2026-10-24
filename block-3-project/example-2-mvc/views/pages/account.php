<?php /** @var App\Models\User $user  @var array $orders */
use App\Repositories\OrderRepository;
?>
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="/">Գլխավոր</a></li>
                    <li aria-current="page">Իմ հաշիվը</li>
                </ol>
            </nav>
            <h1 class="mb-10"><?= e($user->name) ?></h1>
            <p class="text-muted"><?= e($user->email) ?></p>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            <h2 class="section-title mb-30">Իմ պատվերները</h2>

            <?php if ($orders === []): ?>
                <div class="empty-state">
                    <p>Դեռ պատվերներ չունես։</p>
                    <a href="/catalog" class="btn btn--primary">Անցնել կատալոգ</a>
                </div>
            <?php else: ?>
                <div class="scroll-x">
                <table class="table admin-table">
                    <caption class="visually-hidden">Պատվերները՝ նորերը վերևում</caption>
                    <thead><tr><th scope="col">№</th><th scope="col">Ամսաթիվ</th><th scope="col">Հատ</th><th scope="col">Գումար</th><th scope="col">Կարգավիճակ</th></tr></thead>
                    <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><a href="/thanks/<?= (int) $o['id'] ?>" class="link">TM-<?= 20000 + (int) $o['id'] ?></a></td>
                            <td><?= e(date('d.m.Y', strtotime($o['created_at']))) ?></td>
                            <td><?= (int) $o['units'] ?></td>
                            <td><?= formatPrice((int) $o['total']) ?></td>
                            <td><span class="badge"><?= e(OrderRepository::STATUS[$o['status']] ?? $o['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
