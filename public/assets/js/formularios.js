// Lógica para alternar campos al seleccionar un checkbox de programa
function toggleFields(checkbox, sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.style.display = checkbox.checked ? 'block' : 'none';
    }
}

const areaSelect = document.getElementById('areaC');
const subareaSelect = document.getElementById('subarea');
const subareaEspecificaSelect = document.getElementById('subarea_especifica'); // NUEVA variable para el select específico

if (areaSelect && subareaSelect && subareaEspecificaSelect) { // Asegurarse de que todos los selects existan
    // Lógica para Área -> Subárea (ya existe)
    areaSelect.addEventListener('change', function () {
        const areaId = this.value;
        subareaSelect.innerHTML = '<option value="">Seleccione</option>';
        subareaEspecificaSelect.innerHTML = '<option value="">Seleccione</option>'; // Limpiar el tercer select también

        if (subareas[areaId]) {
            subareas[areaId].forEach(function (subarea) {
                const option = document.createElement('option');
                option.value = subarea.id;
                option.textContent = subarea.name;
                subareaSelect.appendChild(option);
            });
        }
    });

    //Subárea -> Subárea Específica
    subareaSelect.addEventListener('change', function () {
        const selectedSubareaId = this.value;
        subareaEspecificaSelect.innerHTML = '<option value="">Seleccione</option>'; // Limpiar el tercer select

        // Usar la nueva variable 'especificas'
        if (especificas[selectedSubareaId]) {
            especificas[selectedSubareaId].forEach(function (especifica) {
                const option = document.createElement('option');
                option.value = especifica.id;
                option.textContent = especifica.name;
                subareaEspecificaSelect.appendChild(option);
            });
        }
    });
}


const ejeSelect = document.getElementById('eje');
const objSelect = document.getElementById('objetivo_nacional');
if (ejeSelect) {
    ejeSelect.addEventListener('change', function () {
        const ejeId = this.value;
        objSelect.innerHTML = '<option value="">Seleccione</option>';

        if (obj_nac[ejeId]) {
            obj_nac[ejeId].forEach(function (obj) {
                const option = document.createElement('option');
                option.value = obj.id;
                option.textContent = obj.name;
                objSelect.appendChild(option);
            });
        }
    });
}

const dominioSelect = document.getElementById('dominios');
const lineaSelect = document.getElementById('lineas');
if (dominioSelect) {
    dominioSelect.addEventListener('change', function () {
        const dominioId = this.value;
        lineaSelect.innerHTML = '<option value="">Seleccione</option>';
        if (lineas[dominioId]) {
            lineas[dominioId].forEach(function (linea) {
                const option = document.createElement('option');
                option.value = linea.id;
                option.textContent = linea.name;
                lineaSelect.appendChild(option);
            });
        }
    });
}



let currentStep = 0;
const form = document.getElementById('multiStepForm');
const fieldsets = form.querySelectorAll('fieldset');

function showStep(stepIndex) {
    fieldsets.forEach((fieldset, index) => {
        if (index === stepIndex) {
            fieldset.classList.add('active');
        } else {
            fieldset.classList.remove('active');
        }
    });

    // Actualiza los botones de navegación
    const prevButton = fieldsets[currentStep].querySelector('.navigation-buttons button:first-child');
    const nextButton = fieldsets[currentStep].querySelector('.navigation-buttons button:last-child');

    if (prevButton) {
        prevButton.disabled = currentStep === 0;
    }
    if (nextButton) {
        nextButton.disabled = currentStep === fieldsets.length - 1 && nextButton.type !== 'submit';
    }
}

function nextStep() {
    // Puedes añadir lógica de validación aquí antes de pasar al siguiente paso
    // Por ejemplo: if (!validateCurrentStep()) return;

    if (currentStep < fieldsets.length - 1) {
        currentStep++;
        showStep(currentStep);
    }
}

function prevStep() {
    if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
    }
}


// Variable para mantener un índice único para cada nueva fila, si lo necesitas
// ... (código anterior de formularios.js) ...

// Variable para mantener un índice único para cada nueva fila
let rowIndex = 0;

function addRow() {
    const tableBody = document.getElementById('cuerpoTablaFacultades');
    // Obtener la etiqueta <template>
    const templateElement = document.getElementById('facultyRowTemplate');

    if (!tableBody || !templateElement) {
        console.error('Elementos de tabla o plantilla no encontrados (cuerpoTablaFacultades o facultyRowTemplate).');
        return;
    }

    // Clonar el contenido de la plantilla (que es el <tr>)
    const newRow = templateElement.content.cloneNode(true).firstElementChild;

    // Actualizar los IDs de los elementos dentro de la fila clonada para que sean únicos
    newRow.querySelectorAll('[id]').forEach(el => {
        el.id = el.id + '_' + rowIndex;
    });

    // Añadir un botón para eliminar la fila
    const deleteCell = document.createElement('td');
    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Eliminar';
    deleteButton.type = 'button';
    deleteButton.className = 'btn btn-danger btn-sm';
    deleteButton.onclick = function() {
        newRow.remove();
    };
    deleteCell.appendChild(deleteButton);
    newRow.appendChild(deleteCell);

    tableBody.appendChild(newRow); // Añadir la nueva fila al cuerpo de la tabla

    // Adjuntar el evento onchange al select de facultad de la NUEVA fila
    const selectFacultad = newRow.querySelector('select[name="facultad[]"]');
    if (selectFacultad) {
        selectFacultad.addEventListener('change', function() {
            handleFacultadChange(this);
        });
    }

    rowIndex++; // Incrementar el índice para la próxima fila
}

// Función para manejar el cambio de facultad y cargar carreras (ya modificada para tu API)
function handleFacultadChange(selectFacultadElement) {
    const idFacultad = selectFacultadElement.value;
    const row = selectFacultadElement.closest('tr');
    const selectCarreraElement = row.querySelector('select[name="carrera[]"]');

    selectCarreraElement.innerHTML = '<option value="">Cargando Carreras...</option>';

    if (idFacultad) {
        fetch(`${baseUrl}/index.php?c=Propuestas&m=getCarrerasPorFacultadAjax&idFacultad=${idFacultad}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                selectCarreraElement.innerHTML = '<option value="">Seleccione una Carrera</option>';
                if (data.success && data.carreras && data.carreras.length > 0) {
                    data.carreras.forEach(carrera => {
                        const option = document.createElement('option');
                        option.value = carrera.CodCarrera;
                        option.textContent = carrera.Carrera;
                        selectCarreraElement.appendChild(option);
                    });
                } else {
                    console.warn('No se encontraron carreras para esta facultad o error en la API:', data.Mensaje || 'Respuesta inesperada');
                    selectCarreraElement.innerHTML = '<option value="">No hay carreras disponibles</option>';
                }
            })
            .catch(error => {
                console.error('Error en la solicitud AJAX para carreras:', error);
                selectCarreraElement.innerHTML = '<option value="">Error al cargar carreras</option>';
            });
    } else {
        selectCarreraElement.innerHTML = '<option value="">Seleccione una Carrera</option>';
    }
}

// Inicializa mostrando el primer paso y agrega la primera fila al cargar el DOM
document.addEventListener('DOMContentLoaded', () => {
    showStep(currentStep);
    addRow(); // Esto agregará la primera fila al cargar la página
});

// Asigna la función addRow al botón "Agregar"
const addRowButton = document.getElementById('addRowBtn');
if (addRowButton) {
    addRowButton.addEventListener('click', addRow);
}



function generarFilasCiclo(cantidad) {
    var $tbody = $("#form_cinco #estudiantes_ciclo tbody");
    $tbody.empty();


    for (var i = 1; i <= cantidad; i++) {
        var fila = '<tr>' +
            '<td>' + i + '</td>' +
            '<td><input type="number" class="num_estudiantes" value="0"></td>' +
            '<td><input type="number" class="num_discapacidad" value="0"></td>' +
            '</tr>';
        $tbody.append(fila);
    }


    $tbody.find('input').on('input', function () {
        recalcularTotales();
    });


    recalcularTotales();
}


function recalcularTotales() {
    var totalEstudiantes = 0;
    var totalDiscapacidad = 0;


    $("#form_cinco #estudiantes_ciclo tbody tr").each(function () {
        var est = parseFloat($(this).find('.num_estudiantes').val()) || 0;
        var disc = parseFloat($(this).find('.num_discapacidad').val()) || 0;
        totalEstudiantes += est;
        totalDiscapacidad += disc;
    });


    $("#form_cinco #total_estudiantes").val(totalEstudiantes);
    $("#form_cinco #total_discapacidad").val(totalDiscapacidad);


    $("#form_cinco #total_intervienen").val(totalEstudiantes + totalDiscapacidad);
}


// Elimina la llave de cierre '}' si tu archivo formularios.js no está envuelto en una función
// La llave de cierre '}' al final de tu archivo `formularios.js` que me enviaste antes
// parece estar de más si no hay una función envolvente. Si tu editor de código te da un error de sintaxis, elimínala.
// }