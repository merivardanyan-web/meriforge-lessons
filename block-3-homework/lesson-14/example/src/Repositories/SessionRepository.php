<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;
use RuntimeException;
use Throwable;

/** Ընթերցանության օրերը — PHP Դաս 14, տնային 4 (OrderRepository-ի նման՝ տրանզակցիայով) */
class SessionRepository
{
    public function __construct(private PDO $pdo) {}

    public function forBook(int $bookId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT read_on, pages, note
            FROM reading_sessions
            WHERE book_id = :id
            ORDER BY read_on DESC, id DESC
        ');
        $stmt->execute(['id' => $bookId]);

        return $stmt->fetchAll();
    }

    /** INSERT reading_sessions + UPDATE books.read_pages՝ կամ երկուսը, կամ ոչ մեկը (Դաս 11) */
    public function log(int $bookId, int $pages, string $date, ?string $note): void
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO reading_sessions (book_id, pages, read_on, note)
                VALUES (:book, :pages, :read_on, :note)
            ');
            $stmt->execute(['book' => $bookId, 'pages' => $pages, 'read_on' => $date, 'note' => $note]);

            $stmt = $this->pdo->prepare('
                UPDATE books
                SET read_pages = read_pages + :pages
                WHERE id = :id AND read_pages + :pages2 <= pages
            ');
            $stmt->execute(['pages' => $pages, 'pages2' => $pages, 'id' => $bookId]);

            if ($stmt->rowCount() === 0) {
                throw new RuntimeException('Էջերը շատ են․ գիրքն այդքան էջ չունի');
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
