export default class SalesScrolling {
    private indexCards: number;
    private cardsNumber: number;
    private datas: { totalCards: number };
    private selectors;
    private readonly cardsTotal: number;
    private readonly gap: number;
    private readonly cardWidth: number;

    constructor() {
        this.indexCards = 0;
        this.cardsNumber = 3;
        this.datas = this.getData();
        this.cardsTotal = this.datas.totalCards;
        this.selectors = this.setListeners();
        this.gap = this.updateCardsNumber().gap;
        this.cardWidth = this.updateCardsNumber().cardWidth;
        this.scroll();
        this.stopProcess();
    }

    getData(): { totalCards: number } {
        const promoContainer = document.querySelector(".promo-card") as HTMLDivElement;
        const cards = promoContainer.querySelectorAll(".card-container") as NodeListOf<HTMLDivElement>;
        const totalCards = cards.length;

        return {totalCards}

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
        window.addEventListener('resize', () => this.updateCardsNumber());
        return {prevButton, nextButton};
    }

    scroll(): void {
        const cardWidthWithMargin = this.cardWidth + this.gap;
        console.log(this.gap)
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

    updateCardsNumber(): { gap: number; cardWidth: number } {

        const screenWidth = window.innerWidth;
        const container = document.querySelector('.promo-card') as HTMLDivElement;
        const scrollContainer = document.querySelector('.scroll-container') as HTMLDivElement;
        const cards = scrollContainer.querySelectorAll('.card-container') as NodeListOf<HTMLDivElement>;

        if (screenWidth > 1200) {
            this.cardsNumber = 3;
        } else if (screenWidth > 768) {
            this.cardsNumber = 2;
        } else {
            this.cardsNumber = 1;
        }
        const containerWidth = container.offsetWidth;

        const totalCardWidth = containerWidth * 0.9;
        const totalGapWidth = containerWidth * 0.1;

        const cardWidth = totalCardWidth / this.cardsNumber;
        const gap = this.cardsNumber > 1 ? totalGapWidth / (this.cardsNumber - 1) : totalGapWidth / this.cardsNumber;

        cards.forEach((card: HTMLDivElement, index: number) => {
            card.style.width = `${cardWidth}px`;
            if (index < cards.length - 1) {
                card.style.marginRight = `${gap}px`;
            } else {
                card.style.marginRight = `0px`;
            }
        });

        return {gap, cardWidth};
    }


}