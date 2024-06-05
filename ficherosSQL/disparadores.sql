create or replace TRIGGER before_insert_gimnasio
BEFORE INSERT ON Gimnasio
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    -- Comprobar si el correo electrónico del nuevo gimnasio ya existe en otras tablas
    SELECT COUNT(*)
    INTO v_count
    FROM (
        SELECT correo_Gimnasio AS correo FROM Gimnasio
        UNION ALL
        SELECT correo_Cliente AS correo FROM Cliente
        UNION ALL
        SELECT correo_Empleado AS correo FROM Tabla_Monitores
        UNION ALL
        SELECT correo_Empleado AS correo FROM Tabla_Monitores
    ) tabla_correos
    WHERE tabla_correos.correo = :NEW.correo_Gimnasio;

    -- Si se encuentra algún correo duplicado, lanzar una excepción
    IF v_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'El correo electrónico ya existe en otra tabla.');
    END IF;
END;
/
create or replace TRIGGER before_insert_cliente
BEFORE INSERT ON cliente
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    -- Comprobar si el correo electrónico del nuevo cliente ya existe en otras tablas
    SELECT COUNT(*)
    INTO v_count
    FROM (
        SELECT correo_Gimnasio AS correo FROM Gimnasio
        UNION ALL
        SELECT correo_Cliente AS correo FROM Cliente
        UNION ALL
        SELECT correo_Empleado AS correo FROM Tabla_Entrenadores
        UNION ALL
        SELECT correo_Empleado AS correo FROM Tabla_Monitores
    ) tabla_correos
    WHERE tabla_correos.correo = :NEW.correo_cliente;

    -- Si se encuentra algún correo duplicado, lanzar una excepción
    IF v_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'El correo electrónico ya existe en otra tabla.');
    END IF;
END;
/
create or replace TRIGGER before_insert_Tabla_Entrenadores
BEFORE INSERT ON Tabla_Entrenadores
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    -- Comprobar si el correo electrónico del nuevo Tabla_Entrenadores ya existe en otras tablas
    SELECT COUNT(*)
    INTO v_count
    FROM (
        SELECT correo_Gimnasio AS correo FROM Gimnasio
        UNION ALL
        SELECT correo_Cliente AS correo FROM Cliente
        UNION ALL
        SELECT correo_Empleado AS correo FROM Tabla_Entrenadores
        UNION ALL
        SELECT correo_Empleado AS correo FROM Tabla_Monitores
    ) tabla_correos
    WHERE tabla_correos.correo = :NEW.correo_empleado;

    -- Si se encuentra algún correo duplicado, lanzar una excepción
    IF v_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'El correo electrónico ya existe en otra tabla.');
    END IF;
END;
/
create or replace TRIGGER before_insert_Tabla_Monitores
BEFORE INSERT ON Tabla_Monitores
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    -- Comprobar si el correo electrónico del nuevo Tabla_Monitores ya existe en otras tablas
    SELECT COUNT(*)
    INTO v_count
    FROM (
        SELECT correo_Gimnasio AS correo FROM Gimnasio
        UNION ALL
        SELECT correo_Cliente AS correo FROM Cliente
        UNION ALL
        SELECT correo_Empleado AS correo FROM Tabla_Monitores
        UNION ALL
        SELECT correo_Empleado AS correo FROM Tabla_Monitores
    ) tabla_correos
    WHERE tabla_correos.correo = :NEW.correo_empleado;

    -- Si se encuentra algún correo duplicado, lanzar una excepción
    IF v_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'El correo electrónico ya existe en otra tabla.');
    END IF;
END;
/
CREATE OR REPLACE TRIGGER before_update_cliente_correo
BEFORE UPDATE OF correo_cliente ON cliente
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    -- Comprobar si el nuevo correo electrónico del cliente ya existe en otras tablas
    SELECT COUNT(*)
    INTO v_count
    FROM (
        SELECT correo_cliente AS correo FROM cliente WHERE id_cliente != :OLD.id_cliente
        UNION ALL
        SELECT correo_gimnasio AS correo FROM Gimnasio
        UNION ALL
        SELECT correo_Empleado AS correo FROM Tabla_Entrenadores
        UNION ALL
        SELECT correo_Empleado AS correo FROM Tabla_Monitores
    ) tabla_correos
    WHERE tabla_correos.correo = :NEW.correo_cliente;

    -- Si se encuentra algún correo duplicado, lanzar una excepción
    IF v_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'El nuevo correo electrónico ya existe en otra tabla.');
    END IF;
END;
/
CREATE OR REPLACE TRIGGER before_update_monitor_correo
BEFORE UPDATE OF correo_empleado ON Tabla_monitores
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    -- Comprobar si el nuevo correo electrónico del cliente ya existe en otras tablas
    SELECT COUNT(*)
    INTO v_count
    FROM (
        SELECT correo_empleado AS correo FROM tabla_monitores WHERE id_empleado != :OLD.id_empleado
        UNION ALL
        SELECT correo_gimnasio AS correo FROM Gimnasio
        UNION ALL
        SELECT correo_Empleado AS correo FROM Tabla_Entrenadores
        UNION ALL
        SELECT correo_cliente AS correo FROM cliente
    ) tabla_correos
    WHERE tabla_correos.correo = :NEW.correo_empleado;

    -- Si se encuentra algún correo duplicado, lanzar una excepción
    IF v_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'El nuevo correo electrónico ya existe en otra tabla.');
    END IF;
END;
/
CREATE OR REPLACE TRIGGER before_update_entrenador_correo
BEFORE UPDATE OF correo_empleado ON Tabla_entrenadores
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    -- Comprobar si el nuevo correo electrónico del cliente ya existe en otras tablas
    SELECT COUNT(*)
    INTO v_count
    FROM (
        SELECT correo_empleado AS correo FROM tabla_entrenadores WHERE id_empleado != :OLD.id_empleado
        UNION ALL
        SELECT correo_gimnasio AS correo FROM Gimnasio
        UNION ALL
        SELECT correo_Empleado AS correo FROM Tabla_Monitores
        UNION ALL
        SELECT correo_cliente AS correo FROM cliente
    ) tabla_correos
    WHERE tabla_correos.correo = :NEW.correo_empleado;

    -- Si se encuentra algún correo duplicado, lanzar una excepción
    IF v_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'El nuevo correo electrónico ya existe en otra tabla.');
    END IF;
END;
/