/* 
* This example uses the node MongoDB module to connect to the local
* mongodb database on this virtual machine
*
* More here: http://mongodb.github.io/node-mongodb-native/markdown-docs/insert.html
*/

//require node modules (see package.json)
var MongoClient = require('mongodb').MongoClient, format = require('util').format;

//connect away
MongoClient.connect('mongodb://localhost:27017/pruebas', function(err, db) {
  if (err) throw err;
  console.log("Connected to Database");

  //simple json record
	var document = {
    "Abuse": {
        "abuse": "FALSE",
        "childhood": "",
        "id_house_abuse": "0",
        "women": ""
    },
    "House": {
        "address": "Barro prieto",
        "area": "RURAL",
        "gps_precision": "21",
        "id_house": "0",
        "latitude": "9.35183666666667",
        "longitude": "-75.4756016666667",
        "name": "Romero bertel",
        "phone": "3126116562",
        "type": "VEREDA"
    },
    "HousePoll": {
        "bedrooms": "2",
        "earth_floor": "true",
        "electric_power": "true",
        "enough_light": "true",
        "floor_materials": "Tierra",
        "garbage_treatment": "Quemada",
        "id_house_poll": "0",
        "kitchen_inside_bedroom": "true",
        "latrine": "false",
        "lighting": "Electríca",
        "natural_gas": "false",
        "pet": "true",
        "pets": "perros-pajaros",
        "rats": "false",
        "roof_materials": "Teja de barro, zinc, cemento, Sin Cielo Raso Losa o Plancha, Asbesto, Cemento con cielo Raso.",
        "roof_state": "Bueno",
        "sewage": "false",
        "sewerage": "false",
        "shower": "false",
        "type": "Familiar",
        "walls_materials": "Zinc, Tela, Carto?n, Latas o Desechos Guadua, Can?a, Esterilla",
        "walls_state": "Regular",
        "water": "false",
        "water_source": "Pozo artesanal",
        "water_state": "Ninguno"
    },
    "People": [
        {
            "People": {
                "affiliation_contribution": "Beneficiario",
                "affiliation_type": "Subsidiado",
                "app_version": "0.66",
                "benefits": "Ninguno",
                "birth_date": "2005-05-05",
                "department": "70",
                "displaced": "false",
                "eps": "Comfasucre",
                "eps_id": "48137",
                "first_name": "LILIANA MARCELA",
                "gender": "F",
                "handicapped": "false",
                "house_rol": "HIJO(A)",
                "id_cc": "0",
                "id_other": "",
                "id_person": "85814",
                "id_rc": "",
                "id_ti": "1103498207",
                "identification_type": "TI",
                "last_name": "ROMERO BERTEL",
                "occupation": "Estudiante",
                "phone": "3126116562",
                "race": "INDIGENA",
                "sisben": "1",
                "studies": "Básica primaria",
                "town": "1"
            },
            "PeopleAM": {
                "abdominal_circumference": "0",
                "blood_pressure_diastolic": "0",
                "blood_pressure_media": "0",
                "blood_pressure_systolic": "0",
                "id_person_am": "0",
                "oximetry": "98",
                "size": "128",
                "weight": "21"
            },
            "PeopleDentalCare": {
                "caries_control": "false",
                "control_last_semester": "false",
                "daily_brushing": "2",
                "id_person_dental_care": "0",
                "plaque_control": "false"
            },
            "PeopleDisease": [
            ],
            "PeopleFertility": {
                "abortion": "false",
                "abortion_number": "0",
                "adult_control": "true",
                "breast_exam": "",
                "children_birth": "",
                "children_number": "0",
                "cytology": "true",
                "first_age_sexual_activity": "0",
                "food_reinforcement": "true",
                "has_children": "false",
                "hpv": "true",
                "hpv_dose": "",
                "hpv_finish": "false",
                "id_person_fertility": "0",
                "knowledge_exam": "true",
                "last_menstruation": "0001-01-01",
                "menstruation_active": "false",
                "menstruation_final_age": "0",
                "menstruation_initial_age": "0",
                "planification_type": "",
                "planned_parenthood": "true",
                "pregnancy": "true",
                "pregnancy_control": "true",
                "pregnancy_controls": "",
                "reinforcement_supplier": "ALCALDIA",
                "sexual_active": "true",
                "supplier": "",
                "vaccine_rubella_mmr": "true",
                "vaccine_td_tt": "",
                "vaccine_yellow_fever": "true"
            },
            "PeopleGrowing": {
                "behavior_evaluation": "false",
                "behavior_problem": "false",
                "deworming_treatment": "0",
                "food_reinforcement": "true",
                "grow_development_program": "false",
                "hearing_problem": "false",
                "id_person_growing": "0",
                "language_evaluation": "false",
                "motion_evaluation": "false",
                "reinforcement_supplier": "ALCALDIA",
                "vaccination": "true",
                "vaccination_complete": "true",
                "vision_problem": "false"
            },
            "PeopleMedicament": [
            ],
            "PeopleRisk": {
                "acute_myocardial_infarct": "",
                "anemia": "",
                "asphyxia": "",
                "backbone_issues": "",
                "blood_sugar": "",
                "cancer": "",
                "dizziness": "",
                "family_record": "hipertension-madre,",
                "gastritis_ulcer": "",
                "heart_failure": "",
                "hepatitis": "",
                "high_cholesterol": "",
                "high_pressure": "",
                "id_person_risk": "0",
                "migraine": "",
                "seizures": "",
                "sti": "",
                "surgeries": "",
                "vih": "",
                "vision_problems": "",
                "weight_lost": ""
            }
        }
    ],
    "SatisfactionPoll": {
        "attention": "Fácil",
        "attention_staff": "Buena",
        "facilities": "Buena",
        "general_concept": "Buena",
        "general_perception": "Buena",
        "information_available": "Adecuada",
        "response": "Fácil"
    },
    "h1": "c6c3e0d516cf80790d7520aac1c9af7a",
    "h2": "0A:3E:07:AE:5A:8F"
};
  
	//insert record
	db.collection('personas').insert(document, function(err, records) {
		if (err) throw err;
		//console.log("Record added as "+records[0]._id);
		console.log("Record added as ");
	});
});