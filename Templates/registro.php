<?php

	function cifrar($p)
	{
		// Selecciona la sal y pimienta
		
		for($i=0;$i<5;$i++)
		{
			$x1 = rand(36,62);
			$c1 = chr($x1+$x1);
			
			$x2 = rand(32,62);
			$c2 = chr($x2+$x2);
			
			if(!isset($ascii1))
				$ascii1 = $c1;
			else
				$ascii1 = $ascii1 . $c1;
			
			if(!isset($ascii2))
				$ascii2 = $c2;
			else
				$ascii2 = $ascii2 . $c2;
		}
		
		//....
		// Cifra el código de la contraseña
		
		$c = strlen($p);
		
		for($i=0;$i<$c;$i++)
		{
			$concat = substr($p, $i, 1);
			$ascii = ord($concat);
			$ascii = $ascii+5;
			if($ascii > 122)
				$ascii = $ascii-85;
			$resul = chr($ascii);
			if(!isset($r))
				$r = $resul;
			else
				$r = $r . $resul;
		}
		
		//Caracteres aleatorios de la cadena
		
		$rn = t2($r);
		for($i=0;$i<3;$i++)
		{
			$h = rand(0,$c);
			$new = substr($r,$h,1);
			
			if(!isset($n))
				$n = $new;
			else
				$n = $n . $new;
		}
		
		//....
		
		$rn = $ascii1 . $rn;
		$rn = $rn . $n;
		$rn = $rn . $ascii2;
		
		//....
		
		return $rn;
	}
	
	// Se usa un hash y se pierde cierta información al sumar los valores en ascii de dos números
	
	function t2($res)
	{
		
		$a = strlen($res);
		if($a%2 == 0)
		{
			for($i=0;$i<$a;$i+=2)
			{
				$r = substr($res,$i,2);
				for($j=0;$j<=1;$j++)
				{
					$s = substr($r,$j,1);
					if(!isset($t1))
						$t1 = $s;
					else
						$t2 = $s;
				}
				$d1 = ord($t1);
				$d2 = ord($t2);
				$d = $d1 + $d2;
				if($d >	 126)
					$d = $d - 134;
				$e = chr($d);
				if(!isset($rn))
					$rn = $e;
				else
					$rn = $rn . $e;
			}
		}
		else
		{
			
			for($i=0;$i<$a+1;$i+=2)
			{
				
				$r = substr($res,$i,2);
				for($j=0;$j<=1;$j++)
				{
					$s = substr($r,$j,1);
					if(!isset($t1))
						$t1 = $s;
					else
						$t2 = $s;
				}
				$d1 = ord($t1);
				$d2 = ord($t2);
				$d = $d1 + $d2;
				if($d >	 126)
					$d = $d - 134;
				$e = chr($d);
				if(!isset($rn))
					$rn = $e;
				else
					$rn = $rn . $e;
				
			}
			
		}
		return $rn;
		
	}
	
	// termina el hash.
	
echo '<!DOCTYPE html>
	<html>
		<head>
			<title> Seguridad </title>
		</head>
		<body>';
	if(isset($_COOKIE['nombre']))
	{
		if(!isset($_POST['nombre']))
		{
		
echo		'<form action="registro.php" method="POST">
			
				Nombre de usuario: <input type="text" maxleght="15" name="nombre" required autofocus/> <small> Tu nombre puede tener números, letras, y algunos caracters especiales(@, . , _ , -), y debe tener de 5 a 15 caracteres </small><br/>
			
				Contraseña: <input type="password" name="password" maxleght="15" required/> 			<small> Puede contener letras o números, asi como algunos caracteres especiales(@, _ , -). Debe de tener una longitud mínima de 8 caracteres y puede llegar hasta 15 </small><br/>
			
				Sexo:<br/>
					Masculino: <input type="radio" name="sexo" value="m"/><br/>
					Femenino: <input type="radio" name="sexo" value="f"/><br/>
				
				<input type="submit" value="Registrarse">
				<br/><a href="proyecto_seg.php"> Volver al menú </a>
			</form>';
		
			$value = rand(1,10);
			if(!isset($_COOKIE['inicio']))
				setcookie("Inicio", $value, time() + 10);
		}
		else//if(isset($_COOKIE['Inicio'])) Intente evitar el Cross site request
		{
		
			//Escapar caracteres especiales.
		
			$nombre = htmlspecialchars($_POST['nombre']);
			$nombre = addslashes($nombre);
			$pass = htmlspecialchars($_POST['password']);
			$pass = addslashes($pass);
			$conect = mysqli_connect("localhost","root");
		
			if(!isset($_POST['sexo']))
				$sexo = 's';
			else
				$sexo = $_POST['sexo'];
		
			//.
		
			// Busquedas y validacones a la base de datos
		
			if(mysqli_select_db($conect,"seg"))
			{
				$search = mysqli_query($conect,"SELECT nombre FROM user WHERE nombre LIKE '%$nombre%';");
				$nombre = mysqli_real_escape_string($conect, $nombre);
				$pass = mysqli_real_escape_string($conect, $pass);
				$na = mysqli_fetch_array(mysqli_query($conect,"SELECT nombre FROM user WHERE nombre LIKE '%$nombre%';"));
				$name = $na['nombre'];
			
				// Expresiones regulares
			
				$nc = "/[a-zA-Z0-9\@\.\_\-]{5,15}/";
				$pc = "/[a-z0-9\@\_\-]{8,15}/";
				$compn = preg_match($nc, $nombre);
				$compp = preg_match($pc, $pass);
				
				//.....
			
				// Comprobación del nombre
			
				if($compn != 0)
				{
				
					// Comprobación de la contraseña
				
					if($compp == 0)
					{
						echo 'Tu cadena no es válida
						<a href="registro.php"> Volver a intentar </a>';
					}	//(Contraseña)
					else	// Cuando no se cumpla
					{
						// Validación para pasar a bd
					
						if($nombre != $name)
						{
							// Estable un salud según el sexo
						
							if($sexo == 'f')
								echo 'Bienvenida: '.$nombre;
							elseif($sexo == 'm')
									echo 'Bienvenido: '.$nombre;
								else
									echo 'Bienvenid@ abordo: '.$nombre;
						
							//....(saludo)
							// Introduce en la base de datos
						
echo						'<br/><a href="ingresar.php"> Continuar </a>';
							$contra = cifrar($pass);
							mysqli_query($conect, "INSERT INTO user VALUES ('$nombre','$contra','$sexo')");
						
							//....(introduce)
						
						}	//(bd)
						else	// Cuando no se cumpla
						{
						
							echo "Nombre ya existente
							<br/><a href='registro.php'> Volver a intentar </a>";
						
						}	//....(bd/si no)
					
					}	//....(Contraseña/si no)
				
				} 	//....(Nombre)			
				else	// Si no se cumple
				{
					echo 'Tu cadena no es válida
					<a href="registro.php"> Volver a intentar </a>';
				}	//....(Nombre/si no)
			}
			else
				echo "No esta bien";
		
			mysqli_close($conect);
		
			//.
			$value = rand(1,100);
			setcookie("Inicio", "0", time() - 1);
			setcookie("go", $value, time() + 10);
		}
	}
	else
	{
echo	'Hubo un error en nuestra página, es posible que estes ingresando de una página falsa o el 		
		tiempo de espera de la conección a caducado. Por tu seguridad no podras continuar con el 
		proceso, esto para preservar tu información y la de los demás usuario.
		<a href="proyecto_seg.php"> Volver </a>';
	}
		
echo 	'</body>
	</html>';
?>