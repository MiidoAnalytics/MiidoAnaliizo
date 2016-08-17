<?php

	define('_SP'			, '|_|'	);
	define('_DEPARTAMENT'	, '70'	);//"$dp");
	define('_CITY'			, '1'	);//"$ct"	);

	define('_USER'		, 'miido_admin'	);
	define('_PASSWORD'	, 'z]=Wf4QbU%%.');
	define('_HOST'		, 'localhost'	);
	define('_PORT'		, '3306'		);
	//define('_DB1'		, 'analiizo'	);
	//define('_DB2'		, 'encuestas2');
	define('_DB3'		, 'miido_cirugiaestetica');

	define('_PEOPLE_T'	, 'comfasucre'		);
	define('_DISEASES_T', 'diseases'		);
	define('_CUMS_T'	, 'vademecum'		);
	define('_CUPS_T'	, 'cups'			);
	define('_CIUO_T'	, 'occupation'		);
	
	define('_PEOPLE_D'	, '1'				);
	define('_DISEASES_D', '1'				);
	define('_CUMS_D'	, '2'				);
	define('_CUPS_D'	, '2'				);
	define('_CIUO_D'	, '2'				);
	
	define('_PEOPLE_F'	,
		'numeroCarnet,tipoIdentificacion,identificacion,primerApellido,'.
		'segundoApellido,primerNombre,segundoNombre,fechaNacimiento,genero,'.
		'tipoAfiliado,nivelSisben,telefono'						);
	define('_DISEASES_F', 'code,description'					);
	define('_CUMS_F'	, 'strCodMedicine,strMedicineName'		);
	define('_CUPS_F'	, 'strCodCups,strCupsName'				);
	define('_CIUO_F'	, 'strCodeOccupation,strNameOccupation'	);
	
	define('_PEOPLE_C', 'codigoDepartamento='._DEPARTAMENT._SP.'codigoMunicipio='._CITY);
?>