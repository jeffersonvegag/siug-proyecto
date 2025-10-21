# Configuración de Base de Datos y Ruta Base

El archivo `config/database.php` contiene la configuración de acceso a la base de datos SQL Server.

## Configuración de `base_url`

El archivo `config/app.php` gestiona la ruta base de todo el módulo mediante el parámetro `'base_url'`.
'base_url' => '/convenio_UG/public' cambiarlo de acuedo a al situcaion este caso es convenio_UG(el nombre de la carpeta)
ya que est manera la ruta de todo este  modulo

## Configuración de la Base de Datos (`config/database.php`)
**En desarrollo local:**
- Si se usa autenticación integrada de Windows, deja los campos 'usuario' y 'clave' en blanco.

**En producción:**
- El administrador del servidor debe editar 'usuario' y 'clave' según las credenciales asignadas para la base de datos SQL Server.
- También debe cambiar el valor de 'Server' y 'Database' si corresponde.

**Ejemplo:**
    'dsn' => 'sqlsrv:Server=SERVIDOR_PRODUCCION;Database=NOMBRE_BASE',
    'usuario' => 'usuario_sql',
    'clave'   => 'clave_sql',

## Organización de archivos

- `writable/documentos_convenios_generados/`: Almacena todos los documentos DOCX generados desde los formularios.
- `public/uploads_logo_contraparte/`: Contiene los logos subidos por las contrapartes para ser insertados en los convenios.

BD
-- Crear la base de datos
CREATE DATABASE convenios_ug_bd;
GO

-- Usar la base de datos creada
USE convenios_ug_bd;
GO

-- Crear la tabla convenios_subidos
CREATE TABLE convenios_subidos (
    id_con_sub                INT IDENTITY(1,1) PRIMARY KEY,
    nombre_original_con_sub   VARCHAR(255) NOT NULL,   -- nombre del archivo subido por el usuario
    nombre_guardado_con_sub   VARCHAR(255) NOT NULL,   -- nombre físico en el servidor
    tipo_con_sub              VARCHAR(100) NOT NULL,   -- 'WORD' o 'PDF'
    ruta_archivo_con_sub      VARCHAR(255) NOT NULL,   -- ruta absoluta o relativa
    fecha_subida_con_sub      DATETIME DEFAULT GETDATE(),
    descripcion_con_sub       VARCHAR(255) NULL        -- descripción opcional
);
GO

-- Crear la tabla documentos_generados
CREATE TABLE documentos_generados (
    id_doc_gen INT IDENTITY(1,1) PRIMARY KEY,
    codigo_convenio_dog_gen UNIQUEIDENTIFIER NULL,
    nombre_archivo_doc_gen VARCHAR(255) NULL,
    ruta_archivo_doc_gen VARCHAR(255) NOT NULL,
    tipo_documento_doc_gen VARCHAR(100) NULL CHECK (tipo_documento_doc_gen IN ('WORD', 'PDF')),
    fecha_generacion_doc_gen DATETIME DEFAULT GETDATE()
);
GO

-- Crear la tabla datos_convenios
CREATE TABLE datos_convenios (
    id_dat_con INT IDENTITY(1,1) PRIMARY KEY,
    id_doc_gen INT, -- clave foránea a documentos_generados
    tipo_convenio_dat_con VARCHAR(50),
    fecha_creacion_dat_con DATETIME DEFAULT GETDATE(),
    ruta_archivo_formulario_dat_con VARCHAR(255) NOT NULL, -- Ruta del JSON/TXT con datos del formulario
    CONSTRAINT fk_doc_gen FOREIGN KEY (id_doc_gen) REFERENCES documentos_generados(id_doc_gen) ON DELETE CASCADE
);
GO
