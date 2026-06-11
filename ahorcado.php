<?php
session_start();

// === PALABRAS ===
$palabras = ["transistor","algoritmo","circuito","variable","resistencia",
             "compilador","frecuencia","protocolo","amplificador","funcion"];

// === INICIALIZAR SESION (siempre, antes de todo) ===
if (!isset($_SESSION['palabra']))  $_SESSION['palabra']  = $palabras[array_rand($palabras)];
if (!isset($_SESSION['errores']))  $_SESSION['errores']  = 0;
if (!isset($_SESSION['letras']))   $_SESSION['letras']   = [];

// === NUEVA PARTIDA ===
if (isset($_POST['nueva'])) {
    $_SESSION['palabra']  = $palabras[array_rand($palabras)];
    $_SESSION['errores']  = 0;
    $_SESSION['letras']   = [];
}

// === PROCESAR LETRA ===
if (isset($_POST['letra'])) {
    $letra = strtolower($_POST['letra']);
    if (!in_array($letra, $_SESSION['letras'])) {
        $_SESSION['letras'][] = $letra;
        if (strpos($_SESSION['palabra'], $letra) === false)
            $_SESSION['errores']++;
    }
}

// === VARIABLES ===
$palabra = $_SESSION['palabra'];
$errores = $_SESSION['errores'];
$letras  = $_SESSION['letras'];
$MAX     = 7;

$gano   = !array_diff(str_split($palabra), $letras);
$perdio = $errores >= $MAX;

// === PARTES DEL MUÑECO ===
$partes = [
    1 => '<circle cx="100" cy="60" r="20" stroke="#e74c3c" stroke-width="3" fill="none"/>',
    2 => '<line x1="100" y1="80"  x2="100" y2="150" stroke="#e67e22" stroke-width="3"/>',
    3 => '<line x1="100" y1="100" x2="70"  y2="130" stroke="#f1c40f" stroke-width="3"/>',
    4 => '<line x1="100" y1="100" x2="130" y2="130" stroke="#f1c40f" stroke-width="3"/>',
    5 => '<line x1="100" y1="150" x2="75"  y2="185" stroke="#2ecc71" stroke-width="3"/>',
    6 => '<line x1="100" y1="150" x2="125" y2="185" stroke="#2ecc71" stroke-width="3"/>',
    7 => '<line x1="89"  y1="54"  x2="89"  y2="62"  stroke="#e74c3c" stroke-width="2"/>
          <line x1="111" y1="54"  x2="111" y2="62"  stroke="#e74c3c" stroke-width="2"/>',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ahorcado</title>
<style>
  body { font-family: Arial, sans-serif; background:#1a1a2e; color:#eee; text-align:center; padding:30px; }
  h1   { color:#a78bfa; margin-bottom:20px; }
  svg  { background:#0f0f23; border-radius:12px; margin:10px; }
  .palabra span {
    display:inline-block; width:30px; border-bottom:3px solid #a78bfa;
    font-size:28px; font-weight:bold; margin:4px; text-align:center; color:#7fff7f;
  }
  .teclado button {
    width:38px; height:38px; margin:3px; font-size:14px; font-weight:bold;
    background:#2d2d4e; color:#eee; border:1px solid #555; border-radius:6px; cursor:pointer;
  }
  .teclado button:disabled { opacity:0.3; }
  .teclado button:hover:not(:disabled) { background:#a78bfa; color:#fff; }
  .btn { background:#a78bfa; color:#fff; border:none; padding:10px 24px;
         font-size:16px; border-radius:8px; cursor:pointer; margin-top:16px; }
  .msg { font-size:24px; font-weight:bold; margin:16px; }
  .gano  { color:#7fff7f; }
  .perdio{ color:#ff6b6b; }
</style>
</head>
<body>

<h1>⚡ El Ahorcado</h1>

<svg width="200" height="220">
  <line x1="20"  y1="210" x2="180" y2="210" stroke="#888" stroke-width="3"/>
  <line x1="50"  y1="210" x2="50"  y2="10"  stroke="#888" stroke-width="3"/>
  <line x1="50"  y1="10"  x2="100" y2="10"  stroke="#888" stroke-width="3"/>
  <line x1="100" y1="10"  x2="100" y2="40"  stroke="#888" stroke-width="2"/>
  <?php for ($i = 1; $i <= $errores; $i++) echo $partes[$i] ?? ''; ?>
</svg>

<p>Errores: <strong style="color:#ff6b6b"><?= $errores ?> / <?= $MAX ?></strong></p>

<div class="palabra" style="margin:20px 0">
  <?php foreach (str_split($palabra) as $c): ?>
    <span><?= in_array($c, $letras) ? strtoupper($c) : '' ?></span>
  <?php endforeach; ?>
</div>

<?php if ($gano): ?>
  <div class="msg gano">¡GANASTE! 🎉</div>
<?php elseif ($perdio): ?>
  <div class="msg perdio">💀 Perdiste — Era: <strong><?= strtoupper($palabra) ?></strong></div>
<?php endif; ?>

<?php if (!$gano && !$perdio): ?>
<form method="POST" class="teclado">
  <?php foreach (str_split('abcdefghijklmnopqrstuvwxyz') as $l): ?>
    <button name="letra" value="<?= $l ?>" <?= in_array($l, $letras) ? 'disabled' : '' ?>>
      <?= strtoupper($l) ?>
    </button>
  <?php endforeach; ?>
</form>
<?php endif; ?>

<form method="POST">
  <button class="btn" name="nueva" value="1">🔄 Nueva partida</button>
</form>

</body>
</html>