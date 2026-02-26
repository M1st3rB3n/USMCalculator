import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['qoeInput', 'qoeRadio', 'totalTechnique', 'totalArtistique', 'totalGlobal'];

    connect() {
        // Optionnel : synchronisation initiale au chargement
        this.syncRadiosFromInputs();
    }

    updateInput(event) {
        event.stopPropagation();
        const radio = event.currentTarget;
        const index = radio.dataset.index;
        const val = radio.value;
        const input = this.element.querySelector(`.qoe-input[data-index="${index}"]`);

        if (input) {
            input.value = val;
            // Déclencher 'input' pour que LiveComponent voie le changement si nécessaire
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
        this.calculateTotals();
    }

    unset(event) {
        event.preventDefault();
        event.stopPropagation();
        const button = event.currentTarget;
        const index = button.dataset.index;
        const input = this.element.querySelector(`.qoe-input[data-index="${index}"]`);

        if (input) {
            input.value = '';
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }

        // Décocher les radios pour cet index
        const radios = this.element.querySelectorAll(`.qoe-radio[data-index="${index}"]`);
        radios.forEach(r => r.checked = false);
        this.calculateTotals();
    }

    syncRadiosFromInputs() {
        const inputs = this.element.querySelectorAll('.qoe-input');
        inputs.forEach(input => {
            const index = input.dataset.index;
            const val = input.value;
            if (val === '') {
                const radios = this.element.querySelectorAll(`.qoe-radio[data-index="${index}"]`);
                radios.forEach(r => r.checked = false);
            } else {
                const radio = this.element.querySelector(`.qoe-radio[data-index="${index}"][value="${val}"]`);
                if (radio) {
                    radio.checked = true;
                } else {
                    const radios = this.element.querySelectorAll(`.qoe-radio[data-index="${index}"]`);
                    radios.forEach(r => r.checked = false);
                }
            }
        });
        this.calculateTotals();
    }

    handleInputChange(event) {
        const input = event.currentTarget;
        const index = input.dataset.index;
        const val = input.value;

        if (val === '') {
            const radios = this.element.querySelectorAll(`.qoe-radio[data-index="${index}"]`);
            radios.forEach(r => r.checked = false);
        } else {
            const radio = this.element.querySelector(`.qoe-radio[data-index="${index}"][value="${val}"]`);
            if (radio) {
                radio.checked = true;
            } else {
                const radios = this.element.querySelectorAll(`.qoe-radio[data-index="${index}"]`);
                radios.forEach(r => r.checked = false);
            }
        }
        this.calculateTotals();
    }

    calculateTotals() {
        console.log("calculateTotals");
        let totalTech = 0;
        let totalArt = 0;

        this.element.querySelectorAll('.tech-qoe-input').forEach(input => {
            totalTech += parseFloat(input.value) || 0;
        });

        this.element.querySelectorAll('.art-qoe-input').forEach(input => {
            totalArt += parseFloat(input.value) || 0;
        });

        const totalGlobal = totalTech + totalArt;

        if (this.hasTotalTechniqueTarget) {
            this.totalTechniqueTarget.textContent = totalTech.toFixed(2);
        }
        if (this.hasTotalArtistiqueTarget) {
            this.totalArtistiqueTarget.textContent = totalArt.toFixed(2);
        }
        if (this.hasTotalGlobalTarget) {
            this.totalGlobalTarget.textContent = totalGlobal.toFixed(2);
        }
    }
}
