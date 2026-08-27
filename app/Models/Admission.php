<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Admission extends Model
{
    protected static string $table = 'admissions';

    public const STATUSES = [
        'pending'      => 'Pending',
        'under_review' => 'Under Review',
        'accepted'     => 'Accepted',
        'rejected'     => 'Rejected',
    ];

    public const CLASSES = [
        'Playgroup', 'Nursery', 'KG',
        'Grade I', 'Grade II', 'Grade III', 'Grade IV', 'Grade V',
        'Grade VI', 'Grade VII', 'Grade VIII',
        'Grade IX (Science)', 'Grade X (Science)',
        '1st Year (Pre-Medical)', '1st Year (Pre-Engineering)',
        '2nd Year (Pre-Medical)', '2nd Year (Pre-Engineering)',
    ];

    /** Fields an applicant fills, and the office can later correct. */
    public const EDITABLE = [
        'student_name', 'father_name', 'bform', 'dob', 'gender',
        'class_applied', 'prev_school', 'guardian_phone', 'address', 'notes',
    ];

    /**
     * The one set of rules for an application, used by the public form and by
     * the admin edit screen. Keeping them here means a correction made in the
     * office cannot store something the public form would have refused.
     *
     * @return string[] human-readable errors, empty when the data is good
     */
    public static function validate(array $data): array
    {
        $errors = [];

        if (mb_strlen(trim($data['student_name'] ?? '')) < 3) {
            $errors[] = 'Student name is required (minimum 3 characters).';
        }
        if (mb_strlen(trim($data['father_name'] ?? '')) < 3) {
            $errors[] = "Father's name is required.";
        }
        if (!preg_match('/^\d{5}-?\d{7}-?\d$/', $data['bform'] ?? '')) {
            $errors[] = 'B-Form / CNIC must be 13 digits (e.g. 21202-1234567-1).';
        }
        $dob = $data['dob'] ?? '';
        if (!$dob || strtotime($dob) === false || strtotime($dob) > time()) {
            $errors[] = 'A valid date of birth is required.';
        }
        if (!in_array($data['gender'] ?? '', ['male', 'female'], true)) {
            $errors[] = "Please select the student's gender.";
        }
        if (!in_array($data['class_applied'] ?? '', self::CLASSES, true)) {
            $errors[] = 'Please select the class being applied for.';
        }
        if (!preg_match('/^[0-9+\-\s]{10,16}$/', $data['guardian_phone'] ?? '')) {
            $errors[] = 'A valid guardian phone number is required (e.g. 0300 1234567).';
        }
        if (mb_strlen(trim($data['address'] ?? '')) < 10) {
            $errors[] = 'Please provide the full home address.';
        }

        return $errors;
    }

    /** Store the B-Form in one shape whatever the typist used. */
    public static function normaliseBform(string $bform): string
    {
        $digits = preg_replace('/\D/', '', $bform);

        return substr($digits, 0, 5) . '-' . substr($digits, 5, 7) . '-' . substr($digits, 12, 1);
    }

    /**
     * A short-lived, collision-proof placeholder for the insert step — two
     * submissions arriving in the same instant can never generate the same
     * value, unlike a counter based on the current row count.
     */
    public static function pendingAppNo(): string
    {
        return 'PENDING-' . bin2hex(random_bytes(8));
    }

    /**
     * The real, permanent reference number, built from the row's own
     * auto-increment id once it exists. Ids are never reused after a row is
     * deleted, so — unlike counting rows — this can never collide with a
     * number already given to a different family.
     */
    public static function formatAppNo(int $id): string
    {
        return 'PCS-' . date('y') . '-' . str_pad((string) $id, 4, '0', STR_PAD_LEFT);
    }
}
