//Je déclare les constantes correspondant aux éléments HTML
const inputLabel = document.getElementById("label") as HTMLInputElement | null;
const inputZip = document.getElementById("zip_code") as HTMLInputElement | null;
const inputCity = document.getElementById("ville") as HTMLInputElement | null;
const inputId = document.getElementById("villeId") as HTMLInputElement | null;
const inputCpId = document.getElementById("selectedPostalCodesId") as HTMLInputElement | null;
const dataCities = document.getElementById("cityList") as HTMLDataListElement | null;
const codePostauxSelect = document.getElementById("postalCodeSelect") as HTMLSelectElement | null;

//Je crée un écouteur afin que chaque input dans le champ ville effectue une requête à la BDD
if (inputCity) {
    inputCity.addEventListener("input", (event: Event) => {
        if (dataCities) dataCities.style.display = "block";
        const target = event.target as HTMLInputElement;
        const string = target.value;

        fetch("https://127.0.0.1:8000/adresse/ajax/ville/" + string)
            .then((response) => response.json())
            .then((json) => createDivCity(json));
    });
}

//Fonction qui crée des div avec la ville et le code département depuis un JSON et ajoute un évènement au click pour l'autocomplétion
function createDivCity(json: { ville: string; codeDepartement: string; id: string; codePostaux: any }[]) {
    if (dataCities) dataCities.innerHTML = "";

    json.forEach((city) => {
        const {ville: nameCity, codeDepartement: departementCity, id: idCity, codePostaux} = city;

        // Créer une nouvelle option
        const option = document.createElement("option");
        option.value = `${nameCity} ${departementCity}`;
        option.setAttribute("data-codes-postaux", JSON.stringify(codePostaux));
        option.setAttribute("id", idCity);

        if (dataCities) dataCities.appendChild(option);
    });
}

//Ajoute un écouteur d'événement sur l'input "ville"
if (inputCity) {
    inputCity.addEventListener("input", function (event: Event) {
        const target = event.target as HTMLInputElement;
        const selectedOption = dataCities?.querySelector(`option[value="${target.value}"]`) as HTMLOptionElement | null;

        if (selectedOption) {
            const cityId = selectedOption.getAttribute("id") || "";
            const codesPostaux = JSON.parse(selectedOption.getAttribute("data-codes-postaux") || "[]");

            if (inputId) inputId.value = cityId; // Stockez l'id de la ville sélectionnée dans le champ caché

            //Mettez à jour le champ de sélection des codes postaux
            if (codePostauxSelect) {
                codePostauxSelect.innerHTML = ""; // Réinitialise les options actuelles

                //Ajoutez une option vide en haut de la liste déroulante
                const defaultOption = document.createElement("option");
                defaultOption.value = "";
                defaultOption.text = "Sélectionnez un code postal";
                codePostauxSelect.appendChild(defaultOption);

                codesPostaux.forEach((code: { libelle: string; id: string }) => {
                    const option = document.createElement("option");
                    option.value = code.libelle;
                    option.text = code.libelle;
                    codePostauxSelect.appendChild(option);
                });

                //Ajoutez un écouteur d'événement pour mettre à jour l'ID du code postal sélectionné
                codePostauxSelect.addEventListener("change", function () {
                    const selectedPostalCode = codesPostaux.find(
                        (code: { libelle: string }) => code.libelle === codePostauxSelect.value
                    );
                    if (inputCpId) {
                        inputCpId.value = selectedPostalCode ? selectedPostalCode.id : ""; // Stockez l'id du code postal sélectionné dans le champ caché
                    }
                });
            }
        } else {
            if (inputId) inputId.value = "";
            if (inputCpId) inputCpId.value = ""; // Réinitialisez également le champ caché si aucune ville n'est sélectionnée
        }
    });
}
