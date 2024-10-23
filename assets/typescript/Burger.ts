export default class Burger {
    private readonly selector: string;
    public size: number;

    constructor(size: number, selector: string) {
        this.size = size;
        this.selector = selector;
        this.render();
    }

    render(): void {
        const burgerInputId: HTMLButtonElement = document.querySelector(this.selector) as HTMLButtonElement;
        if (burgerInputId) {
            this.resize()
            this.handleClickBurger()
        }
    }

    resize(): void {
        const size: number = this.size;
        const burgerInputId: HTMLButtonElement = document.querySelector(this.selector) as HTMLButtonElement;

        const checkSize = (): void => {
            const changeSize: number = window.innerWidth;
            const ul: HTMLUListElement = document.querySelector(".nav-ul") as HTMLUListElement;
            if (ul) {
                ul.style.display = changeSize < size ? "none" : "flex";
                burgerInputId.style.display = changeSize < size ? "block" : "none";
            }
        };
        const checkFooterSize = (): void => {
            const changeSize: number = window.innerWidth;
            const ul: HTMLUListElement = document.querySelector(".content-list") as HTMLUListElement;
            const divTitle: HTMLDivElement = document.querySelector(".content-title") as HTMLDivElement;
            const navTitle: HTMLHeadingElement = divTitle.getElementsByTagName("h3")[1] as HTMLHeadingElement;
            const followTitle: HTMLHeadingElement = divTitle.getElementsByTagName("h3")[0] as HTMLHeadingElement;
            if (ul) {
                ul.style.display = changeSize < size ? "none" : "flex";
                navTitle.style.display = changeSize < size ? "none" : "block";
                followTitle.style.display = changeSize < size ? "none" : "block";
            }
        };
        if (size) {
            checkSize();
            checkFooterSize();
            window.addEventListener("resize", () => checkSize());
            window.addEventListener("resize", () => checkFooterSize());

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
}