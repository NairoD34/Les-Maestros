export default class Burger {
    private readonly selector: string;
    public size: number;

    constructor(size: number, selector: string) {
        this.size = size;
        this.selector = selector;
        this.checkSize();
        this.checkFooterSize();
        this.resize();
        this.handleClickBurger();
        this.render();
    }

    render(): void {
        document.addEventListener("DOMContentLoaded", () => {
            const burgerInputId: HTMLButtonElement = document.querySelector(this.selector) as HTMLButtonElement;
            if (burgerInputId) {
                this.checkSize();
                this.checkFooterSize();
                this.resize();
                this.handleClickBurger();
            }
        });
    }

    resize(): void {
        if (this.size) {
            this.checkSize();
            this.checkFooterSize();
            window.addEventListener("resize", () => this.checkSize());
            window.addEventListener("resize", () => this.checkFooterSize());

        }
    }

    handleClickBurger(): void {
        const burgerInputId: HTMLButtonElement = document.querySelector(this.selector) as HTMLButtonElement;
        if (burgerInputId) {
            burgerInputId.addEventListener("click", function (e: MouseEvent) {
                const ul: HTMLUListElement = document.querySelector(".nav-ul") as HTMLUListElement;
                if (ul) ul.style.display === "none" ? ul.style.display = "block" : ul.style.display = "none";
            });
        }
    }

    checkSize() {
        const burgerInputId: HTMLButtonElement = document.querySelector(this.selector) as HTMLButtonElement;
        const changeSize: number = window.innerWidth;
        const ul: HTMLUListElement = document.querySelector(".nav-ul") as HTMLUListElement;
        if (ul) {
            ul.style.display = changeSize < this.size ? "none" : "flex";
            burgerInputId.style.display = changeSize < this.size ? "block" : "none";
        }
    }

    checkFooterSize() {
        const changeSize: number = window.innerWidth;
        const ul: HTMLUListElement = document.querySelector(".content-list") as HTMLUListElement;
        const divTitle: HTMLDivElement = document.querySelector(".content-title") as HTMLDivElement;
        const navTitle: HTMLHeadingElement = divTitle.getElementsByTagName("h3")[1] as HTMLHeadingElement;
        const followTitle: HTMLHeadingElement = divTitle.getElementsByTagName("h3")[0] as HTMLHeadingElement;
        if (ul) {
            ul.style.display = changeSize < this.size ? "none" : "flex";
            navTitle.style.display = changeSize < this.size ? "none" : "block";
            followTitle.style.display = changeSize < this.size ? "none" : "block";
        }
    }
}