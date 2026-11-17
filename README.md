# Sistema de Control de Préstamos de Equipo Tecnológico

**Materia:** Taller de Base de Datos  
**Proyecto Integrador Final — 2026**  
**Alumno:** George Reyes Agustín  
**Grupo:** TBD 08:00 - 09:00  

---

## 1. Propósito de la Aplicación
Esta aplicación web centraliza el registro, seguimiento y control de los préstamos y devoluciones de equipo tecnológico del Departamento de Soporte y Servicios Tecnológicos.

El sistema resuelve problemáticas operativas mediante:
- Identificación de la disponibilidad de los equipos en tiempo real.
- Prevención de préstamos duplicados y bloqueo de entrega de dispositivos que se encuentran en mantenimiento.
- Conservación de la trazabilidad sobre quién tiene un equipo, sus fechas de devolución y su historial de uso.
- Separación de accesos entre los administradores que operan los registros y los usuarios que únicamente consultan la información.

---

## 2. Tecnologías Utilizadas
De acuerdo con los requisitos y el entorno de evaluación del proyecto, el desarrollo emplea:
- **Sistema Operativo:** Linux (Debian 13, 64 bits)
- **Servidor Web:** Apache
- **Backend:** PHP 8.4
- **Base de Datos:** MariaDB 11.8.6
- **Frontend:** HTML y CSS

---

## 3. Modelo de Datos (Avance 1 - DDL)
La base de datos `db_inventario` utiliza un modelo relacional normalizado con llaves foráneas configuradas en `ON DELETE RESTRICT` para garantizar la integridad referencial y evitar la pérdida de información en cascada.

### Descripción de Tablas

#### Catálogos
Cuentan con restricciones `UNIQUE` para evitar registros duplicados.
- **`departamentos`**: Almacena las áreas institucionales a las que pertenecen los solicitantes.
- **`categorias`**: Clasifica los tipos de equipo.
- **`marcas`**: Registra los fabricantes de los dispositivos.

#### Entidades Principales
- **`usuarios_app`**: Gestiona las credenciales del personal de soporte, diferenciando entre roles de `Administrador` y `Consulta` para el control de la interfaz web.
- **`solicitantes`**: Contiene los datos del personal institucional que pide equipos, identificados por su número institucional.
- **`equipos`**: Registra cada dispositivo mediante su etiqueta única (`id_inventario`). Su estado controla la lógica del sistema (`Disponible`, `Prestado`, `Mantenimiento`, `Baja`). El estado `Baja` permite retirar un equipo conservando sus movimientos históricos.

#### Operaciones y Auditoría
- **`prestamos`**: Tabla transaccional que vincula el hardware con el solicitante y el personal en turno. Registra las fechas de salida y recepción junto con las condiciones de entrega.
- **`bitacora_mantenimiento`**: Controla las fallas reportadas y los periodos de revisión técnica.
- **`historial_estados`**: Tabla diseñada para registrar los cambios en la condición de los equipos a lo largo del tiempo, operada mediante un trigger.

### Diagrama Entidad-Relación (ERD)

```mermaid
erDiagram
    DEPARTAMENTOS {
        int id_departamento PK
        varchar(100) nombre UK
    }
    CATEGORIAS {
        int id_categoria PK
        varchar(100) nombre UK
    }
    MARCAS {
        int id_marca PK
        varchar(100) nombre UK
    }
    USUARIOS_APP {
        int id_usuario PK
        varchar(50) username UK
        varchar(255) password_hash
        enum rol "Administrador, Consulta"
    }
    SOLICITANTES {
        int id_solicitante PK
        varchar(50) numero_institucional UK
        varchar(150) nombre
        int id_departamento FK
    }
    EQUIPOS {
        varchar(50) id_inventario PK
        varchar(200) descripcion
        int id_categoria FK
        int id_marca FK
        enum condicion_actual "Disponible, Prestado, Mantenimiento, Baja"
    }
    PRESTAMOS {
        int id_prestamo PK
        varchar(50) id_inventario FK
        int id_solicitante FK
        int id_usuario_entrega FK
        int id_usuario_recepcion FK "NULL"
        datetime fecha_prestamo
        datetime fecha_prevista_devolucion
        datetime fecha_recepcion "NULL"
        text condicion_regreso "NULL"
    }
    BITACORA_MANTENIMIENTO {
        int id_mantenimiento PK
        varchar(50) id_inventario FK
        datetime fecha_reporte
        text descripcion_falla
        datetime fecha_reparacion "NULL"
    }
    HISTORIAL_ESTADOS {
        int id_historial PK
        varchar(50) id_inventario FK
        varchar(20) estado_anterior "NULL"
        varchar(20) estado_nuevo "NULL"
        datetime fecha_cambio
    }

    DEPARTAMENTOS ||--o{ SOLICITANTES : "tiene"
    CATEGORIAS ||--o{ EQUIPOS : "clasifica"
    MARCAS ||--o{ EQUIPOS : "fabrica"
    EQUIPOS ||--o{ PRESTAMOS : "se presta en"
    SOLICITANTES ||--o{ PRESTAMOS : "solicita"
    USUARIOS_APP ||--o{ PRESTAMOS : "entrega (id_usuario_entrega)"
    USUARIOS_APP |o--o{ PRESTAMOS : "recibe (id_usuario_recepcion)"
    EQUIPOS ||--o{ BITACORA_MANTENIMIENTO : "registra fallas en"
    EQUIPOS ||--o{ HISTORIAL_ESTADOS : "genera cambios en"
```

---

## 4. Estructura del Repositorio
- `base_datos.sql` — Script DDL con la creación de la base de datos, tablas, llaves primarias, foráneas y restricciones de integridad.
- `README.md` — Documentación inicial del proyecto y descripción del modelo de datos.