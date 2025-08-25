<?php
session_start();
include_once 'data-conection.php';

// Obtener todos los registros de jóvenes con todos los campos
$query = "SELECT id, nombres, apellidos, direccion, telefono, pais, departamento, edad, estado_civil, foto, descripcion, acepta_terminos, creado_en FROM usuarios ORDER BY id DESC";
$result = mysqli_query($connection, $query);

// Verificar si hay error en la consulta
if (!$result) {
    die("Error en la consulta: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Jóvenes</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .header-purple {
            background: linear-gradient(135deg, #3B234A, #523961);
        }
        .dropdown-toggle::after {
            display: none;
        }
        .table-container {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            display: flex;
            flex-direction: column;
            height: auto;
            min-height: 500px; /* Altura mínima para evitar que el footer suba demasiado */
        }
        .dropdown-toggle::after {
            display: none;
        }
        .table-responsive {
            border-radius: 0;
            flex-grow: 1;
            overflow-x: auto;
        }
        .btn-action {
            border: none;
            background: none;
            padding: 0.25rem 0.5rem;
        }
        .btn-add {
            background: linear-gradient(135deg, #3B234A, #523961);
            border-color: #3B234A;
            color: white;
        }
        .btn-add:hover {
            background: linear-gradient(135deg, #674D69, #886c8aff);
            border-color: #674D69;
            color: white;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .avatar-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #3B234A;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .table-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 1rem;
            margin-top: auto; /* Empuja el footer hacia abajo */
        }
        /* Estilos para mejorar la visualización en móviles */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            .table th, .table td {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header con título y botón agregar -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">Registro de Jóvenes</h1>
                        <p class="text-muted mb-0">Gestión de usuarios registrados</p>
                    </div>
                    <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#registrarModal">
                        <i class="bi bi-plus-lg me-2"></i>Agregar Nuevo
                    </button>
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="table-container">
                    <!-- Tabla -->
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Nombres</th>
                                    <th scope="col">Apellidos</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col">País</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Edad</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($row['foto']) && file_exists('uploads/' . $row['foto'])): ?>
                                                    <img src="uploads/<?php echo htmlspecialchars($row['foto']); ?>" 
                                                         alt="Avatar" class="user-avatar">
                                                <?php else: ?>
                                                    <div class="avatar-placeholder">
                                                        <?php echo strtoupper(substr($row['nombres'], 0, 1)); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['nombres']); ?></td>
                                            <td><?php echo htmlspecialchars($row['apellidos']); ?></td>
                                            <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                                            <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                            <td><?php echo htmlspecialchars($row['pais']); ?></td>
                                            <td><?php echo htmlspecialchars($row['departamento']); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($row['edad']); ?></td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-action dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="perfil.php/?id=<?php echo $row['id']; ?>">
                                                                <i class="bi bi-eye me-2"></i>Ver perfil completo
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item" onclick="editarJoven(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['nombres'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($row['apellidos'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($row['direccion'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($row['telefono'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($row['pais'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($row['departamento'], ENT_QUOTES); ?>', <?php echo $row['edad']; ?>)">
                                                                <i class="bi bi-pencil me-2"></i>Editar
                                                            </button>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="confirmarEliminar(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['nombres'] . ' ' . $row['apellidos'], ENT_QUOTES); ?>')">
                                                                <i class="bi bi-trash me-2"></i>Eliminar
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox display-1 d-block mb-3 text-black-50"></i>
                                            <h5>No hay registros disponibles</h5>
                                            <p class="mb-0">Comienza agregando un nuevo joven al sistema</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="px-4 py-3 bg-light border-top table-footer">
                        <div class="row align-items-center">
                            <div class="col">
                                <small class="text-muted">
                                    <i class="bi bi-people me-2"></i>
                                    Mostrando <?php echo mysqli_num_rows($result); ?> registro<?php echo mysqli_num_rows($result) != 1 ? 's' : ''; ?>
                                </small>
                            </div>
                            <div class="col-auto">
                                <small class="text-muted">
                                    Última actualización: <?php echo date('d/m/Y H:i'); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Joven -->
    <div class="modal fade" id="registrarModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header header-purple text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus me-2"></i>Registrar Nuevo Joven
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="procesar_registro.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombres" class="form-label">Nombres *</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos *</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección *</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono *</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edad" class="form-label">Edad *</label>
                                <input type="number" class="form-control" id="edad" name="edad" min="15" max="30" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pais" class="form-label">País *</label>
                                <select class="form-select" id="pais" name="pais" required>
                                    <option value="">Seleccionar país</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="México">México</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Chile">Chile</option>
                                    <option value="Perú">Perú</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="departamento" class="form-label">Departamento *</label>
                                <select class="form-select" id="departamento" name="departamento" required>
                                    <option value="">Seleccionar departamento</option>
                                    <option value="Cundinamarca">Cundinamarca</option>
                                    <option value="Antioquia">Antioquia</option>
                                    <option value="Valle del Cauca">Valle del Cauca</option>
                                    <option value="Atlántico">Atlántico</option>
                                    <option value="Bogotá D.C.">Bogotá D.C.</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado_civil" class="form-label">Estado Civil *</label>
                                <select class="form-select" id="estado_civil" name="estado_civil" required>
                                    <option value="">Seleccionar estado civil</option>
                                    <option value="Soltero">Soltero(a)</option>
                                    <option value="Casado">Casado(a)</option>
                                    <option value="Divorciado">Divorciado(a)</option>
                                    <option value="Viudo">Viudo(a)</option>
                                    <option value="Unión libre">Unión libre</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="foto" class="form-label">Foto de perfil</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <small class="text-muted">Formatos: JPG, PNG, GIF (máx. 2MB)</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción *</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required placeholder="Cuéntanos algo sobre ti..."></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="acepta_terminos" name="acepta_terminos" value="1" required>
                            <label class="form-check-label" for="acepta_terminos">
                                Acepto los términos y condiciones *
                            </label>
                        </div>
                        <small class="text-muted">Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-add">
                            <i class="bi bi-save me-2"></i>Guardar Registro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Joven -->
    <div class="modal fade" id="editarModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header header-purple text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil me-2"></i>Editar Joven
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="procesar_edicion.php" method="POST">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="edit_nombres" name="nombres" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="edit_apellidos" name="apellidos" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="edit_direccion" name="direccion" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="edit_telefono" name="telefono" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_edad" class="form-label">Edad</label>
                                <input type="number" class="form-control" id="edit_edad" name="edad" min="15" max="30" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_pais" class="form-label">País</label>
                                <select class="form-select" id="edit_pais" name="pais" required>
                                    <option value="">Seleccionar país</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="México">México</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Chile">Chile</option>
                                    <option value="Perú">Perú</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_departamento" class="form-label">Departamento</label>
                                <select class="form-select" id="edit_departamento" name="departamento" required>
                                    <option value="">Seleccionar departamento</option>
                                    <option value="Cundinamarca">Cundinamarca</option>
                                    <option value="Antioquia">Antioquia</option>
                                    <option value="Valle del Cauca">Valle del Cauca</option>
                                    <option value="Atlántico">Atlántico</option>
                                    <option value="Bogotá D.C.">Bogotá D.C.</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="eliminarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-person-x display-4 text-danger"></i>
                    </div>
                    <p class="text-center">¿Está seguro de que desea eliminar el registro de:</p>
                    <p class="text-center"><strong id="nombreEliminar" class="fs-5"></strong>?</p>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Atención:</strong> Esta acción no se puede deshacer y eliminará permanentemente todos los datos del usuario.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <a href="#" id="btnEliminar" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Sí, Eliminar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Función para confirmar eliminación
        function confirmarEliminar(id, nombre) {
            document.getElementById('nombreEliminar').textContent = nombre;
            document.getElementById('btnEliminar').href = 'eliminar.php?id=' + id;
            var modal = new bootstrap.Modal(document.getElementById('eliminarModal'));
            modal.show();
        }

        // Función para editar joven
        function editarJoven(id, nombres, apellidos, direccion, telefono, pais, departamento, edad) {
            // Llenar los campos del modal con los datos del joven
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombres').value = nombres;
            document.getElementById('edit_apellidos').value = apellidos;
            document.getElementById('edit_direccion').value = direccion;
            document.getElementById('edit_telefono').value = telefono;
            document.getElementById('edit_pais').value = pais;
            document.getElementById('edit_departamento').value = departamento;
            document.getElementById('edit_edad').value = edad;
            
            // Mostrar el modal
            var modal = new bootstrap.Modal(document.getElementById('editarModal'));
            modal.show();
        }

        // Mostrar alertas si existen
        <?php if (isset($_GET['success'])): ?>
            <?php if ($_GET['success'] == 'registered'): ?>
                showAlert('Joven registrado exitosamente', 'success');
            <?php elseif ($_GET['success'] == 'updated'): ?>
                showAlert('Registro actualizado exitosamente', 'success');
            <?php elseif ($_GET['success'] == 'deleted'): ?>
                showAlert('Registro eliminado exitosamente', 'success');
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            showAlert('Error: <?php echo htmlspecialchars($_GET['error']); ?>', 'error');
        <?php endif; ?>

        // Función para mostrar alertas modernas
        function showAlert(message, type) {
            const alertType = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
            
            const alertHTML = `
                <div class="alert ${alertType} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                    <i class="bi ${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', alertHTML);
            
            // Auto-remover después de 5 segundos
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        // Validación del formulario de registro
        document.getElementById('registrarModal').addEventListener('show.bs.modal', function() {
            // Resetear formulario
            this.querySelector('form').reset();
        });

        // Prevenir envío de formulario si no se acepta términos
        document.querySelector('#registrarModal form').addEventListener('submit', function(e) {
            const terminosCheck = document.getElementById('acepta_terminos');
            if (!terminosCheck.checked) {
                e.preventDefault();
                showAlert('Debe aceptar los términos y condiciones para continuar', 'error');
                return false;
            }
        });

        // Validar archivo de imagen
        document.getElementById('foto').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Validar tamaño (2MB máximo)
                if (file.size > 2 * 1024 * 1024) {
                    showAlert('El archivo es demasiado grande. Máximo 2MB permitido.', 'error');
                    this.value = '';
                    return;
                }
                
                // Validar tipo de archivo
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    showAlert('Tipo de archivo no válido. Solo se permiten JPG, PNG y GIF.', 'error');
                    this.value = '';
                    return;
                }
            }
        });
    </script>
</body>
</html>