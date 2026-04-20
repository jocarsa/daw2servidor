<?php
header("Content-Type: text/xml; charset=utf-8");

function responderSOAP($precio, $plazoDias, $zona, $urgente, $requestId = "") {
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '
        <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
            <respuestaInfo>
            <servidor>ServicioEnvioPostalPHP</servidor>
            <requestId>' . htmlspecialchars($requestId, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</requestId>
            </respuestaInfo>
        </soap:Header>
        <soap:Body>
            <calcularEnvioResponse>
            <precio>' . $precio . '</precio>
            <plazoDias>' . $plazoDias . '</plazoDias>
            <zona>' . htmlspecialchars($zona, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</zona>
            <urgente>' . ($urgente ? 'true' : 'false') . '</urgente>
            </calcularEnvioResponse>
        </soap:Body>
        </soap:Envelope>';
    exit;
}

function responderFault($mensaje) {
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '
        <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Body>
            <soap:Fault>
            <faultcode>SOAP-ENV:Client</faultcode>
            <faultstring>' . htmlspecialchars($mensaje, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</faultstring>
            </soap:Fault>
        </soap:Body>
        </soap:Envelope>';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responderFault("Este servicio SOAP solo acepta peticiones POST.");
}

$xmlRecibido = file_get_contents("php://input");

if (trim($xmlRecibido) === "") {
    responderFault("No se recibió ningún XML.");
}

libxml_use_internal_errors(true);

$dom = new DOMDocument();

if (!$dom->loadXML($xmlRecibido)) {
    responderFault("El XML recibido no es válido.");
}

$xpath = new DOMXPath($dom);
$xpath->registerNamespace("soap", "http://schemas.xmlsoap.org/soap/envelope/");

$body = $xpath->query("//soap:Body")->item(0);

if (!$body) {
    responderFault("No se encontró el elemento Body.");
}

$header = $xpath->query("//soap:Header")->item(0);
$requestId = "";

if ($header instanceof DOMElement) {
    $requestIdNode = $header->getElementsByTagName("requestId")->item(0);

    if ($requestIdNode) {
        $requestId = trim($requestIdNode->textContent);
    }
}

$operacionNode = null;

foreach ($body->childNodes as $nodo) {
    if ($nodo instanceof DOMElement) {
        $operacionNode = $nodo;
        break;
    }
}

if (!$operacionNode) {
    responderFault("No se encontró ninguna operación dentro del Body.");
}

$operacion = $operacionNode->localName;

if ($operacion !== "calcularEnvio") {
    responderFault("La operación '$operacion' no está soportada.");
}

$pesoNode = $operacionNode->getElementsByTagName("peso")->item(0);
$zonaNode = $operacionNode->getElementsByTagName("zona")->item(0);
$urgenteNode = $operacionNode->getElementsByTagName("urgente")->item(0);

if (!$pesoNode || !$zonaNode || !$urgenteNode) {
    responderFault("Faltan parámetros obligatorios: peso, zona o urgente.");
}

$peso = trim($pesoNode->textContent);
$zona = trim($zonaNode->textContent);
$urgenteTexto = trim($urgenteNode->textContent);

if (!is_numeric($peso)) {
    responderFault("El peso debe ser numérico.");
}

$peso = (float)$peso;

if ($peso <= 0) {
    responderFault("El peso debe ser mayor que 0.");
}

$zonasPermitidas = ["peninsula", "baleares", "canarias", "internacional"];

if (!in_array($zona, $zonasPermitidas, true)) {
    responderFault("La zona indicada no es válida.");
}

$urgente = ($urgenteTexto === "true");

// Precio base según zona
switch ($zona) {
    case "peninsula":
        $precioBase = 4.50;
        $plazoBase = 3;
        break;

    case "baleares":
        $precioBase = 7.00;
        $plazoBase = 4;
        break;

    case "canarias":
        $precioBase = 9.50;
        $plazoBase = 5;
        break;

    case "internacional":
        $precioBase = 15.00;
        $plazoBase = 7;
        break;

    default:
        responderFault("No se pudo calcular la zona.");
}

// Recargo por peso
if ($peso <= 1) {
    $recargoPeso = 0;
} elseif ($peso <= 5) {
    $recargoPeso = 2.50;
} else {
    $recargoPeso = 5.00;
}

// Recargo urgente
$recargoUrgente = $urgente ? 6.00 : 0;

// Ajuste de plazo
$plazoDias = $urgente ? max(1, $plazoBase - 2) : $plazoBase;

// Cálculo final
$precioFinal = $precioBase + $recargoPeso + $recargoUrgente;
$precioFinal = number_format($precioFinal, 2, ".", "");

responderSOAP($precioFinal, $plazoDias, $zona, $urgente, $requestId);
?>