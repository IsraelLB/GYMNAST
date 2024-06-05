CREATE TABLE Cliente OF Tipo_Cliente (
    CONSTRAINT PK_Cliente PRIMARY KEY (id_Cliente),
    nombre_Cliente NOT NULL,
    apellido_Cliente NOT NULL,
    telefono_Cliente NOT NULL,
    correo_Cliente NOT NULL,
    direccion_Cliente NOT NULL,
    fecha_nacimiento_Cliente NOT NULL,
    Apuntado NOT NULL
);
/
CREATE TABLE Gimnasio OF Tipo_Gimnasio (
    CONSTRAINT PK_Gimnasio PRIMARY KEY (id_Gimnasio),
    nombre_Gimnasio NOT NULL,
    direccion_Gimnasio NOT NULL,
    telefono_Gimnasio NOT NULL,
    correo_Gimnasio NOT NULL
)
NESTED TABLE Lista_de_Clientes STORE AS Socios,
NESTED TABLE Lista_de_Entrenadores STORE AS Entrenadores,
NESTED TABLE Lista_de_Monitores STORE AS Monitores,
NESTED TABLE Tiene_Maquinas STORE AS Maquinas;
/
CREATE TABLE Empleado OF Tipo_Empleado (
    CONSTRAINT PK_Empleado PRIMARY KEY (id_Empleado),
    nombre_Empleado NOT NULL,
    apellido_Empleado NOT NULL,
    telefono_Empleado NOT NULL,
    correo_Empleado NOT NULL,
    direccion_Empleado NOT NULL,
    fecha_nacimiento_Empleado NOT NULL,
    Trabaja NOT NULL
);
/
CREATE TABLE MaquinasTabla OF Tipo_maquinas (
    CONSTRAINT PK_Maquinas PRIMARY KEY (id_maquina),
    nombre_maquina NOT NULL,
    descripcion_maquina NOT NULL
);
/
CREATE TABLE Tabla_Entrenadores OF Tipo_Entrenador_Personal (
    nombre_Empleado NOT NULL,
    apellido_Empleado NOT NULL,
    telefono_Empleado NOT NULL,
    correo_Empleado NOT NULL,
    direccion_Empleado NOT NULL,
    fecha_nacimiento_Empleado NOT NULL,
    Trabaja NOT NULL,
    Titulacion NOT NULL,
    Experiencia NOT NULL
)
NESTED TABLE EntrenaA STORE AS Entrenados;
/
CREATE TABLE Tabla_Monitores OF Tipo_Monitor (
    nombre_Empleado NOT NULL,
    apellido_Empleado NOT NULL,
    telefono_Empleado NOT NULL,
    correo_Empleado NOT NULL,
    direccion_Empleado NOT NULL,
    fecha_nacimiento_Empleado NOT NULL,
    Trabaja NOT NULL,
    Turno NOT NULL
);
/