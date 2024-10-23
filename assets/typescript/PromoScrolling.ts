export default class PromoScrolling {
    private indexCards: number;
    private readonly cardsNumber: number;
    private datas: { totalCards: number, sizeCard: number };
    private selectors;
    private readonly cardsTotal: number;

    constructor() {
        this.indexCards = 0;
        this.cardsNumber = 3;
        this.datas = this.getData();
        this.cardsTotal = this.datas.totalCards;
        this.selectors = this.setListeners();

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

        return {prevButton, nextButton};
    }

    scroll(): void {
        const cardWidthWithMargin = this.datas.sizeCard + 40;
        const offset = -(this.indexCards * cardWidthWithMargin);
        const container = document.querySelector(".scroll-container") as HTMLDivElement;
        container.style.transform = `translateX(${offset}px)`;
        if (this.indexCards > this.datas.totalCards - this.cardsNumber) {
            const newoffset = offset + this.datas.sizeCard;
            container.style.transform = `translateX(${newoffset}px)`;
        }
        this.stopProcess();
    }

    next(): void {
        if (this.indexCards < this.datas.totalCards - this.cardsNumber) {
            this.indexCards += this.cardsNumber;
            this.scroll()
        }
    }

    prev(): void {
        if (this.indexCards > 0) {
            this.indexCards -= this.cardsNumber;
            this.scroll()

        }
    }

}