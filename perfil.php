<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - Bootstrap</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #3b234a;
            --primary-hover: #2a1a35;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .profile-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            padding: 2rem 0;
        }
        
        .profile-image-container {
            position: relative;
            display: inline-block;
        }
        
        .profile-image {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .profile-image:hover {
            transform: scale(1.05);
        }
        
        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .image-upload-overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border: 3px solid white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .image-upload-overlay:hover {
            background-color: var(--primary-hover);
            transform: scale(1.1);
        }
        
        .camera-placeholder {
            font-size: 3rem;
            color: #6c757d;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(59, 35, 74, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .edit-indicator {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            background-color: #28a745;
            border-radius: 50%;
            border: 2px solid white;
            display: none;
        }
        
        .edit-mode .edit-indicator {
            display: block;
        }
        
        .readonly-field {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            cursor: not-allowed;
        }
        
        .profile-body {
            padding: 2rem;
        }
        
        @media (max-width: 768px) {
            .profile-image {
                width: 120px;
                height: 120px;
            }
            
            .profile-header {
                padding: 1.5rem 0;
            }
            
            .profile-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Success Alert -->
        <div class="alert alert-success alert-dismissible fade" role="alert" id="successAlert">
            <i class="bi bi-check-circle-fill me-2"></i>
            Los cambios han sido guardados exitosamente
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        
        <div class="profile-container">
            <!-- Profile Header -->
            <div class="profile-header text-center">
                <div class="profile-image-container">
                    <div class="profile-image bg-light" onclick="document.getElementById('profileImageInput').click()">
                        <i class="bi bi-camera camera-placeholder" id="cameraIcon"></i>
                        <img id="profileImg" style="display: none;" alt="Perfil">
                        <div class="image-upload-overlay">
                            <i class="bi bi-pencil"></i>
                        </div>
                    </div>
                    <input type="file" id="profileImageInput" class="d-none" accept="image/*">
                </div>
                <h3 class="mt-3 mb-1" id="displayName">Perfil de Usuario</h3>
                <p class="mb-0 opacity-75" id="displayInfo">Información personal</p>
            </div>
            
            <!-- Profile Body -->
            <div class="profile-body">
                <form id="profileForm">
                    <div class="row g-3">
                        <!-- Nombres y Apellidos -->
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="nombres" class="form-label fw-bold">
                                    <i class="bi bi-person me-2"></i>Nombres
                                </label>
                                <input type="text" class="form-control readonly-field" id="nombres" 
                                       name="nombres" placeholder="Nombres" readonly>
                                <div class="edit-indicator"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="apellidos" class="form-label fw-bold">
                                    <i class="bi bi-person me-2"></i>Apellidos
                                </label>
                                <input type="text" class="form-control readonly-field" id="apellidos" 
                                       name="apellidos" placeholder="Apellidos" readonly>
                                <div class="edit-indicator"></div>
                            </div>
                        </div>
                        
                        <!-- Dirección -->
                        <div class="col-12">
                            <div class="form-group position-relative">
                                <label for="direccion" class="form-label fw-bold">
                                    <i class="bi bi-geo-alt me-2"></i>Dirección
                                </label>
                                <input type="text" class="form-control readonly-field" id="direccion" 
                                       name="direccion" placeholder="Dirección" readonly>
                                <div class="edit-indicator"></div>
                            </div>
                        </div>
                        
                        <!-- Teléfono -->
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="telefono" class="form-label fw-bold">
                                    <i class="bi bi-telephone me-2"></i>Teléfono
                                </label>
                                <input type="tel" class="form-control readonly-field" id="telefono" 
                                       name="telefono" placeholder="Teléfono" readonly>
                                <div class="edit-indicator"></div>
                            </div>
                        </div>
                        
                        <!-- Edad -->
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="edad" class="form-label fw-bold">
                                    <i class="bi bi-calendar me-2"></i>Edad
                                </label>
                                <input type="number" class="form-control readonly-field" id="edad" 
                                       name="edad" placeholder="Edad" readonly min="1" max="120">
                                <div class="edit-indicator"></div>
                            </div>
                        </div>
                        
                        <!-- País y Departamento -->
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="pais" class="form-label fw-bold">
                                    <i class="bi bi-flag me-2"></i>País
                                </label>
                                <select class="form-select readonly-field" id="pais" name="pais" disabled>
                                    <option value="">Seleccionar país</option>
                                    <option value="colombia">Colombia</option>
                                    <option value="argentina">Argentina</option>
                                    <option value="brasil">Brasil</option>
                                    <option value="chile">Chile</option>
                                    <option value="ecuador">Ecuador</option>
                                    <option value="peru">Perú</option>
                                    <option value="uruguay">Uruguay</option>
                                    <option value="venezuela">Venezuela</option>
                                    <option value="mexico">México</option>
                                    <option value="españa">España</option>
                                    <option value="estados_unidos">Estados Unidos</option>
                                    <option value="otro">Otro</option>
                                </select>
                                <div class="edit-indicator"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="departamento" class="form-label fw-bold">
                                    <i class="bi bi-map me-2"></i>Departamento
                                </label>
                                <select class="form-select readonly-field" id="departamento" name="departamento" disabled>
                                    <option value="">Seleccionar departamento</option>
                                    <option value="amazonas">Amazonas</option>
                                    <option value="antioquia">Antioquia</option>
                                    <option value="arauca">Arauca</option>
                                    <option value="atlantico">Atlántico</option>
                                    <option value="bolivar">Bolívar</option>
                                    <option value="boyaca">Boyacá</option>
                                    <option value="caldas">Caldas</option>
                                    <option value="cundinamarca">Cundinamarca</option>
                                    <option value="valle_cauca">Valle del Cauca</option>
                                    <option value="santander">Santander</option>
                                    <option value="norte_santander">Norte de Santander</option>
                                </select>
                                <div class="edit-indicator"></div>
                            </div>
                        </div>
                        
                        <!-- Estado Civil -->
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="estado_civil" class="form-label fw-bold">
                                    <i class="bi bi-heart me-2"></i>Estado Civil
                                </label>
                                <select class="form-select readonly-field" id="estado_civil" name="estado_civil" disabled>
                                    <option value="">Estado civil</option>
                                    <option value="soltero">Soltero/a</option>
                                    <option value="casado">Casado/a</option>
                                    <option value="union_libre">Unión libre</option>
                                    <option value="divorciado">Divorciado/a</option>
                                    <option value="viudo">Viudo/a</option>
                                    <option value="separado">Separado/a</option>
                                </select>
                                <div class="edit-indicator"></div>
                            </div>
                        </div>
                        
                        <!-- Descripción -->
                        <div class="col-12">
                            <div class="form-group position-relative">
                                <label for="descripcion" class="form-label fw-bold">
                                    <i class="bi bi-card-text me-2"></i>Descripción
                                </label>
                                <textarea class="form-control readonly-field" id="descripcion" 
                                          name="descripcion" rows="4" placeholder="Descripción personal" readonly></textarea>
                                <div class="edit-indicator"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
                        <button type="button" class="btn btn-outline-primary btn-lg" id="editBtn">
                            <i class="bi bi-pencil me-2"></i>Editar Perfil
                        </button>
                        
                        <button type="button" class="btn btn-success btn-lg d-none" id="saveBtn">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="saveSpinner"></span>
                            <i class="bi bi-check-lg me-2"></i>Guardar Cambios
                        </button>
                        
                        <button type="button" class="btn btn-outline-secondary btn-lg d-none" id="cancelBtn">
                            <i class="bi bi-x-lg me-2"></i>Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Datos simulados de perfil
        let profileData = {
            nombres: 'Juan Carlos',
            apellidos: 'Pérez García',
            direccion: 'Calle 123 #45-67, Bogotá D.C.',
            telefono: '300 123 4567',
            pais: 'colombia',
            departamento: 'cundinamarca',
            edad: '25',
            estado_civil: 'soltero',
            descripcion: 'Joven profesional en desarrollo de software, apasionado por la tecnología y el aprendizaje continuo. Tiene experiencia en desarrollo web y móvil.',
            profileImage: null
        };

        // Referencias a elementos
        const form = document.getElementById('profileForm');
        const editBtn = document.getElementById('editBtn');
        const saveBtn = document.getElementById('saveBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const successAlert = document.getElementById('successAlert');
        const profileImageInput = document.getElementById('profileImageInput');
        const profileImg = document.getElementById('profileImg');
        const cameraIcon = document.getElementById('cameraIcon');
        const saveSpinner = document.getElementById('saveSpinner');
        const displayName = document.getElementById('displayName');
        const displayInfo = document.getElementById('displayInfo');

        let isEditing = false;
        let originalData = {};

        // Inicializar
        document.addEventListener('DOMContentLoaded', function () {
            loadProfileData();
        });

        // Event listeners
        editBtn.addEventListener('click', enableEditMode);
        saveBtn.addEventListener('click', saveChanges);
        cancelBtn.addEventListener('click', cancelEdit);
        profileImageInput.addEventListener('change', handleImageUpload);

        function loadProfileData() {
            console.log('Cargando datos del perfil...');
            
            // Llenar campos
            Object.keys(profileData).forEach(key => {
                const element = document.getElementById(key);
                if (element && profileData[key]) {
                    element.value = profileData[key];
                }
            });

            // Actualizar header
            updateProfileHeader();

            // Cargar imagen si existe
            if (profileData.profileImage) {
                profileImg.src = profileData.profileImage;
                profileImg.style.display = 'block';
                cameraIcon.style.display = 'none';
            }
        }

        function updateProfileHeader() {
            const fullName = `${profileData.nombres || ''} ${profileData.apellidos || ''}`.trim();
            displayName.textContent = fullName || 'Perfil de Usuario';
            
            const info = [];
            if (profileData.edad) info.push(`${profileData.edad} años`);
            if (profileData.pais) info.push(profileData.pais.charAt(0).toUpperCase() + profileData.pais.slice(1));
            
            displayInfo.textContent = info.length > 0 ? info.join(' • ') : 'Información personal';
        }

        function enableEditMode() {
            isEditing = true;
            originalData = { ...profileData };

            // Habilitar campos
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type !== 'file') {
                    input.removeAttribute('readonly');
                    input.removeAttribute('disabled');
                    input.classList.remove('readonly-field');
                    input.classList.add('form-control', 'form-select');
                    input.parentNode.classList.add('edit-mode');
                }
            });

            // Cambiar botones
            editBtn.classList.add('d-none');
            saveBtn.classList.remove('d-none');
            cancelBtn.classList.remove('d-none');

            console.log('Modo edición activado');
        }

        function saveChanges() {
            // Mostrar spinner
            saveSpinner.classList.remove('d-none');
            saveBtn.disabled = true;

            if (!validateForm()) {
                saveSpinner.classList.add('d-none');
                saveBtn.disabled = false;
                return;
            }

            // Recopilar datos
            const formData = new FormData(form);
            const updatedData = {};

            for (let [key, value] of formData.entries()) {
                updatedData[key] = value;
            }

            // Simular guardado
            setTimeout(() => {
                console.log('Guardando en BD:', updatedData);
                
                profileData = { ...profileData, ...updatedData };
                updateProfileHeader();
                disableEditMode();
                showSuccessMessage();

                saveSpinner.classList.add('d-none');
                saveBtn.disabled = false;
            }, 2000);
        }

        function cancelEdit() {
            const modal = new bootstrap.Modal(document.createElement('div'));
            
            if (confirm('¿Estás seguro de cancelar los cambios?')) {
                // Restaurar datos
                Object.keys(originalData).forEach(key => {
                    const element = document.getElementById(key);
                    if (element) {
                        element.value = originalData[key] || '';
                    }
                });

                disableEditMode();
                console.log('Cambios cancelados');
            }
        }

        function disableEditMode() {
            isEditing = false;

            // Deshabilitar campos
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type !== 'file') {
                    input.setAttribute('readonly', 'true');
                    if (input.tagName === 'SELECT') {
                        input.setAttribute('disabled', 'true');
                    }
                    input.classList.add('readonly-field');
                    input.parentNode.classList.remove('edit-mode');
                }
            });

            // Cambiar botones
            editBtn.classList.remove('d-none');
            saveBtn.classList.add('d-none');
            cancelBtn.classList.add('d-none');
        }

        function validateForm() {
            const requiredFields = ['nombres', 'apellidos', 'direccion', 'telefono', 'edad'];

            for (let field of requiredFields) {
                const element = document.getElementById(field);
                if (!element.value.trim()) {
                    showAlert(`El campo ${field} es obligatorio`, 'danger');
                    element.focus();
                    return false;
                }
            }

            const edad = parseInt(document.getElementById('edad').value);
            if (edad < 1 || edad > 120) {
                showAlert('La edad debe estar entre 1 y 120 años', 'danger');
                return false;
            }

            return true;
        }

        function handleImageUpload(e) {
            const file = e.target.files[0];
            if (file) {
                if (!file.type.startsWith('image/')) {
                    showAlert('Selecciona una imagen válida', 'warning');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    showAlert('La imagen debe ser menor a 5MB', 'warning');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    profileImg.src = e.target.result;
                    profileImg.style.display = 'block';
                    cameraIcon.style.display = 'none';
                    profileData.profileImage = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function showSuccessMessage() {
            successAlert.classList.add('show');
            setTimeout(() => {
                successAlert.classList.remove('show');
            }, 5000);
        }

        function showAlert(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 4000);
        }

        // Funciones para integración con backend
        function loadFromDatabase(userId) {
            console.log(`Cargando perfil del usuario: ${userId}`);
            // Implementar llamada al API
        }

        function saveToDatabase(data) {
            console.log('Enviando al servidor:', data);
            // Implementar llamada al API
            return fetch('/api/profile/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
        }
    </script>
</body>
</html>