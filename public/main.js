//Tableau qui stock tous les formulaires
var forms = [];
//Tableau qui stock toutes les databases
var databases = [];
//Tableau qui stock les inputs du formulaire en cours d'édition
var inputs = [];
//Tableau qui stock les champs de la database en cours d'édition
var fields = [];


//Div dans laquelle sont affichés les forms
var module_forms = document.getElementById('module_forms');
//Div dans laquelle sont affichés les databases
var module_databases = document.getElementById('module_databases');
//Div dans laquelle sont affichés les inputs de chaque form
var formInputs = document.getElementById('form_inputs');
//Div dans laquelle sont affichés les fields de chaque database
var databaseFields = document.getElementById('database_fields');


//Bouton de validation d'un formulaire
var addForm = document.getElementById('add_form');
addForm.addEventListener('click', function(){
    //Récupère le nom du formulaire
    var nameOfForm = document.getElementById('form_name');

    //Talbeau qui stock le formulaire en cours
    var form = {};
    form['form_name'] = nameOfForm.value;
    form['inputs'] = inputs;

    //Ajout du formulaire à la liste des formulaires
    var id = forms.push(form) - 1;

    //Affichage du formulaire dans la liste des formulaires
    var tr = document.createElement("tr");
    tr.setAttribute("id", "form_" + id);

    //Création d'un td pour chaque valeur dans le tableau input
    for(var key in form) {
        if(form[key].constructor !== Array) {
            var td = document.createElement("td");
            td.innerHTML = form[key];
            tr.appendChild(td);
        }
    };

    //Création d'une case supprimer
    var td = document.createElement("td");
    var a = document.createElement("a");
    a.setAttribute("href", "");
    a.setAttribute("onclick", "supprimerForm(" + id + "); return false;");
    a.innerText = "Supprimer";
    td.appendChild(a);
    tr.appendChild(td);

    //Ajout de la ligne au tableau des formulaires
    module_forms.appendChild(tr);

    //Resets des champs
    inputs = [];
    nameOfForm.value = "";
    formInputs.innerHTML = "";
});


//Boutton de validation d'ajout d'un input
var add_input = document.getElementById('add_input');
add_input.addEventListener('click', function(){
    //Get input values
    var inputName = document.getElementById('input_name');
    var displayName = document.getElementById('display_name');
    var description = document.getElementById('input_description');
    var placeholder = document.getElementById('placeholder');

    //Tableau qui stock les valeurs de l'input en cours
    var input = {};
    input["name"] = inputName.value;
    input["display_name"] = displayName.value;
    input["description"] = description.value;
    input["placeHolder"] = placeholder.value;
    var id = inputs.push(input) - 1;


    //Affichage de l'input sous le form name
    var tr = document.createElement("tr");
    tr.setAttribute("id", "item_" + id);

    //Création d'un td pour chaque valeur dans le tableau input
    for(var key in input) {
        if(input[key].constructor !== Array) {
            var td = document.createElement("td");
            td.innerHTML = input[key];
            tr.appendChild(td);
        }
    };

    //Création d'une case supprimer
    var td = document.createElement("td");
    var a = document.createElement("a");
    a.setAttribute("href", "");
    a.setAttribute("onclick", "supprimerItem(" + id + "); return false;");
    a.innerText = "Supprimer";
    td.appendChild(a);
    tr.appendChild(td);

    //Ajout de la ligne au tableau des inputs
    formInputs.appendChild(tr);

    //Reset des champs
    inputName.value = "";
    displayName.value = "";
    description.value = "";
    placeholder.value = "";
});


//Affiche le champs longueur si besoin (selon le type de champs)
var selectType = document.getElementById("field_type");
var field_length_parent = document.getElementById("field_length_parent");
var field_length = document.getElementById("field_length");
selectType.addEventListener('change', function () {
    var option = selectType.querySelector('option[value="' + selectType.value + '"]');

    if(option.getAttribute("needLength") == "true") {
        field_length_parent.style.display = "block";
    }
    else {
        field_length.value = "";
        field_length_parent.style.display = "none";
    }
})

//Bouton de validation d'une database
var addDatabase = document.getElementById('add_database');
addDatabase.addEventListener('click', function(){
    //Récupère le nom de la database
    var database_name = document.getElementById('database_name');

    //Talbeau qui stock la database en cours
    var database = {};
    database['database_name'] = database_name.value;
    database['fields'] = fields;

    //Ajout de la database à la liste des databases
    var id = databases.push(database) - 1;

    //Affichage de la database dans la liste des databases
    var tr = document.createElement("tr");
    tr.setAttribute("id", "database_" + id);

    //Création d'un td pour chaque valeur dans le tableau input
    for(var key in database) {
        if(database[key].constructor !== Array) {
            var td = document.createElement("td");
            td.innerHTML = database[key];
            tr.appendChild(td);
        }
    };

    //Création d'une case supprimer
    var td = document.createElement("td");
    var a = document.createElement("a");
    a.setAttribute("href", "");
    a.setAttribute("onclick", "supprimerDatabase(" + id + "); return false;");
    a.innerText = "Supprimer";
    td.appendChild(a);
    tr.appendChild(td);

    //Ajout de la ligne au tableau des databases
    module_databases.appendChild(tr);

    //Resets des champs
    fields = [];
    database_name.value = "";
    databaseFields.innerHTML = "";
});

//Boutton de validation d'ajout d'un champs
var add_field = document.getElementById('add_field');
add_field.addEventListener('click', function(){
    //Get field values
    var fieldName = document.getElementById('field_name');
    var length = document.getElementById('field_length');
    var fieldNull = document.querySelector('input[name="field_null"]:checked').value;
    var defaultValue = document.getElementById('field_default_value');

    var selectType = document.getElementById('field_type');
    var selected_option = selectType.querySelector('option[value="' + selectType.value + '"]');
    var text_type = selected_option.innerText;

    if(selectType.value == 0) {
        alert('Veuillez sélectionner un type !');
        return "false";
    }

    //Tableau qui stock les valeurs du champs en cours
    var field = {};
    field["name"] = fieldName.value;
    field["type"] = text_type;
    field["length"] = length.value;
    field["null"] = fieldNull;
    field["defaultValue"] = defaultValue.value;
    var id = fields.push(field) - 1;


    //Affichage du champs sous le database name
    var tr = document.createElement("tr");
    tr.setAttribute("id", "field_" + id);

    //Création d'un td pour chaque valeur dans le tableau field
    for(var key in field) {
        if(field[key].constructor !== Array) {
            var td = document.createElement("td");
            td.innerHTML = field[key];
            tr.appendChild(td);
        }
    };

    //Création d'une case supprimer
    var td = document.createElement("td");
    var a = document.createElement("a");
    a.setAttribute("href", "");
    a.setAttribute("onclick", "supprimerField(" + id + "); return false;");
    a.innerText = "Supprimer";
    td.appendChild(a);
    tr.appendChild(td);

    //Ajout de la ligne au tableau des inputs
    databaseFields.appendChild(tr);

    //Reset des champs
    fieldName.value = "";
    selectType.value = 0;
    length.value = "";
    defaultValue.value = "";

    var field_length_parent = document.getElementById("field_length_parent");
    field_length_parent.style.display = "none";
});

//Click sur le boutton générer
var generateModule = document.getElementById('generateModule');
generateModule.addEventListener('click', function(){

    //On récupère toutes les valeurs générales du module
    var moduleName = document.getElementById('module_name');
    var moduleDisplayName = document.getElementById('module_display_name');
    var moduleDescription = document.getElementById('module_description');
    var moduleAuthor = document.getElementById('module_author');
    var moduleVersion = document.getElementById('module_version');
    var moduleAlertNotif = document.querySelector('input[name="module_alert_notifications"]:checked').value;
    var moduleCron = document.querySelector('input[name="module_alert_cron"]:checked').value;

    //Convertion des valeurs en tableau
    var arrayModuleData = {
        moduleName: moduleName.value,
        moduleDisplayName: moduleDisplayName.value,
        moduleDescription: moduleDescription.value,
        moduleAuthor: moduleAuthor.value,
        moduleVersion: moduleVersion.value,
        moduleAlertNotif : moduleAlertNotif,
        moduleCron : moduleCron,
        forms: forms,
        databases: databases,
    };

    //Convertion en JSON
    var myJsonString = JSON.stringify(arrayModuleData);
    sendAjax(myJsonString);
});

function supprimerItem(id) {
    inputs.splice(id);
    var table_line = document.getElementById("item_" + id);
    table_line.remove();
}

function supprimerForm(id) {
    forms.splice(id);
    var table_line = document.getElementById("form_" + id);
    table_line.remove();
}

function supprimerDatabase(id) {
    databases.splice(id);
    var table_line = document.getElementById("database_" + id);
    table_line.remove();
}

function supprimerField(id) {
    fields.splice(id);
    var table_line = document.getElementById("field_" + id);
    table_line.remove();
}

function sendAjax(data) {

    var xhr = getXMLHttpRequest();
    xhr.open("POST", "module_generator/cron.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("x=" + data);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                //document.getElementById("debug_php").innerHTML = this.response;
                var response = JSON.parse(this.response);
                downloadFile('module_generator/modules_generated/' + response.fileName, response.fileName);


            } else {
                // Le serveur a renvoyé un status d'erreur
                alert('Erreur Ajax');
            }
        }
    }

}

function downloadFile(url, name) {
    var link = document.createElement("a");
    link.download = name;
    link.href = url;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    console.debug(link);
}

//Initializare XHR object
function getXMLHttpRequest() {
    var xhr = null;

    if (window.XMLHttpRequest || window.ActiveXObject) {
        if (window.ActiveXObject) {
            try {
                xhr = new ActiveXObject("Msxml2.XMLHTTP");
            } catch(e) {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
        } else {
            xhr = new XMLHttpRequest();
        }
    } else {
        alert("Votre navigateur ne supporte pas des requetes AJAX");
        return null;
    }

    return xhr;
}