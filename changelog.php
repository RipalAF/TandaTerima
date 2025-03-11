<?php
$repo_owner = 'RipalAF';
$repo_name = 'TandaTerima';
$api_url = "https://api.github.com/repos/$repo_owner/$repo_name/commits";

// Ambil data dari GitHub
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$response = curl_exec($ch);
curl_close($ch);

$commits = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changelog - Tanda Terima APP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto my-8 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-center text-blue-600 mb-6">Changelog - Tanda Terima APP</h1>

        <?php if (is_array($commits) && count($commits) > 0): ?>
            <ul class="space-y-4">
                <?php foreach ($commits as $commit): ?>
                    <?php
                    $message_lines = explode("\n", $commit['commit']['message']); 
                    $version = trim($message_lines[0]); // Baris pertama sebagai versi (misal: v1.2.8)
                    $description = isset($message_lines[1]) ? trim($message_lines[1]) : "Deskripsi tidak tersedia"; // Baris kedua sebagai deskripsi
                    $author = $commit['commit']['author']['name'];
                    $date = date('d M Y, H:i', strtotime($commit['commit']['author']['date']));
                    ?>
                    <li class="p-4 border-l-4 border-blue-500 bg-gray-50 shadow-md rounded-md">
                        <p class="text-lg font-semibold"><?= htmlspecialchars($version) ?></p>
                        <p class="text-sm text-gray-600">Oleh <span class="font-bold"><?= htmlspecialchars($author) ?></span> pada <?= htmlspecialchars($date) ?></p>
                        <a href="<?= $commit['html_url'] ?>" target="_blank" class="text-blue-500 text-sm underline">Lihat di GitHub</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-center text-gray-600">Belum ada commit yang tersedia.</p>
        <?php endif; ?>
    </div>
</body>
</html>
