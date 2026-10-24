<?php
declare(strict_types=1);

// ⚠️ ՓՈՐՁՆԱԿԱՆ ֆայլ — PHP Դաս 10, տնային 2 և 3
// Ցույց է տալիս SQL injection-ը և դրա ուղղումը։ Փորձից հետո ՋՆՋԻՐ, երբեք մի հրապարակիր
// ?mode=bad  → տողերի միացում (խոցելի)
// ?mode=good → prepared statement
require_once __DIR__ . '/bootstrap.php';

$mode = ($_GET['mode'] ?? 'bad') === 'good' ? 'good' : 'bad';
$id   = $_GET['id'] ?? '1';

// ---------- Տնային 2 · գիրք id-ով ----------
if ($mode === 'bad') {
    $sql  = "SELECT id, title FROM books WHERE id = $id";        // ❌ օգտագործողի տեքստը SQL-ի մեջ
    $rows = db()->query($sql)->fetchAll();
} else {
    $sql  = 'SELECT id, title FROM books WHERE id = :id';         // ✅ տվյալը առանձին
    $stmt = db()->prepare($sql);
    $stmt->execute(['id' => $id]);
    $rows = $stmt->fetchAll();
}

// ---------- Տնային 3 · «մուտք» ----------
$login = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass  = $_POST['password'] ?? '';

    if ($mode === 'bad') {
        $loginSql = "SELECT email FROM lab_members WHERE email = '$email' AND password = '$pass'";
        $login = db()->query($loginSql)->fetch() ?: null;
    } else {
        $loginSql = 'SELECT email FROM lab_members WHERE email = :email AND password = :password';
        $stmt = db()->prepare($loginSql);
        $stmt->execute(['email' => $email, 'password' => $pass]);
        $login = $stmt->fetch() ?: null;
    }
}
?>
<!DOCTYPE html>
<html lang="hy">
<head><meta charset="UTF-8"><title>SQL injection · փորձ</title><link rel="stylesheet" href="css/global.css"><link rel="stylesheet" href="css/main.css"></head>
<body>
<main class="row"><div class="col w-100">
    <h1>SQL injection · <?= $mode === 'bad' ? '❌ խոցելի' : '✅ պաշտպանված' ?></h1>
    <p>
        <a href="?mode=bad&amp;id=1">bad, id=1</a> ·
        <a href="?mode=bad&amp;id=<?= e(urlencode('1 OR 1=1')) ?>">bad, id=1 OR 1=1</a> ·
        <a href="?mode=good&amp;id=<?= e(urlencode('1 OR 1=1')) ?>">good, id=1 OR 1=1</a>
    </p>

    <h2>Հարցումը</h2>
    <pre><?= e($mode === 'bad' ? $sql : $sql . '   ← :id = ' . var_export($id, true)) ?></pre>
    <p>Գտնվեց <strong><?= count($rows) ?></strong> տող․</p>
    <ul><?php foreach ($rows as $r): ?><li><?= (int) $r['id'] ?> · <?= e($r['title']) ?></li><?php endforeach; ?></ul>

    <h2>Մուտքի շրջանցում</h2>
    <form method="post" action="?mode=<?= $mode ?>">
        <p><label>Էլ. հասցե <input type="text" name="email" value="<?= e($_POST['email'] ?? "' OR '1'='1' -- ") ?>"></label></p>
        <p><label>Գաղտնաբառ <input type="text" name="password" value="<?= e($_POST['password'] ?? 'ցանկացած') ?>"></label></p>
        <button class="btn btn--primary btn--sm">Մուտք</button>
    </form>
    <?php if (isset($loginSql)): ?>
        <pre><?= e($loginSql) ?></pre>
        <p class="alert <?= $login ? 'alert--danger' : 'alert--success' ?>"><?= $login ? 'Ներս ես մտել որպես ' . e($login['email']) . '՝ առանց գաղտնաբառի' : 'Մուտքը մերժված է' ?></p>
    <?php endif; ?>
</div></main>
</body>
</html>
