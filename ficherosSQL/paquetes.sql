create or replace PACKAGE paquete_cliente AS

    FUNCTION iniciar_sesion_cliente(p_correo VARCHAR2, p_contrasena VARCHAR2) RETURN NUMBER;
    FUNCTION obtener_id_entrenador(p_id_cliente NUMBER) RETURN NUMBER;
    FUNCTION obtener_id_gimnasio(p_id_cliente NUMBER) RETURN NUMBER;
    PROCEDURE Asignar_Entrenador(p_id_Cliente NUMBER, p_id_Entrenador NUMBER);
    PROCEDURE Eliminar_Cliente(p_id_Cliente NUMBER);
    PROCEDURE insertar_cliente(
        p_id_Gimnasio NUMBER,
        p_nombre VARCHAR2,
        p_apellido VARCHAR2,
        p_telefono VARCHAR2,
        p_correo VARCHAR2,
        p_direccion VARCHAR2,
        p_fecha_nacimiento DATE,
        p_contrasena VARCHAR2
    );

END paquete_cliente;
/
create or replace PACKAGE BODY paquete_cliente AS

    FUNCTION iniciar_sesion_cliente(p_correo VARCHAR2, p_contrasena VARCHAR2) RETURN NUMBER AS
        v_id_cliente NUMBER;
    BEGIN
        SELECT id_Cliente INTO v_id_cliente
        FROM Cliente
        WHERE correo_Cliente = p_correo
        AND contrasena_Cliente = p_contrasena;

        RETURN v_id_cliente;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN 0;
    END iniciar_sesion_cliente;

    FUNCTION obtener_id_entrenador(p_id_cliente NUMBER) RETURN NUMBER AS
        v_id_empleado NUMBER;
    BEGIN
        SELECT DEREF(c.Entrenado).id_empleado INTO v_id_empleado
        FROM Cliente c
        WHERE c.id_Cliente = p_id_cliente;

        RETURN v_id_empleado;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN NULL; -- o devuelve 0 si prefieres
    END obtener_id_entrenador;

    FUNCTION obtener_id_gimnasio(p_id_cliente NUMBER) RETURN NUMBER AS
        v_id_gimnasio NUMBER;
    BEGIN
        SELECT DEREF(c.Apuntado).id_Gimnasio INTO v_id_gimnasio
        FROM Cliente c
        WHERE c.id_Cliente = p_id_cliente;

        RETURN v_id_gimnasio;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN NULL; -- o devuelve 0 si prefieres
    END obtener_id_gimnasio;

    PROCEDURE Asignar_Entrenador(
        p_id_Cliente NUMBER,
        p_id_Entrenador NUMBER
    ) AS
        ref_entrenador REF Tipo_Entrenador_Personal;
        ref_cli REF Tipo_Cliente;
        lista_actual Tipo_Entrenados;
    BEGIN
        -- Obtener la referencia al entrenador
        SELECT REF(te)
        INTO ref_entrenador
        FROM Tabla_Entrenadores te
        WHERE te.id_Empleado = p_id_Entrenador;

        -- Actualizar el campo Entrenado del cliente con la referencia al entrenador
        UPDATE Cliente c
        SET c.Entrenado = ref_entrenador
        WHERE c.id_Cliente = p_id_Cliente;

        SELECT REF(c)
        INTO ref_cli
        FROM Cliente c
        WHERE c.id_cliente = p_id_Cliente;

        -- Añadir la referencia del cliente al campo EntrenaA del entrenador
        SELECT EntrenaA
        INTO lista_actual
        FROM Tabla_Entrenadores
        WHERE id_empleado = p_id_Entrenador;

        -- Si la lista  es NULL, inicializarla con una lista vacía
        IF lista_actual IS NULL THEN
            lista_actual := Tipo_Entrenados();
        END IF;
        lista_actual := lista_actual MULTISET UNION Tipo_Entrenados(ref_cli);

        UPDATE tabla_entrenadores
        SET EntrenaA = lista_actual
        WHERE id_empleado = p_id_Entrenador;

        COMMIT;

        DBMS_OUTPUT.PUT_LINE('Cliente ' || p_id_Cliente || ' asignado correctamente al entrenador ' || p_id_Entrenador);
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            DBMS_OUTPUT.PUT_LINE('No se encontró el cliente o el entrenador.');
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE('Error: ' || SQLERRM);
    END Asignar_Entrenador;

    PROCEDURE Eliminar_Cliente(
        p_id_Cliente NUMBER
    ) AS
    BEGIN
        DELETE FROM Cliente
        WHERE id_Cliente = p_id_Cliente;

        COMMIT;

        DBMS_OUTPUT.PUT_LINE('Cliente eliminado correctamente.');
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            DBMS_OUTPUT.PUT_LINE('No se encontró el cliente.');
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE('Error: ' || SQLERRM);
    END Eliminar_Cliente;

    PROCEDURE insertar_cliente(
        p_id_Gimnasio NUMBER,
        p_nombre VARCHAR2,
        p_apellido VARCHAR2,
        p_telefono VARCHAR2,
        p_correo VARCHAR2,
        p_direccion VARCHAR2,
        p_fecha_nacimiento DATE,
        p_contrasena VARCHAR2
    ) AS
        ref_gim REF Tipo_Gimnasio;
        ref_cliente REF Tipo_Cliente;
        lista_actual Tipo_Socios;
        p_id_cliente NUMBER;
    BEGIN
        SELECT REF(g)
        INTO ref_gim
        FROM Gimnasio g
        WHERE g.id_Gimnasio = p_id_Gimnasio;
        p_id_cliente := Cliente_seq.NEXTVAL;

        INSERT INTO Cliente VALUES (
            Tipo_Cliente(
                p_id_cliente, 
                p_nombre,
                p_apellido,
                p_telefono,
                p_correo,
                p_direccion,
                p_fecha_nacimiento,
                p_contrasena, 
                ref_gim, 
                NULL
            )
        );

        SELECT REF(c)
        INTO ref_cliente
        FROM Cliente c
        WHERE c.id_Cliente = p_id_cliente;

        SELECT Lista_de_Clientes
        INTO lista_actual
        FROM Gimnasio
        WHERE id_Gimnasio = p_id_Gimnasio;

        -- Si la lista  es NULL, inicializarla con una lista vacía
        IF lista_actual IS NULL THEN
            lista_actual := Tipo_Socios();
        END IF;
        lista_actual := lista_actual MULTISET UNION Tipo_Socios(ref_cliente);

        UPDATE Gimnasio
        SET Lista_de_Clientes = lista_actual
        WHERE id_Gimnasio = p_id_Gimnasio;
    END insertar_cliente;

END paquete_cliente;
/

CREATE OR REPLACE PACKAGE paquete_gimnasio AS

    PROCEDURE Agregar_Maquina_a_Gimnasio (
        id_maquina_param IN NUMBER,
        id_gimnasio_param IN NUMBER
    );

    PROCEDURE Eliminar_Gimnasio(
        p_id_Gimnasio NUMBER
    );

    PROCEDURE eliminar_maquina_gimnasio (
        p_id_maquina IN INT,
        p_id_gimnasio IN INT
    );

    PROCEDURE Insertar_Gimnasio (
        p_nombre_Gimnasio IN VARCHAR2,
        p_direccion_Gimnasio IN VARCHAR2,
        p_telefono_Gimnasio IN VARCHAR2,
        p_correo_Gimnasio IN VARCHAR2
    );

    FUNCTION Obtener_Numero_Clientes_Gimnasio(
        p_id_Gimnasio NUMBER
    ) RETURN NUMBER;

    FUNCTION Obtener_Numero_Entrenadores_Gimnasio(
        p_id_Gimnasio NUMBER
    ) RETURN NUMBER;

    FUNCTION Obtener_Numero_Maquinas_Gimnasio(
        p_id_Gimnasio NUMBER
    ) RETURN NUMBER;

    FUNCTION Obtener_Numero_Monitores_Gimnasio(
        p_id_Gimnasio NUMBER
    ) RETURN NUMBER;

END paquete_gimnasio;
/

CREATE OR REPLACE PACKAGE BODY paquete_gimnasio AS

    PROCEDURE Agregar_Maquina_a_Gimnasio (
        id_maquina_param IN NUMBER,
        id_gimnasio_param IN NUMBER
    ) AS
        M_Ref REF Tipo_Maquinas;
        G_Ref REF Tipo_Gimnasio;
        lista_actual Tipo_Lista_Ref_Maquinas;
    BEGIN
        -- Obtener la referencia a la máquina
        SELECT REF(M) INTO M_Ref 
        FROM maquinastabla M 
        WHERE M.id_maquina = id_maquina_param;

        -- Obtener la lista actual de máquinas en el gimnasio
        SELECT Tiene_Maquinas
        INTO lista_actual
        FROM gimnasio
        WHERE id_gimnasio = id_gimnasio_param;

        -- Si la lista de máquinas es NULL, inicializarla con una lista vacía
        IF lista_actual IS NULL THEN
            lista_actual := Tipo_Lista_Ref_Maquinas();
        END IF;

        -- Agregar la nueva referencia a la lista actual de máquinas
        lista_actual := lista_actual MULTISET UNION Tipo_Lista_Ref_Maquinas(M_Ref);

        -- Actualizar la lista de máquinas en el gimnasio
        UPDATE gimnasio G
        SET G.Tiene_Maquinas = lista_actual
        WHERE G.id_gimnasio = id_gimnasio_param;
        --Obtener referencia a gimnasio:
        SELECT REF(g)
        INTO G_Ref
        FROM Gimnasio g
        WHERE g.id_Gimnasio = id_gimnasio_param;

        UPDATE maquinastabla M
        SET M.EstaEn = G_Ref
        WHERE M.id_maquina = id_maquina_param;

        COMMIT;

        DBMS_OUTPUT.PUT_LINE('Máquina agregada al gimnasio correctamente.');
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            DBMS_OUTPUT.PUT_LINE('Error: No se encontró la máquina o el gimnasio.');
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE('Error: ' || SQLERRM);
    END Agregar_Maquina_a_Gimnasio;

    PROCEDURE Eliminar_Gimnasio(
        p_id_Gimnasio NUMBER
    ) AS
    BEGIN
        DELETE FROM Cliente c 
        WHERE c.Apuntado IN (
        SELECT REF(g) 
        FROM Gimnasio g 
        WHERE g.id_gimnasio = p_id_Gimnasio
        );
        DELETE FROM Tabla_monitores m
        WHERE m.Trabaja IN (
        SELECT REF(g) 
        FROM Gimnasio g 
        WHERE g.id_gimnasio = p_id_Gimnasio
        );
        DELETE FROM Tabla_entrenadores e
        WHERE e.Trabaja IN (
        SELECT REF(g) 
        FROM Gimnasio g 
        WHERE g.id_gimnasio = p_id_Gimnasio
        );
        DELETE FROM Gimnasio
        WHERE id_Gimnasio = p_id_Gimnasio;

        COMMIT;

        DBMS_OUTPUT.PUT_LINE('Gimnasio eliminado correctamente.');
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            DBMS_OUTPUT.PUT_LINE('No se encontró el gimnasio.');
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE('Error: ' || SQLERRM);
    END Eliminar_Gimnasio;

    PROCEDURE eliminar_maquina_gimnasio (
        p_id_maquina IN INT,
        p_id_gimnasio IN INT
    ) IS
    BEGIN
        DELETE FROM TABLE(
            SELECT TIENE_MAQUINAS 
            FROM Gimnasio 
            WHERE ID_gimnasio = p_id_gimnasio
        ) T
        WHERE COLUMN_VALUE = (
            SELECT REF(m) 
            FROM maquinastabla m 
            WHERE m.id_maquina = p_id_maquina
        );
        UPDATE MaquinasTabla
        SET EstaEn = NULL
        WHERE id_maquina = p_id_maquina;
    END eliminar_maquina_gimnasio;

    PROCEDURE Insertar_Gimnasio (
        p_nombre_Gimnasio IN VARCHAR2,
        p_direccion_Gimnasio IN VARCHAR2,
        p_telefono_Gimnasio IN VARCHAR2,
        p_correo_Gimnasio IN VARCHAR2
    ) AS
        v_id_Gimnasio NUMBER;
    BEGIN
        -- Obtener el próximo valor de la secuencia para el ID del gimnasio
        SELECT Gimnasio_seq.NEXTVAL INTO v_id_Gimnasio FROM DUAL;

        -- Insertar el nuevo gimnasio
        INSERT INTO Gimnasio (id_Gimnasio, nombre_Gimnasio, direccion_Gimnasio, telefono_Gimnasio, correo_Gimnasio)
        VALUES (v_id_Gimnasio, p_nombre_Gimnasio, p_direccion_Gimnasio, p_telefono_Gimnasio, p_correo_Gimnasio);

        COMMIT;

        DBMS_OUTPUT.PUT_LINE('Gimnasio agregado correctamente con ID: ' || v_id_Gimnasio);
    EXCEPTION
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE('Error: ' || SQLERRM);
    END Insertar_Gimnasio;

    FUNCTION Obtener_Numero_Clientes_Gimnasio(
        p_id_Gimnasio NUMBER
    ) RETURN NUMBER IS
        v_numero_clientes NUMBER := 0;
    BEGIN
        SELECT COUNT(*) INTO v_numero_clientes
        FROM TABLE(
            SELECT Lista_de_Clientes
            FROM Gimnasio
            WHERE id_Gimnasio = p_id_Gimnasio
        );

        RETURN v_numero_clientes;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN -1; -- Retorna -1 si no se encuentra el gimnasio con el ID dado
    END Obtener_Numero_Clientes_Gimnasio;

    FUNCTION Obtener_Numero_Entrenadores_Gimnasio(
        p_id_Gimnasio NUMBER
    ) RETURN NUMBER IS
        v_numero_entrenadores NUMBER := 0;
    BEGIN
        SELECT COUNT(*) INTO v_numero_entrenadores
        FROM TABLE(
            SELECT Lista_de_entrenadores
            FROM Gimnasio
            WHERE id_Gimnasio = p_id_Gimnasio
        );

        RETURN v_numero_entrenadores;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN -1; -- Retorna -1 si no se encuentra el gimnasio con el ID dado
    END Obtener_Numero_Entrenadores_Gimnasio;

    FUNCTION Obtener_Numero_Maquinas_Gimnasio(
        p_id_Gimnasio NUMBER
    ) RETURN NUMBER IS
        v_numero_maquinas NUMBER := 0;
    BEGIN
        SELECT COUNT(*) INTO v_numero_maquinas
        FROM TABLE(
            SELECT Tiene_maquinas
            FROM Gimnasio
            WHERE id_Gimnasio = p_id_Gimnasio
        );

        RETURN v_numero_maquinas;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN -1; -- Retorna -1 si no se encuentra el gimnasio con el ID dado
    END Obtener_Numero_Maquinas_Gimnasio;

    FUNCTION Obtener_Numero_Monitores_Gimnasio(
        p_id_Gimnasio NUMBER
    ) RETURN NUMBER IS
        v_numero_monitores NUMBER := 0;
    BEGIN
        SELECT COUNT(*) INTO v_numero_monitores
        FROM TABLE(
            SELECT Lista_de_monitores
            FROM Gimnasio
            WHERE id_Gimnasio = p_id_Gimnasio
        );

        RETURN v_numero_monitores;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN -1; -- Retorna -1 si no se encuentra el gimnasio con el ID dado
    END Obtener_Numero_Monitores_Gimnasio;

END paquete_gimnasio;
/

CREATE OR REPLACE PACKAGE paquete_maquinas AS

    PROCEDURE Eliminar_Maquina(
        p_id_Maquina NUMBER
    );

    PROCEDURE Insertar_Maquina (
        p_nombre_maquina IN VARCHAR2,
        p_descripcion_maquina IN VARCHAR2,
        p_imagen BLOB
    );

    FUNCTION obtener_id_gimnasio_por_maquina(p_id_maquina NUMBER) RETURN NUMBER;

END paquete_maquinas;
/

CREATE OR REPLACE PACKAGE BODY paquete_maquinas AS

    PROCEDURE Eliminar_Maquina(
        p_id_Maquina NUMBER
    ) AS
    BEGIN
        DELETE FROM MaquinasTabla
        WHERE id_maquina = p_id_Maquina;

        COMMIT;

        DBMS_OUTPUT.PUT_LINE('Máquina eliminada correctamente.');
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            DBMS_OUTPUT.PUT_LINE('No se encontró la máquina.');
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE('Error: ' || SQLERRM);
    END Eliminar_Maquina;

    PROCEDURE Insertar_Maquina (
        p_nombre_maquina IN VARCHAR2,
        p_descripcion_maquina IN VARCHAR2,
        p_imagen BLOB
    ) AS
        v_id_maquina NUMBER;
    BEGIN
        -- Obtener el próximo valor de la secuencia para el ID de la máquina
        SELECT Maquina_seq.NEXTVAL INTO v_id_maquina FROM DUAL;

        -- Insertar la nueva máquina
        INSERT INTO MaquinasTabla (id_maquina, nombre_maquina, descripcion_maquina, imagen)
        VALUES (v_id_maquina, p_nombre_maquina, p_descripcion_maquina, p_imagen);

        COMMIT;

        DBMS_OUTPUT.PUT_LINE('Máquina insertada correctamente con ID: ' || v_id_maquina);
    EXCEPTION
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE('Error: ' || SQLERRM);
    END Insertar_Maquina;

    FUNCTION obtener_id_gimnasio_por_maquina(p_id_maquina NUMBER) RETURN NUMBER AS
        v_id_gimnasio NUMBER;
    BEGIN
        SELECT DEREF(m.estaen).id_Gimnasio INTO v_id_gimnasio
        FROM maquinastabla m
        WHERE m.id_maquina = p_id_maquina;

        RETURN v_id_gimnasio;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN NULL; 
    END obtener_id_gimnasio_por_maquina;

END paquete_maquinas;
/

CREATE OR REPLACE PACKAGE paquete_empleados AS

    PROCEDURE Desvincular_Cliente_Entrenador(
        p_id_Cliente NUMBER,
        p_id_Entrenador NUMBER
    );

    PROCEDURE Eliminar_Empleado(
        p_id_Empleado NUMBER
    );

    PROCEDURE insertar_empleado_entrenador(
        p_id_Gimnasio NUMBER,
        p_nombre VARCHAR2,
        p_apellido VARCHAR2,
        p_telefono VARCHAR2,
        p_correo VARCHAR2,
        p_direccion VARCHAR2,
        p_fecha_nacimiento DATE,
        p_contrasena VARCHAR2,
        p_titulacion VARCHAR2,
        p_experiencia VARCHAR2,
        p_imagen BLOB
    );

    PROCEDURE insertar_empleado_monitor(
        p_id_Gimnasio NUMBER,
        p_nombre VARCHAR2,
        p_apellido VARCHAR2,
        p_telefono VARCHAR2,
        p_correo VARCHAR2,
        p_direccion VARCHAR2,
        p_fecha_nacimiento DATE,
        p_contrasena VARCHAR2,
        p_turno VARCHAR2
    );

    FUNCTION iniciar_sesion_entrenador(p_correo VARCHAR2, p_contrasena VARCHAR2) RETURN NUMBER;

    FUNCTION iniciar_sesion_monitor(p_correo VARCHAR2, p_contrasena VARCHAR2) RETURN NUMBER;

    FUNCTION obtener_id_gimnasio_por_entrenador(p_id_empleado NUMBER) RETURN NUMBER;

    FUNCTION obtener_id_gimnasio_por_monitor(p_id_empleado NUMBER) RETURN NUMBER;

END paquete_empleados;
/

CREATE OR REPLACE PACKAGE BODY paquete_empleados AS

    PROCEDURE Desvincular_Cliente_Entrenador(
        p_id_Cliente NUMBER,
        p_id_Entrenador NUMBER
    ) AS
    BEGIN
        -- Desvincular al cliente estableciendo Entrenado como NULL
        UPDATE Cliente
        SET Entrenado = NULL
        WHERE id_Cliente = p_id_Cliente;

        -- Eliminar la referencia del cliente en la tabla anidada EntrenaA de Tabla_Entrenadores
        DELETE FROM TABLE (
            SELECT EntrenaA
            FROM Tabla_Entrenadores 
            WHERE id_Empleado = p_id_Entrenador
        ) t
        WHERE COLUMN_VALUE = (
            SELECT REF(c) 
            FROM cliente c
            WHERE c.id_cliente = p_id_cliente
        );

        COMMIT;

        DBMS_OUTPUT.PUT_LINE('Cliente desvinculado del entrenador correctamente.');
    EXCEPTION
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE('Error: ' || SQLERRM);
    END Desvincular_Cliente_Entrenador;

    PROCEDURE Eliminar_Empleado(
        p_id_Empleado NUMBER
    ) AS
        v_empleado_existente NUMBER;
    BEGIN
        -- Verificar si el ID de empleado corresponde a un entrenador
        SELECT COUNT(*)
        INTO v_empleado_existente
        FROM Tabla_Entrenadores
        WHERE id_Empleado = p_id_Empleado;

        IF v_empleado_existente > 0 THEN
            -- Si es un entrenador, eliminar de la tabla de entrenadores
            DELETE FROM Tabla_Entrenadores WHERE id_Empleado = p_id_Empleado;

            DBMS_OUTPUT.PUT_LINE('Empleado entrenador eliminado correctamente.');
        ELSE
            -- Si no es un entrenador, verificar si es un monitor
            SELECT COUNT(*)
            INTO v_empleado_existente
            FROM Tabla_Monitores
            WHERE id_Empleado = p_id_Empleado;

            IF v_empleado_existente > 0 THEN
                -- Si es un monitor, eliminar de la tabla de monitores
                DELETE FROM Tabla_Monitores WHERE id_Empleado = p_id_Empleado;

                DBMS_OUTPUT.PUT_LINE('Empleado monitor eliminado correctamente.');
            ELSE
                -- Si no se encuentra en ninguna tabla, mostrar mensaje de error
                DBMS_OUTPUT.PUT_LINE('No se encontró el empleado.');
            END IF;
        END IF;

        COMMIT;
    EXCEPTION
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE('Error: ' || SQLERRM);
    END Eliminar_Empleado;

    PROCEDURE insertar_empleado_entrenador(
        p_id_Gimnasio NUMBER,
        p_nombre VARCHAR2,
        p_apellido VARCHAR2,
        p_telefono VARCHAR2,
        p_correo VARCHAR2,
        p_direccion VARCHAR2,
        p_fecha_nacimiento DATE,
        p_contrasena VARCHAR2,
        p_titulacion VARCHAR2,
        p_experiencia VARCHAR2,
        p_imagen BLOB
    ) AS
        ref_gim REF Tipo_Gimnasio;
        ref_entrenador REF Tipo_Entrenador_Personal;
        lista_actual Tipo_Entrenadores;
        p_id_empleado NUMBER;
    BEGIN
        -- Obtener la referencia al gimnasio
        SELECT REF(g)
        INTO ref_gim
        FROM Gimnasio g
        WHERE g.id_Gimnasio = p_id_Gimnasio;

        -- Generar el próximo ID de empleado
        p_id_empleado := Empleado_seq.NEXTVAL;

        -- Insertar el empleado en la tabla Empleado
        INSERT INTO Tabla_Entrenadores VALUES (
            Tipo_Entrenador_Personal(
                p_id_empleado, 
                p_nombre,
                p_apellido,
                p_telefono,
                p_correo,
                p_direccion,
                p_fecha_nacimiento,
                p_contrasena, 
                ref_gim,
                p_titulacion,
                p_experiencia,
                NULL,
                p_imagen
            )
        );

        -- Obtener la referencia del nuevo empleado
        SELECT REF(e)
        INTO ref_entrenador
        FROM  tabla_entrenadores e
        WHERE e.id_Empleado = p_id_empleado;

        -- Obtener la lista actual de empleados del gimnasio
        SELECT Lista_de_Entrenadores
        INTO lista_actual
        FROM Gimnasio
        WHERE id_Gimnasio = p_id_Gimnasio;

        -- Si la lista de empleados es NULL, inicializarla con una lista vacía
        IF lista_actual IS NULL THEN
            lista_actual := Tipo_Entrenadores();
        END IF;

        -- Agregar el nuevo empleado a la lista actual de empleados
        lista_actual := lista_actual MULTISET UNION Tipo_Entrenadores(ref_entrenador);

        -- Actualizar la lista de empleados en la tabla Gimnasio
        UPDATE Gimnasio
        SET Lista_de_Entrenadores = lista_actual
        WHERE id_Gimnasio = p_id_Gimnasio;
    END insertar_empleado_entrenador;

    PROCEDURE insertar_empleado_monitor(
        p_id_Gimnasio NUMBER,
        p_nombre VARCHAR2,
        p_apellido VARCHAR2,
        p_telefono VARCHAR2,
        p_correo VARCHAR2,
        p_direccion VARCHAR2,
        p_fecha_nacimiento DATE,
        p_contrasena VARCHAR2,
        p_turno VARCHAR2
    ) AS
        ref_gim REF Tipo_Gimnasio;
        ref_monitor REF Tipo_Monitor;
        lista_actual Tipo_Monitores;
        p_id_empleado NUMBER;
    BEGIN
        -- Obtener la referencia al gimnasio
        SELECT REF(g)
        INTO ref_gim
        FROM Gimnasio g
        WHERE g.id_Gimnasio = p_id_Gimnasio;

        -- Generar el próximo ID de empleado
        p_id_empleado := Empleado_seq.NEXTVAL;

        -- Insertar el empleado en la tabla Empleado
        INSERT INTO Tabla_Monitores VALUES (
            Tipo_Monitor(
                p_id_empleado, 
                p_nombre,
                p_apellido,
                p_telefono,
                p_correo,
                p_direccion,
                p_fecha_nacimiento,
                p_contrasena, 
                ref_gim,
                p_turno 
            )
        );

        -- Obtener la referencia del nuevo empleado
        SELECT REF(m)
        INTO ref_monitor
        FROM  tabla_monitores m
        WHERE m.id_Empleado = p_id_empleado;

        -- Obtener la lista actual de empleados del gimnasio
        SELECT Lista_de_Monitores
        INTO lista_actual
        FROM Gimnasio
        WHERE id_Gimnasio = p_id_Gimnasio;

        -- Si la lista de empleados es NULL, inicializarla con una lista vacía
        IF lista_actual IS NULL THEN
            lista_actual := Tipo_Monitores();
        END IF;

        -- Agregar el nuevo empleado a la lista actual de empleados
        lista_actual := lista_actual MULTISET UNION Tipo_Monitores(ref_monitor);

        -- Actualizar la lista de empleados en la tabla Gimnasio
        UPDATE Gimnasio
        SET Lista_de_Monitores = lista_actual
        WHERE id_Gimnasio = p_id_Gimnasio;
    END insertar_empleado_monitor;

    FUNCTION iniciar_sesion_entrenador(p_correo VARCHAR2, p_contrasena VARCHAR2) RETURN NUMBER AS
        v_id_empleado NUMBER;
    BEGIN
        SELECT id_empleado INTO v_id_empleado
        FROM tabla_entrenadores
        WHERE correo_empleado = p_correo
        AND contrasena_empleado = p_contrasena;

        RETURN v_id_empleado;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN 0;
    END iniciar_sesion_entrenador;

    FUNCTION iniciar_sesion_monitor(p_correo VARCHAR2, p_contrasena VARCHAR2) RETURN NUMBER AS
        v_id_empleado NUMBER;
    BEGIN
        SELECT id_empleado INTO v_id_empleado
        FROM tabla_monitores
        WHERE correo_empleado = p_correo
        AND contrasena_empleado = p_contrasena;

        RETURN v_id_empleado;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN 0;
    END iniciar_sesion_monitor;

    FUNCTION obtener_id_gimnasio_por_entrenador(p_id_empleado NUMBER) RETURN NUMBER AS
        v_id_gimnasio NUMBER;
    BEGIN
        SELECT DEREF(e.trabaja).id_Gimnasio INTO v_id_gimnasio
        FROM tabla_entrenadores e
        WHERE e.id_empleado = p_id_empleado;

        RETURN v_id_gimnasio;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN NULL; 
    END obtener_id_gimnasio_por_entrenador;

    FUNCTION obtener_id_gimnasio_por_monitor(p_id_empleado NUMBER) RETURN NUMBER AS
        v_id_gimnasio NUMBER;
    BEGIN
        SELECT DEREF(m.trabaja).id_Gimnasio INTO v_id_gimnasio
        FROM tabla_monitores m
        WHERE m.id_empleado = p_id_empleado;

        RETURN v_id_gimnasio;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN NULL; 
    END obtener_id_gimnasio_por_monitor;

END paquete_empleados;
/