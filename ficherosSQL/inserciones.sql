BEGIN
    paquete_gimnasio.Insertar_Gimnasio('Fitness Plus', 'Calle Principal 123', '555-1234', 'fitnessplus@example.com');
    paquete_gimnasio.Insertar_Gimnasio('Gimnasio Vitalidad', 'Avenida Central 456', '555-5678', 'vitalidadgym@example.com');
    paquete_gimnasio.Insertar_Gimnasio('Energia Activa', 'Calle Secundaria 789', '555-9012', 'energiaactiva@example.com');

    paquete_maquinas.Insertar_Maquina('Cinta de Correr', 'Maquina para correr con diferentes niveles.', NULL);
    paquete_maquinas.Insertar_Maquina('Banco de Pesas', 'Maquina para levantamiento con inclinacion.', NULL);
    paquete_maquinas.Insertar_Maquina('Maquina de Remo', 'Ejercicio cardiovascular que simula el remo.', NULL);
    paquete_maquinas.Insertar_Maquina('Maquina de Escaleras', 'Maquina que simula subir escaleras.', NULL);
    paquete_maquinas.Insertar_Maquina('Maquina Eliptica', 'Ejercicio cardiovascular que simula caminar.', NULL);
    paquete_gimnasio.Agregar_Maquina_a_Gimnasio(1,1);
    paquete_gimnasio.Agregar_Maquina_a_Gimnasio(2,1);
    paquete_gimnasio.Agregar_Maquina_a_Gimnasio(3,1);
    paquete_gimnasio.Agregar_Maquina_a_Gimnasio(4,1);
    paquete_gimnasio.Agregar_Maquina_a_Gimnasio(5,2);

        -- Insertar seis empleados entrenadores en diferentes gimnasios
    paquete_empleados.insertar_empleado_entrenador(1,'Juan','Martinez','555-1234','empleado@example.com','Calle Principal 123',TO_DATE('1990-05-15','YYYY-MM-DD'),'pw','Entrenador Personal Certificado','experiencia en entrenamiento personalizado',null);
    paquete_empleados.insertar_empleado_entrenador(1,'Maria','Lopez','555-5678','marialopez@example.com','Avenida Central 456',TO_DATE('1985-09-20','YYYY-MM-DD'),'clave123','Entrenadora de CrossFit','experiencia en entrenamiento funcional',null);
    paquete_empleados.insertar_empleado_entrenador(1,'Pedro','Garcia','555-9012','pedrogarcia@example.com','Calle Secundaria 789',TO_DATE('1983-12-10','YYYY-MM-DD'),'password123','Entrenador de Musculacion','experiencia en levantamiento de pesas',null);
    paquete_empleados.insertar_empleado_entrenador(1,'Laura','Fernandez','555-2468','laurafernandez@example.com','Avenida Norte 246',TO_DATE('1988-03-05','YYYY-MM-DD'),'clave456','Entrenadora de Yoga','experiencia en practica de yoga',null);
    paquete_empleados.insertar_empleado_entrenador(2,'Carlos','Gonzalez','555-1357','carlosgonzalez@example.com','Calle Este 357',TO_DATE('1995-07-12','YYYY-MM-DD'),'password456','Entrenador de Pilates','experiencia en Pilates',null);
    paquete_empleados.insertar_empleado_entrenador(3,'Lucia','Sanchez','555-7890','luciasanchez@example.com','Avenida Oeste 789',TO_DATE('1994-11-28','YYYY-MM-DD'),'contrasena789','Entrenadora de Boxeo','experiencia en entrenamiento de boxeo',null);
    
    -- Insertar seis empleados monitores en diferentes gimnasios
    paquete_empleados.insertar_empleado_monitor(1,'Ana','Martinez','555-4321','monitor@example.com','Calle Principal 456',TO_DATE('1992-08-25','YYYY-MM-DD'),'pw','Turno Manana');
    paquete_empleados.insertar_empleado_monitor(1,'Luis','Gomez','555-8765','luisgomez@example.com','Avenida Central 789',TO_DATE('1991-11-30','YYYY-MM-DD'),'clave789','Turno Tarde');
    paquete_empleados.insertar_empleado_monitor(2,'Sofia','Hernandez','555-2109','sofiahernandez@example.com','Calle Secundaria 123',TO_DATE('1993-06-18','YYYY-MM-DD'),'password987','Turno Noche');
    paquete_empleados.insertar_empleado_monitor(2,'Diego','Perez','555-3698','diegoperez@example.com','Avenida Norte 789',TO_DATE('1990-02-17','YYYY-MM-DD'),'contrasena369','Turno Manana');
    paquete_empleados.insertar_empleado_monitor(3,'Elena','Ruiz','555-7531','elenaruiz@example.com','Calle Este 159',TO_DATE('1989-04-24','YYYY-MM-DD'),'clave159','Turno Tarde');
    paquete_empleados.insertar_empleado_monitor(3,'Javier','Santos','555-6842','javiersantos@example.com','Avenida Sur 753',TO_DATE('1996-01-10','YYYY-MM-DD'),'password753','Turno Noche');

    paquete_cliente.insertar_cliente(1, 'Juan', 'Perez', '555-1234', 'cliente@example.com', 'Calle Principal 123', TO_DATE('1990-05-15', 'YYYY-MM-DD'), 'pw');
    paquete_cliente.insertar_cliente(1, 'Maria', 'Gomez', '555-5678', 'mariagomez@example.com', 'Avenida Central 456', TO_DATE('1985-09-20', 'YYYY-MM-DD'), 'clave123');
    paquete_cliente.insertar_cliente(2, 'Pedro', 'Perez', '555-9012', 'pedroperez@example.com', 'Calle Secundaria 789', TO_DATE('1983-12-10', 'YYYY-MM-DD'), 'password123');
    paquete_cliente.insertar_cliente(2, 'Laura', 'Hernandez', '555-2468', 'laurahernandez@example.com', 'Avenida Norte 246', TO_DATE('1988-03-05', 'YYYY-MM-DD'), 'clave456');
    paquete_cliente.insertar_cliente(3, 'Carlos', 'Ruiz', '555-1357', 'carlosruiz@example.com', 'Calle Este 357', TO_DATE('1995-07-12', 'YYYY-MM-DD'), 'password456');
    paquete_cliente.insertar_cliente(3, 'Lucia', 'Martinez', '555-7890', 'luciamartinez@example.com', 'Avenida Oeste 789', TO_DATE('1994-11-28', 'YYYY-MM-DD'), 'contrasena789');
    paquete_cliente.Asignar_Entrenador(1, 1);
    paquete_cliente.Asignar_Entrenador(2, 1);
    paquete_cliente.Asignar_Entrenador(3, 1);
END;