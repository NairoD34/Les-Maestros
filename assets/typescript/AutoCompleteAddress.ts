class AutoCompleteAddress {

    private readonly inputCity: HTMLInputElement | null;
    private readonly inputId: HTMLInputElement | null;
    private readonly inputCpId: HTMLInputElement | null;
    private readonly dataCities: HTMLDataListElement | null;
    private readonly codePostauxSelect: HTMLSelectElement | null;

    constructor() {
        this.inputCity = document.getElementById("ville") as HTMLInputElement | null;
        this.inputId = document.getElementById("villeId") as HTMLInputElement | null;
        this.inputCpId = document.getElementById("selectedPostalCodesId") as HTMLInputElement | null;
        this.dataCities = document.getElementById("cityList") as HTMLDataListElement | null;
        this.codePostauxSelect = document.getElementById("postalCodeSelect") as HTMLSelectElement | null;
        this.initializeEventListeners();
    }

    private initializeEventListeners(): void {
        if (this.inputCity) {
            this.inputCity.addEventListener("input", (event: Event) => this.handleCityInput(event));
        }
    }

    private handleCityInput(event: Event): void {
        if (this.dataCities) this.dataCities.style.display = "block";

        const target = event.target as HTMLInputElement;
        const {value} = target


        fetch(`http://127.0.0.1:8000/adresse/ajax/ville/${value}`)
            .then((response) => response.json())
            .then((json) => this.createDivCity(json));
    }

    private createDivCity(json: { ville: string; codeDepartement: string; id: string; codePostaux: any }[]): void {
        if (this.dataCities) this.dataCities.innerHTML = "";

        json.forEach((city) => {

            const {ville, codeDepartement, codePostaux, id} = city;

            const option = document.createElement("option");
            option.value = `${ville} ${codeDepartement}`;
            option.setAttribute("data-codes-postaux", JSON.stringify(codePostaux));
            option.setAttribute("id", id);

            if (this.dataCities) this.dataCities.appendChild(option);
        });

        this.addCitySelectionListener();
    }

    private addCitySelectionListener(): void {
        if (this.inputCity) {
            this.inputCity.addEventListener("input", (event: Event) => this.handleCitySelection(event));
        }
    }

    private handleCitySelection(event: Event): void {
        const target = event.target as HTMLInputElement;
        const selectedOption = this.dataCities?.querySelector(`option[value="${target.value}"]`) as HTMLOptionElement | null;

        if (selectedOption) {
            const cityId = selectedOption.getAttribute("id") || "";
            const codesPostaux = JSON.parse(selectedOption.getAttribute("data-codes-postaux") || "[]");

            if (this.inputId) this.inputId.value = cityId;

            this.updatePostalCodeSelect(codesPostaux);
        } else {
            this.resetInputs();
        }
    }

    private updatePostalCodeSelect(codesPostaux: { libelle: string; id: string }[]): void {
        if (this.codePostauxSelect) {
            this.codePostauxSelect.innerHTML = "";

            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.text = "Sélectionnez un code postal";
            this.codePostauxSelect.appendChild(defaultOption);

            codesPostaux.forEach((code) => {
                const option = document.createElement("option");
                option.value = code.libelle;
                option.text = code.libelle;
                if (this.codePostauxSelect) this.codePostauxSelect.appendChild(option);
            });

            this.addPostalCodeChangeListener(codesPostaux);
        }
    }

    private addPostalCodeChangeListener(codesPostaux: { libelle: string; id: string }[]): void {
        if (this.codePostauxSelect) {
            this.codePostauxSelect.addEventListener("change", () => {
                const selectedPostalCode = codesPostaux.find(
                    (code) => code.libelle === this.codePostauxSelect?.value
                );
                if (this.inputCpId) {
                    this.inputCpId.value = selectedPostalCode ? selectedPostalCode.id : "";
                }
            });
        }
    }

    private resetInputs(): void {
        if (this.inputId) this.inputId.value = "";
        if (this.inputCpId) this.inputCpId.value = "";
    }
}

new AutoCompleteAddress();


