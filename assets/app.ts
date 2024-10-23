import './styles/app.scss'

import Burger from "./typescript/Burger"
import Slideshow from "./typescript/Slideshow";
import IsActive from "./typescript/IsActive";
import PromoScrolling from "./typescript/PromoScrolling";

new Burger(1368, ".nav-burger")
new Slideshow(3000)
new IsActive(".isActive")
new PromoScrolling();