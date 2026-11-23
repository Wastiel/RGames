<?php
// h() = "escapar" texto antes de imprimir no HTML (protege contra XSS)
function h($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

// Formata número como dinheiro brasileiro: 1234.5 -> R$ 1.234,50
function moeda($valor) {
    return 'R$ ' . number_format((float)$valor, 2, ',', '.');
}

// Formata horas: 12.5 -> 12,5 h
function horas($valor) {
    return number_format((float)$valor, 1, ',', '.') . ' h';
}
