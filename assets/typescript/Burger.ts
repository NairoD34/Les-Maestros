export default class Burger {
    private readonly selector: string;
    public size: number;

    constructor(size: number, selector: string) {
        this.size = size;
        this.selector = selector;
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
                const div: HTMLDivElement = document.querySelector(".burger-background") as HTMLDivElement;
                const burgerOpen = document.querySelector(".burger-icon") as HTMLElement;
                const burgerClose = document.querySelector(".close-icon") as HTMLElement;
                const burgerStyle = window.getComputedStyle(div);

                if (div) {
                    if (burgerStyle.opacity === "0") {
                        div.classList.add("open");
                        if (burgerClose) burgerClose.style.display = "block";
                        if (burgerOpen) burgerOpen.style.display = "none";
                    } else {
                        div.classList.remove("open");
                        if (burgerClose) burgerClose.style.display = "none";
                        if (burgerOpen) burgerOpen.style.display = "block";
                    }
                }
            });
        }
    }

    checkSize() {
        const burgerInputId: HTMLButtonElement = document.querySelector(this.selector) as HTMLButtonElement;
        const changeSize: number = window.innerWidth;
        const ul: HTMLUListElement = document.querySelector(".nav-ul") as HTMLUListElement;
        const content: HTMLDivElement = document.querySelector(".nav-content") as HTMLDivElement;

        if (ul) {
            ul.style.display = changeSize < this.size ? "none" : "flex";
            burgerInputId.style.display = changeSize < this.size ? "block" : "none";
        }
        if (changeSize < this.size) {
            content.style.justifyContent = "end";
            content.style.gap = "30px";

        }
    }

    checkFooterSize() {
        const changeSize: number = window.innerWidth;
        const ul: HTMLUListElement = document.querySelector(".content-list") as HTMLUListElement;
        const divTitle: HTMLDivElement = document.querySelector(".content-title") as HTMLDivElement;
        const navTitle: HTMLHeadingElement = divTitle.getElementsByTagName("h3")[1] as HTMLHeadingElement;
        const followTitle: HTMLHeadingElement = divTitle.getElementsByTagName("h3")[0] as HTMLHeadingElement;
        if (ul) {
            ul.style.display = changeSize < 1368 ? "none" : "flex";
            navTitle.style.display = changeSize < 1368 ? "none" : "block";
            followTitle.style.display = changeSize < 1368 ? "none" : "block";
        }
    }
}