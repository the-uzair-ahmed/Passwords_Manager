<?php
require_once __DIR__ . '/../conn/conn.php';
$flash = flash_get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars(($config['app_name'] ?? 'Password Manager')); ?></title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <link rel="stylesheet" href="./assets/style.css">
</head>
<body>

<div id="pmToastHost" class="pm-toast"></div>

<?php if ($flash): ?>
<script>
  window.addEventListener("DOMContentLoaded", () => {
    if (window.pmToast) pmToast(<?php echo json_encode($flash['message']); ?>, <?php echo json_encode($flash['type']); ?>);
    else alert(<?php echo json_encode($flash['message']); ?>);
  });
</script>
<?php endif; ?>
