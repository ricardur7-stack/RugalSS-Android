<?php

$branco = "\e[97m";
$preto = "\e[30m\e[1m";
$amarelo = "\e[93m";
$laranja = "\e[38;5;208m";
$azul   = "\e[34m";
$lazul  = "\e[36m";
$cln    = "\e[0m";
$verde  = "\e[92m";
$fverde = "\e[32m";
$vermelho = "\e[91m";
$magenta = "\e[35m";
$azulbg = "\e[44m";
$lazulbg = "\e[106m";
$verdebg = "\e[42m";
$lverdebg = "\e[102m";
$amarelobg = "\e[43m";
$lamarelobg = "\e[103m";
$vermelhobg = "\e[101m";
$cinza = "\e[37m";
$ciano = "\e[36m";
$bold = "\e[1m";


/* =========================
   BANNER RUGALSS
========================= */
function rugal_banner(){
echo "\e[97m
╔══════════════════════════════════════════════════════════════╗
║                                                              ║
║              \e[97mRugalSS Android \e[36mSecurity Scanner\e[97m              ║
║                 \e[90mdiscord.gg/rugal\e[97m                          ║
║                                                              ║
╚══════════════════════════════════════════════════════════════╝

      ██████╗ ██╗   ██╗ ██████╗  █████╗ ██╗         ███████╗███████╗
      ██╔══██╗██║   ██║██╔════╝ ██╔══██╗██║         ██╔════╝██╔════╝
      ██████╔╝██║   ██║██║  ███╗███████║██║         ███████╗███████╗
      ██╔══██╗██║   ██║██║   ██║██╔══██║██║         ╚════██║╚════██║
      ██║  ██║╚██████╔╝╚██████╔╝██║  ██║███████╗    ███████║███████║
      ╚═╝  ╚═╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═╝╚══════╝    ╚══════╝╚══════╝

           \e[36mCoded By: RugalSS | Android Security Toolkit\e[0m

";
}

echo $cln;


/* =========================
   UPDATER
========================= */
function atualizar()
{
    global $cln, $bold, $fverde, $vermelho, $azul;

    echo "\n".$bold.$azul."┌─ RUGALSS UPDATER\n".$cln;
    echo $vermelho."  ⟳ Atualizando, aguarde...\n\n".$cln;

    system("git fetch origin && git reset --hard origin/master && git clean -f -d");

    echo $bold.$fverde."  ✓ Atualização concluída! Reinicie o scanner\n".$cln;
    exit;
}


/* =========================
   SCANNER (MESMA LÓGICA)
========================= */
function detectarBypassShell() {

    global $bold, $vermelho, $amarelo, $fverde, $azul, $branco, $cln, $verde, $ciano;

    $bypassDetectado = false;

    echo "\n";
    echo $bold.$ciano."╔═══════════════════════════════════════════════════════════════════╗\n";
    echo $bold.$ciano."║                RUGALSS • ANÁLISE DE SEGURANÇA                    ║\n";
    echo $bold.$ciano."╚═══════════════════════════════════════════════════════════════════╝\n\n".$cln;

    /* =========================
       DISPOSITIVO
    ========================= */

    echo $bold.$azul."[1] Verificando dispositivo...\n".$cln;

    $devices = shell_exec('adb devices 2>&1');

    if (strpos($devices, 'device') === false || strpos($devices, 'unauthorized') !== false) {
        echo $bold.$vermelho."[✗] Nenhum dispositivo autorizado!\n".$cln;
        return false;
    }

    echo $bold.$verde."[✓] Conectado com sucesso\n\n".$cln;


    /* =========================
       SELINUX
    ========================= */

    echo $bold.$azul."[2] SELinux...\n".$cln;

    $selinux = trim(shell_exec('adb shell getenforce'));

    if ($selinux === 'Permissive') {
        echo $vermelho."[✗] PERMISSIVE (suspeito)\n".$cln;
        $bypassDetectado = true;
    } else {
        echo $verde."[✓] ENFORCING\n".$cln;
    }


    /* =========================
       BINÁRIO SU
    ========================= */

    echo "\n".$bold.$azul."[3] Binário SU...\n".$cln;

    $su = trim(shell_exec('adb shell "which su"'));

    if (!empty($su)) {
        echo $vermelho."[✗] Root detectado: $su\n".$cln;
        $bypassDetectado = true;
    } else {
        echo $verde."[✓] Sem root\n".$cln;
    }


    /* =========================
       RESULTADO FINAL
    ========================= */

    echo "\n".$bold;

    if ($bypassDetectado){
        echo $vermelho."⚠ POSSÍVEL BYPASS / ROOT DETECTADO!\n".$cln;
    } else {
        echo $fverde."✓ Dispositivo limpo\n".$cln;
    }
}
