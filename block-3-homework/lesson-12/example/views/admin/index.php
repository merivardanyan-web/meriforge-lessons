<section>
    <div class="row">
        <div class="col w-100">
            <div class="flex justify-between align-center flex-wrap gap-10 mt-20">
                <h1>Գրքեր (<?= count($books) ?>)</h1>

                <!-- Որոնում ադմինում — տնային 8 -->
                <form method="get" action="index.php" class="flex gap-10" role="search">
                    <label for="q" class="visually-hidden">Որոնել գիրք</label>
                    <input id="q" name="q" type="search" placeholder="Վերնագիր կամ հեղինակ" value="<?= e($q) ?>">
                    <button class="btn btn--ghost btn--sm">Որոնել</button>
                </form>
            </div>

            <!-- Մեկ ֆորմա զանգվածային թաքցման համար․ checkbox-ները՝ ids[] — տնային 9 -->
            <form method="post" action="archive.php">
                <div class="table-wrap">
                <table class="admin-table">
                    <caption class="visually-hidden">Բոլոր գրքերը, ներառյալ թաքցվածները</caption>
                    <thead>
                        <tr>
                            <th scope="col"><span class="visually-hidden">Նշել</span></th>
                            <th scope="col">Վերնագիր</th>
                            <th scope="col">Հեղինակ</th>
                            <th scope="col">Ժանր</th>
                            <th scope="col">Կարդացած</th>
                            <th scope="col">Կարգավիճակ</th>
                            <th scope="col"><span class="visually-hidden">Գործողություններ</span></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($books as $b): ?>
                        <tr class="<?= $b['is_active'] ? '' : 'row-inactive' ?>">
                            <td><input type="checkbox" name="ids[]" value="<?= (int) $b['id'] ?>" aria-label="Նշել՝ <?= e($b['title']) ?>"<?= $b['is_active'] ? '' : ' disabled' ?>></td>
                            <td><?= e($b['title']) ?></td>
                            <td><?= e($b['author']) ?></td>
                            <td><?= e($b['genre'] ?? '—') ?></td>
                            <td><?= (int) $b['read_pages'] ?> / <?= (int) $b['pages'] ?></td>
                            <td><?= $b['is_active'] ? 'Ակտիվ' : 'Թաքցված' ?></td>
                            <td><a href="form.php?id=<?= (int) $b['id'] ?>" class="btn btn--ghost btn--sm">Խմբագրել</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>

                <?php if ($books === []): ?>
                    <p class="empty mt-20">Ոչինչ չգտնվեց։</p>
                <?php else: ?>
                    <button type="submit" class="btn btn--danger btn--sm mt-20">Թաքցնել նշվածները</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</section>
