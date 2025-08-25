<?php
// profile_api.php - API para manejar operaciones del perfil
require_once 'data-connection.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
$action = $request[0] ?? '';

try {
    switch ($method) {
        case 'GET':
            if ($action === 'profile' && isset($_GET['id'])) {
                getProfile($db, $_GET['id']);
            } else {
                sendJsonResponse(['error' => 'Endpoint no encontrado'], 404);
            }
            break;
            
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            if ($action === 'profile') {
                if (isset($input['id']) && $input['id'] > 0) {
                    updateProfile($db, $input);
                } else {
                    createProfile($db, $input);
                }
            } else {
                sendJsonResponse(['error' => 'Endpoint no encontrado'], 404);
            }
            break;
            
        default:
            sendJsonResponse(['error' => 'Método no permitido'], 405);
            break;
    }
} catch (Exception $e) {
    sendJsonResponse(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
}

function getProfile($db, $userId) {
    try {
        $query = "SELECT id, nombres, apellidos, direccion, telefono, pais, departamento, 
                         estado_civil, foto, edad, descripcion, acepta_terminos, creado_en 
                  FROM usuarios WHERE id = :id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Convertir foto a base64 si existe
            if (!empty($user['foto']) && file_exists($user['foto'])) {
                $imageData = base64_encode(file_get_contents($user['foto']));
                $imageType = pathinfo($user['foto'], PATHINFO_EXTENSION);
                $user['profileImage'] = "data:image/$imageType;base64,$imageData";
            } else {
                $user['profileImage'] = null;
            }
            
            sendJsonResponse([
                'success' => true,
                'data' => $user
            ]);
        } else {
            sendJsonResponse(['error' => 'Usuario no encontrado'], 404);
        }
    } catch (Exception $e) {
        sendJsonResponse(['error' => 'Error al obtener el perfil: ' . $e->getMessage()], 500);
    }
}

function createProfile($db, $data) {
    // Validar datos
    $errors = validateProfileData($data);
    if (!empty($errors)) {
        sendJsonResponse(['error' => 'Errores de validación', 'details' => $errors], 400);
    }
    
    try {
        $query = "INSERT INTO usuarios (nombres, apellidos, direccion, telefono, pais, 
                                      departamento, estado_civil, foto, edad, descripcion, acepta_terminos) 
                  VALUES (:nombres, :apellidos, :direccion, :telefono, :pais, 
                          :departamento, :estado_civil, :foto, :edad, :descripcion, :acepta_terminos)";
        
        $stmt = $db->prepare($query);
        
        // Manejar imagen si se proporciona
        $fotoPath = null;
        if (!empty($data['profileImage'])) {
            $fotoPath = saveProfileImage($data['profileImage'], null);
        }
        
        $stmt->bindParam(':nombres', $data['nombres']);
        $stmt->bindParam(':apellidos', $data['apellidos']);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':pais', $data['pais']);
        $stmt->bindParam(':departamento', $data['departamento']);
        $stmt->bindParam(':estado_civil', $data['estado_civil']);
        $stmt->bindParam(':foto', $fotoPath);
        $stmt->bindParam(':edad', $data['edad']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':acepta_terminos', $data['acepta_terminos'] ?? 1);
        
        $stmt->execute();
        
        $userId = $db->lastInsertId();
        
        sendJsonResponse([
            'success' => true,
            'message' => 'Perfil creado exitosamente',
            'user_id' => $userId
        ]);
        
    } catch (Exception $e) {
        sendJsonResponse(['error' => 'Error al crear el perfil: ' . $e->getMessage()], 500);
    }
}

function updateProfile($db, $data) {
    // Validar datos
    $errors = validateProfileData($data);
    if (!empty($errors)) {
        sendJsonResponse(['error' => 'Errores de validación', 'details' => $errors], 400);
    }
    
    try {
        $query = "UPDATE usuarios SET 
                    nombres = :nombres, 
                    apellidos = :apellidos, 
                    direccion = :direccion, 
                    telefono = :telefono, 
                    pais = :pais, 
                    departamento = :departamento, 
                    estado_civil = :estado_civil, 
                    edad = :edad, 
                    descripcion = :descripcion";
        
        // Agregar foto a la query si se proporciona
        if (isset($data['profileImage'])) {
            $query .= ", foto = :foto";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $db->prepare($query);
        
        // Manejar imagen si se proporciona
        if (isset($data['profileImage'])) {
            $fotoPath = saveProfileImage($data['profileImage'], $data['id']);
            $stmt->bindParam(':foto', $fotoPath);
        }
        
        $stmt->bindParam(':nombres', $data['nombres']);
        $stmt->bindParam(':apellidos', $data['apellidos']);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':pais', $data['pais']);
        $stmt->bindParam(':departamento', $data['departamento']);
        $stmt->bindParam(':estado_civil', $data['estado_civil']);
        $stmt->bindParam(':edad', $data['edad']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        
        $stmt->execute();
        
        sendJsonResponse([
            'success' => true,
            'message' => 'Perfil actualizado exitosamente'
        ]);
        
    } catch (Exception $e) {
        sendJsonResponse(['error' => 'Error al actualizar el perfil: ' . $e->getMessage()], 500);
    }
}

function saveProfileImage($base64Image, $userId) {
    // Crear directorio de uploads si no existe
    $uploadDir = 'uploads/profiles/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Extraer datos de la imagen base64
    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
        $data = substr($base64Image, strpos($base64Image, ',') + 1);
        $type = strtolower($type[1]);
        
        if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
            throw new Exception('Tipo de imagen no válido');
        }
        
        $data = base64_decode($data);
        
        if ($data === false) {
            throw new Exception('Error al decodificar la imagen');
        }
        
        // Generar nombre único para la imagen
        $filename = ($userId ? "user_{$userId}_" : "temp_") . uniqid() . ".$type";
        $filepath = $uploadDir . $filename;
        
        if (file_put_contents($filepath, $data)) {
            return $filepath;
        } else {
            throw new Exception('Error al guardar la imagen');
        }
    } else {
        throw new Exception('Formato de imagen base64 no válido');
    }
}
?>