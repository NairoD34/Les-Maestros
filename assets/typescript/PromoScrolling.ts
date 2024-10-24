export default class PromoScrolling {
    private indexCards: number;
    private cardsNumber: number;
    private datas: { totalCards: number, sizeCard: number };
    private selectors;
    private readonly cardsTotal: number;

    constructor() {
        this.indexCards = 0;
        this.cardsNumber = 3;
        this.datas = this.getData();
        this.cardsTotal = this.datas.totalCards;
        this.selectors = this.setListeners();
        this.updateCardsNumber();
        this.stopProcess();
    }

    getData(): { totalCards: number, sizeCard: number } {
        const promoContainer = document.querySelector(".promo-card") as HTMLDivElement;
        const cards = promoContainer.querySelectorAll(".card-container") as NodeListOf<HTMLDivElement>;
        const totalCards = cards.length;
        const sizeCard = cards[0].clientWidth;

        return {totalCards, sizeCard}

    }

    stopProcess() {
        if (this.indexCards <= 0) {
            this.selectors.prevButton.style.display = "none";
        } else {
            this.selectors.prevButton.style.display = "block";
        }
        if (this.indexCards >= this.cardsTotal - this.cardsNumber) {
            this.selectors.nextButton.style.display = "none";
        } else {
            this.selectors.nextButton.style.display = "block";
        }
    }

    setListeners() {
        const prevButton = document.querySelector(".prev-btn") as HTMLButtonElement;
        const nextButton = document.querySelector(".next-btn") as HTMLButtonElement;

        prevButton.addEventListener("click", () => this.prev());
        nextButton.addEventListener("click", () => this.next());
        window.addEventListener('resize', () => {
            this.updateCardsNumber();
            this.scroll();
        });
        return {prevButton, nextButton};
    }

    scroll(): void {
        const gap = this.cardsNumber === 1 ? 60 : 30;
        const cardWidthWithMargin = this.datas.sizeCard + gap;

        const offset = -(this.indexCards * cardWidthWithMargin);
        const container = document.querySelector(".scroll-container") as HTMLDivElement;
        container.style.transform = `translateX(${offset}px)`;

        this.stopProcess();
    }

    next(): void {
        const maxIndex = this.datas.totalCards - 1;
        if (this.indexCards < maxIndex) {
            this.indexCards += this.cardsNumber;
            if (this.indexCards > maxIndex) {
                this.indexCards = maxIndex;
            }
            this.scroll()
        }
    }

    prev(): void {
        if (this.indexCards > 0) {
            this.indexCards -= this.cardsNumber;
            this.scroll()

        }
    }

    updateCardsNumber(): void {
        const screenWidth = window.innerWidth;

        if (screenWidth > 1200) {
            this.cardsNumber = 3;
        } else if (screenWidth > 768) {
            this.cardsNumber = 2;
        } else {
            this.cardsNumber = 1;
        }
    }
}