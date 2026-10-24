<?php
declare(strict_types=1);

// Կապի ֆորմա — PHP Դաս 6, flash-ով՝ Դաս 7

require_once __DIR__ . '/bootstrap.php';

$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateContact($_POST);

    if ($errors === []) {
        // Այստեղ կուղարկեինք նամակ կամ կպահեինք բազայում
        flash('success', 'Շնորհակալություն։ Կպատասխանենք մեկ աշխատանքային օրվա ընթացքում։');
        redirect('/contact.php');            // PRG․ F5-ը չի կրկնի ուղարկումը ⭐
    }

    $old = $_POST;
}

renderPage('pages/contact', [
    'errors' => $errors,
    'old'    => $old,
    'topics' => CONTACT_TOPICS,
], 'Կապ — ' . SITE_NAME, 'contact');
