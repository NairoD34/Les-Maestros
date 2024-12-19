export default class SalesScrolling {
    private indexCards: number;
    private datas: { totalCards: number };
    private selectors;
    private cardsNumber: number | undefined;

    constructor() {
        this.indexCards = 0;
        this.datas = this.getData();
        this.selectors = this.setListeners();
        this.updateCardsNumber();
        this.scroll();
        this.stopProcess();
    }

    getData(): { totalCards: number } {
        const promoContainer = document.querySelector(".promo-card") as HTMLDivElement;
        const cards = promoContainer.querySelectorAll(".card-container") as NodeListOf<HTMLDivElement>;
        return {totalCards: cards.length};
    }

    stopProcess() {
        this.selectors.prevButton.style.display = this.indexCards <= 0 ? "none" : "block";
        if (this.cardsNumber) {
            if (this.indexCards >= this.datas.totalCards - this.cardsNumber) {
                this.indexCards = -1;
            }
        }
    }

    setListeners() {
        const prevButton = document.querySelector(".prev-btn") as HTMLButtonElement;
        const nextButton = document.querySelector(".next-btn") as HTMLButtonElement;

        prevButton.addEventListener("click", () => this.prev());
        nextButton.addEventListener("click", () => this.next());
        window.addEventListener('resize', () => {
            this.resetOnResize();
        });
        return {prevButton, nextButton};
    }

    resetOnResize() {
        this.indexCards = 0;
        const container = document.querySelector(".scroll-container") as HTMLDivElement;
        container.style.transform = `translateX(0px)`;
        this.updateCardsNumber();
        this.scroll();
    }

    scroll(): void {
        const {gap, cardWidth} = this.updateCardsNumber();
        const offset = -(this.indexCards * (cardWidth + gap));
        const container = document.querySelector(".scroll-container") as HTMLDivElement;
        container.style.transform = `translateX(${offset}px)`;

        this.stopProcess();
    }

    next(): void {
        const maxIndex = this.cardsNumber ? Math.max(0, this.datas.totalCards - this.cardsNumber) : 0;
        if (this.indexCards < maxIndex) {
            this.indexCards++;
            this.scroll();
        }
    }

    prev(): void {
        if (this.indexCards > 0) {
            this.indexCards--;
            this.scroll();
        }
    }

    updateCardsNumber(): { gap: number; cardWidth: number } {
        const screenWidth = window.innerWidth;
        const container = document.querySelector('.promo-card') as HTMLDivElement;
        const cards = container.querySelectorAll('.card-container') as NodeListOf<HTMLDivElement>;

        this.cardsNumber = screenWidth > 1200 ? 3 : screenWidth > 768 ? 2 : 1;

        const containerWidth = container.offsetWidth;
        const totalCardWidth = containerWidth * 0.9;
        const totalGapWidth = containerWidth * 0.1;

        const cardWidth = totalCardWidth / this.cardsNumber;
        const gap = this.cardsNumber > 1 ? totalGapWidth / (this.cardsNumber - 1) : totalGapWidth;

        cards.forEach((card: HTMLDivElement, index: number) => {
            card.style.width = `${cardWidth}px`;
            card.style.marginRight = index < cards.length - 1 ? `${gap}px` : `0px`;
        });

        return {gap, cardWidth};
    }
}
