export default class IsActive {

    constructor(private className: string) {
        this.setActiveClassOnLoad()
    }


    setActiveClassOnLoad(): void {
        const ulLists: NodeListOf<HTMLUListElement> = document.querySelectorAll(this.className) as NodeListOf<HTMLUListElement>;
        const currentPath: string = window.location.pathname;

        ulLists.forEach(ulList => {
            if (ulList) {
                const links: HTMLCollectionOf<HTMLAnchorElement> = ulList.getElementsByTagName('a') as HTMLCollectionOf<HTMLAnchorElement>;

                for (let i: number = 0; i < links.length; i++) {
                    const link: HTMLAnchorElement = links[i] as HTMLAnchorElement;

                    if (link.pathname === currentPath) {
                        const navA: HTMLAnchorElement = link.closest('a') as HTMLAnchorElement;
                        if (navA) {
                            navA.classList.add('active');
                            navA.style.color = '#D2AC62';
                        }
                    }
                }
            }
        });
    }


}