<?php

/* =====================================================
   🎯 RUGAL SCREENSHARE - THEME CORE
   Visual: Dark • Minimal • Modern
   ===================================================== */

$branco = "\e[97m";
$preto = "\e[30m\e[1m";
$amarelo = "\e[93m";
$laranja = "\e[38;5;208m";
$azul   = "\e[34m";
$ciano  = "\e[36m";
$verde  = "\e[92m";
$fverde = "\e[32m";
$vermelho = "\e[91m";
$magenta = "\e[35m";
$cinza = "\e[37m";
$bold   = "\e[1m";
$cln    = "\e[0m";


/* =====================================================
   🎨 BANNER MODERNO
   ===================================================== */

function rugal_banner(){
    global $bold,$ciano,$branco,$cinza,$cln;

echo $bold.$ciano."

╔══════════════════════════════════════════════════════╗
║                                                      ║
║        R U G A L   S C R E E N S H A R E            ║
║                                                      ║
║        Android Security • Anti-Cheat Scanner         ║
║                                                      ║
╚══════════════════════════════════════════════════════╝

".$cinza."     ► Proteção • Detecção • Integridade
     ► Build Profissional para Telagens".$cln."

";
}

echo $cln;


/* =====================================================
   🔄 UPDATER
   ===================================================== */

function atualizar()
{
    global $cln,$bold,$fverde,$vermelho,$ciano;

    echo "\n".$bold.$ciano."┌─ RUGAL UPDATER".$cln."\n";
    echo $vermelho."  ⟳ Atualizando sistema...\n\n".$cln;

    system("git fetch origin && git reset --hard origin/master && git clean -f -d");

    echo $bold.$fverde."  ✓ Atualização concluída! Reinicie o scanner\n".$cln;
    exit;
}


/* =====================================================
   🛡️ DETECÇÃO PRINCIPAL
   (mantive TODA lógica original, só renomeei/estilizei)
   ===================================================== */

function detectarBypassShell() {

    global $bold,$vermelho,$amarelo,$fverde,$azul,$cln,$verde,$ciano;

    $bypassDetectado = false;
    $totalVerificacoes = 0;
    $problemasEncontrados = 0;

    echo "\n";
    echo $bold.$ciano."═══════════════════════════════════════════════════════\n";
    echo "      RUGAL DEVICE SECURITY ANALYSIS\n";
    echo "═══════════════════════════════════════════════════════".$cln."\n\n";


/* =====================================================
   [1] DEVICE CHECK
   ===================================================== */

    echo $bold.$azul."[1] Verificando dispositivo...".$cln."\n";

    $devices = shell_exec('adb devices 2>&1');

    if (strpos($devices, 'device') === false || strpos($devices, 'unauthorized') !== false) {
        echo $vermelho."✗ Nenhum dispositivo autorizado\n".$cln;
        return false;
    }

    echo $verde."✓ Dispositivo conectado\n".$cln;
    $totalVerificacoes++;


/* =====================================================
   [2] VERIFIED BOOT
   ===================================================== */

    echo "\n".$bold.$azul."[2] Boot State".$cln."\n";

    $state = trim(shell_exec('adb shell getprop ro.boot.verifiedbootstate'));

    if ($state === 'green'){
        echo $verde."✓ Sistema íntegro\n".$cln;
    } else {
        echo $vermelho."✗ Estado suspeito: $state\n".$cln;
        $bypassDetectado = true;
        $problemasEncontrados++;
    }

    $totalVerificacoes++;


/* =====================================================
   [3] SELINUX
   ===================================================== */

    echo "\n".$bold.$azul."[3] SELinux".$cln."\n";

    $selinux = trim(shell_exec('adb shell getenforce'));

    if($selinux === "Enforcing"){
        echo $verde."✓ Proteção ativa\n".$cln;
    } else {
        echo $vermelho."✗ Modo permissivo detectado\n".$cln;
        $bypassDetectado = true;
        $problemasEncontrados++;
    }

    $totalVerificacoes++;


/* =====================================================
   RESULTADO FINAL
   ===================================================== */

    echo "\n".$bold.$ciano."═══════════════════════════════════════════════════════".$cln."\n";

    if($bypassDetectado){
        echo $vermelho.$bold."⚠ RISCO DETECTADO\n";
        echo "Problemas encontrados: $problemasEncontrados\n".$cln;
    } else {
        echo $verde.$bold."✓ DISPOSITIVO LIMPO\n".$cln;
    }

    echo $cinza."Verificações executadas: $totalVerificacoes".$cln."\n\n";

    return !$bypassDetectado;
}
