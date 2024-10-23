export default class Slideshow {
    private nbImages: number;
    private readonly speed: number;
    private readonly images: string[];
    private domElements: { imgArray: HTMLDivElement[] };
    private interval: NodeJS.Timeout | null = null;
    private isAnimating: boolean = false;

    constructor(speed: number) {
        this.nbImages = 0;
        this.speed = speed;
        this.images = [];
        this.feedSlideShow();
        this.domElements = this.render();
        this.setupEventListeners();
        this.animateSlideShow();
    }

    feedSlideShow(): any[] {
        const imgElements: HTMLImageElement[] = this.getCategorieImgs();
        this.nbImages = imgElements.length
        for (let i: number = 0; i < imgElements.length; i++) {
            const img: HTMLImageElement = imgElements[i];
            this.images[i] = imgElements[i].src;

            img.style.width = "100%";
            img.style.height = "auto";
        }
        return this.images;
    }

    getCategorieImgs(): HTMLImageElement[] {
        const imgs: NodeListOf<HTMLImageElement> = document.querySelectorAll(".img-carousel") as NodeListOf<HTMLImageElement>;
        return Array.from(imgs)
    }

    render(): { imgArray: HTMLDivElement[] } {
        const imgArray: HTMLDivElement[] = [];
        const divItems: NodeListOf<HTMLDivElement> = document.querySelectorAll(".carousel-items") as NodeListOf<HTMLDivElement>;
        divItems.forEach((divItem: HTMLDivElement, index: number): void => {
            if (index === 0 && !divItem.classList.contains("active")) {
                divItem.classList.add("active");
            }
            imgArray.push(divItem)
            divItem.style.display = divItem.classList.contains("active") ? "block" : "none";
        });
        return {
            imgArray,
        };
    }

    animateSlideShow(): void {
        if (this.isAnimating) return;
        this.isAnimating = true;
        this.interval = setInterval((): void => {
            for (let i: number = 0; i < this.nbImages; i++) {
                if (this.domElements.imgArray[i].style.display === "block") {
                    this.domElements.imgArray[(i + 1) % this.nbImages].style.display =
                        "block";
                    this.domElements.imgArray[i].style.display = "none";
                    break;
                }

            }
        }, this.speed);
    }

    stopSlideShow(): void {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
            this.isAnimating = false;
        }
    }

    setupEventListeners(): void {
        const carouselElement: HTMLDivElement = document.querySelector(".carousel-container") as HTMLDivElement;
        if (carouselElement) {
            carouselElement.addEventListener("mouseover", () => this.stopSlideShow());
            carouselElement.addEventListener("mouseout", () => this.animateSlideShow());
        }
    }

}
