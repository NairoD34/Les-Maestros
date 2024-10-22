export default class Slideshow {
    private nb_images: number;
    private readonly speed: number;
    private images: any[];
    private dom_elements: { imgArray: any[] };
    private interval: number;

    constructor(speed: number) {
        this.nb_images = 0;
        this.speed = speed;
        this.images = [];
        this.feedSlideShow();
        this.dom_elements = this.render();
        this.animateSlideShow();
        this.interval = 0;
    }

    feedSlideShow() {
        const imgElements = this.getCategorieImgs();
        this.nb_images = imgElements.length
        for (let i = 0; i < imgElements.length; i++) {
            const img = imgElements[i];
            this.images[i] = imgElements[i].src;

            img.style.width = "100%";
            img.style.height = "auto";
        }
        return this.images;
    }

    getCategorieImgs() {
        const imgs = document.querySelectorAll(".img-carousel") as NodeListOf<HTMLImageElement>;
        return Array.from(imgs)
    }

    render() {
        const imgArray: HTMLDivElement[] = [];
        const divItems = document.querySelectorAll(".carousel-items") as NodeListOf<HTMLDivElement>;
        divItems.forEach((divItem, index) => {
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

    animateSlideShow() {
        let setIntervalId = setInterval(() => {
            for (let i = 0; i < this.nb_images; i++) {
                if (this.dom_elements.imgArray[i].style.display === "block") {
                    this.dom_elements.imgArray[(i + 1) % this.nb_images].style.display =
                        "block";
                    this.dom_elements.imgArray[i].style.display = "none";
                    break;
                }

            }
        }, this.speed);
    }

}
