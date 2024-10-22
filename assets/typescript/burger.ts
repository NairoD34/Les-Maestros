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
        if (size) {
            checkSize();
            window.addEventListener("resize", () => checkSize());

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