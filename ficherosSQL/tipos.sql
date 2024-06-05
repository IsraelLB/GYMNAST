
CREATE OR REPLACE TYPE Tipo_Cliente AS OBJECT (
    id_Cliente NUMBER,
    nombre_Cliente VARCHAR2(50),
    apellido_Cliente VARCHAR2(50),
    telefono_Cliente VARCHAR2(50),
    correo_Cliente VARCHAR2(50),
    direccion_Cliente VARCHAR2(50),
    fecha_nacimiento_Cliente DATE,
    contrasena_Cliente VARCHAR2(250),
    Apuntado REF Tipo_Gimnasio,
    Entrenado REF Tipo_Entrenador_Personal
);
/
CREATE OR REPLACE TYPE Tipo_Socios AS TABLE OF REF Tipo_Cliente;
/
CREATE OR REPLACE TYPE Tipo_Entrenados AS TABLE OF REF Tipo_Cliente;
/


CREATE OR REPLACE TYPE Tipo_Gimnasio AS OBJECT (
    id_Gimnasio NUMBER,
    nombre_Gimnasio VARCHAR2(50),
    direccion_Gimnasio VARCHAR2(50),
    telefono_Gimnasio VARCHAR2(50),
    correo_Gimnasio VARCHAR2(50),
    Lista_de_Clientes Tipo_Socios,
    Lista_de_Entrenadores Tipo_Entrenadores,
    Lista_de_Monitores Tipo_Monitores,
    Tiene_Maquinas Tipo_Lista_Ref_Maquinas
);
/

CREATE OR REPLACE TYPE Tipo_Empleado AS OBJECT (
    id_Empleado NUMBER,
    nombre_Empleado VARCHAR2(50),
    apellido_Empleado VARCHAR2(50),
    telefono_Empleado VARCHAR2(50),
    correo_Empleado VARCHAR2(50),
    direccion_Empleado VARCHAR2(50),
    fecha_nacimiento_Empleado DATE,
    contrasena_Empleado VARCHAR2(250),
    Trabaja REF Tipo_Gimnasio
)NOT FINAL;
/
CREATE OR REPLACE TYPE Tipo_Maquinas AS OBJECT (
    id_maquina NUMBER,
    nombre_maquina VARCHAR2(50),
    descripcion_maquina VARCHAR2(50),
    EstaEn REF Tipo_Gimnasio,
    imagen BLOB
);
/
CREATE OR REPLACE TYPE Tipo_Lista_Ref_Maquinas AS TABLE OF REF Tipo_Maquinas;
/

CREATE OR REPLACE TYPE Tipo_Entrenador_Personal UNDER Tipo_Empleado (
    Titulacion VARCHAR2(50),
    Experiencia VARCHAR2(50),
    EntrenaA Tipo_Entrenados,
    imagen BLOB
);
/
CREATE TYPE Tipo_Entrenadores AS TABLE OF REF Tipo_Entrenador_Personal;
/
CREATE OR REPLACE TYPE Tipo_Monitor UNDER Tipo_Empleado (
    Turno VARCHAR2(50)
);
/
CREATE TYPE Tipo_Monitores AS TABLE OF REF Tipo_Monitor;
/