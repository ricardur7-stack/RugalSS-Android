<?php
/* ======================================================
   🎮 RugalSS Android Security Scanner
   Tema Dark • Clean • Modern
   ====================================================== */

/* ========= CORES ========= */
$reset   = "\e[0m";
$bold    = "\e[1m";

$white   = "\e[97m";
$gray    = "\e[90m";
$cyan    = "\e[36m";
$blue    = "\e[34m";
$green   = "\e[92m";
$red     = "\e[91m";
$yellow  = "\e[93m";
$purple  = "\e[35m";

/* ========= HELPERS ========= */
function line($char="═"){ echo str_repeat($char, 66) . "\n"; }

function ok($msg){
    global $green,$reset,$bold;
    echo "{$bold}{$green}  ✓ {$msg}{$reset}\n";
}

function warn($msg){
    global $yellow,$reset,$bold;
    echo "{$bold}{$yellow}  ⚠ {$msg}{$reset}\n";
}

function fail($msg){
    global $red,$reset,$bold;
    echo "{$bold}{$red}  ✗ {$msg}{$reset}\n";
}

function titleBox($txt){
    global $cyan,$bold,$reset;

    echo "\n{$bold}{$cyan}╔"; line("═");
    echo "║  $txt\n";
    echo "╚"; line("═");
    echo "{$reset}";
}

/* ========= BANNER ========= */
function rugal_banner(){
    global $cyan,$purple,$white,$gray,$reset,$bold;

    echo "{$bold}{$purple}
   ██████╗ ██╗   ██╗ ██████╗  █████╗ ██╗     ███████╗███████╗
   ██╔══██╗██║   ██║██╔════╝ ██╔══██╗██║     ██╔════╝██╔════╝
   ██████╔╝██║   ██║██║  ███╗███████║██║     █████╗  ███████╗
   ██╔══██╗██║   ██║██║   ██║██╔══██║██║     ██╔══╝  ╚════██║
   ██║  ██║╚██████╔╝╚██████╔╝██║  ██║███████╗███████╗███████║
   ╚═╝  ╚═╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═╝╚══════╝╚══════╝╚══════╝
{$reset}
{$cyan}           RugalSS Android • ScreenSchare
{$gray}           Anti-Root • Anti-Bypass • Anti-Hook
{$reset}\n";
}

/* ========= UPDATER ========= */
function atualizar(){
    titleBox("RUGALSS UPDATE");

    warn("Atualizando sistema...");

    system("git fetch origin && git reset --hard origin/master && git clean -f -d");

    ok("Atualização concluída! Reinicie o scanner.");
    exit;
}

/* ========= DETECÇÃO ========= */
function detectarBypassShell(){

    $bypass = false;

    titleBox("ANÁLISE COMPLETA DO DISPOSITIVO");

    /* ---------- DISPOSITIVO ---------- */
    echo "\n[1] Dispositivo ADB\n";

    $devices = shell_exec('adb devices 2>&1');

    if(strpos($devices,'device') === false || strpos($devices,'unauthorized') !== false){
        fail("Nenhum dispositivo autorizado encontrado.");
        return false;
    }

    ok("Dispositivo conectado");


    /* ---------- VERIFIED BOOT ---------- */
    echo "\n[2] Verified Boot\n";

    $state = trim(shell_exec('adb shell getprop ro.boot.verifiedbootstate'));

    switch($state){
        case "green":  ok("Boot verificado"); break;
        case "yellow": warn("Sistema modificado"); $bypass=true; break;
        case "orange": fail("Bootloader desbloqueado"); $bypass=true; break;
        default: warn("Estado desconhecido: $state");
    }


    /* ---------- SELINUX ---------- */
    echo "\n[3] SELinux\n";

    $selinux = trim(shell_exec('adb shell getenforce'));

    if($selinux == "Permissive"){
        fail("Modo permissivo (suspeito)");
        $bypass=true;
    } else {
        ok("Enforcing ativo");
    }


    /* ---------- BINÁRIOS SU ---------- */
    echo "\n[4] Root binaries\n";

    $paths = [
        '/system/bin/su',
        '/system/xbin/su',
        '/sbin/su',
        '/data/adb/magisk'
    ];

    foreach($paths as $p){
        $r = trim(shell_exec("adb shell 'test -f $p && echo FOUND'"));
        if($r == "FOUND"){
            fail("Encontrado: $p");
            $bypass=true;
        }
    }

    if(!$bypass) ok("Nenhum root detectado");


    /* ---------- MAGISK ---------- */
    echo "\n[5] Magisk\n";

    $magisk = shell_exec("adb shell pm list packages | grep -i magisk");

    if(trim($magisk)){
        fail("Magisk detectado");
        $bypass=true;
    } else ok("Limpo");


    /* ---------- RESULTADO ---------- */
    titleBox("RESULTADO FINAL");

    if($bypass){
        fail("DISPOSITIVO SUSPEITO / MODIFICADO");
    } else {
        ok("DISPOSITIVO LIMPO E SEGURO");
    }

    return !$bypass;
}


/* ========= EXECUÇÃO ========= */
rugal_banner();
detectarBypassShell();
