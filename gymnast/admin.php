<?php
session_start();
echo"<body style='background-color: #300; color: white; font-family: Arial, sans-serif;'>\n";
echo "<div style='background-color: #333; padding: 20px; text-align: center;'>\n";
echo "<a href='logout.php' style='display: inline-block; padding: 10px 20px; background-color: red; color: white; text-decoration: none; margin: 10px;'>Log Out</a>\n";
echo "<a href='admin_gimnasio.php' style='display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; margin: 10px;'>Gimnasio</a>\n";
echo "<a href='admin_entrenadores.php' style='display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; margin: 10px;'>Entrenadores</a>\n";
echo "<a href='admin_monitores.php' style='display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; margin: 10px;'>Monitores</a>\n";
echo "<a href='admin_clientes.php' style='display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; margin: 10px;'>Clientes</a>\n";
echo "<a href='admin_maquinas.php' style='display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; margin: 10px;'>Máquinas</a>\n";
echo "</div>\n";
echo "</body>\n";
?>