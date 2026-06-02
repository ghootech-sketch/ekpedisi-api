<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/config.php';
$pdo = getPDO();

$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$parts = explode('/', $path);
$resource = $parts[0] ?? '';
$id = isset($parts[1]) && is_numeric($parts[1]) ? intval($parts[1]) : null;
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];

// Simple router for /surat-jalan and /invoices (expand as needed)
if($resource === 'surat-jalan'){
    if($method === 'GET'){
        if($id){
            $stmt = $pdo->prepare('SELECT * FROM surat_jalan WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch());
        }else{
            $stmt = $pdo->query('SELECT * FROM surat_jalan ORDER BY tanggal DESC, id DESC');
            echo json_encode($stmt->fetchAll());
        }
        exit;
    }
    if($method === 'POST'){
        $sql = 'INSERT INTO surat_jalan (noSJ, tanggal, penerima, pemasukanKantor, biayaSopir, statusPemasukan, statusSopir, sopir) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $body['noSJ'] ?? '',
            $body['tanggal'] ?? date('Y-m-d'),
            $body['penerima'] ?? '',
            $body['pemasukanKantor'] ?? 0,
            $body['biayaSopir'] ?? 0,
            $body['statusPemasukan'] ?? 'Belum Lunas',
            $body['statusSopir'] ?? 'Belum Dibayar',
            $body['sopir'] ?? null
        ]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        exit;
    }
    if($method === 'PUT' && $id){
        $fields = [];
        $params = [];
        foreach(['noSJ','tanggal','penerima','pemasukanKantor','biayaSopir','statusPemasukan','statusSopir','sopir'] as $f){
            if(array_key_exists($f, $body)){
                $fields[] = "$f = ?";
                $params[] = $body[$f];
            }
        }
        if(count($fields) === 0){ http_response_code(400); echo json_encode(['error'=>'no fields']); exit; }
        $params[] = $id;
        $sql = 'UPDATE surat_jalan SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['ok'=>true]);
        exit;
    }
    if($method === 'DELETE' && $id){
        $stmt = $pdo->prepare('DELETE FROM surat_jalan WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['ok'=>true]);
        exit;
    }
}

if($resource === 'invoices'){
    if($method === 'GET'){
        if($id){
            $stmt = $pdo->prepare('SELECT * FROM invoices WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch());
        }else{
            $stmt = $pdo->query('SELECT * FROM invoices ORDER BY tanggal DESC, id DESC');
            echo json_encode($stmt->fetchAll());
        }
        exit;
    }
    if($method === 'POST'){
        $sql = 'INSERT INTO invoices (nomor, tanggal, customer, total, status, data) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $body['nomor'] ?? '',
            $body['tanggal'] ?? date('Y-m-d'),
            $body['customer'] ?? '',
            $body['total'] ?? 0,
            $body['status'] ?? 'Belum Lunas',
            isset($body['data']) ? json_encode($body['data']) : null
        ]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        exit;
    }
    if($method === 'PUT' && $id){
        $fields = [];
        $params = [];
        foreach(['nomor','tanggal','customer','total','status','data'] as $f){
            if(array_key_exists($f, $body)){
                if($f === 'data') $params[] = json_encode($body['data']); else $params[] = $body[$f];
                $fields[] = "$f = ?";
            }
        }
        if(count($fields) === 0){ http_response_code(400); echo json_encode(['error'=>'no fields']); exit; }
        $params[] = $id;
        $sql = 'UPDATE invoices SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['ok'=>true]);
        exit;
    }
    if($method === 'DELETE' && $id){
        $stmt = $pdo->prepare('DELETE FROM invoices WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['ok'=>true]);
        exit;
    }
}

// Fallback
http_response_code(404);
echo json_encode(['error' => 'Not Found']);
